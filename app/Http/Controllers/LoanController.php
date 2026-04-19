<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Models\User; // Tambahkan import User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LoanController extends Controller
{
    /**
     * Daftar pinjaman milik user yang login.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $accountIds = $user->accounts()->pluck('id');

        $loans = Loan::whereIn('account_id', $accountIds)
            ->with('account')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return view('loans.index', compact('loans'));
    }

    /**
     * Form pengajuan pinjaman baru.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Sesuaikan nama variabel menjadi userAccounts agar sinkron dengan Blade
        $userAccounts = $user->accounts()
            ->where('status', 'active')
            ->where('account_type', 'tabungan')
            ->get();

        return view('loans.create', compact('userAccounts'));
    }
    

    /**
     * Simulasi cicilan (AJAX).
     */
    public function simulate(Request $request)
    {
        $request->validate([
            'principal'     => ['required', 'integer', 'min:1000000'],
            'interest_rate' => ['required', 'numeric', 'min:1', 'max:30'],
            'tenor_months'  => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $result = Loan::calculateMonthlyInstallment(
            $request->principal,
            $request->interest_rate,
            $request->tenor_months
        );

        return response()->json([
            'principal'            => $request->principal,
            'interest_rate'        => $request->interest_rate,
            'tenor_months'         => $request->tenor_months,
            'total_interest'       => $result['total_interest'],
            'total_debt'           => $result['total_debt'],
            'monthly_installment'  => $result['monthly_installment'],
            'formatted' => [
                'principal'           => 'Rp ' . number_format($request->principal, 0, ',', '.'),
                'total_interest'      => 'Rp ' . number_format($result['total_interest'], 0, ',', '.'),
                'total_debt'          => 'Rp ' . number_format($result['total_debt'], 0, ',', '.'),
                'monthly_installment' => 'Rp ' . number_format($result['monthly_installment'], 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Ajukan pinjaman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id'    => ['required', 'exists:accounts,id'],
            'principal'     => ['required', 'integer', 'min:1000000', 'max:500000000'],
            'interest_rate' => ['required', 'numeric', 'min:1', 'max:30'],
            'tenor_months'  => ['required', 'integer', 'min:1', 'max:60'],
            'purpose'       => ['required', 'string', 'max:255'],
        ]);

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== Auth::id()) {
            abort(403, 'Rekening bukan milikmu.');
        }

        // Cek apakah sudah ada pinjaman aktif / pending
        if ($account->loans()->whereIn('status', [Loan::STATUS_ACTIVE, Loan::STATUS_PENDING, Loan::STATUS_OVERDUE])->exists()) {
            return back()->with('error', 'Masih ada pinjaman aktif atau pending. Lunasi terlebih dahulu.');
        }

        $result = Loan::calculateMonthlyInstallment(
            $request->principal,
            $request->interest_rate,
            $request->tenor_months
        );

        Loan::create([
            'account_id'          => $account->id,
            'principal'           => $request->principal,
            'interest_rate'       => $request->interest_rate,
            'tenor_months'        => $request->tenor_months,
            'monthly_installment' => $result['monthly_installment'],
            'total_debt'          => $result['total_debt'],
            'remaining_debt'      => $result['total_debt'],
            'paid_installments'   => 0,
            'status'              => Loan::STATUS_PENDING,
            'purpose'             => $request->purpose,
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Pengajuan pinjaman berhasil dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Detail pinjaman.
     */
    public function show(Loan $loan)
{
    /** @var \App\Models\User $user */
    $user = auth()->user();

    // Sekarang Intelephense tidak akan protes lagi
    if ($user->role === 'user' && $loan->account->user_id !== $user->id) {
        abort(403);
    }

    if ($user->role === 'admin') {
        return view('admin.loans.show', compact('loan'));
    }

    return view('loans.show', compact('loan'));
}

    /**
     * Bayar cicilan pinjaman.
     */
   public function pay(Request $request, Loan $loan)
{
    $this->authorizeLoan($loan);

    $request->validate([
        'account_id' => ['required', 'exists:accounts,id'],
        'pin'        => ['required', 'string', 'size:6'], // Gunakan size:6
    ]);

    // 1. Cek Status Pinjaman
    if (!$loan->isActive()) {
        return back()->with('error', 'Pinjaman tidak dalam status aktif atau sudah lunas.');
    }

    $account = Account::findOrFail($request->account_id);

    // 2. Validasi Kepemilikan Rekening
    if ($account->user_id !== Auth::id()) {
        abort(403);
    }

    // 3. Validasi PIN
    // TIPS: Jika PIN kamu di database BUKAN hasil Hash::make, ganti baris ini menjadi:
    // if ($request->pin !== $account->pin) { ... }
    if (!Hash::check($request->pin, $account->pin)) {
        return back()->withErrors(['pin' => 'PIN yang Anda masukkan salah.'])->withInput();
    }

    $amount = (int) $loan->monthly_installment;

    // 4. Cek Saldo
    if ($account->balance < $amount) {
        return back()->with('error', 'Saldo rekening ' . $account->account_number . ' tidak mencukupi.');
    }

    // 5. Kalkulasi Pembagian Pokok & Bunga
    $totalInterest    = $loan->total_debt - $loan->principal;
    $interestPerMonth = (int) ceil($totalInterest / $loan->tenor_months);
    $principalPerMonth = $amount - $interestPerMonth;
    $remainingAfter   = $loan->remaining_debt - $amount;

    try {
        DB::transaction(function () use ($account, $loan, $amount, $principalPerMonth, $interestPerMonth, $remainingAfter) {
            $balanceBefore = $account->balance;
            
            // Kurangi Saldo
            $account->decrement('balance', $amount);
            $account->refresh();

            // Catat Transaksi
            $tx = Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_LOAN_PAYMENT,
                'amount'           => $amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Pembayaran Cicilan INB Ke-' . ($loan->paid_installments + 1),
                'reference_number' => $this->generateRefCode('CIC'),
                'status'           => 'success',
            ]);

            // Catat Detail Pembayaran Pinjaman
            LoanPayment::create([
                'transaction_id'     => $tx->id,
                'loan_id'            => $loan->id,
                'amount'             => $amount,
                'principal_paid'     => $principalPerMonth,
                'interest_paid'      => $interestPerMonth,
                'remaining_after'    => max(0, $remainingAfter),
                'installment_number' => $loan->paid_installments + 1,
                'paid_at'            => now(),
            ]);

            // Update Status Pinjaman
            $newPaidCount = $loan->paid_installments + 1;
            $isPaidOff    = $newPaidCount >= $loan->tenor_months || $remainingAfter <= 0;

            $loan->update([
                'paid_installments' => $newPaidCount,
                'remaining_debt'    => max(0, $remainingAfter),
                'status'            => $isPaidOff ? Loan::STATUS_PAID_OFF : Loan::STATUS_ACTIVE,
            ]);
        });

        $message = $loan->fresh()->isPaidOff()
            ? '🎉 Selamat! Pinjaman Anda telah LUNAS.'
            : 'Pembayaran cicilan ke-' . ($loan->paid_installments) . ' berhasil.';

        return redirect()->route('loans.show', $loan->id)->with('success', $message);

    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
    }
}

    /**
     * Setujui pinjaman (Admin).
     */
    public function approve(Request $request, Loan $loan)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        if (!$loan->isPending()) {
            return back()->with('error', 'Hanya pinjaman berstatus pending yang dapat disetujui.');
        }

        DB::transaction(function () use ($loan) {
            $account = $loan->account;

            $balanceBefore = $account->balance;
            $account->increment('balance', $loan->principal);
            $account->refresh();

            Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_LOAN_DISBURSE,
                'amount'           => $loan->principal,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Pencairan pinjaman INB - ' . $loan->purpose,
                'reference_number' => $this->generateRefCode('LNS'),
                'status'           => 'success',
            ]);

            $loan->update([
                'status'       => Loan::STATUS_ACTIVE,
                'disbursed_at' => now()->toDateString(),
                'due_date'     => now()->addMonth()->toDateString(),
            ]);
        });

        return back()->with('success', 'Pinjaman disetujui dan dana telah dicairkan ke rekening INB nasabah.');
    }

            /**
         * Menampilkan halaman form pembayaran cicilan.
         */
        public function paymentForm(Loan $loan)
        {
            $this->authorizeLoan($loan);

            if (!$loan->isActive()) {
                return redirect()->route('loans.show', $loan)->with('error', 'Pinjaman tidak dalam status aktif.');
            }

            /** @var User $user */
            $user = Auth::user();
            $userAccounts = $user->accounts()->where('status', 'active')->get();

            return view('loans.pay', compact('loan', 'userAccounts'));
        }

    public function reject(Request $request, Loan $loan)
{
    // 1. Cek Role Admin
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    // 2. Validasi (Pastikan field ini dikirim dari Blade)
    $request->validate([
        'rejection_reason' => ['required', 'string', 'max:500'],
    ]);

    // 3. Cek Status (Gunakan string langsung jika konstanta tidak terbaca)
    if ($loan->status !== 'pending') {
        return back()->with('error', 'Status pinjaman sudah bukan pending.');
    }

    // 4. Update
    $loan->update([
        'status' => 'rejected', // Sesuaikan dengan nama status di DB kamu
        'rejection_reason' => $request->rejection_reason,
    ]);

    return redirect()->route('admin.loans.index')->with('success', 'Pinjaman berhasil ditolak.');
}

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function authorizeLoan(Loan $loan): void
    {
        /** @var User $user */
        $user = Auth::user();
        $userAccountIds = $user->accounts()->pluck('id');

        if (!$userAccountIds->contains($loan->account_id) && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }

    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8)) . mt_rand(100, 999);
        } while (Transaction::where('reference_number', $code)->exists());

        return $code;
    }
}
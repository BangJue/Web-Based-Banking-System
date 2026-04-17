<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Transaction;
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
        $accountIds = Auth::user()->accounts()->pluck('id');

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
        $accounts = Auth::user()->accounts()
            ->where('status', 'active')
            ->where('account_type', 'tabungan')
            ->get();

        return view('loans.create', compact('accounts'));
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
        $this->authorizeLoan($loan);

        $loan->load(['account.user', 'loanPayments.transaction']);

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
            'pin'        => ['required', 'digits:6'],
        ]);

        if (!$loan->isActive()) {
            return back()->with('error', 'Pinjaman tidak dalam status aktif.');
        }

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Hash::check($request->pin, $account->pin)) {
            return back()->withErrors(['pin' => 'PIN tidak sesuai.']);
        }

        $amount = $loan->monthly_installment;

        if ($account->balance < $amount) {
            return back()->with('error', 'Saldo tidak mencukupi untuk membayar cicilan Rp ' . number_format($amount, 0, ',', '.'));
        }

        // Hitung porsi pokok & bunga (flat)
        $totalInterest   = $loan->total_debt - $loan->principal;
        $interestPerMonth = (int) ceil($totalInterest / $loan->tenor_months);
        $principalPerMonth = $amount - $interestPerMonth;
        $remainingAfter  = $loan->remaining_debt - $amount;

        DB::transaction(function () use ($account, $loan, $amount, $principalPerMonth, $interestPerMonth, $remainingAfter) {
            $balanceBefore = $account->balance;
            $account->decrement('balance', $amount);
            $account->refresh();

            $tx = Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_LOAN_PAYMENT,
                'amount'           => $amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Cicilan pinjaman ke-' . ($loan->paid_installments + 1),
                'reference_number' => $this->generateRefCode('CIC'),
                'status'           => 'success',
            ]);

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

            $newPaidInstallments = $loan->paid_installments + 1;
            $isPaidOff           = $newPaidInstallments >= $loan->tenor_months;

            $loan->update([
                'paid_installments' => $newPaidInstallments,
                'remaining_debt'    => max(0, $remainingAfter),
                'status'            => $isPaidOff ? Loan::STATUS_PAID_OFF : Loan::STATUS_ACTIVE,
            ]);
        });

        $message = $loan->fresh()->isPaidOff()
            ? '🎉 Selamat! Pinjaman kamu sudah LUNAS.'
            : 'Cicilan ke-' . $loan->fresh()->paid_installments . ' berhasil dibayar.';

        return redirect()->route('loans.show', $loan)->with('success', $message);
    }

    /**
     * Setujui pinjaman (Admin).
     */
    public function approve(Request $request, Loan $loan)
    {
        if (!Auth::user()->isAdmin()) {
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

            // Catat transaksi pencairan
            Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_LOAN_DISBURSE,
                'amount'           => $loan->principal,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Pencairan pinjaman - ' . $loan->purpose,
                'reference_number' => $this->generateRefCode('LNS'),
                'status'           => 'success',
            ]);

            $loan->update([
                'status'       => Loan::STATUS_ACTIVE,
                'disbursed_at' => now()->toDateString(),
                'due_date'     => now()->addMonth()->toDateString(),
            ]);
        });

        return back()->with('success', 'Pinjaman disetujui dan dana telah dicairkan ke rekening.');
    }

    /**
     * Tolak pinjaman (Admin).
     */
    public function reject(Request $request, Loan $loan)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        if (!$loan->isPending()) {
            return back()->with('error', 'Hanya pinjaman berstatus pending yang dapat ditolak.');
        }

        $loan->update([
            'status'           => Loan::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Pinjaman ditolak.');
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function authorizeLoan(Loan $loan): void
    {
        $userAccountIds = Auth::user()->accounts()->pluck('id');

        if (!$userAccountIds->contains($loan->account_id) && !Auth::user()->isAdmin()) {
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

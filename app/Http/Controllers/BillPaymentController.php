<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BillPaymentController extends Controller
{
    const ADMIN_FEE = 2500;

    /**
     * Pilih tagihan yang akan dibayar.
     */
    public function index()
    {
        $bills    = Bill::active()->orderBy('category')->get()->groupBy('category');
        $accounts = Auth::user()->accounts()->where('status', 'active')->get();

        return view('bill_payments.index', compact('bills', 'accounts'));
    }

    /**
     * Form pembayaran tagihan tertentu.
     */
    public function create(Bill $bill)
    {
        abort_unless($bill->is_active, 404, 'Tagihan tidak tersedia.');

        $accounts = Auth::user()->accounts()->where('status', 'active')->get();

        return view('bill_payments.create', compact('bill', 'accounts'));
    }

    /**
     * Inquiry tagihan sebelum bayar (AJAX).
     * Di sistem nyata: integrasikan dengan API masing-masing penyedia.
     */
    public function inquiry(Request $request)
    {
        $request->validate([
            'bill_id'         => ['required', 'exists:bills,id'],
            'customer_number' => ['required', 'string', 'max:255'],
        ]);

        // Dummy response — ganti dengan integrasi API nyata
        $bill   = Bill::findOrFail($request->bill_id);
        $amount = match ($bill->category) {
            'listrik'  => 150000,
            'air'      => 75000,
            'bpjs'     => 120000,
            'internet' => 350000,
            default    => 100000,
        };

        return response()->json([
            'customer_name' => 'Nama Pelanggan',
            'customer_number' => $request->customer_number,
            'bill_name'     => $bill->bill_name,
            'amount'        => $amount,
            'period'        => now()->format('Y-m'),
            'admin_fee'     => self::ADMIN_FEE,
            'total'         => $amount + self::ADMIN_FEE,
        ]);
    }

    /**
     * Proses pembayaran tagihan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id'      => ['required', 'exists:accounts,id'],
            'bill_id'         => ['required', 'exists:bills,id'],
            'customer_number' => ['required', 'string', 'max:255'],
            'customer_name'   => ['nullable', 'string', 'max:255'],
            'amount'          => ['required', 'integer', 'min:1000'],
            'period'          => ['nullable', 'string', 'max:20'],
            'pin'             => ['required', 'digits:6'],
        ]);

        $account = Account::findOrFail($request->account_id);
        $bill    = Bill::findOrFail($request->bill_id);

        if ($account->user_id !== Auth::id()) {
            abort(403, 'Rekening bukan milikmu.');
        }

        if ($account->status !== 'active') {
            return back()->with('error', 'Rekening tidak aktif.');
        }

        if (!Hash::check($request->pin, $account->pin)) {
            return back()->withErrors(['pin' => 'PIN tidak sesuai.'])->withInput();
        }

        $total = $request->amount + self::ADMIN_FEE;

        if ($account->balance < $total) {
            return back()->with('error', 'Saldo tidak mencukupi. Dibutuhkan Rp ' . number_format($total, 0, ',', '.'));
        }

        DB::transaction(function () use ($account, $bill, $request, $total) {
            $balanceBefore = $account->balance;
            $account->decrement('balance', $total);
            $account->refresh();

            $tx = Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_BILL_PAYMENT,
                'amount'           => $total,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Bayar ' . $bill->bill_name . ' (' . $request->customer_number . ')',
                'reference_number' => $this->generateRefCode('BPY'),
                'status'           => 'success',
            ]);

            BillPayment::create([
                'transaction_id'  => $tx->id,
                'account_id'      => $account->id,
                'bill_id'         => $bill->id,
                'customer_number' => $request->customer_number,
                'customer_name'   => $request->customer_name,
                'amount'          => $request->amount,
                'admin_fee'       => self::ADMIN_FEE,
                'period'          => $request->period,
                'status'          => 'success',
            ]);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Pembayaran ' . $bill->bill_name . ' berhasil.');
    }

    /**
     * Riwayat pembayaran tagihan user.
     */
    public function history(Request $request)
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        $payments = BillPayment::whereIn('account_id', $accountIds)
            ->with(['bill', 'account', 'transaction'])
            ->when($request->bill_id, fn($q) => $q->where('bill_id', $request->bill_id))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $bills = Bill::active()->orderBy('bill_name')->get();

        return view('bill_payments.history', compact('payments', 'bills'));
    }

    /**
     * Detail pembayaran tagihan.
     */
    public function show(BillPayment $billPayment)
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        if (!$accountIds->contains($billPayment->account_id) && !Auth::user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $billPayment->load(['bill', 'account.user', 'transaction']);

        return view('bill_payments.show', compact('billPayment'));
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8)) . mt_rand(100, 999);
        } while (Transaction::where('reference_number', $code)->exists());

        return $code;
    }
}

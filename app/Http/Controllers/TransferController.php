<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TransferController extends Controller
{
    /**
     * Form transfer uang.
     */
    public function create()
    {
        $accounts = Auth::user()->accounts()->where('status', 'active')->get();

        return view('transfers.create', compact('accounts'));
    }

    /**
     * Cek rekening tujuan (AJAX — dipanggil saat user ketik nomor rekening).
     */
    public function checkDestination(Request $request)
    {
        $request->validate([
            'account_number' => ['required', 'string'],
        ]);

        $destination = Account::where('account_number', $request->account_number)
            ->where('status', 'active')
            ->with('user:id,name')
            ->first();

        if (!$destination) {
            return response()->json(['error' => 'Rekening tujuan tidak ditemukan atau tidak aktif.'], 404);
        }

        return response()->json([
            'account_number' => $destination->account_number,
            'account_type'   => $destination->account_type_label,
            'owner_name'     => $destination->user->name,
        ]);
    }

    /**
     * Proses transfer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_account_id'    => ['required', 'exists:accounts,id'],
            'to_account_number'  => ['required', 'string'],
            'amount'             => ['required', 'integer', 'min:10000'],
            'method'             => ['required', Rule::in(array_keys(Transfer::ADMIN_FEES))],
            'note'               => ['nullable', 'string', 'max:255'],
            'pin'                => ['required', 'digits:6'],
        ]);

        $fromAccount = Account::findOrFail($request->from_account_id);
        $toAccount   = Account::where('account_number', $request->to_account_number)
            ->where('status', 'active')
            ->firstOrFail();

        // Otorisasi rekening asal
        if ($fromAccount->user_id !== Auth::id()) {
            abort(403, 'Rekening bukan milikmu.');
        }

        // Tidak boleh transfer ke rekening sendiri
        if ($fromAccount->id === $toAccount->id) {
            return back()->with('error', 'Tidak dapat transfer ke rekening yang sama.');
        }

        // Verifikasi PIN
        if (!Hash::check($request->pin, $fromAccount->pin)) {
            return back()->withErrors(['pin' => 'PIN tidak sesuai.'])->withInput();
        }

        $adminFee = Transfer::getFeeByMethod($request->method);
        $total    = $request->amount + $adminFee;

        if ($fromAccount->balance < $total) {
            return back()->with('error', 'Saldo tidak mencukupi. Butuh Rp ' . number_format($total, 0, ',', '.'));
        }

        DB::transaction(function () use ($fromAccount, $toAccount, $request, $adminFee, $total) {
            $balanceBefore = $fromAccount->balance;
            $fromAccount->decrement('balance', $total);
            $fromAccount->refresh();

            // Transaksi debit rekening asal
            $txOut = Transaction::create([
                'account_id'       => $fromAccount->id,
                'type'             => Transaction::TYPE_TRANSFER_OUT,
                'amount'           => $total,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $fromAccount->balance,
                'description'      => 'Transfer ke ' . $toAccount->account_number . ' - ' . ($request->note ?? '-'),
                'reference_number' => $this->generateRefCode('TRF'),
                'status'           => 'success',
            ]);

            // Transaksi kredit rekening tujuan
            $balanceBeforeTo = $toAccount->balance;
            $toAccount->increment('balance', $request->amount);
            $toAccount->refresh();

            $txIn = Transaction::create([
                'account_id'       => $toAccount->id,
                'type'             => Transaction::TYPE_TRANSFER_IN,
                'amount'           => $request->amount,
                'balance_before'   => $balanceBeforeTo,
                'balance_after'    => $toAccount->balance,
                'description'      => 'Transfer dari ' . $fromAccount->account_number,
                'reference_number' => $txOut->reference_number, // sama agar bisa ditelusuri
                'status'           => 'success',
            ]);

            // Detail transfer
            Transfer::create([
                'transaction_id'  => $txOut->id,
                'from_account_id' => $fromAccount->id,
                'to_account_id'   => $toAccount->id,
                'amount'          => $request->amount,
                'admin_fee'       => $adminFee,
                'method'          => $request->method,
                'note'            => $request->note,
            ]);
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transfer Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dikirim.');
    }

    /**
     * Riwayat transfer user.
     */
    public function index(Request $request)
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        $transfers = Transfer::where(function ($q) use ($accountIds) {
            $q->whereIn('from_account_id', $accountIds)
              ->orWhereIn('to_account_id', $accountIds);
        })
            ->with([
                'transaction',
                'fromAccount.user',
                'toAccount.user',
            ])
            ->when($request->method, fn($q) => $q->where('method', $request->method))
            ->when($request->date_from, fn($q) => $q->whereHas('transaction', fn($tq) =>
                $tq->whereDate('created_at', '>=', $request->date_from)
            ))
            ->when($request->date_to, fn($q) => $q->whereHas('transaction', fn($tq) =>
                $tq->whereDate('created_at', '<=', $request->date_to)
            ))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('transfers.index', compact('transfers'));
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

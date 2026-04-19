<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Riwayat semua transaksi dari semua rekening milik user.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $accountIds = $user->accounts()->pluck('id');

        $transactions = Transaction::whereIn('account_id', $accountIds)
            ->with('account')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $userAccounts = $user->accounts()->get(['id', 'account_number', 'account_type']);
        $types = Transaction::whereIn('account_id', $accountIds)->distinct()->pluck('type');

        return view('transactions.index', compact('transactions', 'userAccounts', 'types'));
    }

    /**
     * Detail satu transaksi.
     */
    public function show(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $transaction->load([
            'account.user',
            'transfer.fromAccount.user',
            'transfer.toAccount.user',
            'topUp',
            'billPayment.bill',
            'loanPayment.loan',
            'savingsBookEntry',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Riwayat transaksi per rekening tertentu.
     */
    public function byAccount(Account $account, Request $request)
    {
        $this->authorizeAccount($account);

        $transactions = $account->transactions()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('transactions.by_account', compact('account', 'transactions'));
    }

    /**
     * Unduh bukti transaksi (PDF).
     */
    public function receipt(Transaction $transaction)
    {
        $this->authorizeTransaction($transaction);

        $transaction->load([
            'account.user',
            'transfer.fromAccount.user',
            'transfer.toAccount.user',
            'topUp',
            'billPayment.bill',
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('transactions.receipt', compact('transaction'));

        return $pdf->download('bukti-' . ($transaction->reference_code ?? $transaction->id) . '.pdf');
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function authorizeTransaction(Transaction $transaction): void
    {
        /** @var User $user */
        $user = Auth::user();
        $userAccountIds = $user->accounts()->pluck('id');

        // Pastikan model User memiliki method isAdmin() atau cek role
        if (!$userAccountIds->contains($transaction->account_id) && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }

    private function authorizeAccount(Account $account): void
    {
        /** @var User $user */
        $user = Auth::user();

        if ($account->user_id !== $user->id && $user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
    }
}
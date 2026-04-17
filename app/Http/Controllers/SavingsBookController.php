<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\SavingsBook;
use App\Models\SavingsBookEntry;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF Facade

class SavingsBookController extends Controller
{
    /**
     * Daftar buku tabungan milik user.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        $accountIds = $user->accounts()->pluck('id');

        $savingsBooks = SavingsBook::whereIn('account_id', $accountIds)
            ->with(['account', 'entries' => fn($q) => $q->latest('entry_date')->limit(1)])
            ->withCount('entries')
            ->get();

        // Rekening yang belum punya buku tabungan
        $accountsWithoutBook = $user->accounts()
            ->where('status', 'active')
            ->whereDoesntHave('savingsBook')
            ->get();

        return view('savings_books.index', compact('savingsBooks', 'accountsWithoutBook'));
    }

    /**
     * Terbitkan buku tabungan baru untuk rekening tertentu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
        ]);

        $account = Account::findOrFail($request->account_id);

        /** @var User $user */
        $user = Auth::user();

        if ($account->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        if ($account->savingsBook()->exists()) {
            return back()->with('error', 'Rekening ini sudah memiliki buku tabungan.');
        }

        $savingsBook = SavingsBook::create([
            'account_id'  => $account->id,
            'book_number' => $this->generateBookNumber(),
            'issued_at'   => now()->toDateString(),
        ]);

        return redirect()->route('savings_books.show', $savingsBook)
            ->with('success', 'Buku tabungan berhasil diterbitkan. No: ' . $savingsBook->book_number);
    }

    /**
     * Detail buku tabungan + daftar mutasi.
     */
    public function show(SavingsBook $savingsBook, Request $request)
    {
        $this->authorizeSavingsBook($savingsBook);

        $entries = $savingsBook->entries()
            ->with('transaction')
            ->when($request->date_from, fn($q) => $q->where('entry_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->where('entry_date', '<=', $request->date_to))
            ->orderBy('entry_date')
            ->orderBy('id')
            ->paginate(30)
            ->withQueryString();

        $savingsBook->load('account.user');

        return view('savings_books.show', compact('savingsBook', 'entries'));
    }

    /**
     * Sinkronisasi / cetak mutasi baru dari tabel transactions ke buku tabungan.
     */
    public function sync(SavingsBook $savingsBook)
    {
        $this->authorizeSavingsBook($savingsBook);

        $account     = $savingsBook->account;
        $lastPrinted = $savingsBook->last_printed;

        $transactions = Transaction::where('account_id', $account->id)
            ->where('status', 'success')
            ->when($lastPrinted, fn($q) => $q->where('created_at', '>', $lastPrinted))
            ->orderBy('created_at')
            ->get();

        if ($transactions->isEmpty()) {
            return back()->with('info', 'Buku tabungan sudah up-to-date, tidak ada mutasi baru.');
        }

        DB::transaction(function () use ($savingsBook, $transactions) {
            foreach ($transactions as $tx) {
                $isIncoming = in_array($tx->type, [
                    Transaction::TYPE_TRANSFER_IN,
                    Transaction::TYPE_TOP_UP,
                    Transaction::TYPE_LOAN_DISBURSE,
                    Transaction::TYPE_DEPOSIT,
                ]);

                SavingsBookEntry::create([
                    'savings_book_id' => $savingsBook->id,
                    'transaction_id'  => $tx->id,
                    'entry_date'      => $tx->created_at->toDateString(),
                    'description'     => $tx->description ?? ucfirst(str_replace('_', ' ', $tx->type)),
                    'debit'           => $isIncoming ? $tx->amount : 0,
                    'credit'          => $isIncoming ? 0 : $tx->amount,
                    'balance'         => $tx->balance_after,
                ]);
            }

            $savingsBook->update(['last_printed' => now()]);
        });

        return redirect()->route('savings_books.show', $savingsBook)
            ->with('success', $transactions->count() . ' mutasi baru berhasil disinkronkan ke buku tabungan.');
    }

    /**
     * Unduh buku tabungan sebagai PDF.
     */
    public function download(SavingsBook $savingsBook)
    {
        $this->authorizeSavingsBook($savingsBook);

        $entries = $savingsBook->entries()
            ->with('transaction')
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();

        $savingsBook->load('account.user');

        // Menggunakan Pdf facade yang sudah di-import di atas
        $pdf = Pdf::loadView(
            'savings_books.pdf',
            compact('savingsBook', 'entries')
        )->setPaper('A4', 'landscape');

        return $pdf->download('buku-tabungan-' . $savingsBook->book_number . '.pdf');
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function authorizeSavingsBook(SavingsBook $savingsBook): void
    {
        /** @var User $user */
        $user = Auth::user();
        
        $userAccountIds = $user->accounts()->pluck('id');

        if (!$userAccountIds->contains($savingsBook->account_id) && !$user->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
    }

    private function generateBookNumber(): string
    {
        do {
            $number = 'SB' . str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT);
        } while (SavingsBook::where('book_number', $number)->exists());

        return $number;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Daftar semua rekening milik user yang login.
     */
    public function index()
    {
        $accounts = Account::where('user_id', Auth::id())
            ->withCount('transactions')
            ->latest()
            ->get();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Form buka rekening baru.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Simpan rekening baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_type' => ['required', Rule::in(['tabungan', 'giro', 'deposito'])],
            'pin'          => ['required', 'digits:6', 'confirmed'],
        ]);

        // Batasi 1 rekening per tipe per user
        $exists = Account::where('user_id', Auth::id())
            ->where('account_type', $request->account_type)
            ->whereIn('status', ['active', 'inactive'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kamu sudah memiliki rekening ' . $request->account_type . ' yang aktif.');
        }

        $account = Account::create([
            'user_id'        => Auth::id(),
            'account_number' => $this->generateAccountNumber(),
            'account_type'   => $request->account_type,
            'balance'        => 0,
            'currency'       => 'IDR',
            'status'         => 'active',
            'pin'            => Hash::make($request->pin),
            'opened_at'      => now(),
        ]);

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Rekening berhasil dibuka. Nomor rekening: ' . $account->account_number);
    }

    /**
     * Detail rekening + mutasi terkini.
     */
    public function show(Account $account)
    {
        $this->authorizeAccount($account);

        $account->load(['user', 'savingsBook']);

        $transactions = $account->transactions()
            ->with(['transfer', 'topUp', 'billPayment.bill'])
            ->latest()
            ->paginate(15);

        return view('accounts.show', compact('account', 'transactions'));
    }

    /**
     * Form ubah PIN rekening.
     */
    public function edit(Account $account)
    {
        $this->authorizeAccount($account);

        return view('accounts.edit', compact('account'));
    }

    /**
     * Proses ubah PIN.
     */
    public function update(Request $request, Account $account)
    {
        $this->authorizeAccount($account);

        $request->validate([
            'current_pin' => ['required', 'digits:6'],
            'pin'         => ['required', 'digits:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_pin, $account->pin)) {
            return back()->withErrors(['current_pin' => 'PIN saat ini tidak sesuai.']);
        }

        $account->update(['pin' => Hash::make($request->pin)]);

        return redirect()->route('accounts.show', $account)
            ->with('success', 'PIN berhasil diperbarui.');
    }

    /**
     * Tutup rekening (soft close).
     */
    public function destroy(Account $account)
    {
        $this->authorizeAccount($account);

        if ($account->balance > 0) {
            return back()->with('error', 'Saldo harus 0 sebelum rekening dapat ditutup.');
        }

        if ($account->loans()->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->with('error', 'Masih ada pinjaman aktif yang belum lunas.');
        }

        $account->update(['status' => 'closed']);

        return redirect()->route('accounts.index')
            ->with('success', 'Rekening berhasil ditutup.');
    }

    /**
     * Blokir / aktifkan kembali rekening (Admin).
     */
    public function toggleBlock(Account $account)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403);
        }

        $newStatus = $account->status === 'blocked' ? 'active' : 'blocked';
        $account->update(['status' => $newStatus]);

        $label = $newStatus === 'blocked' ? 'diblokir' : 'diaktifkan kembali';

        return back()->with('success', "Rekening {$account->account_number} berhasil {$label}.");
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function generateAccountNumber(): string
    {
        do {
            $number = 'INB' . mt_rand(1000000000, 9999999999);
        } while (Account::where('account_number', $number)->exists());

        return $number;
    }

    private function authorizeAccount(Account $account): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($account->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }
    }
}
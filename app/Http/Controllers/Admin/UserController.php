<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Daftar semua user dengan filter & pencarian.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->with(['accounts' => fn($q) => $q->select('id', 'user_id', 'account_type', 'balance', 'status')])
            ->withCount('accounts')
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Detail user: profil, rekening, transaksi terbaru.
     */
    public function show(User $user)
    {
        /** @var User $user */
        $user->load(['profile', 'accounts']);

        $recentTransactions = Transaction::whereIn('account_id', $user->accounts->pluck('id'))
            ->with('account')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'recentTransactions'));
    }

    /**
     * Form edit user (Admin).
     */
    public function edit(User $user)
    {
        /** @var User $user */
        $user->load('profile');
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user (Admin).
     */
    public function update(Request $request, User $user)
    {
        /** @var User $user */
        
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'      => ['nullable', 'string', 'max:20'],
            'role'       => ['required', Rule::in(['admin', 'user'])],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        // Cegah admin mengganti role diri sendiri
        if ($user->id === auth()->id()) {
            if ($request->role !== 'admin') {
                return back()->with('error', 'Tidak dapat mengganti role akun sendiri.');
            }
        }

        $user->update($request->only([
            'name', 'email', 'phone', 'role',
        ]) + ['is_active' => $request->boolean('is_active', $user->is_active)]);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Toggle aktif / nonaktif user.
     */
    public function toggleActive(User $user)
    {
        /** @var User $user */

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User {$user->name} berhasil {$status}.");
    }

    /**
     * Reset password user (Admin).
     */
    public function resetPassword(Request $request, User $user)
    {
        /** @var User $user */

        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password user ' . $user->name . ' berhasil direset.');
    }

    /**
     * Hapus user (Admin).
     */
    public function destroy(User $user)
    {
        /** @var User $user */

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->accounts()->where('balance', '>', 0)->exists()) {
            return back()->with('error', 'User masih memiliki saldo.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User ' . $user->name . ' berhasil dihapus.');
    }

    /**
     * Daftar semua rekening (Admin).
     */
    public function accounts(Request $request)
    {
        $accounts = Account::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q
                ->where('account_number', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', '%' . $request->search . '%')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.accounts.index', compact('accounts'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Menampilkan daftar nasabah dengan fitur Search dan Filter.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian (Nama atau Email)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Fitur Filter Role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Fitur Filter Status Aktif
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $users = $query->withCount('accounts')
            ->with('accounts')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString(); // Menjaga parameter filter saat pindah halaman pagination

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan detail nasabah beserta 10 transaksi terakhir.
     */
    public function show(User $user)
    {
        // Mengambil 10 transaksi terakhir dari semua rekening milik user ini
        $accountIds = $user->accounts->pluck('id');
        
        $recentTransactions = Transaction::whereIn('account_id', $accountIds)
            ->with('account')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.show', compact('user', 'recentTransactions'));
    }

    /**
     * Mengaktifkan atau Menonaktifkan User.
     */
    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'ditangguhkan';
        return back()->with('success', "Akun nasabah berhasil {$status}.");
    }

    /**
     * Reset Password Nasabah dari Modal.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', "Password untuk nasabah {$user->name} berhasil diperbarui.");
    }

    /**
     * Menghapus Nasabah secara permanen (Zona Berbahaya).
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            // Hapus relasi jika tidak menggunakan cascade delete di database
            $user->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Data nasabah telah dihapus permanen dari sistem.');
    }

    /**
     * Menampilkan form edit (untuk route admin.users.edit).
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
}
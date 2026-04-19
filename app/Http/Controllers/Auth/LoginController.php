<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi user.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->has('remember');

        // 2. Coba login (Attempt)
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // AMBIL DATA USER
            $user = Auth::user();

            // 3. LOGIKA REDIRECT BERDASARKAN ROLE
            // Jika role adalah admin, arahkan ke dashboard admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Portal Admin Terverifikasi. Selamat bekerja, ' . $user->name . '!');
            }

            // Jika nasabah biasa, arahkan ke dashboard user
            // Menggunakan intended() agar jika user sebelumnya mencoba akses fitur tertentu, 
            // dia akan dikembalikan ke halaman tersebut.
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        // 4. Jika gagal, lempar error ke form
        throw ValidationException::withMessages([
            'email' => ['Kredensial yang Anda berikan tidak cocok dengan data kami.'],
        ]);
    }

    /**
     * Proses Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Bersihkan session sepenuhnya
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil logout.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Menampilkan profil user (Dashboard Profil).
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Eager load relasi profile dan accounts agar efisien
        $user->load(['profile', 'accounts']);

        return view('profile.show', compact('user'));
    }

    /**
     * Menampilkan form edit profil.
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Memproses pembaruan data profil.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone'   => ['nullable', 'string', 'max:20'],
            'nik'     => ['nullable', 'string', 'size:16', Rule::unique('profiles', 'nik')->ignore($user->profile->id ?? 0)],
            'address' => ['nullable', 'string'],
            'city'    => ['nullable', 'string', 'max:100'],
        ]);

        // 1. Update data dasar di tabel users
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // 2. Update atau buat data tambahan di tabel profiles
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone'   => $request->phone,
                'nik'     => $request->nik,
                'address' => $request->address,
                'city'    => $request->city,
            ]
        );

        return redirect()->route('profile.show')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Opsional: Update Password (Jika dibutuhkan nanti)
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'min:8'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil user.
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load(['profile', 'accounts']);

        return view('profile.show', compact('user'));
    }

    /**
     * Form edit profil.
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');
        
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Simpan perubahan profil.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city'    => ['nullable', 'string', 'max:255'],
            'photo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->update([
            'name'  => $request->name,
            'phone' => $request->phone,
        ]);

        // Upload foto profil
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->update(['photo' => $path]);
        }

        // Upsert tabel profiles
        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik'     => $user->nik,
                'phone'   => $request->phone,
                'address' => $request->address,
                'city'    => $request->city,
            ]
        );

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Form ganti password.
     */
    public function editPassword()
    {
        return view('profile.password');
    }

    /**
     * Proses ganti password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah.');
    }

    /**
     * Hapus foto profil.
     */
    public function deletePhoto()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
        }

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }
}
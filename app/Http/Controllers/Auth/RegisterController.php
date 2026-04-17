<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Form registrasi nasabah baru.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi: buat user + profile + rekening tabungan otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['required', 'string', 'max:20'],
            'nik'        => ['required', 'string', 'size:16', 'unique:users,nik'],
            'birth_date' => ['required', 'date', 'before:-17 years'],
            'gender'     => ['required', Rule::in(['male', 'female'])],
            'address'    => ['required', 'string', 'max:1000'],
            'city'       => ['required', 'string', 'max:255'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'pin'        => ['required', 'digits:6', 'confirmed'],
        ], [
            'birth_date.before' => 'Usia minimal 17 tahun untuk membuka rekening.',
            'nik.size'          => 'NIK harus 16 digit.',
            'nik.unique'        => 'NIK sudah terdaftar.',
            'email.unique'      => 'Email sudah terdaftar.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'phone'      => $request->phone,
                'nik'        => $request->nik,
                'birth_date' => $request->birth_date,
                'gender'     => $request->gender,
                'address'    => $request->address,
                'password'   => Hash::make($request->password),
                'role'       => 'user',
                'is_active'  => true,
            ]);

            Profile::create([
                'user_id' => $user->id,
                'nik'     => $request->nik,
                'phone'   => $request->phone,
                'address' => $request->address,
                'city'    => $request->city,
            ]);

            Account::create([
                'user_id'        => $user->id,
                'account_number' => $this->generateAccountNumber(),
                'account_type'   => 'tabungan',
                'balance'        => 0,
                'currency'       => 'IDR',
                'status'         => 'active',
                'pin'            => Hash::make($request->pin),
                'opened_at'      => now(),
            ]);

            Auth::login($user);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang! Akun dan rekening tabungan Anda berhasil dibuat.');
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
}

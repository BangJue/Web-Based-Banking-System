<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    /**
     * Menampilkan Profil Eksklusif Admin
     */
    public function show()
{
    /** @var User $admin */
    $admin = Auth::user();
    $admin->load('profile');

    // Mengambil data statistik
    $pending_loans = Loan::where('status', 'pending')->count();
    $total_users = User::where('role', 'user')->count();

    // Kirim variabel ke view menggunakan compact dengan nama string-nya
    return view('admin.profile.show', compact('admin', 'pending_loans', 'total_users'));
}


}
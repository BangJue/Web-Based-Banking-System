<?php

namespace App\Http\Controllers;

use App\Models\User; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Sekarang Intelephense tahu $user adalah Model User yang punya method load()
        $user->load('profile');

        return view('profile', compact('user'));
    }
}
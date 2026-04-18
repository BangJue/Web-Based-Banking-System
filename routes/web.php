<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // Fix: Menghilangkan error Undefined Type Request
use Illuminate\Foundation\Auth\EmailVerificationRequest; // Fix: Menghilangkan error Undefined Type EmailVerificationRequest
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillPaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SavingsBookController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================
// Guest Only (Belum Login)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store']);
});

// ============================================================
// Authenticated User (Sudah Login)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // ── Verifikasi Email (Wajib di LUAR middleware 'verified') ──
    // Halaman pemberitahuan verifikasi (Notice)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Proses pengiriman ulang link verifikasi
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Proses verifikasi saat link di email diklik
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');


    // ============================================================
    // Route yang WAJIB TERVERIFIKASI (Sudah Klik Link Email)
    // ============================================================
    Route::middleware(['verified'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'user'])->name('home');
        Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');

        // ── Rekening ────────────────────────────────────────────
        Route::resource('accounts', AccountController::class);
        Route::patch('accounts/{account}/toggle-block', [AccountController::class, 'toggleBlock'])
            ->name('accounts.toggle-block');

        // ── Transaksi ───────────────────────────────────────────
        Route::controller(TransactionController::class)->group(function () {
            Route::get('transactions', 'index')->name('transactions.index');
            Route::get('transactions/{transaction}', 'show')->name('transactions.show');
            Route::get('transactions/{transaction}/receipt', 'receipt')->name('transactions.receipt');
            Route::get('accounts/{account}/transactions', 'byAccount')->name('transactions.by_account');
        });

        // ── Transfer ────────────────────────────────────────────
        Route::middleware(['auth', 'verified'])->group(function () {
            // Route untuk menampilkan form transfer
            Route::get('/transfer', [TransferController::class, 'create'])->name('transfers.create');
            // TAMBAHKAN BARIS INI:
            Route::get('/transfer/check', [TransferController::class, 'checkDestination'])->name('transfers.check');
            // Route untuk proses submit transfer
            Route::post('/transfer', [TransferController::class, 'store'])->name('transfers.store');
            // Riwayat transfer
            Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
        });

        // ── Top Up (User) ───────────────────────────────────────
        Route::controller(TopUpController::class)->group(function () {
            Route::get('top-up', 'create')->name('top_ups.create');
            Route::post('top-up', 'store')->name('top_ups.store');
            Route::get('top-ups', 'index')->name('top_ups.index');
            Route::get('top-ups/{topUp}', 'show')->name('top_ups.show');
        });

        // ── Tagihan ─────────────────────────────────────────────
        Route::get('bills', [BillController::class, 'index'])->name('bills.index');
        Route::controller(BillPaymentController::class)->group(function () {
            Route::post('bill-payments/inquiry', 'inquiry')->name('bill_payments.inquiry');
            Route::get('bills/{bill}/pay', 'create')->name('bill_payments.create');
            Route::post('bill-payments', 'store')->name('bill_payments.store');
            Route::get('bill-payments/history', 'history')->name('bill_payments.history');
            Route::get('bill-payments/{billPayment}', 'show')->name('bill_payments.show');
        });

        // ── Pinjaman ────────────────────────────────────────────
        Route::controller(LoanController::class)->group(function () {
            Route::get('loans', 'index')->name('loans.index');
            Route::get('loans/apply', 'create')->name('loans.create');
            Route::post('loans/simulate', 'simulate')->name('loans.simulate');
            Route::post('loans', 'store')->name('loans.store');
            Route::get('loans/{loan}', 'show')->name('loans.show');
            Route::post('loans/{loan}/pay', 'pay')->name('loans.pay');
        });

        // ── Buku Tabungan ───────────────────────────────────────
        Route::controller(SavingsBookController::class)->group(function () {
            Route::get('savings-books', 'index')->name('savings_books.index');
            Route::post('savings-books', 'store')->name('savings_books.store');
            Route::get('savings-books/{savingsBook}', 'show')->name('savings_books.show');
            Route::post('savings-books/{savingsBook}/sync', 'sync')->name('savings_books.sync');
            Route::get('savings-books/{savingsBook}/download', 'download')->name('savings_books.download');
        });

        // ── Profil ──────────────────────────────────────────────
        Route::controller(ProfileController::class)->group(function () {
            Route::get('profile', 'show')->name('profile.show');
            Route::get('profile/edit', 'edit')->name('profile.edit');
            Route::put('profile', 'update')->name('profile.update');
            Route::get('profile/password', 'editPassword')->name('profile.password');
            Route::put('profile/password', 'updatePassword')->name('profile.password.update');
            Route::delete('profile/photo', 'deletePhoto')->name('profile.photo.delete');
        });

        // ============================================================
        // Admin Area (Khusus Role Admin & Sudah Verifikasi)
        // ============================================================
        Route::prefix('admin')->name('admin.')->group(function () {

            // Dashboard Admin
            Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

            // ── Manajemen User ──────────────────────────────────────
            Route::resource('users', AdminUserController::class)->except(['create', 'store']);
            Route::controller(AdminUserController::class)->group(function () {
                Route::patch('users/{user}/toggle-active', 'toggleActive')->name('users.toggle_active');
                Route::post('users/{user}/reset-password', 'resetPassword')->name('users.reset_password');
                Route::get('accounts', 'accounts')->name('accounts.index');
            });

            // ── Manajemen Tagihan (Admin CRUD) ──────────────────────
            Route::resource('bills', BillController::class)->except(['index', 'show']);

            // ── Manajemen Pinjaman ──────────────────────────────────
            Route::controller(LoanController::class)->group(function () {
                Route::post('loans/{loan}/approve', 'approve')->name('loans.approve');
                Route::post('loans/{loan}/reject', 'reject')->name('loans.reject');
            });

            // ── Top Up Manual oleh Admin ────────────────────────────
            Route::controller(TopUpController::class)->group(function () {
                Route::get('accounts/{account}/top-up', 'create')->name('accounts.top_up');
                Route::post('accounts/{account}/top-up', 'store')->name('accounts.top_up.store');
            });
        });
    });
});
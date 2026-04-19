<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard utama nasabah.
     */
    public function user()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Memuat relasi profile dan accounts sekaligus agar lebih efisien
        $user->load(['profile', 'accounts']);

        $accountIds = $user->accounts->pluck('id');

        // Mengambil koleksi dari relasi yang sudah di-load (menghindari query ulang)
        $accounts     = $user->accounts->where('status', 'active');
        $totalBalance = $accounts->sum('balance');

        // 5 transaksi terbaru
        $recentTransactions = Transaction::whereIn('account_id', $accountIds)
            ->where('status', 'success')
            ->with('account')
            ->latest()
            ->limit(5)
            ->get();

        // Pinjaman aktif / jatuh tempo
        $activeLoans = Loan::whereIn('account_id', $accountIds)
            ->whereIn('status', [Loan::STATUS_ACTIVE, Loan::STATUS_OVERDUE])
            ->with('account')
            ->get();

        // Statistik 30 hari terakhir (kredit vs debit)
        $monthlyStats = Transaction::whereIn('account_id', $accountIds)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(30))
            ->select('type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        // Data grafik mutasi harian 7 hari terakhir
        $chartData = Transaction::whereIn('account_id', $accountIds)
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type IN ("transfer_in","top_up","loan_disbursement","deposit") THEN amount ELSE 0 END) as credit'),
                DB::raw('SUM(CASE WHEN type NOT IN ("transfer_in","top_up","loan_disbursement","deposit") THEN amount ELSE 0 END) as debit')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard.user', compact(
            'user',
            'accounts',
            'totalBalance',
            'recentTransactions',
            'activeLoans',
            'monthlyStats',
            'chartData'
        ));
    }

    /**
     * Dashboard admin.
     */
    public function admin()
    {
        $stats = [
            'total_users'        => User::where('role', 'user')->count(),
            'active_accounts'    => Account::where('status', 'active')->count(),
            'blocked_accounts'   => Account::where('status', 'blocked')->count(),
            'total_balance'      => Account::sum('balance'),
            'pending_loans'      => Loan::where('status', Loan::STATUS_PENDING)->count(),
            'active_loans'       => Loan::where('status', Loan::STATUS_ACTIVE)->count(),
            'overdue_loans'      => Loan::where('status', Loan::STATUS_OVERDUE)->count(),
            'today_transactions' => Transaction::whereDate('created_at', today())
                ->where('status', 'success')->count(),
            'today_volume'       => Transaction::whereDate('created_at', today())
                ->where('status', 'success')->sum('amount'),
        ];

        // Grafik volume transaksi harian 7 hari terakhir
        $dailyTransactions = Transaction::where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as volume')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Pinjaman pending menunggu persetujuan
        $pendingLoans = Loan::where('status', Loan::STATUS_PENDING)
            ->with('account.user')
            ->latest()
            ->limit(10)
            ->get();

        // User baru terdaftar
        $newUsers = User::where('role', 'user')
            ->with('accounts')
            ->latest()
            ->limit(5)
            ->get();

        // Distribusi tipe transaksi hari ini
        $todayTypeDistribution = Transaction::whereDate('created_at', today())
            ->where('status', 'success')
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        return view('admin.dashboard.admin', compact(
            'stats',
            'dailyTransactions',
            'pendingLoans',
            'newUsers',
            'todayTypeDistribution'
        ));
    }
}
@extends('layouts.admin')

@section('title', 'Executive Dashboard')

@section('content')
<div class="space-y-7">

    {{-- ================================================================
         HEADER
    ================================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Indonesia National Bank</p>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-0.5">Executive Dashboard</h1>
            <p class="text-gray-400 text-sm font-medium mt-0.5">Monitoring performa & operasional sistem hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5 bg-white border border-gray-100 shadow-sm px-4 py-3 rounded-2xl self-start sm:self-auto">
            <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-calendar-day text-white text-xs"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none">Hari Ini</p>
                <p class="text-sm font-black text-gray-900 mt-0.5">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         ROW 1 · STATS CARDS
    ================================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Total Dana --}}
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 flex flex-col justify-between gap-5">
            <div class="flex items-start justify-between">
                <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-vault text-green-600 text-base"></i>
                </div>
                <span class="text-[9px] font-black text-green-600 bg-green-50 px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                    <i class="fa-solid fa-arrow-trend-up text-[8px]"></i> Stabil
                </span>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Total Dana Nasabah</p>
                <h3 class="text-xl font-black text-gray-900 leading-none">
                    Rp {{ number_format($stats['total_balance'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- Volume Transaksi --}}
        <div class="bg-slate-900 rounded-[1.75rem] shadow-xl p-6 flex flex-col justify-between gap-5">
            <div class="w-11 h-11 bg-indigo-600/20 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-bolt text-indigo-400 text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest leading-none mb-2">Volume Hari Ini</p>
                <h3 class="text-xl font-black text-indigo-400 leading-none">
                    Rp {{ number_format($stats['today_volume'], 0, ',', '.') }}
                </h3>
                <p class="text-[10px] font-bold text-gray-500 mt-2">{{ $stats['today_transactions'] }} transaksi berhasil</p>
            </div>
        </div>

        {{-- Status Akun --}}
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 flex flex-col justify-between gap-5">
            <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-600 text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Status Akun</p>
                <h3 class="text-xl font-black text-gray-900 leading-none mb-3">{{ $stats['total_users'] }} <span class="text-xs text-gray-400 font-bold">Nasabah</span></h3>
                <div class="flex gap-3">
                    <span class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1 rounded-full uppercase">
                        {{ $stats['active_accounts'] }} Aktif
                    </span>
                    <span class="text-[10px] font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-full uppercase">
                        {{ $stats['blocked_accounts'] }} Blokir
                    </span>
                </div>
            </div>
        </div>

        {{-- Status Pinjaman --}}
        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6 flex flex-col justify-between gap-5">
            <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-stamp text-amber-600 text-base"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-2">Status Pinjaman</p>
                <h3 class="text-xl font-black text-amber-600 leading-none mb-3">{{ $stats['pending_loans'] }} <span class="text-xs text-gray-400 font-bold">Menunggu</span></h3>
                <div class="flex gap-3">
                    <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full uppercase">
                        {{ $stats['active_loans'] }} Aktif
                    </span>
                    <span class="text-[10px] font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-full uppercase">
                        {{ $stats['overdue_loans'] }} Lewat
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- ================================================================
         ROW 2 · LOAN APPROVAL TABLE + RIGHT COLUMN
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT: Loan approval --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Persetujuan Pinjaman --}}
            <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-7 py-5 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-base text-gray-900 leading-none">Persetujuan Pinjaman Baru</h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-1">Pengajuan yang menunggu review</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">
                            {{ $pendingLoans->count() }} Pending
                        </span>
                        <a href="{{ route('admin.loans.index') }}"
                           class="text-indigo-600 text-[10px] font-black hover:underline uppercase tracking-wider">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50/60">
                                <th class="px-6 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Nasabah</th>
                                <th class="px-6 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Jumlah</th>
                                <th class="px-6 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Tenor</th>
                                <th class="px-6 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest hidden lg:table-cell">Tujuan</th>
                                <th class="px-6 py-3.5 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pendingLoans as $loan)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900 text-sm leading-none">{{ $loan->account->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono mt-1">{{ $loan->account->account_number }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-black text-gray-900 leading-none">Rp {{ number_format($loan->principal, 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1">Bunga {{ $loan->interest_rate }}%</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-bold text-gray-700">{{ $loan->tenor_months }} Bln</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}/bln</p>
                                </td>
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <p class="text-xs text-gray-400 italic max-w-[140px] truncate">{{ $loan->purpose ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.loans.show', $loan->id) }}"
                                       class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-600 text-[11px] font-black px-3.5 py-2 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                                        Review <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fa-solid fa-check-circle text-gray-200 text-3xl mb-2 block"></i>
                                    <p class="text-gray-400 text-sm font-medium">Tidak ada pengajuan pinjaman baru.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Aktivitas 7 Hari --}}
            <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-7">
                <div class="mb-6">
                    <h3 class="font-black text-base text-gray-900 leading-none">Aktivitas 7 Hari Terakhir</h3>
                    <p class="text-[10px] text-gray-400 font-medium mt-1">Volume transaksi harian</p>
                </div>
                @php $maxVolume = $dailyTransactions->max('volume') ?: 1; @endphp
                <div class="space-y-3.5">
                    @foreach($dailyTransactions as $dt)
                    <div class="flex items-center gap-4">
                        <p class="w-14 text-[10px] font-black text-gray-400 uppercase shrink-0">
                            {{ \Carbon\Carbon::parse($dt->date)->format('d M') }}
                        </p>
                        <div class="flex-1 h-2.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full transition-all duration-700"
                                 style="width: {{ min(($dt->volume / $maxVolume) * 100, 100) }}%"></div>
                        </div>
                        <p class="w-36 text-right text-xs font-black text-gray-700 shrink-0">
                            Rp {{ number_format($dt->volume, 0, ',', '.') }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-6">

            {{-- Nasabah Terbaru --}}
            <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-black text-base text-gray-900 leading-none">Nasabah Terbaru</h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-1">Registrasi terkini</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="text-indigo-600 text-[10px] font-black hover:underline uppercase tracking-wider">
                        Semua
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($newUsers as $u)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center font-black text-gray-600 text-sm flex-shrink-0">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 leading-none truncate">{{ $u->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5 truncate">{{ $u->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $u->id) }}"
                           class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center text-gray-400 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Jenis Transaksi Hari Ini --}}
            <div class="bg-indigo-600 rounded-[1.75rem] p-6 text-white shadow-xl shadow-indigo-200">
                <div class="flex items-center gap-2.5 mb-5">
                    <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-chart-pie text-white text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-sm leading-none">Distribusi Transaksi</h3>
                        <p class="text-[10px] text-indigo-300 font-medium mt-0.5">Hari ini</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @forelse($todayTypeDistribution as $type)
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-indigo-100 capitalize">
                            {{ ucwords(str_replace('_', ' ', $type->type)) }}
                        </span>
                        <span class="bg-white/15 border border-white/10 px-3 py-1 rounded-lg text-xs font-black tabular-nums">
                            {{ $type->count }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fa-solid fa-inbox text-indigo-400 text-2xl mb-2 block"></i>
                        <p class="text-xs text-indigo-300 font-medium">Belum ada transaksi hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
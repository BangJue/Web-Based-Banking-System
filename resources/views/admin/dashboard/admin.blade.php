@extends('layouts.admin')

@section('title', 'Executive Dashboard')

@section('content')
<div class="max-w-[1600px] mx-auto space-y-8 pb-10">

    {{-- ================================================================
         HEADER SECTION
    ================================================================ --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-gray-200 pb-8">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="w-8 h-[2px] bg-blue-600"></span>
                <p class="text-[11px] font-black text-blue-600 uppercase tracking-[0.3em]">System Overview</p>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Executive Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Laporan analitik performa operasional <span class="font-bold text-slate-700">Indonesia National Bank</span> hari ini.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-2 pr-5 rounded-2xl border border-gray-200 shadow-sm">
            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center border border-gray-100 text-blue-600">
                <i class="fa-solid fa-calendar-check text-lg"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Data Per Tanggal</p>
                <p class="text-sm font-black text-slate-900 mt-1">{{ now()->format('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         TOP STATS GRID
    ================================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

        {{-- Total Dana - White Minimalist --}}
        <div class="group bg-white rounded-3xl border border-gray-200 p-6 transition-all duration-300 hover:border-blue-400 hover:shadow-xl hover:shadow-blue-500/5 relative overflow-hidden">
            <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                <div class="flex items-start justify-between">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-vault text-xl"></i>
                    </div>
                    <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Stabil
                    </div>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Dana Nasabah</p>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">
                        <span class="text-sm font-bold text-slate-400 mr-0.5 font-sans">Rp</span>{{ number_format($stats['total_balance'], 0, ',', '.') }}
                    </h3>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-[0.03] text-7xl rotate-12 group-hover:rotate-0 transition-transform duration-500">
                <i class="fa-solid fa-vault text-slate-900"></i>
            </div>
        </div>

        {{-- Volume Transaksi - Dark Slate (High Contrast) --}}
        <div class="group bg-slate-900 rounded-3xl p-6 shadow-2xl shadow-slate-900/20 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                <div class="w-12 h-12 bg-white/10 text-blue-400 rounded-2xl flex items-center justify-center backdrop-blur-md">
                    <i class="fa-solid fa-bolt-lightning text-xl"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Volume Hari Ini</p>
                    <h3 class="text-2xl font-black text-white tracking-tighter italic">
                        <span class="text-sm font-bold text-slate-500 mr-0.5">Rp</span>{{ number_format($stats['today_volume'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[10px] font-bold text-blue-400 mt-2 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-[8px]"></i> {{ $stats['today_transactions'] }} Transaksi Berhasil
                    </p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-blue-600/10 rounded-full blur-3xl"></div>
        </div>

        {{-- Status Akun --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-6 flex flex-col justify-between gap-6 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center border border-gray-100">
                <i class="fa-solid fa-users-gear text-xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Populasi Nasabah</p>
                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['total_users'] }} <span class="text-xs text-slate-400 font-bold uppercase ml-1">Jiwa</span></h3>
                <div class="flex gap-2 mt-3">
                    <span class="flex-1 text-center text-[9px] font-black text-emerald-600 bg-emerald-50 py-2 rounded-xl border border-emerald-100 uppercase">
                        {{ $stats['active_accounts'] }} Aktif
                    </span>
                    <span class="flex-1 text-center text-[9px] font-black text-rose-600 bg-rose-50 py-2 rounded-xl border border-rose-100 uppercase">
                        {{ $stats['blocked_accounts'] }} Blokir
                    </span>
                </div>
            </div>
        </div>

        {{-- Status Pinjaman --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-6 flex flex-col justify-between gap-6 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center border border-amber-100">
                <i class="fa-solid fa-file-invoice-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Antrian Kredit</p>
                <h3 class="text-2xl font-black text-amber-600 tracking-tighter">{{ $stats['pending_loans'] }} <span class="text-xs text-slate-400 font-bold uppercase ml-1 font-sans italic">Pending</span></h3>
                <div class="flex gap-2 mt-3">
                    <span class="flex-1 text-center text-[9px] font-black text-blue-600 bg-blue-50 py-2 rounded-xl border border-blue-100 uppercase tracking-tighter">
                        {{ $stats['active_loans'] }} Berjalan
                    </span>
                    <span class="flex-1 text-center text-[9px] font-black text-rose-600 bg-rose-50 py-2 rounded-xl border border-rose-100 uppercase">
                        {{ $stats['overdue_loans'] }} Lewat
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         MAIN CONTENT AREA
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- LEFT COLUMN: Loan approval --}}
        <div class="xl:col-span-2 space-y-8">

            {{-- Table Card --}}
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="font-black text-lg text-slate-900 tracking-tight">Persetujuan Pinjaman</h3>
                        <p class="text-xs text-slate-400 font-medium">Review manual pengajuan kredit nasabah</p>
                    </div>
                    <a href="{{ route('admin.loans.index') }}" class="bg-slate-50 text-slate-600 text-[11px] font-black px-4 py-2 rounded-xl border border-gray-200 hover:bg-slate-900 hover:text-white transition-all uppercase tracking-widest">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nasabah</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tenor</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pendingLoans as $loan)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-black text-xs border border-gray-200 group-hover:bg-white transition-colors">
                                            {{ strtoupper(substr($loan->account->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-900 text-sm tracking-tight">{{ $loan->account->user->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $loan->account->account_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-black text-slate-900 tracking-tight">Rp {{ number_format($loan->principal, 0, ',', '.') }}</p>
                                    <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md uppercase">Bunga {{ $loan->interest_rate }}%</span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-bold text-slate-700 tracking-tighter">{{ $loan->tenor_months }} Bulan</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Cicilan: Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}/bln</p>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('admin.loans.show', $loan->id) }}"
                                       class="inline-flex items-center gap-2 bg-blue-600 text-white text-[11px] font-black px-5 py-2.5 rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-blue-500/20 hover:shadow-none">
                                       Review <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-gray-300">
                                        <i class="fa-solid fa-mug-hot text-gray-300 text-xl"></i>
                                    </div>
                                    <p class="text-slate-400 text-sm font-medium tracking-tight">Semua pengajuan telah diproses.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 7-Day Performance Chart (Clean Style) --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="font-black text-lg text-slate-900 tracking-tight">Performa Transaksi</h3>
                        <p class="text-xs text-slate-400 font-medium mt-1">Statistik volume 7 hari terakhir</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Volume (IDR)</span>
                    </div>
                </div>
                
                @php $maxVolume = $dailyTransactions->max('volume') ?: 1; @endphp
                <div class="space-y-6">
                    @foreach($dailyTransactions as $dt)
                    <div class="group">
                        <div class="flex justify-between items-end mb-2">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($dt->date)->format('d M') }}
                            </p>
                            <p class="text-xs font-black text-slate-900 tabular-nums">
                                <span class="text-[10px] text-slate-400 font-normal mr-1">IDR</span>{{ number_format($dt->volume, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-gray-100 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-1000 ease-out group-hover:brightness-110"
                                 style="width: {{ min(($dt->volume / $maxVolume) * 100, 100) }}%">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-8">

            {{-- New Members List --}}
            <div class="bg-white rounded-3xl border border-gray-200 p-7 shadow-sm overflow-hidden relative">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="font-black text-base text-slate-900 tracking-tight">Nasabah Baru</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Join Recently</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="w-8 h-8 rounded-full bg-slate-50 border border-gray-200 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                
                <div class="space-y-6 relative z-10">
                    @foreach($newUsers as $u)
                    <div class="flex items-center gap-4 group">
                        <div class="w-11 h-11 bg-white border border-gray-200 rounded-2xl flex items-center justify-center font-black text-blue-600 text-sm shadow-sm group-hover:border-blue-600 transition-colors">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-900 leading-none truncate tracking-tight">{{ $u->name }}</p>
                            <p class="text-[11px] text-slate-400 font-medium mt-1 truncate">{{ $u->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $u->id) }}" class="text-slate-300 hover:text-blue-600 transition-colors">
                            <i class="fa-solid fa-circle-user text-xl"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Type Distribution - Premium Card --}}
            <div class="bg-gradient-to-br from-blue-700 to-indigo-900 rounded-3xl p-8 text-white shadow-2xl shadow-blue-900/30 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                            <i class="fa-solid fa-chart-pie text-blue-200"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-sm uppercase tracking-widest">Distribusi Transaksi</h3>
                            <p class="text-[10px] text-blue-200/60 font-medium">Berdasarkan Tipe (Hari Ini)</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($todayTypeDistribution as $type)
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between text-xs font-bold">
                                <span class="text-blue-100/80 tracking-wide">{{ ucwords(str_replace('_', ' ', $type->type)) }}</span>
                                <span class="tabular-nums">{{ $type->count }}</span>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-300 rounded-full" style="width: {{ ($type->count / max($todayTypeDistribution->sum('count'), 1)) * 100 }}%"></div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-hourglass-start text-blue-300/30"></i>
                            </div>
                            <p class="text-[11px] text-blue-300/50 font-black uppercase tracking-widest">No Traffic Yet</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                {{-- Decorative circles --}}
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-blue-400/10 rounded-full -ml-12 -mb-12 blur-2xl"></div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Custom font-black tracking for premium feel */
    .tracking-tighter { letter-spacing: -0.05em; }
    .tracking-tight { letter-spacing: -0.025em; }
</style>
@endsection
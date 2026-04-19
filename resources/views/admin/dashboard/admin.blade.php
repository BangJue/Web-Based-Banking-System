@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Executive Dashboard</h1>
            <p class="text-gray-500 text-sm font-medium">Monitoring performa Indonesia National Bank (INB) hari ini.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
            <div class="bg-blue-600 text-white p-2 rounded-xl">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="pr-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Hari Ini</p>
                <p class="text-sm font-bold text-gray-800">{{ now()->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Dana Nasabah</p>
            <h3 class="text-2xl font-black text-gray-800 leading-none">Rp {{ number_format($stats['total_balance'], 0, ',', '.') }}</h3>
            <div class="mt-4 flex items-center text-[11px] font-bold text-green-500 bg-green-50 w-fit px-2 py-1 rounded-lg">
                <i class="fas fa-arrow-up mr-1"></i> Stabil
            </div>
        </div>

        <div class="bg-gray-900 p-6 rounded-[2.5rem] shadow-xl text-white">
            <p class="text-gray-500 text-[10px] font-black uppercase tracking-widest mb-1">Volume Transaksi Hari Ini</p>
            <h3 class="text-2xl font-black text-blue-400 leading-none">Rp {{ number_format($stats['today_volume'], 0, ',', '.') }}</h3>
            <p class="mt-4 text-[11px] font-bold text-gray-400">{{ $stats['today_transactions'] }} Transaksi Berhasil</p>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Status Akun</p>
            <div class="flex justify-between items-end">
                <h3 class="text-2xl font-black text-gray-800 leading-none">{{ $stats['total_users'] }} <span class="text-xs text-gray-400 font-bold uppercase">Users</span></h3>
                <div class="text-right">
                    <p class="text-[10px] text-green-500 font-bold uppercase">{{ $stats['active_accounts'] }} Active</p>
                    <p class="text-[10px] text-red-500 font-bold uppercase">{{ $stats['blocked_accounts'] }} Blocked</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm">
            <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Status Pinjaman</p>
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-2xl font-black text-yellow-600 leading-none">{{ $stats['pending_loans'] }}</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Menunggu</p>
                </div>
                <div class="text-right border-l pl-4 border-gray-50">
                    <p class="text-[10px] text-blue-600 font-bold uppercase">{{ $stats['active_loans'] }} Active</p>
                    <p class="text-[10px] text-red-600 font-bold uppercase">{{ $stats['overdue_loans'] }} Overdue</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 flex items-center justify-between border-b border-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Persetujuan Pinjaman Baru</h3>
                    <span class="bg-yellow-100 text-yellow-600 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">{{ $pendingLoans->count() }} Pengajuan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/50">
                            <tr class="text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                <th class="px-8 py-4">Nasabah</th>
                                <th class="px-8 py-4">Jumlah</th>
                                <th class="px-8 py-4">Tenor</th>
                                <th class="px-8 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($pendingLoans as $loan)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <p class="font-bold text-gray-800 text-sm">{{ $loan->account->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium font-mono">{{ $loan->account->account_number }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <p class="text-sm font-black text-gray-800">Rp {{ number_format($loan->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs font-bold text-gray-500">{{ $loan->duration_months }} Bln</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.loans.show', $loan->id) }}" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 text-xs font-black px-4 py-2 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                        Review <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm italic">Tidak ada pengajuan pinjaman baru.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 font-black uppercase tracking-tight">Aktivitas 7 Hari Terakhir</h3>
                <div class="space-y-4">
                    @foreach($dailyTransactions as $dt)
                    <div class="flex items-center gap-4">
                        <p class="w-20 text-[10px] font-black text-gray-400 uppercase">{{ \Carbon\Carbon::parse($dt->date)->format('d M') }}</p>
                        <div class="flex-1 h-3 bg-gray-50 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(($dt->volume / max($stats['today_volume'], 1)) * 100, 100) }}%"></div>
                        </div>
                        <p class="w-32 text-right text-xs font-bold text-gray-700">Rp {{ number_format($dt->volume, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
                <h3 class="text-sm font-black text-gray-800 mb-6 uppercase tracking-widest">Nasabah Terbaru</h3>
                <div class="space-y-6">
                    @foreach($newUsers as $u)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-bold text-gray-500">
                            {{ substr($u->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-800 leading-none mb-1">{{ $u->name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">{{ $u->email }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $u->id) }}" class="text-gray-300 hover:text-blue-600">
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100">
                <h3 class="text-sm font-black mb-6 uppercase tracking-widest opacity-80">Jenis Transaksi Hari Ini</h3>
                <div class="space-y-4">
                    @foreach($todayTypeDistribution as $type)
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold capitalize">{{ str_replace('_', ' ', $type->type) }}</span>
                        <span class="bg-blue-400/30 px-3 py-1 rounded-lg text-xs font-black">{{ $type->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
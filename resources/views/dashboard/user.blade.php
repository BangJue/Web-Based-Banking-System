@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
<div class="space-y-8">

    {{-- ================================================================
         ROW 1 · BALANCE HERO + STATS
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-5">

        {{-- Balance card --}}
        <div class="xl:col-span-3 relative overflow-hidden bg-blue-600 rounded-[1.5rem] p-5 md:p-6 text-white shadow-xl shadow-blue-500/20 group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="text-blue-200 font-black tracking-[0.15em] uppercase text-[9px] opacity-80">
                            Total Saldo Tersedia
                        </p>
                        <span class="bg-white/10 text-[8px] px-2 py-0.5 rounded-md border border-white/10 font-mono">
                            {{ $user->name }} <!-- Menampilkan Nama Pemilik -->
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-4xl font-black mt-1 tracking-tighter">
                        IDR {{ number_format($totalBalance, 0, ',', '.') }}
                    </h1>

                    <div class="flex items-center gap-3 mt-2">
                        <!-- Menampilkan Nomor Rekening Utama -->
                        <div class="flex items-center gap-1.5 bg-black/10 px-2 py-1 rounded-lg">
                            <i class="fa-solid fa-fingerprint text-[10px] text-blue-300"></i>
                            <p class="text-white font-mono text-[11px] tracking-widest">
                                {{ $accounts->first()->account_number ?? 'No Account' }}
                            </p>
                        </div>
                        <p class="text-blue-200/80 text-[10px] font-medium">
                            {{ $accounts->count() }} Rekening Aktif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('transfers.create') }}"
                    class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-black hover:text-white transition-all duration-300 flex items-center gap-2 text-xs shadow-lg shadow-black/5">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i> Transfer
                    </a>
                    <a href="{{ route('top_ups.create') }}"
                    class="bg-white/15 border border-white/20 px-4 py-2 rounded-lg font-bold hover:bg-white/25 transition-all duration-300 flex items-center gap-2 text-xs backdrop-blur-sm">
                        <i class="fa-solid fa-plus text-[10px]"></i> Top Up
                    </a>
                </div>
            </div>

            {{-- Decorative Elements --}}
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700 pointer-events-none"></div>
            <div class="absolute right-6 top-5 opacity-10 text-[5rem] leading-none pointer-events-none select-none">
                <i class="fa-solid fa-building-columns"></i>
            </div>
        </div>

        {{-- Stats dark card --}}
        <div class="bg-black rounded-[2rem] p-6 text-white flex flex-col justify-between">
            <div>
                <p class="text-gray-500 text-[9px] font-black uppercase tracking-[0.2em]">Rekening Aktif</p>
                <h3 class="text-4xl font-black mt-2 leading-none">
                    {{ $accounts->count() }}
                </h3>
                <p class="text-blue-500 text-xs font-bold mt-1">Akun Terdaftar</p>
            </div>
            <div class="mt-6 pt-5 border-t border-white/10">
                <p class="text-gray-500 text-[9px] font-black uppercase tracking-[0.2em]">Pengeluaran Bulan Ini</p>
                @if($monthlyOutcome > 0)
                    <p class="text-2xl font-black text-red-400 mt-2 leading-none">
                        &minus; IDR {{ number_format($monthlyOutcome, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-2xl font-black text-green-400 mt-2 leading-none">
                        IDR 0
                    </p>
                    <p class="text-[10px] text-gray-600 mt-1">Tidak ada pengeluaran</p>
                @endif
            </div>
        </div>

    </div>

    {{-- ================================================================
         ROW 2 · QUICK ACTIONS
    ================================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
        $actions = [
            ['route' => route('transfers.create'),   'icon' => 'fa-paper-plane',          'label' => 'Transfer',    'color' => 'bg-blue-50 text-blue-600 hover:bg-blue-100'],
            ['route' => route('top_ups.create'),     'icon' => 'fa-circle-plus',           'label' => 'Top Up',      'color' => 'bg-green-50 text-green-600 hover:bg-green-100'],
            ['route' => route('loans.index'),        'icon' => 'fa-hand-holding-dollar',   'label' => 'Pinjaman',    'color' => 'bg-purple-50 text-purple-600 hover:bg-purple-100'],
            ['route' => route('savings_books.index'),'icon' => 'fa-book-open',             'label' => 'Buku Tab.',   'color' => 'bg-amber-50 text-amber-600 hover:bg-amber-100'],
        ];
        @endphp
        @foreach($actions as $act)
        <a href="{{ $act['route'] }}"
           class="{{ $act['color'] }} rounded-2xl p-5 flex flex-col items-center gap-3 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md group">
            <div class="w-11 h-11 rounded-xl bg-white/80 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                <i class="fa-solid {{ $act['icon'] }} text-base"></i>
            </div>
            <span class="text-xs font-black uppercase tracking-wider">{{ $act['label'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- ================================================================
         ROW 3 · CHART + LOANS
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Chart --}}
        <div class="xl:col-span-2 bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-black text-lg text-black leading-none">Arus Transaksi</h3>
                    <p class="text-gray-400 text-xs mt-1 font-medium">Pemasukan & pengeluaran</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 text-xs font-bold text-green-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span> Masuk
                    </div>
                    <div class="flex items-center gap-1.5 text-xs font-bold text-red-600 ml-3">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block"></span> Keluar
                    </div>
                </div>
            </div>

            @if($chartData->isEmpty())
                <div class="h-56 w-full bg-gray-50 rounded-2xl border border-dashed border-gray-200 flex flex-col items-center justify-center gap-2">
                    <i class="fa-solid fa-chart-column text-gray-300 text-3xl"></i>
                    <p class="text-gray-400 text-sm font-medium">Belum ada data transaksi</p>
                </div>
            @else
                <div class="h-56 w-full">
                    <canvas id="transactionFlowChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Loans --}}
        <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-lg text-black leading-none">Pinjaman Aktif</h3>
                    <p class="text-gray-400 text-xs mt-1 font-medium">Status cicilan berjalan</p>
                </div>
                <a href="{{ route('loans.index') }}" class="text-blue-600 text-xs font-black hover:underline uppercase tracking-wide">Lihat</a>
            </div>

            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-0.5 custom-scrollbar">
                @forelse($activeLoans as $loan)
                @php
                    $isOverdue  = $loan->status === \App\Models\Loan::STATUS_OVERDUE;
                    $dueDate    = $loan->due_date
                        ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y')
                        : 'N/A';
                    $progress = $loan->tenor_months > 0
                        ? round(($loan->paid_installments / $loan->tenor_months) * 100)
                        : 0;
                @endphp
                <div class="p-4 rounded-2xl {{ $isOverdue ? 'bg-red-50 border border-red-100' : 'bg-gray-50' }} transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm font-black text-black">Pinjaman #{{ $loan->id }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">Jatuh tempo: {{ $dueDate }}</p>
                        </div>
                        <span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider
                            {{ $isOverdue ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ $isOverdue ? 'Terlambat' : 'Aktif' }}
                        </span>
                    </div>

                    <p class="font-black text-sm {{ $isOverdue ? 'text-red-600' : 'text-black' }}">
                        IDR {{ number_format($loan->principal, 0, ',', '.') }}
                    </p>

                    <div class="flex justify-between text-[10px] text-gray-400 mt-2 mb-1.5">
                        <span>Sisa: <span class="font-bold text-gray-600">IDR {{ number_format($loan->remaining_debt, 0, ',', '.') }}</span></span>
                        <span>{{ $loan->paid_installments }}/{{ $loan->tenor_months }} &middot; {{ $progress }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full transition-all duration-500 {{ $isOverdue ? 'bg-red-500' : 'bg-blue-500' }}"
                             style="width: {{ $progress }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <i class="fa-solid fa-hand-holding-dollar text-gray-200 text-4xl mb-3"></i>
                    <p class="text-gray-400 text-sm font-medium">Tidak ada pinjaman aktif</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ================================================================
         ROW 4 · RECENT TRANSACTIONS
    ================================================================ --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-7 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-lg text-black leading-none">Transaksi Terkini</h3>
                <p class="text-gray-400 text-xs mt-1 font-medium">Aktivitas rekening terbaru</p>
            </div>
            <a href="{{ route('transactions.index') }}"
               class="text-blue-600 font-black text-xs hover:underline uppercase tracking-wider flex items-center gap-1.5">
                Lihat Semua <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        {{-- Mobile: card list | Desktop: table --}}

        {{-- DESKTOP TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60">
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Jenis</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Referensi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentTransactions as $tx)
                    @php
                        $isOutgoing = in_array($tx->type, [
                            'transfer', 'transfer_out', 'bill_payment',
                            'loan_payment', 'withdrawal', 'debit'
                        ]);
                        $typeLabel = ucwords(str_replace('_', ' ', $tx->type));
                    @endphp
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $isOutgoing ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                    <i class="fa-solid {{ $isOutgoing ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }} text-xs"></i>
                                </div>
                                <span class="font-bold text-black text-sm">{{ $typeLabel }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-gray-500">
                            {{ $tx->reference_code ?? ($tx->reference_number ?? '-') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium whitespace-nowrap">
                            {{ $tx->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-sm {{ $isOutgoing ? 'text-red-600' : 'text-green-600' }}">
                                {{ $isOutgoing ? '− ' : '+ ' }}IDR {{ number_format($tx->amount, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- MOBILE CARD LIST --}}
        <div class="md:hidden divide-y divide-gray-50">
            @foreach($recentTransactions as $tx)
            @php
                $isOutgoing = in_array($tx->type, [
                    'transfer', 'transfer_out', 'bill_payment',
                    'loan_payment', 'withdrawal', 'debit'
                ]);
                $typeLabel = ucwords(str_replace('_', ' ', $tx->type));
            @endphp
            <div class="flex items-center gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                    {{ $isOutgoing ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                    <i class="fa-solid {{ $isOutgoing ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }} text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-black truncate">{{ $typeLabel }}</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="font-black text-sm {{ $isOutgoing ? 'text-red-600' : 'text-green-600' }} whitespace-nowrap">
                    {{ $isOutgoing ? '−' : '+' }} IDR {{ number_format($tx->amount, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>

        @if($recentTransactions->isEmpty())
        <div class="text-center py-14">
            <i class="fa-solid fa-clock-rotate-left text-gray-200 text-4xl mb-3"></i>
            <p class="text-gray-400 text-sm font-medium">Belum ada transaksi</p>
        </div>
        @endif
    </div>

</div>
@endsection

{{-- ================================================================
     CHART.JS (no external dep, hanya CDN resmi yg sudah ada)
================================================================ --}}
@if($chartData->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);

    const labels  = chartData.map(r => r.date);
    const credits = chartData.map(r => parseFloat(r.credit) || 0);
    const debits  = chartData.map(r => parseFloat(r.debit)  || 0);

    const ctx = document.getElementById('transactionFlowChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Masuk',
                    data: credits,
                    backgroundColor: 'rgba(34,197,94,0.75)',
                    borderColor: 'rgba(34,197,94,1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Keluar',
                    data: debits,
                    backgroundColor: 'rgba(239,68,68,0.75)',
                    borderColor: 'rgba(239,68,68,1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' IDR ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { weight: '700', size: 11 }, color: '#9ca3af' }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: {
                        font: { weight: '700', size: 11 },
                        color: '#9ca3af',
                        callback: v => {
                            if (v >= 1_000_000) return 'IDR ' + (v/1_000_000).toFixed(1) + 'M';
                            if (v >= 1_000)     return 'IDR ' + (v/1_000).toFixed(0) + 'K';
                            return 'IDR ' + v;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endif
@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
<div class="space-y-6">

    {{-- ================================================================
         ROW 1 · BALANCE HERO + STATS
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-5">

        {{-- Balance card --}}
        <div class="xl:col-span-3 relative overflow-hidden rounded-[28px] p-7 md:p-9 text-white
                    bg-gradient-to-br from-[#0a1628] via-[#0d1f3c] to-[#091220]
                    shadow-[0_24px_60px_rgba(0,0,0,0.18)] group">

            {{-- Ambient glows --}}
            <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-blue-500/10 blur-3xl pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute -bottom-12 -left-10 w-56 h-56 rounded-full bg-indigo-500/8 blur-3xl pointer-events-none"></div>
            {{-- Faint building icon --}}
            <div class="absolute right-8 top-6 opacity-[0.06] text-[6rem] leading-none pointer-events-none select-none">
                <i class="fa-solid fa-building-columns"></i>
            </div>
            {{-- Grid overlay --}}
            <div class="absolute inset-0 opacity-[0.025] pointer-events-none"
                 style="background:linear-gradient(rgba(59,130,246,.6) 1px,transparent 1px),
                        linear-gradient(90deg,rgba(59,130,246,.6) 1px,transparent 1px);
                        background-size:40px 40px"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2.5 mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                        <p class="text-[10px] font-extrabold text-blue-300/80 uppercase tracking-[0.22em]">
                            Total Saldo Tersedia
                        </p>
                        <span class="bg-white/8 border border-white/10 text-[9px] font-mono
                                     px-2 py-0.5 rounded-lg text-blue-200/70">
                            {{ $user->name }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-[2.6rem] font-black tracking-tight leading-none">
                        IDR {{ number_format($totalBalance, 0, ',', '.') }}
                    </h1>

                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex items-center gap-1.5 bg-black/20 border border-white/8
                                    px-2.5 py-1.5 rounded-xl">
                            <i class="fa-solid fa-fingerprint text-[10px] text-blue-300"></i>
                            <p class="text-white/80 font-mono text-[11px] tracking-widest">
                                {{ $accounts->first()->account_number ?? '— —' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                            <p class="text-blue-200/70 text-[10px] font-bold">
                                {{ $accounts->count() }} Rekening Aktif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 flex-shrink-0">
                    <a href="{{ route('transfers.create') }}"
                       class="flex items-center gap-2 px-5 py-2.5 rounded-[12px] text-xs font-extrabold
                              bg-white text-blue-700 shadow-[0_4px_16px_rgba(0,0,0,0.2)]
                              hover:bg-blue-50 hover:scale-[1.03] transition-all duration-200">
                        <i class="fa-solid fa-paper-plane text-[10px]"></i> Transfer
                    </a>
                    <a href="{{ route('top_ups.create') }}"
                       class="flex items-center gap-2 px-5 py-2.5 rounded-[12px] text-xs font-extrabold
                              bg-white/12 border border-white/15 text-white
                              hover:bg-white/20 hover:scale-[1.03] transition-all duration-200 backdrop-blur-sm">
                        <i class="fa-solid fa-plus text-[10px]"></i> Top Up
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats dark card --}}
        <div class="relative overflow-hidden rounded-[28px] bg-[#0a0a0a] p-6 text-white flex flex-col justify-between
                    shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-blue-500/6 blur-3xl pointer-events-none"></div>

            <div class="relative">
                <p class="text-[9px] font-extrabold text-gray-500 uppercase tracking-[0.22em] mb-2">
                    Rekening Aktif
                </p>
                <h3 class="text-5xl font-black leading-none text-white">
                    {{ $accounts->count() }}
                </h3>
                <div class="flex items-center gap-1.5 mt-2">
                    <i class="fa-solid fa-credit-card text-[10px] text-blue-500"></i>
                    <p class="text-blue-500 text-[11px] font-bold">Akun Terdaftar</p>
                </div>
            </div>

            <div class="relative mt-6 pt-5 border-t border-white/8">
                <p class="text-[9px] font-extrabold text-gray-500 uppercase tracking-[0.22em] mb-2">
                    Pengeluaran Bulan Ini
                </p>
                @if($monthlyOutcome > 0)
                    <p class="text-[1.6rem] font-black text-red-400 leading-none">
                        &minus; IDR {{ number_format($monthlyOutcome, 0, ',', '.') }}
                    </p>
                @else
                    <p class="text-[1.6rem] font-black text-green-400 leading-none">IDR 0</p>
                    <p class="text-[10px] text-gray-600 mt-1 font-medium">Tidak ada pengeluaran</p>
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
            ['route' => route('transfers.create'),    'icon' => 'fa-paper-plane',         'label' => 'Transfer',   'bg' => 'bg-blue-50',   'text' => 'text-blue-600',   'hover' => 'hover:bg-blue-100',   'shadow' => 'hover:shadow-blue-100'],
            ['route' => route('top_ups.create'),      'icon' => 'fa-circle-plus',          'label' => 'Top Up',     'bg' => 'bg-green-50',  'text' => 'text-green-600',  'hover' => 'hover:bg-green-100',  'shadow' => 'hover:shadow-green-100'],
            ['route' => route('loans.index'),         'icon' => 'fa-hand-holding-dollar',  'label' => 'Pinjaman',   'bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'hover' => 'hover:bg-purple-100', 'shadow' => 'hover:shadow-purple-100'],
            ['route' => route('savings_books.index'), 'icon' => 'fa-book-open',            'label' => 'Buku Tab.',  'bg' => 'bg-amber-50',  'text' => 'text-amber-600',  'hover' => 'hover:bg-amber-100',  'shadow' => 'hover:shadow-amber-100'],
        ];
        @endphp
        @foreach($actions as $act)
        <a href="{{ $act['route'] }}"
           class="{{ $act['bg'] }} {{ $act['text'] }} {{ $act['hover'] }} {{ $act['shadow'] }}
                  rounded-2xl p-5 flex flex-col items-center gap-3
                  transition-all duration-200 hover:-translate-y-1
                  hover:shadow-lg group border border-transparent hover:border-current/10">
            <div class="w-11 h-11 rounded-xl bg-white shadow-sm
                        flex items-center justify-center
                        group-hover:scale-110 transition-transform duration-200">
                <i class="fa-solid {{ $act['icon'] }} text-base"></i>
            </div>
            <span class="text-[11px] font-extrabold uppercase tracking-wider">{{ $act['label'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- ================================================================
         ROW 3 · CHART + LOANS
    ================================================================ --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Chart --}}
        <div class="xl:col-span-2 bg-white rounded-[24px] p-7 shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="font-black text-lg text-black leading-none">Arus Transaksi</h3>
                    <p class="text-gray-400 text-xs mt-1 font-medium">Pemasukan &amp; pengeluaran 7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-[11px] font-bold text-[#2563eb]">
                        <span class="w-3 h-[3px] rounded-full bg-[#2563eb] inline-block"></span>
                        Masuk
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-bold text-[#f97316]">
                        <span class="w-3 h-[3px] rounded-full bg-[#f97316] inline-block"></span>
                        Keluar
                    </div>
                </div>
            </div>

            @if($chartData->isEmpty())
                <div class="h-56 w-full bg-gray-50 rounded-2xl border border-dashed border-gray-200
                            flex flex-col items-center justify-center gap-2">
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
        <div class="bg-white rounded-[24px] p-7 shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-black text-lg text-black leading-none">Pinjaman Aktif</h3>
                    <p class="text-gray-400 text-xs mt-1 font-medium">Status cicilan berjalan</p>
                </div>
                <a href="{{ route('loans.index') }}"
                   class="text-blue-600 text-[11px] font-extrabold hover:underline uppercase tracking-widest">
                    Lihat
                </a>
            </div>

            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-0.5"
                 style="scrollbar-width:thin;scrollbar-color:#e5e7eb transparent">
                @forelse($activeLoans as $loan)
                @php
                    $isOverdue = $loan->status === \App\Models\Loan::STATUS_OVERDUE;
                    $dueDate   = $loan->due_date
                        ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y')
                        : 'N/A';
                    $progress  = $loan->tenor_months > 0
                        ? round(($loan->paid_installments / $loan->tenor_months) * 100)
                        : 0;
                @endphp
                <div class="p-4 rounded-2xl border transition-colors
                            {{ $isOverdue ? 'bg-red-50 border-red-100' : 'bg-gray-50 border-gray-100' }}">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm font-black text-black">Pinjaman #{{ $loan->id }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                Jatuh tempo: {{ $dueDate }}
                            </p>
                        </div>
                        <span class="text-[9px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider
                            {{ $isOverdue ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                            {{ $isOverdue ? 'Terlambat' : 'Aktif' }}
                        </span>
                    </div>
                    <p class="font-black text-sm {{ $isOverdue ? 'text-red-600' : 'text-black' }}">
                        IDR {{ number_format($loan->principal, 0, ',', '.') }}
                    </p>
                    <div class="flex justify-between text-[10px] text-gray-400 mt-2 mb-1.5">
                        <span>Sisa:
                            <span class="font-bold text-gray-600">
                                IDR {{ number_format($loan->remaining_debt, 0, ',', '.') }}
                            </span>
                        </span>
                        <span>{{ $loan->paid_installments }}/{{ $loan->tenor_months }} &middot; {{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full transition-all duration-500
                                    {{ $isOverdue ? 'bg-red-500' : 'bg-blue-500' }}"
                             style="width:{{ $progress }}%"></div>
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
    <div class="bg-white rounded-[24px] shadow-[0_2px_16px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">

        <div class="px-7 py-5 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-lg text-black leading-none">Transaksi Terkini</h3>
                <p class="text-gray-400 text-xs mt-1 font-medium">Aktivitas rekening terbaru</p>
            </div>
            <a href="{{ route('transactions.index') }}"
               class="flex items-center gap-1.5 text-blue-600 font-extrabold text-[11px]
                      hover:underline uppercase tracking-widest group">
                Lihat Semua
                <i class="fa-solid fa-arrow-right text-[10px]
                          group-hover:translate-x-0.5 transition-transform duration-200"></i>
            </a>
        </div>

        {{-- DESKTOP TABLE --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/70">
                        <th class="px-7 py-3.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jenis</th>
                        <th class="px-7 py-3.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">No. Referensi</th>
                        <th class="px-7 py-3.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-7 py-3.5 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentTransactions as $tx)
                    @php
                        $isOutgoing = in_array($tx->type, [
                            'transfer','transfer_out','bill_payment',
                            'loan_payment','withdrawal','debit'
                        ]);
                        $typeLabel = ucwords(str_replace('_', ' ', $tx->type));
                    @endphp
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $isOutgoing ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                    <i class="fa-solid {{ $isOutgoing ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }} text-xs"></i>
                                </div>
                                <span class="font-bold text-black text-sm">{{ $typeLabel }}</span>
                            </div>
                        </td>
                        <td class="px-7 py-4 text-sm font-mono text-gray-400">
                            {{ $tx->reference_code ?? ($tx->reference_number ?? '—') }}
                        </td>
                        <td class="px-7 py-4 text-[12px] text-gray-400 font-medium whitespace-nowrap">
                            {{ $tx->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-7 py-4 text-right">
                            <span class="font-black text-sm {{ $isOutgoing ? 'text-orange-600' : 'text-blue-600' }}">
                                {{ $isOutgoing ? '−' : '+' }} IDR {{ number_format($tx->amount, 0, ',', '.') }}
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
                    'transfer','transfer_out','bill_payment',
                    'loan_payment','withdrawal','debit'
                ]);
                $typeLabel = ucwords(str_replace('_', ' ', $tx->type));
            @endphp
            <div class="flex items-center gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                    {{ $isOutgoing ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                    <i class="fa-solid {{ $isOutgoing ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }} text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-black truncate">{{ $typeLabel }}</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                        {{ $tx->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
                <span class="font-black text-sm {{ $isOutgoing ? 'text-orange-600' : 'text-blue-600' }} whitespace-nowrap">
                    {{ $isOutgoing ? '−' : '+' }} IDR {{ number_format($tx->amount, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
        </div>

        @if($recentTransactions->isEmpty())
        <div class="text-center py-16">
            <i class="fa-solid fa-clock-rotate-left text-gray-200 text-4xl mb-3"></i>
            <p class="text-gray-400 text-sm font-medium">Belum ada transaksi</p>
        </div>
        @endif
    </div>

</div>
@endsection

{{-- ================================================================
     CHART.JS — Excel-style minimalist bars
================================================================ --}}
@if($chartData->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);

    const labels  = chartData.map(r => {
        const d = new Date(r.date);
        return d.toLocaleDateString('id-ID', { day:'2-digit', month:'short' });
    });
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
                    /* ── Excel-style blue ── */
                    backgroundColor: 'rgba(37,99,235,0.82)',
                    hoverBackgroundColor: 'rgba(37,99,235,1)',
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: { topLeft:3, topRight:3, bottomLeft:0, bottomRight:0 },
                    borderSkipped: 'bottom',
                    /* Slim bars: categoryPercentage controls group width,
                       barPercentage controls individual bar within group */
                    categoryPercentage: 0.55,
                    barPercentage: 0.72,
                },
                {
                    label: 'Keluar',
                    data: debits,
                    /* ── Excel-style orange ── */
                    backgroundColor: 'rgba(249,115,22,0.80)',
                    hoverBackgroundColor: 'rgba(249,115,22,1)',
                    borderColor: 'transparent',
                    borderWidth: 0,
                    borderRadius: { topLeft:3, topRight:3, bottomLeft:0, bottomRight:0 },
                    borderSkipped: 'bottom',
                    categoryPercentage: 0.55,
                    barPercentage: 0.72,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    padding: 12,
                    cornerRadius: 10,
                    titleFont: { family: '"Plus Jakarta Sans"', size: 11, weight: '700' },
                    bodyFont:  { family: '"Plus Jakarta Sans"', size: 12, weight: '800' },
                    callbacks: {
                        title: items => items[0].label,
                        label: ctx => {
                            const val = ctx.parsed.y;
                            const formatted = val >= 1_000_000
                                ? 'IDR ' + (val/1_000_000).toFixed(2) + ' Jt'
                                : 'IDR ' + val.toLocaleString('id-ID');
                            return ' ' + ctx.dataset.label + ': ' + formatted;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        font: { family:'"Plus Jakarta Sans"', weight:'700', size:11 },
                        color: '#9ca3af',
                        padding: 6,
                    }
                },
                y: {
                    position: 'left',
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        lineWidth: 1,
                        drawTicks: false,
                    },
                    border: { display: false, dash: [3,3] },
                    ticks: {
                        font: { family:'"Plus Jakarta Sans"', weight:'700', size:10 },
                        color: '#9ca3af',
                        padding: 10,
                        maxTicksLimit: 5,
                        callback: v => {
                            if (v >= 1_000_000) return (v/1_000_000).toFixed(1) + 'Jt';
                            if (v >= 1_000)     return (v/1_000).toFixed(0) + 'K';
                            return v;
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
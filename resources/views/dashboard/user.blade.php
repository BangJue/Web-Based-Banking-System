@extends('layouts.app')

@section('title', 'Financial Overview')

@section('content')
<div class="space-y-10">

    {{-- ================= HERO + STATS ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        {{-- BALANCE --}}
        <div class="xl:col-span-3 relative overflow-hidden bg-blue-600 rounded-[2rem] p-8 text-white shadow-2xl shadow-blue-500/40 group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div>
                    <p class="text-blue-100 font-medium tracking-wider uppercase text-xs">
                        Total Available Balance
                    </p>
                    <h1 class="text-4xl md:text-5xl font-black mt-2 tracking-tight">
                        IDR {{ number_format($totalBalance, 0, ',', '.') }}
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('transfers.create') }}"
                       class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-black hover:text-white transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Send
                    </a>
                    <a href="{{ route('top_ups.create') }}"
                       class="bg-blue-500/30 backdrop-blur-md border border-white/20 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-all duration-300">
                        <i class="fa-solid fa-plus"></i> Top Up
                    </a>
                </div>

            </div>

            {{-- decorative --}}
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            <div class="absolute right-10 top-10 opacity-20 text-9xl">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        {{-- STATS --}}
        <div class="bg-black rounded-[2rem] p-6 text-white flex flex-col justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Active Accounts</p>
                <h3 class="text-3xl font-bold mt-1">
                    {{ $accounts->count() }}
                    <span class="text-blue-500 text-sm">Accounts</span>
                </h3>
            </div>

            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Monthly Outcome</p>
                <p class="text-2xl font-bold text-red-400 mt-1">
                    - IDR {{ number_format($monthlyStats['debit']->total ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>

    </div>


    {{-- ================= CHART + LOAN ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- CHART --}}
        <div class="xl:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-xl text-black">Transaction Flow</h3>
                <select class="bg-gray-50 border-none rounded-lg text-sm font-bold p-2 focus:ring-2 focus:ring-blue-500">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>

            {{-- IMPROVED CHART PLACEHOLDER --}}
            <div class="h-64 w-full bg-gray-50 rounded-2xl border border-gray-200 flex flex-col items-center justify-center gap-2">
                <i class="fa-solid fa-chart-line text-gray-300 text-3xl"></i>
                <p class="text-gray-400 text-sm">No data visualization yet</p>
            </div>
        </div>

        {{-- LOANS --}}
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
            <h3 class="font-bold text-xl text-black mb-6">Active Loans</h3>

            <div class="space-y-4 max-h-[320px] overflow-y-auto pr-1">
                @forelse($activeLoans as $loan)
                <div class="p-4 rounded-2xl bg-gray-50 hover:bg-blue-50 transition-colors group cursor-pointer">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-bold text-black group-hover:text-blue-600 transition-colors">
                                Loan #{{ $loan->id }}
                            </p>
                            <p class="text-xs text-gray-500 italic">
                                Due: {{ $loan->due_date ?? 'N/A' }}
                            </p>
                        </div>
                        <span class="text-xs font-black px-2 py-1 rounded bg-blue-100 text-blue-600 uppercase italic">
                            Active
                        </span>
                    </div>
                    <p class="mt-2 font-bold text-black">
                        IDR {{ number_format($loan->amount, 0, ',', '.') }}
                    </p>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fa-solid fa-receipt text-gray-200 text-5xl mb-3"></i>
                    <p class="text-gray-400 text-sm">No active loans found</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>


    {{-- ================= TABLE ================= --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-50 flex justify-between items-center">
        <h3 class="font-bold text-xl text-black">Recent Transactions</h3>
        <a href="{{ route('transactions.index') }}" class="text-blue-600 font-bold text-sm hover:underline">
            View All
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Reference</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Date</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Amount</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
                @foreach($recentTransactions as $tx)
                @php
                    // Logika untuk menentukan apakah uang keluar
                    // Sesuaikan dengan konstanta TYPE di model Transaction kamu
                    $isOutgoing = in_array($tx->type, ['transfer', 'bill_payment', 'loan_payment', 'withdrawal', 'debit']);
                @endphp
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $isOutgoing ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                <i class="fa-solid {{ $isOutgoing ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                            </div>
                            <span class="font-bold text-black capitalize">
                                {{ str_replace('_', ' ', $tx->type) }}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-gray-600">
                        {{ $tx->reference_number ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-400 font-medium">
                        {{ $tx->created_at->format('d M Y, H:i') }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <span class="font-black {{ $isOutgoing ? 'text-red-600' : 'text-green-600' }}">
                            {{ $isOutgoing ? '-' : '+' }}
                            IDR {{ number_format($tx->amount, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</div>
@endsection
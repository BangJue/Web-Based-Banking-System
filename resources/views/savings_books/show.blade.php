@extends('layouts.app')

@section('title', 'Detail Buku Tabungan ' . $savingsBook->book_number)

@section('content')
<div class="space-y-8">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 font-medium">
        <a href="{{ route('savings_books.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Buku Tabungan
        </a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-black font-bold font-mono">{{ $savingsBook->book_number }}</span>
    </div>

    {{-- ================= FLASH MESSAGES ================= --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4 text-sm font-medium">
        <i class="fa-solid fa-circle-check text-green-500"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 text-sm font-medium">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
    </div>
    @endif
    @if(session('info'))
    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl px-5 py-4 text-sm font-medium">
        <i class="fa-solid fa-circle-info text-blue-500"></i> {{ session('info') }}
    </div>
    @endif

    {{-- ================= HERO + STATS ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

        {{-- Buku Info (lebar) --}}
        <div class="xl:col-span-3 relative overflow-hidden bg-blue-600 rounded-[2rem] p-8 text-white shadow-2xl shadow-blue-500/40 group">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <span class="bg-white/15 text-white text-xs font-black px-3 py-1 rounded-full font-mono tracking-widest">
                        {{ $savingsBook->book_number }}
                    </span>
                    <p class="text-3xl md:text-4xl font-black mt-4 font-mono tracking-wider">
                        {{ $savingsBook->account->account_number }}
                    </p>
                    <p class="text-blue-200 text-sm mt-1 font-bold uppercase tracking-wider">
                        {{ ucfirst($savingsBook->account->account_type) }} · {{ $savingsBook->account->currency }}
                        · {{ $savingsBook->account->user->name }}
                    </p>
                    <p class="text-blue-300 text-xs mt-2">
                        Diterbitkan {{ \Carbon\Carbon::parse($savingsBook->issued_at)->format('d M Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('savings_books.sync', $savingsBook) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-black hover:text-white transition-all duration-300 flex items-center gap-2">
                            <i class="fa-solid fa-rotate"></i> Sync Mutasi
                        </button>
                    </form>
                    <a href="{{ route('savings_books.download', $savingsBook) }}"
                       class="bg-blue-500/30 backdrop-blur-md border border-white/20 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                    </a>
                </div>
            </div>
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
            <div class="absolute right-10 top-10 opacity-20 text-9xl">
                <i class="fa-solid fa-book-open"></i>
            </div>
        </div>

        {{-- Stats sidebar (dark) --}}
        <div class="bg-black rounded-[2rem] p-6 text-white flex flex-col justify-between">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Saldo Rekening</p>
                <h3 class="text-2xl font-black mt-1 font-mono leading-tight">
                    IDR {{ number_format($savingsBook->account->balance, 0, ',', '.') }}
                </h3>
            </div>
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Entri</p>
                <p class="text-3xl font-bold text-blue-400 mt-1">
                    {{ $entries->total() }}
                    <span class="text-sm text-gray-400 font-normal">mutasi</span>
                </p>
            </div>
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Terakhir Sync</p>
                <p class="text-sm font-bold mt-1 {{ $savingsBook->last_printed ? 'text-green-400' : 'text-amber-400' }}">
                    @if($savingsBook->last_printed)
                        {{ \Carbon\Carbon::parse($savingsBook->last_printed)->diffForHumans() }}
                    @else
                        Belum pernah
                    @endif
                </p>
            </div>
        </div>

    </div>

    {{-- ================= FILTER ================= --}}
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('savings_books.show', $savingsBook) }}"
              class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-black transition-all duration-300 flex items-center gap-2">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request('date_from') || request('date_to'))
                <a href="{{ route('savings_books.show', $savingsBook) }}"
                   class="bg-gray-100 text-gray-600 px-5 py-3 rounded-xl font-bold hover:bg-gray-200 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ================= TABEL MUTASI ================= --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-xl text-black">Mutasi Buku Tabungan</h3>
            <span class="text-gray-400 text-sm font-medium">{{ $entries->total() }} entri total</span>
        </div>

        <div class="overflow-x-auto">
            @if($entries->isEmpty())
            <div class="text-center py-16">
                <i class="fa-solid fa-list-check text-gray-200 text-5xl mb-3"></i>
                <p class="text-gray-400 text-sm">Belum ada mutasi yang tersimpan.<br>Klik <strong class="text-blue-600">Sync Mutasi</strong> untuk menarik mutasi terbaru.</p>
            </div>
            @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">#</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Debit (Masuk)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Kredit (Keluar)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($entries as $i => $entry)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4 text-xs text-gray-400 font-medium">
                            {{ $entries->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-black">{{ $entry->description }}</p>
                            @if($entry->transaction)
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $entry->transaction->reference_code }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->debit > 0)
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-arrow-down text-green-600 text-xs"></i>
                                    </div>
                                    <span class="font-black text-green-600 text-sm">
                                        IDR {{ number_format($entry->debit, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->credit > 0)
                                <div class="flex items-center justify-end gap-2">
                                    <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-arrow-up text-red-600 text-xs"></i>
                                    </div>
                                    <span class="font-black text-red-600 text-sm">
                                        IDR {{ number_format($entry->credit, 0, ',', '.') }}
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-blue-600 text-sm">
                                IDR {{ number_format($entry->balance, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($entries->hasPages())
            <div class="px-6 py-5 border-t border-gray-50 flex items-center justify-between">
                <p class="text-sm text-gray-400 font-medium">
                    Menampilkan {{ $entries->firstItem() }}–{{ $entries->lastItem() }} dari {{ $entries->total() }} entri
                </p>
                <div class="flex items-center gap-2">
                    @if($entries->onFirstPage())
                        <span class="px-4 py-2 rounded-xl bg-gray-50 text-gray-300 text-sm font-bold cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $entries->previousPageUrl() }}"
                           class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    @foreach($entries->getUrlRange(max(1, $entries->currentPage()-2), min($entries->lastPage(), $entries->currentPage()+2)) as $page => $url)
                        @if($page == $entries->currentPage())
                            <span class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 rounded-xl bg-gray-50 text-gray-600 text-sm font-bold hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if($entries->hasMorePages())
                        <a href="{{ $entries->nextPageUrl() }}"
                           class="px-4 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="px-4 py-2 rounded-xl bg-gray-50 text-gray-300 text-sm font-bold cursor-not-allowed">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </span>
                    @endif
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

</div>
@endsection
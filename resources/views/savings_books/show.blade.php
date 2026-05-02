@extends('layouts.app')

@section('title', 'Detail Buku Tabungan ' . $savingsBook->book_number)

@section('content')
<div class="space-y-7">

    {{-- BREADCRUMB --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 font-medium">
        <a href="{{ route('savings_books.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left text-xs"></i> Buku Tabungan
        </a>
        <i class="fa-solid fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-900 font-bold font-mono">{{ $savingsBook->book_number }}</span>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-3.5 text-sm font-medium">
        <i class="fa-solid fa-circle-check text-green-500 flex-shrink-0"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-3.5 text-sm font-medium">
        <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i> {{ session('error') }}
    </div>
    @endif
    @if(session('info'))
    <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl px-5 py-3.5 text-sm font-medium">
        <i class="fa-solid fa-circle-info text-blue-500 flex-shrink-0"></i> {{ session('info') }}
    </div>
    @endif

    {{-- HERO + SIDEBAR --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
    {{-- Hero Card (Sisi Kiri) --}}
    <div class="xl:col-span-3 relative overflow-hidden bg-blue-600 rounded-[1.5rem] p-5 md:p-6 text-white shadow-xl shadow-blue-500/20 group">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="bg-white/15 text-white text-[9px] font-black px-2.5 py-0.5 rounded-full font-mono tracking-widest border border-white/10">
                    {{ $savingsBook->book_number }}
                </span>
                <p class="text-xl md:text-2xl font-black mt-2 font-mono tracking-tighter">
                    {{ $savingsBook->account->account_number }}
                </p>
                <div class="flex flex-col gap-0.5 mt-1">
                    <p class="text-blue-200 text-[10px] font-bold uppercase tracking-wide">
                        {{ ucfirst($savingsBook->account->account_type) }} · {{ $savingsBook->account->currency }} · {{ $savingsBook->account->user->name }}
                    </p>
                    <p class="text-blue-300/80 text-[9px]">
                        Diterbitkan {{ \Carbon\Carbon::parse($savingsBook->issued_at)->format('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="flex flex-row items-center gap-2">
                <form action="{{ route('savings_books.sync', $savingsBook) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-black hover:text-white transition-all duration-300 flex items-center gap-2 text-xs whitespace-nowrap shadow-lg shadow-black/5">
                        <i class="fa-solid fa-rotate text-[10px]"></i> Sync
                    </button>
                </form>
                
                <a href="{{ route('savings_books.download', $savingsBook) }}{{ request()->has('date_from') || request()->has('date_to') ? '?' . http_build_query(request()->only('date_from','date_to')) : '' }}"
                   class="bg-blue-500/30 border border-white/20 px-4 py-2 rounded-lg font-bold hover:bg-white/20 transition-all duration-300 flex items-center gap-2 text-xs whitespace-nowrap backdrop-blur-sm">
                    <i class="fa-solid fa-file-pdf text-[10px]"></i> PDF
                </a>
            </div>
        </div>

        {{-- Decorative Elements (Lebih Kecil) --}}
        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700 pointer-events-none"></div>
        <div class="absolute right-6 top-5 opacity-10 text-[5rem] leading-none pointer-events-none select-none">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>

    {{-- Stats Sidebar (Sisi Kanan) --}}
    <div class="bg-black rounded-[1.5rem] p-5 text-white flex flex-col justify-between shadow-xl">
        <div class="space-y-4">
            <div>
                <p class="text-gray-500 text-[8px] font-black uppercase tracking-[0.2em]">Saldo Rekening</p>
                <h3 class="text-base font-black mt-0.5 font-mono text-white tracking-tight">
                    IDR {{ number_format($savingsBook->account->balance, 0, ',', '.') }}
                </h3>
            </div>
            
            <div class="grid grid-cols-2 xl:grid-cols-1 gap-3">
                <div class="pt-3 border-t border-white/5">
                    <p class="text-gray-500 text-[8px] font-black uppercase tracking-[0.2em]">Entri {{ request('date_from') ? '(Filter)' : '' }}</p>
                    <p class="text-lg font-black text-blue-400 leading-none mt-1">
                        {{ $entries->total() }} <span class="text-[9px] text-gray-500 font-normal uppercase">Mutasi</span>
                    </p>
                </div>

                <div class="pt-3 border-t border-white/5">
                    <p class="text-gray-500 text-[8px] font-black uppercase tracking-[0.2em]">Arus Kas (Filter)</p>
                    <div class="mt-1 space-y-0.5">
                        <p class="text-[10px] font-bold text-green-400">+ {{ number_format($totalDebit, 0, ',', '.') }}</p>
                        <p class="text-[10px] font-bold text-red-400">− {{ number_format($totalCredit, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-white/10">
            <p class="text-gray-500 text-[8px] font-black uppercase tracking-[0.2em]">Status Terakhir</p>
            <p class="text-[10px] font-bold mt-0.5 {{ $savingsBook->last_printed ? 'text-green-400' : 'text-amber-400' }}">
                <i class="fa-solid fa-clock-rotate-left text-[9px] mr-1"></i>
                {{ $savingsBook->last_printed ? \Carbon\Carbon::parse($savingsBook->last_printed)->diffForHumans() : 'Belum Sync' }}
            </p>
        </div>
    </div>
</div>

    {{-- FILTER --}}
    <div class="bg-white rounded-[1.75rem] p-5 md:p-6 shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('savings_books.show', $savingsBook) }}"
              class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-black transition-all duration-300 flex items-center gap-2 text-sm whitespace-nowrap">
                    <i class="fa-solid fa-filter text-xs"></i> Filter
                </button>
                @if(request('date_from') || request('date_to'))
                <a href="{{ route('savings_books.show', $savingsBook) }}"
                   class="bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl font-bold hover:bg-gray-200 transition-all flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-xmark text-xs"></i> Reset
                </a>
                @endif
            </div>
        </form>

        {{-- Info filter aktif --}}
        @if(request('date_from') || request('date_to'))
        <div class="mt-3 flex items-center gap-2 text-xs text-blue-600 font-medium">
            <i class="fa-solid fa-circle-info text-[10px]"></i>
            Menampilkan mutasi
            @if(request('date_from')) dari <strong>{{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}</strong> @endif
            @if(request('date_to')) sampai <strong>{{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}</strong> @endif
            · PDF akan mengikuti filter ini.
        </div>
        @endif
    </div>

    {{-- TABEL MUTASI --}}
    <div class="bg-white rounded-[1.75rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 md:px-7 py-5 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-black text-base text-gray-900 leading-none">Mutasi Buku Tabungan</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-1">{{ $entries->total() }} entri ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            @if($entries->isEmpty())
            <div class="text-center py-14">
                <i class="fa-solid fa-list-check text-gray-200 text-4xl mb-3 block"></i>
                <p class="text-gray-400 text-sm font-medium">Belum ada mutasi yang tersimpan.</p>
                <p class="text-gray-400 text-xs mt-1">Klik <strong class="text-blue-600">Sync Mutasi</strong> untuk menarik data terbaru.</p>
            </div>
            @else
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/60">
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">#</th>
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Debit (Masuk)</th>
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Kredit (Keluar)</th>
                        <th class="px-6 py-3.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($entries as $i => $entry)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-6 py-4 text-[10px] text-gray-400 font-medium">{{ $entries->firstItem() + $i }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ $entry->description }}</p>
                            @if($entry->transaction)
                                <p class="text-[10px] text-gray-400 font-mono mt-0.5">{{ $entry->transaction->reference_code }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->isDebit())
                                <div class="flex items-center justify-end gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-arrow-down text-green-600 text-[9px]"></i>
                                    </div>
                                    <span class="font-black text-green-600 text-sm">{{ $entry->formatted_debit }}</span>
                                </div>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($entry->isCredit())
                                <div class="flex items-center justify-end gap-1.5">
                                    <div class="w-5 h-5 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-arrow-up text-red-600 text-[9px]"></i>
                                    </div>
                                    <span class="font-black text-red-600 text-sm">{{ $entry->formatted_credit }}</span>
                                </div>
                            @else
                                <span class="text-gray-300 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-black text-blue-600 text-sm">{{ $entry->formatted_balance }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- Subtotal row --}}
                <tfoot>
                    <tr class="bg-gray-50/80 border-t-2 border-gray-100">
                        <td colspan="3" class="px-6 py-3.5 text-[10px] font-black text-gray-500 uppercase tracking-wider">
                            Total Periode
                        </td>
                        <td class="px-6 py-3.5 text-right font-black text-green-600 text-sm">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-black text-red-600 text-sm">
                            Rp {{ number_format($totalCredit, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3.5 text-right font-black text-blue-600 text-sm">
                            &mdash;
                        </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Pagination --}}
            @if($entries->hasPages())
            <div class="px-6 md:px-7 py-5 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-400 font-medium">
                    Menampilkan {{ $entries->firstItem() }}–{{ $entries->lastItem() }} dari {{ $entries->total() }} entri
                </p>
                <div class="flex items-center gap-1.5">
                    @if($entries->onFirstPage())
                        <span class="px-3.5 py-2 rounded-xl bg-gray-50 text-gray-300 text-sm font-bold cursor-not-allowed">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $entries->previousPageUrl() }}"
                           class="px-3.5 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    @foreach($entries->getUrlRange(max(1,$entries->currentPage()-2), min($entries->lastPage(),$entries->currentPage()+2)) as $page => $url)
                        @if($page == $entries->currentPage())
                            <span class="px-3.5 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3.5 py-2 rounded-xl bg-gray-50 text-gray-600 text-sm font-bold hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if($entries->hasMorePages())
                        <a href="{{ $entries->nextPageUrl() }}"
                           class="px-3.5 py-2 rounded-xl bg-blue-50 text-blue-600 text-sm font-bold hover:bg-blue-100 transition-colors">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="px-3.5 py-2 rounded-xl bg-gray-50 text-gray-300 text-sm font-bold cursor-not-allowed">
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
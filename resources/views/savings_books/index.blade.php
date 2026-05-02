@extends('layouts.app')

@section('title', 'Buku Tabungan')

@section('content')
<div class="space-y-7">

    {{-- HERO --}}
    <div class="relative overflow-hidden bg-blue-600 rounded-[2rem] p-7 md:p-8 text-white shadow-2xl shadow-blue-500/30 group">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div>
                <p class="text-blue-200 font-semibold tracking-widest uppercase text-[10px]">Layanan Nasabah</p>
                <h1 class="text-3xl md:text-4xl font-black mt-1.5 tracking-tight">Buku Tabungan</h1>
                <p class="text-blue-300 text-sm mt-1.5">Kelola & unduh buku tabungan rekening Anda</p>
            </div>
            <div class="bg-white/10 border border-white/15 px-6 py-4 rounded-2xl text-center shrink-0">
                <p class="text-blue-200 text-[10px] font-black uppercase tracking-widest">Total Buku</p>
                <p class="text-4xl font-black mt-1">{{ $savingsBooks->count() }}</p>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-56 h-56 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700 pointer-events-none"></div>
        <div class="absolute right-8 top-7 opacity-10 text-[8rem] leading-none pointer-events-none select-none">
            <i class="fa-solid fa-book-open"></i>
        </div>
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

    {{-- TERBITKAN BUKU --}}
    @if($accountsWithoutBook->isNotEmpty())
    <div class="bg-white rounded-[1.75rem] p-6 md:p-7 shadow-sm border border-gray-100">
        <div class="mb-5">
            <h3 class="font-black text-base text-gray-900 leading-none">Terbitkan Buku Tabungan</h3>
            <p class="text-gray-400 text-xs font-medium mt-1">Pilih rekening aktif yang belum memiliki buku tabungan</p>
        </div>
        <form action="{{ route('savings_books.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <select name="account_id" required
                class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">— Pilih Rekening —</option>
                @foreach($accountsWithoutBook as $acct)
                    <option value="{{ $acct->id }}">
                        {{ $acct->account_number }} · {{ ucfirst($acct->account_type) }} · IDR {{ number_format($acct->balance, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-black transition-all duration-300 flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                <i class="fa-solid fa-plus text-xs"></i> Terbitkan
            </button>
        </form>
        @error('account_id')
            <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
        @enderror
    </div>
    @endif

    {{-- DAFTAR BUKU --}}
    @if($savingsBooks->isEmpty())
    <div class="bg-white rounded-[1.75rem] py-16 shadow-sm border border-gray-100 text-center">
        <i class="fa-solid fa-book-open text-gray-200 text-5xl mb-4"></i>
        <h4 class="font-black text-base text-gray-900 mb-1">Belum Ada Buku Tabungan</h4>
        <p class="text-gray-400 text-sm">Terbitkan buku tabungan untuk rekening aktif Anda di atas.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($savingsBooks as $book)
        @php
            $isRecent = $book->last_printed && \Carbon\Carbon::parse($book->last_printed)->diffInDays(now()) < 7;
            [$badgeClass, $badgeLabel] = match($book->account->status) {
                'active'  => ['bg-green-100 text-green-700', 'Aktif'],
                'blocked' => ['bg-red-100 text-red-700', 'Diblokir'],
                default   => ['bg-gray-100 text-gray-500', ucfirst($book->account->status)],
            };
        @endphp

        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col">

            {{-- accent stripe --}}
            <div class="h-1 bg-blue-600 w-full"></div>

            <div class="p-5 flex flex-col flex-1">

                {{-- Header row --}}
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">No. Buku</p>
                        <p class="font-black text-sm font-mono text-gray-900 mt-0.5 tracking-wider">{{ $book->book_number }}</p>
                    </div>
                    <span class="text-[9px] font-black px-2.5 py-1 rounded-full {{ $badgeClass }} uppercase tracking-wider">{{ $badgeLabel }}</span>
                </div>

                {{-- Account box --}}
                <div class="bg-gray-50 rounded-xl px-4 py-3 mb-4">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">No. Rekening</p>
                    <p class="font-black text-base font-mono text-gray-900 tracking-wider">{{ $book->account->account_number }}</p>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ ucfirst($book->account->account_type) }} · {{ $book->account->currency }}</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Saldo</p>
                        <p class="text-sm font-black text-gray-900 leading-tight">IDR {{ number_format($book->account->balance, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Mutasi</p>
                        <p class="text-sm font-black text-gray-900">{{ $book->entries_count }} entri</p>
                    </div>
                </div>

                {{-- Sync status --}}
                <div class="flex items-center gap-1.5 mb-5">
                    <div class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $isRecent ? 'bg-green-400' : 'bg-amber-400' }}"></div>
                    <p class="text-[11px] text-gray-400">
                        @if($book->last_printed)
                            Sync {{ \Carbon\Carbon::parse($book->last_printed)->diffForHumans() }}
                        @else
                            Belum pernah disinkronkan
                        @endif
                    </p>
                </div>

                {{-- Actions --}}
                <div class="mt-auto flex gap-2">
                    <a href="{{ route('savings_books.show', $book) }}"
                       class="flex-1 bg-blue-600 text-white text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-blue-700 transition-colors">
                        <i class="fa-solid fa-eye text-[10px]"></i> Detail
                    </a>
                    <form action="{{ route('savings_books.sync', $book) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gray-100 text-gray-700 text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-gray-200 transition-colors">
                            <i class="fa-solid fa-rotate text-[10px]"></i> Sync
                        </button>
                    </form>
                    <a href="{{ route('savings_books.download', $book) }}"
                       class="flex-1 bg-gray-100 text-gray-700 text-xs font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-gray-200 transition-colors">
                        <i class="fa-solid fa-download text-[10px]"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
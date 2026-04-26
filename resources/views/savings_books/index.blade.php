@extends('layouts.app')

@section('title', 'Buku Tabungan')

@section('content')
<div class="space-y-8">

    {{-- ================= HERO ================= --}}
    <div class="relative overflow-hidden bg-blue-600 rounded-[2rem] p-8 text-white shadow-2xl shadow-blue-500/40 group">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <p class="text-blue-100 font-medium tracking-wider uppercase text-xs">Manajemen</p>
                <h1 class="text-4xl md:text-5xl font-black mt-2 tracking-tight">Buku Tabungan</h1>
                <p class="text-blue-200 text-sm mt-2">Kelola & unduh buku tabungan rekening Anda</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-blue-500/30 backdrop-blur-md border border-white/20 px-6 py-4 rounded-xl text-center">
                    <p class="text-blue-200 text-xs font-bold uppercase tracking-wider">Total Buku</p>
                    <p class="text-3xl font-black mt-0.5">{{ $savingsBooks->count() }}</p>
                </div>
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
        <div class="absolute right-10 top-10 opacity-20 text-9xl">
            <i class="fa-solid fa-book-open"></i>
        </div>
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

    {{-- ================= TERBITKAN BUKU BARU ================= --}}
    @if($accountsWithoutBook->isNotEmpty())
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
        <h3 class="font-bold text-xl text-black mb-1">Terbitkan Buku Tabungan</h3>
        <p class="text-gray-400 text-sm mb-6">Pilih rekening aktif yang belum memiliki buku tabungan</p>

        <form action="{{ route('savings_books.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
            @csrf
            <select name="account_id" required
                class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-black focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">— Pilih Rekening —</option>
                @foreach($accountsWithoutBook as $acct)
                    <option value="{{ $acct->id }}">
                        {{ $acct->account_number }} · {{ ucfirst($acct->account_type) }} · IDR {{ number_format($acct->balance, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="bg-blue-600 text-white px-7 py-3 rounded-xl font-bold hover:bg-black transition-all duration-300 flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Terbitkan Sekarang
            </button>
        </form>

        @error('account_id')
            <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
        @enderror
    </div>
    @endif

    {{-- ================= DAFTAR BUKU ================= --}}
    @if($savingsBooks->isEmpty())
    <div class="bg-white rounded-[2rem] p-16 shadow-sm border border-gray-100 text-center">
        <i class="fa-solid fa-book-open text-gray-200 text-6xl mb-4"></i>
        <h4 class="font-bold text-xl text-black mb-2">Belum Ada Buku Tabungan</h4>
        <p class="text-gray-400 text-sm">Terbitkan buku tabungan untuk rekening aktif Anda di atas.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($savingsBooks as $book)
        @php
            $isRecent = $book->last_printed && \Carbon\Carbon::parse($book->last_printed)->diffInDays(now()) < 7;
        @endphp

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all duration-300 flex flex-col">

            {{-- Card Header (biru seperti hero) --}}
            <div class="relative overflow-hidden bg-blue-600 p-6 text-white">
                <div class="relative z-10">
                    <span class="bg-white/15 text-white text-xs font-black px-3 py-1 rounded-full tracking-widest font-mono">
                        {{ $book->book_number }}
                    </span>
                    <p class="text-2xl font-black mt-4 font-mono tracking-wider">
                        {{ $book->account->account_number }}
                    </p>
                    <p class="text-blue-200 text-xs font-bold uppercase tracking-wider mt-1">
                        {{ ucfirst($book->account->account_type) }} · {{ $book->account->currency }}
                    </p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-white/10 text-8xl pointer-events-none">
                    <i class="fa-solid fa-book"></i>
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-6 flex flex-col flex-1">
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Saldo</p>
                        <p class="text-black font-black text-sm leading-tight">
                            IDR {{ number_format($book->account->balance, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Mutasi</p>
                        <p class="text-black font-black text-sm">{{ $book->entries_count }} entri</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-5">
                    <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $isRecent ? 'bg-green-400' : 'bg-amber-400' }}"></div>
                    <p class="text-gray-400 text-xs">
                        @if($book->last_printed)
                            Sync {{ \Carbon\Carbon::parse($book->last_printed)->diffForHumans() }}
                        @else
                            Belum pernah disinkronkan
                        @endif
                    </p>
                </div>

                <div class="mt-auto flex gap-2">
                    <a href="{{ route('savings_books.show', $book) }}"
                       class="flex-1 bg-blue-50 text-blue-600 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-blue-100 transition-colors">
                        <i class="fa-solid fa-eye text-xs"></i> Detail
                    </a>
                    <form action="{{ route('savings_books.sync', $book) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-green-50 text-green-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-green-100 transition-colors">
                            <i class="fa-solid fa-rotate text-xs"></i> Sync
                        </button>
                    </form>
                    <a href="{{ route('savings_books.download', $book) }}"
                       class="flex-1 bg-gray-50 text-gray-700 text-sm font-bold py-2.5 rounded-xl flex items-center justify-center gap-1.5 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-download text-xs"></i> PDF
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="py-12 px-4 max-w-6xl mx-auto">
    <div class="mb-10 text-center md:text-left">
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Pembayaran Tagihan</h1>
        <p class="text-gray-500 font-medium">Pilih layanan yang ingin Anda bayar hari ini.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($bills as $bill)
        <a href="{{ route('bills.pay', $bill->id) }}" 
           class="group bg-white p-8 rounded-[2.5rem] border border-black/10 hover:border-blue-600 shadow-sm hover:shadow-xl hover:shadow-blue-50 transition-all text-center relative overflow-hidden">
            
            {{-- Icon dari Accessor Model --}}
            <div class="text-5xl mb-4 group-hover:scale-110 transition-transform duration-300">
                {{ $bill->category_icon }}
            </div>

            <h4 class="font-black text-gray-800 text-xs uppercase tracking-widest">{{ $bill->bill_name }}</h4>
            <p class="text-[9px] font-bold text-blue-500 mt-2 opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-tighter">Bayar Sekarang &rarr;</p>

            {{-- Dekorasi Tipis --}}
            <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-gray-50 rounded-full group-hover:bg-blue-50 transition-colors"></div>
        </a>
        @empty
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-[3rem] border border-dashed border-gray-200">
            <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Maaf, layanan tagihan belum tersedia.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
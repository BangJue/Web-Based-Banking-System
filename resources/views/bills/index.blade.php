@extends('layouts.app')

@section('content')
<div class="py-10 px-4 max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Pembayaran Tagihan</h1>
        <p class="text-gray-400 text-sm font-medium mt-1">Pilih layanan yang ingin Anda bayar hari ini.</p>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-2xl px-5 py-4">
        <i class="fas fa-circle-check mt-0.5 text-green-500"></i>
        <p class="text-sm font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @forelse($bills->groupBy('category') as $category => $items)
    <div class="mb-8">
        {{-- Label Kategori --}}
        <div class="flex items-center gap-3 mb-4">
            <span class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400">{{ ucfirst($category) }}</span>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($items as $bill)
            <a href="{{ route('bills.pay', $bill->id) }}"
               class="group bg-white border border-black/8 rounded-3xl p-6 flex flex-col items-center gap-3 hover:border-blue-500 hover:shadow-lg hover:shadow-blue-50 transition-all duration-200 text-center">

                {{-- Icon --}}
                <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-2xl group-hover:bg-blue-100 transition-colors">
                    {{ $bill->category_icon }}
                </div>

                <div>
                    <p class="font-black text-gray-800 text-sm">{{ $bill->bill_name }}</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mt-0.5">{{ $bill->category_label }}</p>
                </div>

                <span class="text-[10px] font-black text-blue-500 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">
                    Bayar &rarr;
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
        <div class="text-4xl mb-3">📭</div>
        <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Layanan tagihan belum tersedia.</p>
    </div>
    @endforelse

</div>
@endsection
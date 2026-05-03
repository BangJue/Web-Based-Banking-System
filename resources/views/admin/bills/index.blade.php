@extends('layouts.admin')

@section('title', 'Manajemen Tagihan')

@section('content')
<div class="space-y-7">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Operasional</p>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight mt-0.5">Manajemen Tagihan</h1>
            <p class="text-gray-400 text-sm font-medium mt-0.5">Atur jenis layanan pembayaran yang tersedia untuk nasabah.</p>
        </div>
        <a href="{{ route('admin.bills.create') }}"
           class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-black transition-all duration-300 flex items-center gap-2 text-sm w-fit shadow-sm">
            <i class="fas fa-plus text-xs"></i> Tambah Layanan
        </a>
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

    {{-- GRID CARDS --}}
    @php
    $categoryMeta = [
        'listrik'    => ['icon' => 'fa-bolt',            'bg' => 'bg-yellow-50',  'text' => 'text-yellow-600',  'hover' => 'group-hover:bg-yellow-500'],
        'air'        => ['icon' => 'fa-tint',            'bg' => 'bg-blue-50',    'text' => 'text-blue-600',    'hover' => 'group-hover:bg-blue-500'],
        'internet'   => ['icon' => 'fa-wifi',            'bg' => 'bg-cyan-50',    'text' => 'text-cyan-600',    'hover' => 'group-hover:bg-cyan-500'],
        'telepon'    => ['icon' => 'fa-phone',           'bg' => 'bg-green-50',   'text' => 'text-green-600',   'hover' => 'group-hover:bg-green-500'],
        'bpjs'       => ['icon' => 'fa-heartbeat',       'bg' => 'bg-red-50',     'text' => 'text-red-600',     'hover' => 'group-hover:bg-red-500'],
        'pajak'      => ['icon' => 'fa-landmark',        'bg' => 'bg-slate-50',   'text' => 'text-slate-600',   'hover' => 'group-hover:bg-slate-500'],
        'pendidikan' => ['icon' => 'fa-graduation-cap',  'bg' => 'bg-purple-50',  'text' => 'text-purple-600',  'hover' => 'group-hover:bg-purple-500'],
    ];
    $default = ['icon' => 'fa-file-invoice', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'hover' => 'group-hover:bg-gray-500'];
    @endphp

    @if($bills->isEmpty())
    <div class="bg-white rounded-[1.75rem] py-20 border border-gray-100 shadow-sm text-center">
        <i class="fas fa-receipt text-gray-200 text-5xl mb-4 block"></i>
        <p class="text-gray-400 font-bold text-sm">Belum ada layanan tagihan yang terdaftar.</p>
        <a href="{{ route('admin.bills.create') }}" class="mt-4 inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-black transition-all">
            <i class="fas fa-plus text-xs"></i> Tambah Sekarang
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($bills as $bill)
        @php
            $cat  = $bill->category ?? 'lainnya';
            $meta = $categoryMeta[$cat] ?? $default;
        @endphp

        <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden group flex flex-col">

            {{-- Accent stripe warna per kategori --}}
            <div class="h-1 {{ str_replace('bg-', 'bg-', explode(' ', $meta['bg'])[0]) }} {{ str_replace('50', '500', $meta['bg']) }}"></div>

            <div class="p-5 flex flex-col flex-1">

                {{-- Top row: icon + actions --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 {{ $meta['bg'] }} {{ $meta['text'] }} rounded-2xl flex items-center justify-center text-xl transition-all duration-300 {{ $meta['hover'] }} group-hover:text-white">
                        <i class="fas {{ $meta['icon'] }} text-lg"></i>
                    </div>

                    <div class="flex items-center gap-1">
                        {{-- Status badge --}}
                        <span class="text-[9px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider mr-1
                            {{ $bill->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $bill->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                        <a href="{{ route('admin.bills.edit', $bill->id) }}"
                           class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-amber-50 hover:text-amber-600 flex items-center justify-center text-gray-400 transition-colors">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                        <form action="{{ route('admin.bills.destroy', $bill->id) }}" method="POST"
                              onsubmit="return confirm('Hapus layanan tagihan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-8 h-8 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-600 flex items-center justify-center text-gray-400 transition-colors">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Name + category --}}
                <h3 class="font-black text-base text-gray-900 leading-tight mb-0.5">{{ $bill->bill_name }}</h3>
                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">{{ $bill->category_label }}</p>

                {{-- Divider + admin fee --}}
                <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Biaya Admin</p>
                        @if($bill->admin_fee > 0)
                            <p class="text-sm font-black text-indigo-600">Rp {{ number_format($bill->admin_fee, 0, ',', '.') }}</p>
                        @else
                            <p class="text-sm font-black text-gray-400">Gratis</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Kode</p>
                        <p class="text-xs font-mono font-bold text-gray-500">{{ $bill->bill_code }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Tagihan</h1>
            <p class="text-gray-500 text-sm font-medium">Atur jenis layanan pembayaran yang tersedia untuk nasabah.</p>
        </div>
        <a href="{{ route('admin.bills.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center gap-2 text-sm w-fit">
            <i class="fas fa-plus"></i> Tambah Layanan
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($bills as $bill)
        <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.bills.edit', $bill->id) }}" class="text-gray-300 hover:text-yellow-500 transition-colors">
                        <i class="fas fa-edit text-sm"></i>
                    </a>
                    <form action="{{ route('admin.bills.destroy', $bill->id) }}" method="POST" onsubmit="return confirm('Hapus layanan tagihan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>

            <h3 class="text-lg font-black text-gray-800 mb-1">{{ $bill->name }}</h3>
            <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-4">{{ $bill->category }}</p>
            
            <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                <p class="text-xs text-gray-500 font-medium">Biaya Admin:</p>
                <p class="text-sm font-black text-blue-600">Rp {{ number_format($bill->admin_fee, 0, ',', '.') }}</p>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-gray-50 rounded-[2.5rem] py-20 text-center">
            <i class="fas fa-receipt text-4xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold">Belum ada layanan tagihan yang terdaftar.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
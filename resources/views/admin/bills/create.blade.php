@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bills.index') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-black text-gray-800">Tambah Layanan Baru</h1>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.bills.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Nama Layanan (Provider)</label>
                <input type="text" name="name" required placeholder="Contoh: PLN Pra Bayar, PDAM Palembang"
                    class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Kategori</label>
                    <select name="category" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
                        <option value="Listrik">Listrik</option>
                        <option value="Air">Air</option>
                        <option value="Internet">Internet</option>
                        <option value="Pajak">Pajak</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Biaya Admin (Rp)</label>
                    <input type="number" name="admin_fee" required placeholder="2500"
                        class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all flex items-center justify-center gap-3 text-lg">
                <i class="fas fa-save"></i> Simpan Layanan
            </button>
        </form>
    </div>
</div>
@endsection
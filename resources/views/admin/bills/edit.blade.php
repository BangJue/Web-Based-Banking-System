@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bills.index') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800">Edit Layanan</h1>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Kode: {{ $bill->bill_code }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-600 p-4 rounded-2xl">
            <ul class="list-disc list-inside text-xs font-bold">
                @foreach ($errors->all() as $error)
                    <li>{{ strtoupper($error) }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.bills.update', $bill->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Nama Layanan (Provider)</label>
                <input type="text" name="name" value="{{ old('name', $bill->bill_name) }}" required 
                    class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Kategori</label>
                    <select name="category" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
                        <option value="listrik" {{ old('category', $bill->category) == 'listrik' ? 'selected' : '' }}>Listrik</option>
                        <option value="air" {{ old('category', $bill->category) == 'air' ? 'selected' : '' }}>Air</option>
                        <option value="internet" {{ old('category', $bill->category) == 'internet' ? 'selected' : '' }}>Internet</option>
                        <option value="pajak" {{ old('category', $bill->category) == 'pajak' ? 'selected' : '' }}>Pajak</option>
                        <option value="telepon" {{ old('category', $bill->category) == 'telepon' ? 'selected' : '' }}>Telepon</option>
                        <option value="bpjs" {{ old('category', $bill->category) == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                    </select>
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Status Layanan</label>
                    <select name="is_active" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800">
                        <option value="1" {{ old('is_active', $bill->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $bill->is_active) == 0 ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all flex items-center justify-center gap-3 text-lg">
                    <i class="fas fa-sync-alt"></i> Perbarui Layanan
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="bg-gray-50 p-6 rounded-[2rem] border border-dashed border-gray-200">
        <div class="flex gap-4">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 shadow-sm">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Catatan Sistem</p>
                <p class="text-xs text-gray-500 leading-relaxed mt-1">Mengubah nama layanan akan langsung terlihat oleh semua nasabah. Pastikan kategori yang dipilih sesuai agar ikon muncul dengan benar.</p>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('title', 'Edit Layanan Tagihan')

@section('content')
<div class="space-y-6 max-w-2xl">

    {{-- HEADER --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bills.index') }}"
           class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Operasional</p>
            <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none mt-0.5">Edit Layanan Tagihan</h1>
            <p class="text-gray-400 text-xs font-mono mt-0.5">{{ $bill->bill_code }}</p>
        </div>
    </div>

    {{-- ERRORS --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-2xl">
        <p class="text-xs font-black uppercase tracking-wider mb-2">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-xs font-medium">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white rounded-[1.75rem] border border-gray-100 shadow-sm p-7">
        <form action="{{ route('admin.bills.update', $bill->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Layanan --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                    Nama Layanan (Provider)
                </label>
                <input type="text" name="name" value="{{ old('name', $bill->bill_name) }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>

            {{-- Kategori + Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Kategori</label>
                    <select name="category"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        @php
                        $categories = [
                            'listrik'    => 'Listrik',
                            'air'        => 'Air / PDAM',
                            'internet'   => 'Internet',
                            'telepon'    => 'Telepon',
                            'bpjs'       => 'BPJS',
                            'pajak'      => 'Pajak',
                            'pendidikan' => 'Pendidikan',
                            'lainnya'    => 'Lainnya',
                        ];
                        @endphp
                        @foreach($categories as $val => $label)
                            <option value="{{ $val }}" {{ old('category', $bill->category) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Status</label>
                    <select name="is_active"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        <option value="1" {{ old('is_active', $bill->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $bill->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>

            {{-- Biaya Admin --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                    Biaya Admin (Rp)
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-gray-400">Rp</span>
                    <input type="number" name="admin_fee" value="{{ old('admin_fee', $bill->admin_fee ?? 0) }}"
                           min="0" step="500" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                <p class="text-[10px] text-gray-400 font-medium mt-1.5">Isi 0 jika tidak ada biaya admin.</p>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-black py-3.5 rounded-2xl hover:bg-black transition-all duration-300 flex items-center justify-center gap-2 text-sm shadow-sm">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Info --}}
    <div class="bg-gray-50 p-5 rounded-2xl border border-dashed border-gray-200">
        <div class="flex gap-3">
            <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center text-gray-400 shadow-sm flex-shrink-0">
                <i class="fas fa-circle-info text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Catatan Sistem</p>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Mengubah nama atau kategori akan langsung terlihat oleh nasabah.
                    Icon layanan akan otomatis disesuaikan berdasarkan kategori yang dipilih.
                    Biaya admin dikenakan per transaksi pembayaran tagihan.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
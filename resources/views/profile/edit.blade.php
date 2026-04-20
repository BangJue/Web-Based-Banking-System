@extends('layouts.app')

@section('content')
<div class="py-12 px-4 max-w-3xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('profile.show') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-gray-200 text-gray-500 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Edit Profil</h1>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>
            
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">NIK</label>
                <input type="text" name="nik" value="{{ old('nik', $user->profile->nik ?? '') }}" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kota</label>
                <input type="text" name="city" value="{{ old('city', $user->profile->city ?? '') }}" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            <div class="md:col-span-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat</label>
                <textarea name="address" rows="3" class="w-full mt-2 bg-gray-50 border-none rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">{{ old('address', $user->profile->address ?? '') }}</textarea>
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl transition-all uppercase text-xs tracking-widest shadow-lg shadow-blue-100">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
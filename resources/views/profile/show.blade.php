@extends('layouts.app')

@section('content')
<div class="py-12 px-4 max-w-5xl mx-auto">
    {{-- Header Profil --}}
    <div class="mb-10 flex flex-col md:flex-row items-center gap-6">
        <div class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-blue-200">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">{{ $user->name }}</h1>
            <p class="text-gray-500 font-medium">{{ $user->email }}</p>
            <div class="mt-2 flex gap-2 justify-center md:justify-start">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-full uppercase tracking-wider">Nasabah Personal</span>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black rounded-full uppercase tracking-wider">ID #{{ $user->id }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Sisi Kiri: Informasi Rekening --}}
        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Rekening Saya</h3>
            @forelse($user->accounts as $account)
                <div class="bg-slate-900 p-6 rounded-[2.5rem] text-white shadow-xl relative overflow-hidden group border border-black/20">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    
                    <p class="text-[10px] font-bold opacity-50 uppercase tracking-widest">{{ $account->account_type }}</p>
                    <p class="text-xl font-mono font-bold mt-1">{{ $account->account_number }}</p>
                    
                    <div class="mt-8">
                        <p class="text-[10px] font-bold opacity-50 uppercase">Saldo Tersedia</p>
                        <p class="text-2xl font-black text-blue-400">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-6 bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-300 text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase">Belum ada rekening aktif</p>
                </div>
            @endforelse
        </div>

        {{-- Sisi Kanan: Detail Personal (Outline Hitam Tipis) --}}
        <div class="md:col-span-2 space-y-6">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Detail Informasi Pribadi</h3>
            
            <div class="bg-white p-8 rounded-[2.5rem] border border-black/10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Induk Kependudukan (NIK)</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $user->profile->nik ?? 'Belum diatur' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Telepon</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $user->profile->phone ?? 'Belum diatur' }}</p>
                </div>
                <div class="md:col-span-2 space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Lengkap</p>
                    <p class="font-bold text-gray-800 text-sm leading-relaxed">{{ $user->profile->address ?? 'Belum diatur' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kota</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $user->profile->city ?? 'Belum diatur' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Bergabung Sejak</p>
                    <p class="font-bold text-gray-800 text-sm">{{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Bantuan Card --}}
            <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white flex items-center justify-between shadow-lg shadow-blue-100 border border-black/5">
                <div>
                    <h4 class="text-lg font-black tracking-tight">Butuh Perubahan Data?</h4>
                    <p class="text-blue-100 text-xs font-medium mt-1">Silakan kunjungi kantor cabang terdekat atau hubungi call center.</p>
                </div>
                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-headset text-xl text-white"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
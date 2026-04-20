@extends('layouts.admin')

@section('title', 'Profil Administrator')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">Profil Saya</h1>
            <p class="text-gray-500 text-sm font-medium">Informasi otoritas dan data personal administrator.</p>
        </div>
        <div class="px-4 py-2 bg-slate-100 border border-black/5 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500">
            System Identity: #ADM-{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Sisi Kiri: Ringkasan Utama --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-black/10 shadow-sm text-center">
                <div class="relative inline-block mb-6">
                    <div class="w-32 h-32 bg-blue-600 rounded-[2rem] flex items-center justify-center text-white text-4xl font-black shadow-2xl shadow-blue-200">
                        {{ substr($admin->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 border-4 border-white rounded-full flex items-center justify-center text-white text-xs">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <h2 class="text-xl font-black text-gray-800">{{ $admin->name }}</h2>
                <p class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">{{ $admin->role }}</p>
                
                <div class="mt-6 pt-6 border-t border-gray-50 flex justify-center gap-2">
                    <span class="px-4 py-2 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase tracking-wider">
                        {{ $admin->is_active ? 'Status: Aktif' : 'Status: Non-Aktif' }}
                    </span>
                </div>
            </div>

            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl">
                <h3 class="text-[10px] font-black mb-6 uppercase tracking-widest opacity-40 text-center">Statistik Aktivitas</h3>
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-black text-blue-400">{{ $pending_loans ?? 0 }}</p>
                        <p class="text-[9px] font-bold opacity-40 uppercase">Pending Review</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-emerald-400">{{ $total_users ?? 0 }}</p>
                        <p class="text-[9px] font-bold opacity-40 uppercase">Total Nasabah</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Detail Informasi (Outline Hitam Tipis) --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-10 rounded-[2.5rem] border border-black/10 shadow-sm space-y-10">
                
                {{-- Group: Identitas --}}
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Data Kredensial
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Email</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->email }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-50">

                {{-- Group: Informasi Personal --}}
                <div class="space-y-6">
                    <h3 class="text-[11px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span> Detail Personal
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Induk (NIK)</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->profile->nik ?? 'Data tidak tersedia' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Telepon</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->profile->phone ?? 'Data tidak tersedia' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kota Domisili</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->profile->city ?? 'Data tidak tersedia' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Terdaftar Sejak</p>
                            <p class="text-sm font-bold text-gray-800 mt-1">{{ $admin->created_at->format('d F Y') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Alamat Lengkap</p>
                            <p class="text-sm font-bold text-gray-800 mt-1 leading-relaxed">{{ $admin->profile->address ?? 'Data tidak tersedia' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                    <p class="text-[10px] font-medium text-gray-400 italic">Pembaruan data hanya dapat dilakukan melalui pusat bantuan IT.</p>
                    <div class="flex gap-2">
                        <i class="fab fa-facebook text-gray-300 hover:text-blue-600 cursor-pointer transition-colors"></i>
                        <i class="fab fa-instagram text-gray-300 hover:text-pink-600 cursor-pointer transition-colors"></i>
                        <i class="fab fa-linkedin text-gray-300 hover:text-blue-800 cursor-pointer transition-colors"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
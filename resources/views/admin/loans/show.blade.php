@extends('layouts.admin')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.loans.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-gray-200 text-gray-500 hover:text-indigo-600 transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Review Pinjaman</h1>
            <p class="text-gray-500 text-sm">ID Pengajuan: #{{ $loan->id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Informasi Nasabah</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($loan->account->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xl font-black text-gray-800">{{ $loan->account->user->name }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ $loan->account->user->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase">Nasabah Terverifikasi</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-50">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Nomor Rekening</p>
                        <p class="font-bold text-gray-800">{{ $loan->account->account_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo Saat Ini</p>
                        <p class="font-bold text-indigo-600">Rp {{ number_format($loan->account->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest mb-6">Rincian Pengajuan</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Jumlah Pinjaman</span>
                        <span class="text-lg font-black text-gray-800">Rp {{ number_format($loan->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Tenor / Durasi</span>
                        <span class="font-bold text-gray-800">{{ $loan->duration_months }} Bulan</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Suku Bunga</span>
                        <span class="font-bold text-gray-800">5% (Flat)</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-500 font-medium">Tujuan Pinjaman</span>
                        <span class="font-bold text-gray-800 italic">"Modal Usaha / Keperluan Mendesak"</span>
                    </div>
                </div>
            </div>
        </div>

       <div class="bg-indigo-900 p-8 rounded-[2.5rem] text-white shadow-xl shadow-indigo-100">
    <h3 class="text-sm font-black mb-6 uppercase tracking-widest opacity-70">Keputusan Admin</h3>
    
    @if($loan->status === 'pending')
        <div class="space-y-4">
            <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 bg-green-500 hover:bg-green-600 text-white font-black rounded-2xl transition-all uppercase text-xs tracking-widest" onclick="return confirm('Setujui dan cairkan dana?')">
                    Setujui Pinjaman
                </button>
            </form>

            <hr class="border-white/10 my-4">

            <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase opacity-60 tracking-widest ml-1">Alasan Penolakan</label>
                    <textarea name="rejection_reason" required 
                        class="w-full mt-2 bg-white/10 border border-white/20 rounded-xl p-3 text-xs text-white placeholder:text-white/30 focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none"
                        placeholder="Contoh: Dokumen tidak lengkap atau skor kredit rendah..."
                        rows="3"></textarea>
                    @error('rejection_reason')
                        <p class="text-red-400 text-[10px] mt-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-4 bg-red-500 hover:bg-red-600 text-white font-black rounded-2xl transition-all uppercase text-xs tracking-widest border border-red-400 shadow-lg shadow-red-900/20" onclick="return confirm('Tolak pengajuan ini?')">
                    Tolak Pengajuan
                </button>
            </form>
        </div>
    @else
        <div class="text-center py-4">
            <p class="text-xs font-bold uppercase tracking-widest opacity-60">Status Saat Ini</p>
            <p class="text-2xl font-black mt-2 {{ in_array($loan->status, ['active', 'approved']) ? 'text-green-400' : 'text-red-400' }}">
                {{ strtoupper($loan->status) }}
            </p>
            @if($loan->rejection_reason)
                <p class="mt-4 p-3 bg-black/20 rounded-xl text-[11px] italic opacity-80 border border-white/5">
                    "{{ $loan->rejection_reason }}"
                </p>
            @endif
        </div>
    @endif
</div>

            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Catatan Sistem</p>
                <p class="text-[11px] text-gray-500 leading-relaxed">Persetujuan pinjaman akan secara otomatis menambah saldo ke rekening nasabah terkait.</p>
            </div>
        </div>
    </div>
</div>
@endsection
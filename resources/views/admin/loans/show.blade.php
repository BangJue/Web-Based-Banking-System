@extends('layouts.admin')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    {{-- Header & Back Button --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.loans.index') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-xl border border-gray-200 text-gray-500 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Review Pinjaman</h1>
            <p class="text-gray-500 text-sm font-medium">ID Pengajuan: #{{ $loan->id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Kiri: Informasi Detail --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Informasi Nasabah --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Informasi Nasabah</h3>
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-blue-100">
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
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Rekening</p>
                        <p class="font-bold text-gray-800 font-mono">{{ $loan->account->account_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Saldo Saat Ini</p>
                        <p class="font-bold text-blue-600">Rp {{ number_format($loan->account->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Rincian Pengajuan --}}
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Rincian Pengajuan Pinjaman</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Jumlah Pinjaman</span>
                        <span class="text-lg font-black text-gray-800">Rp {{ number_format($loan->principal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Tenor / Durasi</span>
                        <span class="font-bold text-gray-800">{{ $loan->tenor_months }} Bulan</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Suku Bunga</span>
                        <span class="font-bold text-gray-800">{{ $loan->interest_rate }}% (Flat)</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Angsuran / Bulan</span>
                        <span class="font-black text-blue-600">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-50">
                        <span class="text-gray-500 font-medium">Total Hutang</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($loan->total_debt, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-500 font-medium">Tanggal Pengajuan</span>
                        <span class="font-bold text-gray-800">{{ $loan->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- FIX: Alasan / Tujuan Pinjaman dari Nasabah (kolom purpose) --}}
            @if($loan->purpose)
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Tujuan Pinjaman (dari Nasabah)</h3>
                <div class="flex gap-3 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                    <i class="fas fa-quote-left text-blue-300 text-lg mt-0.5"></i>
                    <p class="text-sm text-blue-800 font-medium italic leading-relaxed">{{ $loan->purpose }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Kanan: Card Keputusan --}}
        <div class="space-y-6">
            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl shadow-slate-200">
                <h3 class="text-[10px] font-black mb-6 uppercase tracking-widest opacity-70">Keputusan Admin</h3>
                
                @if($loan->status === 'pending')
                    <div class="space-y-4">
                        {{-- Form Approve --}}
                        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                class="w-full py-4 bg-green-500 hover:bg-green-600 text-white font-black rounded-2xl transition-all uppercase text-xs tracking-widest shadow-lg shadow-green-900/20"
                                onclick="return confirm('Setujui dan cairkan dana?')">
                                Setujui Pinjaman
                            </button>
                        </form>

                        <div class="relative py-2">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/10"></div></div>
                            <div class="relative flex justify-center text-[10px] uppercase font-bold"><span class="bg-slate-900 px-2 text-white/30">Atau</span></div>
                        </div>

                        {{-- Form Reject --}}
                        <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="text-[10px] font-black uppercase opacity-60 tracking-widest ml-1">Alasan Penolakan</label>
                                <textarea name="rejection_reason" required 
                                    class="w-full mt-2 bg-white/10 border border-white/20 rounded-xl p-3 text-xs text-white placeholder:text-white/30 focus:ring-2 focus:ring-red-500 focus:border-transparent outline-none transition-all"
                                    placeholder="Alasan penolakan..."
                                    rows="3"></textarea>
                                @error('rejection_reason')
                                    <p class="text-red-400 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                class="w-full py-4 bg-transparent hover:bg-red-500/10 text-red-400 hover:text-red-500 font-black rounded-2xl transition-all uppercase text-xs tracking-widest border border-red-500/30"
                                onclick="return confirm('Tolak pengajuan ini?')">
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                @else
                    {{-- Tampilan jika sudah diproses --}}
                    <div class="text-center py-4">
                        <p class="text-[10px] font-bold uppercase tracking-widest opacity-60">Status Saat Ini</p>
                        <div class="mt-4">
                            @if(in_array($loan->status, ['active', 'paid_off']))
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-green-500/20 text-green-400 rounded-full mb-2">
                                    <i class="fas fa-check"></i>
                                </div>
                                <p class="text-2xl font-black text-green-400 uppercase tracking-tight">
                                    {{ $loan->status === 'paid_off' ? 'LUNAS' : 'AKTIF' }}
                                </p>
                                @if($loan->disbursed_at)
                                <p class="text-[10px] text-white/40 font-bold mt-2 uppercase tracking-widest">
                                    Dicairkan: {{ \Carbon\Carbon::parse($loan->disbursed_at)->format('d M Y') }}
                                </p>
                                @endif
                            @elseif($loan->status === 'overdue')
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-500/20 text-orange-400 rounded-full mb-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <p class="text-2xl font-black text-orange-400 uppercase tracking-tight">JATUH TEMPO</p>
                            @else
                                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-500/20 text-red-400 rounded-full mb-2">
                                    <i class="fas fa-times"></i>
                                </div>
                                <p class="text-2xl font-black text-red-400 uppercase tracking-tight">DITOLAK</p>
                            @endif
                        </div>

                        {{-- Alasan penolakan dari admin --}}
                        @if($loan->rejection_reason)
                            <div class="mt-6 p-4 bg-black/20 rounded-2xl text-[11px] text-left border border-white/5">
                                <p class="font-black uppercase tracking-widest opacity-40 mb-2">Alasan Penolakan:</p>
                                <p class="italic text-white/80 leading-relaxed">"{{ $loan->rejection_reason }}"</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Info sisa hutang jika aktif --}}
            @if(in_array($loan->status, ['active', 'overdue']))
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Progress Cicilan</p>
                @php
                    $progress = $loan->tenor_months > 0
                        ? round(($loan->paid_installments / $loan->tenor_months) * 100)
                        : 0;
                @endphp
                <div class="flex justify-between text-xs font-bold text-gray-500 mb-2">
                    <span>{{ $loan->paid_installments }}/{{ $loan->tenor_months }} cicilan</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                    <div class="h-2 rounded-full {{ $loan->status === 'overdue' ? 'bg-orange-500' : 'bg-blue-500' }}"
                         style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500">
                    <span>Sisa Hutang</span>
                    <span class="font-black text-gray-800">Rp {{ number_format($loan->remaining_debt, 0, ',', '.') }}</span>
                </div>
            </div>
            @endif

            <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100">
                <div class="flex gap-3">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Catatan Sistem</p>
                        <p class="text-[11px] text-gray-500 leading-relaxed font-medium">Persetujuan akan memicu mutasi otomatis ke saldo nasabah dan pembuatan jadwal angsuran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
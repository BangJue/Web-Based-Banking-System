@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.accounts.index') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Top Up Saldo (Teller)</h1>
            <p class="text-gray-500 text-sm font-medium">Proses setoran tunai manual ke rekening nasabah.</p>
        </div>
    </div>

    <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <i class="fas fa-vault text-6xl"></i>
        </div>
        
        <div class="relative z-10">
            <p class="text-blue-400 text-[10px] font-black uppercase tracking-[0.2em] mb-4">Rekening Tujuan</p>
            <h3 class="text-xl font-bold mb-1">{{ $account->user->name }}</h3>
            <p class="font-mono text-lg tracking-widest text-gray-400">{{ $account->account_number }}</p>
            
            <div class="mt-8 flex justify-between items-end">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-50 mb-1">Saldo Saat Ini</p>
                    <p class="text-2xl font-black text-white">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                </div>
                <span class="px-3 py-1 rounded-lg bg-blue-600/30 text-blue-400 text-[10px] font-black uppercase">
                    {{ $account->account_type }}
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
        <form action="{{ route('admin.accounts.top_up.store', $account->id) }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Nominal Setoran (Rp)</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-gray-400 text-lg">Rp</span>
                    <input type="number" name="amount" required min="10000" step="1000"
                        placeholder="0"
                        class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl pl-14 pr-6 py-5 focus:ring-2 focus:ring-blue-600 transition-all text-2xl font-black text-gray-800">
                </div>
                @error('amount')
                    <p class="text-red-500 text-xs mt-2 font-bold italic">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block ml-1 tracking-widest">Catatan Transaksi</label>
                <textarea name="description" rows="2" 
                    placeholder="Contoh: Setoran tunai via Teller Cabang Palembang"
                    class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-blue-600 transition-all text-sm font-medium text-gray-600"></textarea>
            </div>

            <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 flex gap-4">
                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                <p class="text-[11px] text-blue-700 font-medium leading-relaxed">
                    Pastikan uang tunai telah diterima secara fisik sebelum menekan tombol konfirmasi. Saldo akan langsung bertambah ke rekening nasabah secara real-time.
                </p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all flex items-center justify-center gap-3 text-lg" onclick="return confirm('Konfirmasi top up saldo ke rekening ini?')">
                <i class="fas fa-plus-circle"></i> Proses Top Up
            </button>
        </form>
    </div>
</div>
@endsection
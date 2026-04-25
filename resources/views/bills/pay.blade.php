@extends('layouts.app')

@section('content')
<div class="py-12 px-4 max-w-3xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('bills.index') }}" class="w-12 h-12 bg-white border border-black/10 rounded-2xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tight">{{ $bill->bill_name }}</h1>
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest">Kategori: {{ $bill->category_label }}</p>
        </div>
    </div>

    <form action="{{ route('bills.process', $bill->id) }}" method="POST" class="space-y-6">
        @csrf
        {{-- Pilih Rekening --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-black/10 shadow-sm">
            <label class="text-[10px] font-black uppercase text-gray-400 mb-4 block tracking-widest">Pilih Sumber Dana</label>
            <div class="grid grid-cols-1 gap-4">
                @foreach($accounts as $account)
                <label class="relative cursor-pointer group">
                    <input type="radio" name="account_id" value="{{ $account->id }}" class="peer hidden" required>
                    <div class="p-6 border border-black/10 rounded-3xl group-hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-600/10 transition-all">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $account->account_type }}</p>
                                <p class="text-sm font-black text-gray-800">{{ $account->account_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-blue-600">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Detail Tagihan --}}
        <div class="bg-white p-8 rounded-[2.5rem] border border-black/10 shadow-sm space-y-6">
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Nomor Pelanggan / ID</label>
                <input type="text" name="customer_number" required placeholder="Contoh: 5123456789"
                    class="w-full bg-gray-50 border-none ring-1 ring-black/5 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800 shadow-inner">
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-2 block tracking-widest">Nominal Pembayaran</label>
                <div class="relative">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-gray-400">Rp</span>
                    <input type="number" name="amount" required placeholder="0"
                        class="w-full bg-gray-50 border-none ring-1 ring-black/5 rounded-2xl pl-14 pr-6 py-4 focus:ring-2 focus:ring-blue-600 transition-all font-bold text-gray-800 shadow-inner text-xl">
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 text-white font-black py-6 rounded-[2rem] hover:bg-black transition-all shadow-xl flex items-center justify-center gap-3 uppercase text-xs tracking-[0.2em]">
            Konfirmasi Pembayaran
        </button>
    </form>
</div>
@endsection
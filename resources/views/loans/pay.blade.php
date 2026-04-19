@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('loans.show', $loan->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Detail Pinjaman
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gray-900 p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-400 text-xs font-black uppercase tracking-widest mb-1">Pembayaran Cicilan</p>
                    <h1 class="text-2xl font-bold">Cicilan Ke-{{ $loan->paid_installments + 1 }}</h1>
                </div>
                <div class="text-right">
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-tighter">ID Pinjaman</p>
                    <p class="font-mono text-sm">#LNS-{{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            
            <div class="mt-8">
                <p class="text-gray-400 text-sm">Total yang harus dibayar:</p>
                <h2 class="text-4xl font-black mt-1 text-white">
                    Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <form action="{{ route('loans.pay', $loan->id) }}" method="POST" class="p-8">
            @csrf
            <div class="space-y-6">
                
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-3 ml-1">Sumber Dana</label>
                    <div class="grid gap-3">
                        @foreach($userAccounts as $acc)
                        <label class="relative flex items-center p-4 cursor-pointer rounded-2xl border-2 border-gray-100 hover:bg-gray-50 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50/30 group">
                            <input type="radio" name="account_id" value="{{ $acc->id }}" class="hidden" required {{ $loop->first ? 'checked' : '' }}>
                            
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">{{ $acc->account_number }}</p>
                                <p class="text-xs text-gray-400 font-medium">{{ ucwords($acc->account_type) }} • <span class="text-blue-600 font-bold">Rp {{ number_format($acc->balance, 0, ',', '.') }}</span></p>
                            </div>
                            
                            <div class="w-5 h-5 rounded-full border-2 border-gray-200 group-has-[:checked]:border-blue-600 group-has-[:checked]:bg-blue-600 flex items-center justify-center transition-all">
                                <div class="w-2 h-2 rounded-full bg-white scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-50">

                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-wider mb-3 ml-1">PIN Transaksi</label>
                    <div class="relative">
                        <input type="password" name="pin" required maxlength="6" pattern="\d*" inputmode="numeric"
                            class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-blue-500 font-mono text-center text-2xl tracking-[1em]" 
                            placeholder="••••••">
                    </div>
                    @error('pin')
                        <p class="text-red-500 text-xs mt-2 font-bold italic ml-1">{{ $message }}</p>
                    @enderror
                    <p class="text-[10px] text-gray-400 mt-3 text-center uppercase font-bold tracking-widest">
                        <i class="fas fa-shield-alt mr-1"></i> Transaksi ini diamankan oleh enkripsi INB
                    </p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all flex items-center justify-center gap-3 text-lg" onclick="return confirm('Konfirmasi pembayaran cicilan ini?')">
                        <i class="fas fa-check-circle"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 bg-blue-50 rounded-3xl p-6 border border-blue-100">
        <h4 class="text-blue-800 font-bold text-sm mb-2 flex items-center gap-2">
            <i class="fas fa-info-circle"></i> Catatan Penting
        </h4>
        <ul class="text-[11px] text-blue-700/70 space-y-1 font-medium leading-relaxed">
            <li>• Pastikan saldo rekening mencukupi sebelum melakukan pembayaran.</li>
            <li>• Pembayaran cicilan akan langsung mengurangi sisa hutang pokok dan bunga secara sistematis.</li>
            <li>• Jika pembayaran dilakukan setelah tanggal jatuh tempo, sistem akan mencatat sebagai keterlambatan.</li>
        </ul>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('transactions.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-gray-100 relative">
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-50 rounded-full"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-gray-50 rounded-full"></div>

        <div class="relative p-10">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 
                    {{ $transaction->status == 'success' ? 'bg-green-100 text-green-500' : 'bg-red-100 text-red-500' }}">
                    <i class="fas {{ $transaction->status == 'success' ? 'fa-check text-3xl' : 'fa-times text-3xl' }}"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800">Transaksi Berhasil</h2>
                <p class="text-gray-400 font-medium">{{ $transaction->created_at->format('d F Y • H:i') }} WIB</p>
            </div>

            <div class="bg-gray-50 rounded-3xl p-8 text-center mb-10 border border-dashed border-gray-200">
                <span class="text-gray-500 text-sm font-bold uppercase tracking-widest block mb-2">Total Nominal</span>
                <h1 class="text-4xl font-black text-gray-900">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </h1>
            </div>

            <div class="space-y-6">
                <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                    <span class="text-gray-400 font-semibold">Nomor Referensi</span>
                    <span class="text-gray-800 font-bold uppercase tracking-wider">{{ $transaction->reference_code ?? 'TXN-'.$transaction->id }}</span>
                </div>

                <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                    <span class="text-gray-400 font-semibold">Jenis Transaksi</span>
                    <span class="bg-blue-50 text-blue-600 px-4 py-1 rounded-full text-xs font-black uppercase">
                        {{ str_replace('_', ' ', $tx->type ?? $transaction->type) }}
                    </span>
                </div>

                @if($transaction->type == 'transfer_out' || $transaction->type == 'transfer_in')
                    <div class="py-4 px-6 bg-blue-50 rounded-2xl">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-left">
                                <p class="text-[10px] font-black text-blue-400 uppercase">Pengirim</p>
                                <p class="text-sm font-bold text-gray-800">{{ $transaction->transfer->fromAccount->user->name ?? 'System' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->transfer->fromAccount->account_number ?? '-' }}</p>
                            </div>
                            <i class="fas fa-long-arrow-alt-right text-blue-300 text-xl"></i>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-blue-400 uppercase">Penerima</p>
                                <p class="text-sm font-bold text-gray-800">{{ $transaction->transfer->toAccount->user->name ?? 'System' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaction->transfer->toAccount->account_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between items-center pb-4 border-b border-gray-100">
                    <span class="text-gray-400 font-semibold">Rekening Digunakan</span>
                    <span class="text-gray-800 font-bold">{{ $transaction->account->account_number }}</span>
                </div>

                @if($transaction->description)
                    <div class="flex justify-between items-start pb-4 border-b border-gray-100">
                        <span class="text-gray-400 font-semibold">Catatan</span>
                        <span class="text-gray-800 font-medium text-right max-w-[200px]">{{ $transaction->description }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-12 grid grid-cols-2 gap-4">
                <a href="{{ route('transactions.receipt', $transaction->id) }}" class="flex items-center justify-center gap-2 bg-gray-900 text-white font-bold py-4 rounded-2xl hover:bg-black transition-all shadow-lg">
                    <i class="fas fa-file-download"></i> Unduh PDF
                </a>
                <button onclick="window.print()" class="flex items-center justify-center gap-2 bg-white border-2 border-gray-100 text-gray-700 font-bold py-4 rounded-2xl hover:bg-gray-50 transition-all">
                    <i class="fas fa-share-alt"></i> Bagikan
                </button>
            </div>
        </div>
    </div>

    <p class="text-center text-gray-400 text-xs mt-8 italic">
        <i class="fas fa-shield-alt mr-1"></i> Transaksi ini telah diverifikasi secara aman oleh Indonesia National Bank Security System.
    </p>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <a href="{{ route('loans.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <div class="flex gap-3">
            @if($loan->status == 'active')
                <a href="{{ route('loans.payment.create', $loan->id) }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                    Bayar Cicilan
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
                <div class="text-center mb-6">
                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider
                        @if($loan->status == 'active') bg-green-100 text-green-600 
                        @elseif($loan->status == 'pending') bg-yellow-100 text-yellow-600
                        @elseif($loan->status == 'paid_off') bg-blue-100 text-blue-600
                        @else bg-red-100 text-red-600 @endif">
                        {{ str_replace('_', ' ', $loan->status) }}
                    </span>
                    <h2 class="text-3xl font-black text-gray-800 mt-4">Rp {{ number_format($loan->remaining_debt, 0, ',', '.') }}</h2>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-1">Sisa Hutang</p>
                </div>

                <div class="space-y-4 border-t border-gray-50 pt-6">
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm font-medium">Pokok Pinjaman</span>
                        <span class="text-gray-800 font-bold text-sm">Rp {{ number_format($loan->principal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm font-medium">Bunga ({{ $loan->interest_rate }}%)</span>
                        <span class="text-gray-800 font-bold text-sm">Rp {{ number_format($loan->total_debt - $loan->principal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm font-medium">Tenor</span>
                        <span class="text-gray-800 font-bold text-sm">{{ $loan->tenor_months }} Bulan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400 text-sm font-medium">Cicilan / Bulan</span>
                        <span class="text-blue-600 font-black text-sm">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white">
                <h4 class="text-sm font-bold mb-4 opacity-60 uppercase tracking-widest">Informasi Rekening</h4>
                <p class="text-xs opacity-50 mb-1 font-medium">Rekening Terhubung</p>
                <p class="font-bold text-lg mb-4">{{ $loan->account->account_number }}</p>
                <p class="text-[11px] leading-relaxed opacity-40 italic">
                    Segala transaksi cicilan akan didebet dari rekening ini atau rekening lain yang Anda pilih saat pembayaran.
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Progress Pinjaman</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Sudah Dibayar</p>
                        <p class="text-lg font-bold text-gray-800">{{ $loan->paid_installments }} <span class="text-xs text-gray-400">Bln</span></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Sisa Cicilan</p>
                        <p class="text-lg font-bold text-gray-800">{{ $loan->tenor_months - $loan->paid_installments }} <span class="text-xs text-gray-400">Bln</span></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Tgl Cair</p>
                        <p class="text-sm font-bold text-gray-800">{{ $loan->disbursed_at ? \Carbon\Carbon::parse($loan->disbursed_at)->format('d/m/Y') : '-' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl">
                        <p class="text-[10px] font-black text-gray-400 uppercase mb-1">Jatuh Tempo</p>
                        <p class="text-sm font-bold text-red-500">{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-gray-800 mb-4">Riwayat Pembayaran Cicilan</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                <th class="pb-4">Bulan Ke</th>
                                <th class="pb-4">Tanggal Bayar</th>
                                <th class="pb-4">Nominal</th>
                                <th class="pb-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($loan->loanPayments as $payment)
                                <tr>
                                    <td class="py-4 font-bold text-gray-700">Cicilan #{{ $payment->installment_number }}</td>
                                    <td class="py-4 text-sm text-gray-500">{{ $payment->paid_at->format('d M Y, H:i') }}</td>
                                    <td class="py-4 font-bold text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="py-4 text-right">
                                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 px-3 py-1 rounded-full uppercase">Sukses</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-gray-400 italic text-sm">Belum ada riwayat pembayaran untuk pinjaman ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($loan->status == 'rejected')
            <div class="bg-red-50 border border-red-100 rounded-[2rem] p-6 text-red-700">
                <h4 class="font-bold flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle"></i> Alasan Penolakan:
                </h4>
                <p class="text-sm opacity-80">{{ $loan->rejection_reason ?? 'Tidak ada alasan spesifik yang diberikan oleh admin.' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
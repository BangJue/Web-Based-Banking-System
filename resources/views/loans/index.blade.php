@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Pinjaman Saya</h1>
            <p class="text-gray-500 mt-1">Kelola pinjaman dan pantau jadwal cicilan Anda di INB.</p>
        </div>
        
        <div class="flex gap-4">
            <div class="bg-blue-600 text-white p-5 rounded-[2rem] shadow-lg shadow-blue-100 min-w-[200px]">
                <p class="text-xs font-bold uppercase opacity-80 tracking-widest">Total Sisa Pinjaman</p>
                <p class="text-2xl font-black mt-1">
                    Rp {{ number_format($loans->where('status', 'active')->sum('remaining_debt'), 0, ',', '.') }}
                </p>
            </div>
            <a href="{{ route('loans.create') }}" class="bg-white border-2 border-gray-100 p-5 rounded-[2rem] hover:border-blue-500 transition-all group flex flex-col justify-center min-w-[150px]">
                <p class="text-blue-600 font-bold group-hover:scale-105 transition-transform text-center">
                    <i class="fas fa-plus-circle mb-1 block text-xl"></i>
                    Ajukan Pinjaman
                </p>
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($loans as $loan)
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-8">
                    <div class="flex flex-col md:flex-row justify-between gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($loan->status == 'active') bg-green-100 text-green-600 
                                    @elseif($loan->status == 'pending') bg-yellow-100 text-yellow-600
                                    @elseif($loan->status == 'paid_off') bg-blue-100 text-blue-600
                                    @else bg-red-100 text-red-600 @endif">
                                    {{ str_replace('_', ' ', $loan->status) }}
                                </span>
                                <span class="text-gray-400 text-xs font-medium italic">Direalisasikan: {{ $loan->disbursed_at ? \Carbon\Carbon::parse($loan->disbursed_at)->format('d M Y') : '-' }}</span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $loan->purpose ?? 'Pinjaman Modal/Kebutuhan' }}</h3>
                            <p class="text-gray-400 text-sm font-medium">Rekening: <span class="text-gray-600 font-bold">{{ $loan->account->account_number }}</span></p>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-8 md:text-right border-t md:border-t-0 pt-6 md:pt-0">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pokok Pinjaman</p>
                                <p class="font-bold text-gray-700 uppercase">Rp {{ number_format($loan->principal, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Cicilan / Bln</p>
                                <p class="font-bold text-blue-600">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tenor</p>
                                <p class="font-bold text-gray-700">{{ $loan->tenor_months }} Bulan</p>
                            </div>
                        </div>
                    </div>

                    @if($loan->status == 'active')
                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Progress Pelunasan</span>
                                <span class="text-xs font-black text-blue-600">{{ $loan->paid_installments }} / {{ $loan->tenor_months }} Bulan</span>
                            </div>
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden">
                                @php 
                                    $percentage = ($loan->paid_installments / $loan->tenor_months) * 100;
                                @endphp
                                <div class="bg-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50/50 px-8 py-4 border-t border-gray-50 flex justify-between items-center">
                    <p class="text-xs text-gray-400 font-medium italic">
                        @if($loan->status == 'active')
                            Jatuh tempo berikutnya: <span class="text-gray-600 font-bold">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</span>
                        @else
                            No action required.
                        @endif
                    </p>
                    <div class="flex gap-2">
                        <a href="{{ route('loans.show', $loan->id) }}" class="text-sm font-bold text-gray-700 bg-white border border-gray-200 px-6 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                            Detail Pinjaman
                        </a>
                        @if($loan->status == 'active')
                            <a href="{{ route('loans.payment.create', $loan->id) }}" class="text-sm font-bold text-white bg-blue-600 px-6 py-2 rounded-xl hover:bg-blue-700 shadow-sm transition-colors">
                                Bayar Cicilan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[3rem] p-20 border border-dashed border-gray-200 text-center">
                <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hand-holding-usd text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Tidak ada pinjaman aktif</h3>
                <p class="text-gray-400 mt-2 max-w-sm mx-auto">Anda belum memiliki riwayat pinjaman di Indonesia National Bank. Butuh dana tambahan untuk usaha?</p>
                <a href="{{ route('loans.create') }}" class="mt-8 inline-block bg-blue-600 text-white font-black px-10 py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    Ajukan Sekarang
                </a>
            </div>
        @endforelse
    </div>

    @if($loans->hasPages())
        <div class="mt-8">
            {{ $loans->links() }}
        </div>
    @endif
</div>
@endsection
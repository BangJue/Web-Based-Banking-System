@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Persetujuan Pinjaman</h1>
            <p class="text-gray-500 text-sm font-medium">Review dan eksekusi pengajuan kredit nasabah.</p>
        </div>
        
        <div class="flex gap-2 bg-white p-1.5 rounded-2xl border border-gray-100 shadow-sm">
            <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status', 'pending') == 'pending' ? 'bg-yellow-500 text-white shadow-lg' : 'text-gray-400 hover:text-gray-600' }}">
                Pending
            </a>
            <a href="{{ route('admin.loans.index', ['status' => 'active']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') == 'active' ? 'bg-green-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-600' }}">
                Aktif
            </a>
            <a href="{{ route('admin.loans.index', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') == 'rejected' ? 'bg-red-600 text-white shadow-lg' : 'text-gray-400 hover:text-gray-600' }}">
                Ditolak
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                        <th class="px-8 py-5">Nasabah & Rekening</th>
                        <th class="px-8 py-5">Detail Pinjaman</th>
                        <th class="px-8 py-5">Angsuran/Bulan</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center font-black text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    {{ substr($loan->account->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $loan->account->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono tracking-tighter">{{ $loan->account->account_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-800">Rp {{ number_format($loan->amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $loan->duration_months }} Bulan • Bunga {{ $loan->interest_rate }}%</p>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-bold text-blue-600 italic">Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-5">
                            @php
                                $colors = [
                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                    'active' => 'bg-green-100 text-green-600',
                                    'rejected' => 'bg-red-100 text-red-600',
                                    'paid' => 'bg-blue-100 text-blue-600',
                                    'overdue' => 'bg-orange-100 text-orange-600',
                                ];
                            @endphp
                            <span class="text-[10px] font-black uppercase px-3 py-1 rounded-lg {{ $colors[$loan->status] ?? 'bg-gray-100' }}">
                                {{ $loan->status }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            @if($loan->status == 'pending')
                            <div class="flex justify-end gap-2">
                                <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan pinjaman ini?')">
                                    @csrf @method('PATCH')
                                    <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" onsubmit="return confirm('Setujui pinjaman? Saldo akan langsung dikirim ke rekening nasabah.')">
                                    @csrf @method('PATCH')
                                    <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <a href="{{ route('loans.show', $loan->id) }}" class="text-gray-300 hover:text-blue-600 transition-colors">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-50 p-6 rounded-full mb-4">
                                    <i class="fas fa-file-invoice-dollar text-4xl text-gray-200"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-400">Belum Ada Pengajuan</h3>
                                <p class="text-gray-400 text-sm">Antrean pinjaman sedang kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
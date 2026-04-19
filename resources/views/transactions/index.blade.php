@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Riwayat Transaksi</h1>
            <p class="text-gray-500">Pantau semua aktivitas keuangan Anda di INB.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-white border border-gray-200 text-gray-700 px-5 py-2.5 rounded-2xl font-semibold shadow-sm hover:bg-gray-50 transition-all">
                <i class="fas fa-print mr-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

   <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 mb-8">
    <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        
        <div class="md:col-span-3">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Pilih Rekening</label>
            <select name="account_id" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 appearance-none">
                <option value="">Semua Rekening</option>
                @foreach($userAccounts as $acc)
                    <option value="{{ $acc->id }}" {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                        {{ $acc->account_number }} ({{ ucwords($acc->account_type) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Jenis</label>
            <select name="type" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-5">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Rentang Tanggal</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-3 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <span class="text-gray-400 font-bold">to</span>
                <div class="relative flex-1">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-3 py-3 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="md:col-span-2 flex items-end">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fas fa-filter text-xs"></i>
                <span>Filter</span>
            </button>
        </div>
    </form>
</div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs font-bold uppercase tracking-widest">
                        <th class="px-6 py-5">Tanggal</th>
                        <th class="px-6 py-5">Jenis & Referensi</th>
                        <th class="px-6 py-5">Rekening</th>
                        <th class="px-6 py-5 text-right">Nominal</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-5">
                                <span class="block font-semibold text-gray-700">{{ $tx->created_at->format('d M Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $tx->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-block px-2 py-1 rounded-lg text-[10px] font-bold uppercase mb-1 
                                    {{ in_array($tx->type, ['top_up', 'transfer_in']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ str_replace('_', ' ', $tx->type) }}
                                </span>
                                <span class="block text-sm text-gray-500 font-medium">{{ $tx->reference_code ?? 'TX-'.$tx->id }}</span>
                            </td>
                            <td class="px-6 py-5 text-sm font-medium text-gray-600">
                                {{ $tx->account->account_number }}
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="block font-bold {{ in_array($tx->type, ['top_up', 'transfer_in']) ? 'text-green-600' : 'text-gray-800' }}">
                                    {{ in_array($tx->type, ['top_up', 'transfer_in']) ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($tx->status == 'success')
                                    <span class="bg-blue-100 text-blue-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Success</span>
                                @elseif($tx->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('transactions.show', $tx->id) }}" class="p-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('transactions.receipt', $tx->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100" title="Unduh PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                                        <i class="fas fa-receipt text-4xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-400">Belum ada transaksi</h3>
                                    <p class="text-gray-400 text-sm">Coba ubah filter pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
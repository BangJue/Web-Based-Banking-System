@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Rekening</h1>
            <p class="text-gray-500 text-sm font-medium">Daftar seluruh rekening nasabah NexusBank.</p>
        </div>
        
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari No. Rekening atau Nama..." 
                    class="bg-white border-none ring-1 ring-gray-200 rounded-2xl pl-11 pr-4 py-2.5 w-64 md:w-80 focus:ring-2 focus:ring-blue-600 transition-all shadow-sm text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                Cari
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                        <th class="px-8 py-5">Pemilik</th>
                        <th class="px-8 py-5">Nomor Rekening</th>
                        <th class="px-8 py-5">Tipe</th>
                        <th class="px-8 py-5">Saldo</th>
                        <th class="px-8 py-5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($accounts as $acc)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-xs text-gray-500">
                                    {{ substr($acc->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $acc->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $acc->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm font-mono font-bold text-gray-600">
                            {{ $acc->account_number }}
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-[10px] font-black uppercase px-2 py-1 rounded-lg bg-blue-50 text-blue-600">
                                {{ $acc->account_type }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-700">Rp {{ number_format($acc->balance, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <span class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $acc->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                <span class="text-xs font-bold {{ $acc->status == 'active' ? 'text-green-600' : 'text-red-600' }} capitalize">{{ $acc->status }}</span>
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-gray-400 italic">Data rekening tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accounts->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50">
            {{ $accounts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
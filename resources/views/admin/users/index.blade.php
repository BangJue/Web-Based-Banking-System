@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Manajemen Nasabah</h1>
            <p class="text-gray-500 text-sm font-medium">Kelola data pengguna dan akses akun INB.</p>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama atau email..." 
                    class="bg-white border-none ring-1 ring-gray-200 rounded-2xl pl-11 pr-4 py-2.5 w-64 md:w-80 focus:ring-2 focus:ring-blue-600 transition-all shadow-sm text-sm">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                Cari
            </button>
        </form>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.users.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('is_active') && !request('role') ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Semua
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('role') == 'admin' ? 'bg-blue-600 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Administrator
        </a>
        <a href="{{ route('admin.users.index', ['is_active' => '1']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('is_active') === '1' ? 'bg-green-600 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Akun Aktif
        </a>
        <a href="{{ route('admin.users.index', ['is_active' => '0']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('is_active') === '0' ? 'bg-red-600 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Ditangguhkan
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-50">
                        <th class="px-8 py-5">Nasabah</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5">Total Saldo</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-sm group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">{{ $u->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-[10px] font-black uppercase px-3 py-1 rounded-lg {{ $u->role == 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-500' }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-sm font-black text-gray-700">Rp {{ number_format($u->accounts->sum('balance'), 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $u->accounts_count }} Rekening</p>
                        </td>
                        <td class="px-8 py-5">
                            <form action="{{ route('admin.users.toggle_active', $u->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-2 group/btn">
                                    @if($u->is_active)
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        <span class="text-xs font-bold text-green-600 group-hover/btn:underline">Aktif</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span class="text-xs font-bold text-red-600 group-hover/btn:underline">Blokir</span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end items-center gap-2">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-all" title="Detail User">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 bg-gray-50 text-gray-400 rounded-xl hover:bg-yellow-50 hover:text-yellow-600 transition-all" title="Edit Data">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-50 p-6 rounded-full mb-4">
                                    <i class="fas fa-user-slash text-4xl text-gray-200"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-400 tracking-tight">Nasabah Tidak Ditemukan</h3>
                                <p class="text-gray-400 text-sm">Coba cari dengan kata kunci lain.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
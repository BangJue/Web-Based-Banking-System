@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">Profil Nasabah</h1>
                <p class="text-gray-500 text-sm font-medium">Informasi mendalam akun ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-8 text-center">
                <div class="w-24 h-24 bg-blue-600 text-white rounded-[2rem] flex items-center justify-center text-4xl font-black mx-auto mb-4 shadow-xl shadow-blue-100">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-xl font-black text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-400 text-sm font-medium mb-4">{{ $user->email }}</p>
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $user->is_active ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $user->is_active ? 'Akun Aktif' : 'Ditangguhkan' }}
                </span>

                <div class="mt-8 pt-8 border-t border-gray-50 space-y-4 text-left">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">NIK</span>
                        <span class="text-gray-800 font-bold">{{ $user->profile->nik ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Telepon</span>
                        <span class="text-gray-800 font-bold">{{ $user->profile->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Bergabung</span>
                        <span class="text-gray-800 font-bold">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-[2.5rem] p-8 text-white">
                <h3 class="text-sm font-black uppercase tracking-widest mb-6 opacity-60 text-blue-400">Daftar Rekening</h3>
                <div class="space-y-4">
                    @foreach($user->accounts as $acc)
                    <div class="p-4 bg-gray-800 rounded-2xl border border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-blue-600">{{ $acc->account_type }}</span>
                            <span class="text-[10px] font-bold {{ $acc->status == 'active' ? 'text-green-400' : 'text-red-400' }}">{{ strtoupper($acc->status) }}</span>
                        </div>
                        <p class="font-mono text-sm tracking-widest">{{ $acc->account_number }}</p>
                        <p class="text-lg font-black mt-1">Rp {{ number_format($acc->balance, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-800">10 Transaksi Terakhir</h3>
                    <i class="fas fa-history text-gray-200 text-xl"></i>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                <th class="px-8 py-4">Tipe & Tanggal</th>
                                <th class="px-8 py-4">Rekening</th>
                                <th class="px-8 py-4 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentTransactions as $tx)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <p class="text-sm font-bold text-gray-800 capitalize">{{ str_replace('_', ' ', $tx->type) }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $tx->created_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-8 py-5 text-sm font-mono text-gray-600">
                                    {{ $tx->account->account_number }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="text-sm font-black {{ in_array($tx->type, ['transfer_in', 'top_up', 'loan_disbursement']) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ in_array($tx->type, ['transfer_in', 'top_up', 'loan_disbursement']) ? '+' : '-' }}
                                        Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-10 text-center text-gray-400 italic text-sm">Belum ada aktivitas transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-red-50 rounded-[2.5rem] p-8 border border-red-100">
                <h3 class="text-red-800 font-black text-sm uppercase tracking-widest mb-4">Zona Berbahaya</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-red-900 leading-tight">Hapus Nasabah</p>
                        <p class="text-xs text-red-700/60 mt-1">Seluruh data akun, rekening, dan riwayat akan dihapus permanen.</p>
                    </div>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus nasabah ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-xl font-bold text-xs hover:bg-red-700 transition-all shadow-lg shadow-red-100">
                            Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="reset-password-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-[2rem] max-w-md w-full p-8 shadow-2xl">
        <h3 class="text-xl font-black text-gray-800 mb-2">Reset Password</h3>
        <p class="text-sm text-gray-500 mb-6">Tentukan password baru untuk nasabah <strong>{{ $user->name }}</strong>.</p>
        
        <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block ml-1">Password Baru</label>
                <input type="password" name="new_password" required class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 transition-all">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-gray-400 mb-1 block ml-1">Konfirmasi Password</label>
                <input type="password" name="new_password_confirmation" required class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-600 transition-all">
            </div>
            <div class="flex gap-2 pt-4">
                <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Simpan Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
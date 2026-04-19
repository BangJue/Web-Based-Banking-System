@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('loans.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-2 font-semibold">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pinjaman
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Pengajuan Pinjaman Baru</h1>
                    <p class="text-gray-500 text-sm">Silakan isi formulir di bawah ini dengan data yang benar.</p>
                </div>

                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Rekening Pencairan</label>
                            <select name="account_id" required class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-blue-500">
                                @foreach($userAccounts as $acc)
                                    <option value="{{ $acc->id }}">
                                        {{ $acc->account_number }} - {{ ucwords($acc->account_type) }} (Saldo: Rp {{ number_format($acc->balance, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Jumlah Pinjaman (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                                <input type="number" name="principal" id="principal" required min="1000000" step="100000" 
                                    class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl pl-12 pr-4 py-4 focus:ring-2 focus:ring-blue-500 font-bold text-lg" 
                                    placeholder="Contoh: 5.000.000" oninput="calculateSimulation()">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-2 ml-1">*Minimal pinjaman Rp 1.000.000</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Tenor (Bulan)</label>
                                <select name="tenor_months" id="tenor" required class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-blue-500" onchange="calculateSimulation()">
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                    <option value="24">24 Bulan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Bunga Per Tahun</label>
                                <div class="bg-gray-100 rounded-2xl px-4 py-4 text-gray-500 font-bold border border-gray-200">
                                    <span id="interest_rate">12</span>% Fixed
                                </div>
                                <input type="hidden" name="interest_rate" value="12">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 ml-1">Tujuan Pinjaman</label>
                            <textarea name="purpose" rows="3" required class="w-full bg-gray-50 border-none ring-1 ring-gray-200 rounded-2xl px-4 py-4 focus:ring-2 focus:ring-blue-500" placeholder="Misal: Modal usaha UMKM atau renovasi rumah..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all text-lg">
                            Ajukan Pinjaman Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-gray-900 text-white rounded-[2.5rem] p-8 sticky top-6 shadow-xl">
                <h3 class="text-xl font-bold mb-6">Simulasi Cicilan</h3>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-800 pb-4">
                        <span class="text-gray-400 text-sm">Total Pinjaman</span>
                        <span class="font-bold" id="sim-principal">Rp 0</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-800 pb-4">
                        <span class="text-gray-400 text-sm">Total Bunga</span>
                        <span class="font-bold" id="sim-interest">Rp 0</span>
                    </div>
                    <div class="py-4 text-center">
                        <span class="text-gray-400 text-xs uppercase tracking-widest block mb-1">Cicilan Per Bulan</span>
                        <h2 class="text-3xl font-black text-blue-400" id="sim-monthly">Rp 0</h2>
                    </div>
                    
                    <div class="bg-gray-800/50 rounded-2xl p-4">
                        <ul class="text-[10px] text-gray-400 space-y-2">
                            <li class="flex gap-2"><i class="fas fa-info-circle text-blue-500"></i> Suku bunga bersifat tetap (Fixed Rate).</li>
                            <li class="flex gap-2"><i class="fas fa-info-circle text-blue-500"></i> Persetujuan membutuhkan waktu 1-3 hari kerja.</li>
                            <li class="flex gap-2"><i class="fas fa-info-circle text-blue-500"></i> Dana akan langsung cair ke rekening yang dipilih.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateSimulation() {
        const principal = document.getElementById('principal').value || 0;
        const tenor = document.getElementById('tenor').value;
        const interestRate = 0.12; // 12% per tahun

        if (principal > 0) {
            const totalInterest = principal * (interestRate * (tenor / 12));
            const totalDebt = parseFloat(principal) + parseFloat(totalInterest);
            const monthlyInstallment = totalDebt / tenor;

            document.getElementById('sim-principal').innerText = 'Rp ' + parseInt(principal).toLocaleString('id-ID');
            document.getElementById('sim-interest').innerText = 'Rp ' + parseInt(totalInterest).toLocaleString('id-ID');
            document.getElementById('sim-monthly').innerText = 'Rp ' + Math.round(monthlyInstallment).toLocaleString('id-ID');
        } else {
            document.getElementById('sim-principal').innerText = 'Rp 0';
            document.getElementById('sim-interest').innerText = 'Rp 0';
            document.getElementById('sim-monthly').innerText = 'Rp 0';
        }
    }
</script>
@endsection
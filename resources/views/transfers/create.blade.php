@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Transfer Uang</h1>
            <p class="text-gray-500">Kirim dana antar rekening NexusBank dengan aman.</p>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
            <form action="{{ route('transfers.store') }}" method="POST" id="transferForm">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">Pilih Rekening Anda</label>
                        <select name="from_account_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->account_number }} - Rp {{ number_format($acc->balance, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">Nomor Rekening Tujuan</label>
                        <input type="text" name="to_account_number" id="dest_account" 
                               class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Contoh: 1000200030" required>
                        <div id="dest_info" class="mt-2 ml-2 text-sm font-semibold text-blue-600 hidden">
                            <i class="fas fa-user-check mr-1"></i> Pemilik: <span id="owner_name"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">Nominal Transfer (IDR)</label>
                        <input type="number" name="amount" placeholder="Min. 10.000" 
                               class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 font-bold text-lg outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">Metode</label>
                            <select name="method" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 outline-none">
                                <option value="bi_fast">BI-FAST (Rp 2.500)</option>
                                <option value="realtime">Realtime (Rp 6.500)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">PIN Transaksi</label>
                            <input type="password" name="pin" maxlength="6" placeholder="******" 
                                   class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 text-center tracking-widest outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-2">Catatan (Opsional)</label>
                        <input type="text" name="note" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 outline-none">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-lg hover:bg-blue-700 transition-all transform active:scale-95 mt-4">
                        Kirim Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Live Search Pemilik Rekening
    const destInput = document.getElementById('dest_account');
    const destInfo = document.getElementById('dest_info');
    const ownerSpan = document.getElementById('owner_name');

    destInput.addEventListener('change', async function() {
        const accountNumber = this.value;
        if(accountNumber.length < 5) return;

        try {
            const response = await fetch(`{{ route('transfers.check') }}?account_number=${accountNumber}`);
            const data = await response.json();

            if(data.owner_name) {
                destInfo.classList.remove('hidden');
                destInfo.classList.replace('text-red-500', 'text-blue-600');
                ownerSpan.innerText = data.owner_name;
            }
        } catch (error) {
            destInfo.classList.remove('hidden');
            destInfo.classList.replace('text-blue-600', 'text-red-500');
            ownerSpan.innerText = "Rekening tidak ditemukan";
        }
    });
</script>
@endsection
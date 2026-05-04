@extends('layouts.app')

@section('content')
<div class="py-10 px-4 max-w-2xl mx-auto">

    {{-- Back + Header --}}
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('bills.index') }}"
           class="w-11 h-11 bg-white border border-black/10 rounded-2xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-300 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center text-xl">
                {{ $bill->category_icon }}
            </div>
            <div>
                <h1 class="text-xl font-black text-gray-800 leading-tight">{{ $bill->bill_name }}</h1>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $bill->category_label }}</p>
            </div>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-sm text-red-700 font-semibold flex items-center gap-2">
                <i class="fas fa-circle-exclamation text-red-400 text-xs"></i>{{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('bills.process', $bill->id) }}" method="POST" class="space-y-5">
        @csrf

        {{-- Pilih Rekening --}}
        <div class="bg-white p-6 rounded-3xl border border-black/8 shadow-sm">
            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-4">Sumber Dana</p>
            <div class="space-y-3">
                @foreach($accounts as $account)
                <label class="cursor-pointer block">
                    <input type="radio" name="account_id" value="{{ $account->id }}"
                           class="peer hidden" {{ old('account_id') == $account->id ? 'checked' : '' }} required>
                    <div class="p-4 border border-black/8 rounded-2xl peer-checked:border-blue-500 peer-checked:bg-blue-50/40 hover:bg-gray-50 transition-all flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ ucfirst($account->account_type) }}</p>
                            <p class="text-sm font-black text-gray-800">{{ $account->account_number }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-blue-600">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Detail Tagihan --}}
        <div class="bg-white p-6 rounded-3xl border border-black/8 shadow-sm space-y-5">
            <p class="text-[10px] font-black uppercase tracking-[0.15em] text-gray-400">Detail Tagihan</p>

            <div>
                <label class="text-xs font-bold text-gray-500 mb-2 block">Nomor Pelanggan</label>
                <input type="text" name="customer_number" required
                       value="{{ old('customer_number') }}"
                       placeholder="Contoh: 5123456789"
                       class="w-full bg-gray-50 ring-1 ring-black/5 border-none rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-all">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500 mb-2 block">Nominal Tagihan</label>
                <div class="relative">
                    <span class="absolute left-5 top-1/2 -translate-y-1/2 font-black text-gray-400 text-sm">Rp</span>
                    <input type="number" name="amount" required
                           value="{{ old('amount') }}"
                           placeholder="0" min="1000"
                           id="amount-input"
                           class="w-full bg-gray-50 ring-1 ring-black/5 border-none rounded-2xl pl-12 pr-5 py-3.5 focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-all text-lg">
                </div>
            </div>

            {{-- Rincian Biaya --}}
            <div class="bg-gray-50 rounded-2xl p-4 space-y-2" id="fee-summary">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">Tagihan</span>
                    <span class="font-bold text-gray-700" id="display-amount">Rp 0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">Biaya Admin</span>
                    <span class="font-bold text-gray-700">Rp 2.500</span>
                </div>
                <div class="h-px bg-gray-200 my-1"></div>
                <div class="flex justify-between">
                    <span class="text-sm font-black text-gray-800">Total</span>
                    <span class="font-black text-blue-600" id="display-total">Rp 2.500</span>
                </div>
            </div>
        </div>

        {{-- PIN --}}
        <div class="bg-white p-6 rounded-3xl border border-black/8 shadow-sm">
            <label class="text-xs font-bold text-gray-500 mb-2 block">PIN Transaksi</label>
            <input type="password" name="pin" required
                   maxlength="6" inputmode="numeric" placeholder="••••••"
                   class="w-full bg-gray-50 ring-1 ring-black/5 border-none rounded-2xl px-5 py-3.5 focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 transition-all tracking-[0.4em] text-center text-lg">
            @error('pin')
            <p class="text-xs text-red-500 font-semibold mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-gray-900 text-white font-black py-5 rounded-2xl hover:bg-black transition-all shadow-lg text-xs uppercase tracking-[0.2em]">
            Bayar Sekarang
        </button>
    </form>
</div>

<script>
    const amountInput = document.getElementById('amount-input');
    const displayAmount = document.getElementById('display-amount');
    const displayTotal  = document.getElementById('display-total');
    const ADMIN_FEE = 2500;

    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    amountInput.addEventListener('input', function () {
        const amount = parseInt(this.value) || 0;
        displayAmount.textContent = formatRupiah(amount);
        displayTotal.textContent  = formatRupiah(amount + ADMIN_FEE);
    });
</script>
@endsection
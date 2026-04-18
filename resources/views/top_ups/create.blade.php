@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Top Up Saldo</h1>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100">
            <form action="{{ route('top_ups.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rekening Tujuan</label>
                        <select name="account_id" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200">
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">
                                    {{ $acc->account_number }} (Rp {{ number_format($acc->balance, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nominal</label>
                        <input type="number" name="amount" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200 font-bold" placeholder="10000">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="source" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200">
                            <option value="mobile_banking">Mobile Banking</option>
                            <option value="atm">ATM</option>
                            <option value="minimarket">Minimarket</option>
                            <option value="transfer_bank">Transfer Bank</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kode Referensi</label>
                        <input type="text" name="reference_code" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-none ring-1 ring-gray-200" placeholder="Contoh: REF123">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-lg hover:bg-blue-700 transition-all">
                        Proses Top Up
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
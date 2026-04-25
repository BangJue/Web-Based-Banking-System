<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index()
    {
        $bills = Bill::active()->get();
        return view('bills.index', compact('bills'));
    }

    public function show(Bill $bill)
    {
        if (!$bill->is_active) {
            return redirect()->route('bills.index');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $accounts = $user->accounts;

        return view('bills.pay', compact('bill', 'accounts'));
    }

    // TAMBAHKAN FUNGSI INI
    public function pay(Request $request, Bill $bill)
    {
        $request->validate([
            'account_id'      => 'required|exists:accounts,id',
            'customer_number' => 'required|string|min:5',
            'amount'          => 'required|numeric|min:1000',
        ]);

        $account = Account::where('id', $request->account_id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        if ($account->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Saldo tidak mencukupi.'])->withInput();
        }

       //2. Proses Transaksi
            // 2. Proses Transaksi
        DB::transaction(function () use ($account, $bill, $request) {
            // Ambil saldo sebelum dipotong untuk audit trail
            $balanceBefore = $account->balance;

            // A. Potong Saldo Rekening
            $account->decrement('balance', $request->amount);
            
            // Ambil saldo setelah dipotong
            $balanceAfter = $account->balance;

            // B. Buat Record di Tabel Transaksi Utama
            $transaction = \App\Models\Transaction::create([
                'user_id'          => Auth::id(),
                'account_id'       => $account->id,
                'amount'           => $request->amount,
                'type'             => 'withdrawal',
                'description'      => 'Pembayaran Tagihan ' . $bill->bill_name,
                'status'           => 'success',
                'reference_number' => 'TRX-' . strtoupper(str()->random(12)),
                // TAMBAHKAN KOLOM AUDIT INI:
                'balance_before'   => $balanceBefore,
                'balance_after'    => $balanceAfter, // Jika ada kolom 'balance_after', masukkan juga
            ]);

            // C. Simpan ke Riwayat Tagihan
            $bill->payments()->create([
                'user_id'         => Auth::id(),
                'account_id'      => $account->id,
                'customer_number' => $request->customer_number,
                'amount'          => $request->amount,
                'status'          => 'success',
                'transaction_id'  => $transaction->id,
            ]);
        });
        return redirect()->route('bills.index')->with('success', 'Pembayaran ' . $bill->bill_name . ' berhasil disimpan!');
    }
}
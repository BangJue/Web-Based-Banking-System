<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\BillPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BillController extends Controller
{
    const ADMIN_FEE = 2500;

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
        $user     = Auth::user();
        $accounts = $user->accounts()->where('status', 'active')->get();

        return view('bills.pay', compact('bill', 'accounts'));
    }

    public function pay(Request $request, Bill $bill)
    {
        $request->validate([
            'account_id'      => 'required|exists:accounts,id',
            'customer_number' => 'required|string|min:5',
            'amount'          => 'required|numeric|min:1000',
            'pin'             => 'required|digits:6',
        ]);

        $account = Account::where('id', $request->account_id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        if ($account->status !== 'active') {
            return back()->with('error', 'Rekening tidak aktif.')->withInput();
        }

        if (!Hash::check($request->pin, $account->pin)) {
            return back()->withErrors(['pin' => 'PIN tidak sesuai.'])->withInput();
        }

        // FIX: total = tagihan + admin fee
        $total = (int) $request->amount + self::ADMIN_FEE;

        if ($account->balance < $total) {
            return back()->withErrors([
                'amount' => 'Saldo tidak mencukupi. Dibutuhkan Rp ' . number_format($total, 0, ',', '.'),
            ])->withInput();
        }

        DB::transaction(function () use ($account, $bill, $request, $total) {
            $balanceBefore = $account->balance;

            // Potong saldo dengan TOTAL (tagihan + admin fee)
            $account->decrement('balance', $total);
            $account->refresh();

            $transaction = Transaction::create([
                'account_id'       => $account->id,
                'type'             => 'withdrawal',
                'amount'           => $total,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Pembayaran Tagihan ' . $bill->bill_name,
                'reference_number' => 'TRX-' . strtoupper(str()->random(12)),
                'status'           => 'success',
            ]);

            BillPayment::create([
                'transaction_id'  => $transaction->id,
                'account_id'      => $account->id,
                'bill_id'         => $bill->id,
                'customer_number' => $request->customer_number,
                'customer_name'   => null,
                'amount'          => (int) $request->amount,
                'admin_fee'       => self::ADMIN_FEE,
                'period'          => null,
                'status'          => 'success',
            ]);
        });

        return redirect()->route('bills.index')
            ->with('success', 'Pembayaran ' . $bill->bill_name . ' sebesar Rp ' . number_format($request->amount + self::ADMIN_FEE, 0, ',', '.') . ' berhasil!');
    }
}
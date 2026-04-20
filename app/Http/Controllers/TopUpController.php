<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\TopUp;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TopUpController extends Controller
{
    public function create(Account $account = null)
    {
        /** @var User $user */
        $user = Auth::user();
        $accounts = $user->accounts()->where('status', 'active')->get();
        return view('top_ups.create', compact('accounts', 'account'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'amount'     => ['required', 'integer', 'min:10000'],
            'source'     => ['required', Rule::in(['atm', 'minimarket', 'mobile_banking', 'transfer_bank'])],
            'reference_code' => ['nullable', 'string', 'max:255'],
        ]);

        $account = Account::findOrFail($request->account_id);
        $user = Auth::user();

        if ($account->user_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        DB::transaction(function () use ($account, $request) {

            $balanceBefore = $account->balance;
            $account->increment('balance', $request->amount);
            $account->refresh();

            $ref = $request->reference_code ?? $this->generateRefCode('TOP');

            // ✅ FIX DI SINI
            $tx = Transaction::create([
                'account_id'       => $account->id,
                'type'             => 'top_up',
                'amount'           => $request->amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Top up via ' . $request->source,
                'status'           => 'success',
                'reference_number' => $ref,
            ]);

            TopUp::create([
                'transaction_id' => $tx->id,
                'account_id'     => $account->id,
                'amount'         => $request->amount,
                'channel'        => $request->source,
                'reference'      => $request->reference_code,
                'status'         => 'success',
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Top Up Berhasil!');
    }

    // 🔥 Tambahin ini biar konsisten sama Transfer
    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8));
        } while (Transaction::where('reference_number', $code)->exists());

        return $code;
    }
}
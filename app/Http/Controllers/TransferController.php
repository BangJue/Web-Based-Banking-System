<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TransferController extends Controller
{
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        $accounts = $user->accounts()->where('status', 'active')->get();

        return view('transfers.create', compact('accounts'));
    }

    public function checkDestination(Request $request)
    {
        $request->validate([
            'account_number' => ['required', 'string'],
        ]);

        $destination = Account::where('account_number', $request->account_number)
            ->where('status', 'active')
            ->with('user:id,name')
            ->first();

        if (!$destination) {
            return response()->json([
                'error' => 'Rekening tujuan tidak ditemukan atau tidak aktif.'
            ], 404);
        }

        return response()->json([
            'account_number' => $destination->account_number,
            'owner_name'     => $destination->user->name,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_account_id'    => ['required', 'exists:accounts,id'],
            'to_account_number'  => ['required', 'string'],
            'amount'             => ['required', 'integer', 'min:10000'],
            'method'             => ['required', Rule::in(['bi_fast', 'realtime', 'skn', 'rtgs'])],
            'note'               => ['nullable', 'string', 'max:255'],
            'pin'                => ['required', 'digits:6'],
        ]);

        $fromAccount = Account::findOrFail($request->from_account_id);
        $toAccount   = Account::where('account_number', $request->to_account_number)
            ->where('status', 'active')
            ->firstOrFail();

        if ($fromAccount->user_id !== Auth::id()) {
            abort(403);
        }

        if ($fromAccount->id === $toAccount->id) {
            return back()->with('error', 'Tidak dapat transfer ke rekening sendiri.');
        }

        if (!Hash::check($request->pin, $fromAccount->pin)) {
            return back()->withErrors(['pin' => 'PIN tidak sesuai.'])->withInput();
        }

        $adminFee = 2500;
        $total    = $request->amount + $adminFee;

        if ($fromAccount->balance < $total) {
            return back()->with('error', 'Saldo tidak mencukupi.');
        }

        DB::transaction(function () use ($fromAccount, $toAccount, $request, $adminFee, $total) {

            // 🔒 Lock biar aman dari race condition
            $fromAccount = Account::where('id', $fromAccount->id)->lockForUpdate()->first();
            $toAccount   = Account::where('id', $toAccount->id)->lockForUpdate()->first();

            $ref = $this->generateRefCode('TRF');

            // 1. Debit pengirim
            $balanceBeforeOut = $fromAccount->balance;
            $fromAccount->decrement('balance', $total);

            $txOut = Transaction::create([
                'account_id'        => $fromAccount->id,
                'type'              => 'transfer_out',
                'amount'            => $total,
                'balance_before'    => $balanceBeforeOut,
                'balance_after'     => $fromAccount->fresh()->balance,
                'description'       => 'Transfer ke ' . $toAccount->account_number . ' (' . $request->note . ')',
                'reference_number'  => $ref, // ✅ FIX DI SINI
            ]);

            // 2. Kredit penerima
            $balanceBeforeIn = $toAccount->balance;
            $toAccount->increment('balance', $request->amount);

            Transaction::create([
                'account_id'        => $toAccount->id,
                'type'              => 'transfer_in',
                'amount'            => $request->amount,
                'balance_before'    => $balanceBeforeIn,
                'balance_after'     => $toAccount->fresh()->balance,
                'description'       => 'Transfer masuk dari ' . $fromAccount->account_number,
                'reference_number'  => $ref, // ✅ FIX DI SINI
            ]);

            // 3. Simpan detail transfer
            Transfer::create([
                'transaction_id'   => $txOut->id,
                'from_account_id' => $fromAccount->id,
                'to_account_id'   => $toAccount->id,
                'amount'          => $request->amount,
                'admin_fee'       => $adminFee,
                'method'          => $request->method,
                'note'            => $request->note,
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Transfer berhasil dikirim.');
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $accountIds = $user->accounts()->pluck('id');

        $transfers = Transfer::whereIn('from_account_id', $accountIds)
            ->orWhereIn('to_account_id', $accountIds)
            ->with(['fromAccount.user', 'toAccount.user', 'transaction'])
            ->latest()
            ->paginate(15);

        return view('transfers.index', compact('transfers'));
    }

    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8));
        } while (Transaction::where('reference_number', $code)->exists()); // ✅ FIX DI SINI

        return $code;
    }
}
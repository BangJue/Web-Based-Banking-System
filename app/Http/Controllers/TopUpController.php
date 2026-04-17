<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\TopUp;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TopUpController extends Controller
{
    /**
     * Form top up saldo.
     */
    public function create(Account $account = null)
    {
        $accounts = Auth::user()->accounts()->where('status', 'active')->get();

        return view('top_ups.create', compact('accounts', 'account'));
    }

    /**
     * Proses top up.
     * Hanya admin yang bisa top up ke rekening orang lain.
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => ['required', 'exists:accounts,id'],
            'amount'     => ['required', 'integer', 'min:10000', 'max:100000000'],
            'source'     => ['required', Rule::in([
                TopUp::SOURCE_ATM,
                TopUp::SOURCE_MINIMARKET,
                TopUp::SOURCE_MOBILE_BANKING,
                TopUp::SOURCE_INTERNET,
                TopUp::SOURCE_TELLER,
                TopUp::SOURCE_TRANSFER,
            ])],
            'reference_code' => ['nullable', 'string', 'max:255'],
        ]);

        $account = Account::findOrFail($request->account_id);

        if ($account->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Tidak dapat top up ke rekening orang lain.');
        }

        if ($account->status !== 'active') {
            return back()->with('error', 'Rekening tidak aktif.');
        }

        DB::transaction(function () use ($account, $request) {
            $balanceBefore = $account->balance;
            $account->increment('balance', $request->amount);
            $account->refresh();

            $tx = Transaction::create([
                'account_id'       => $account->id,
                'type'             => Transaction::TYPE_TOP_UP,
                'amount'           => $request->amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->balance,
                'description'      => 'Top up via ' . (new TopUp(['source' => $request->source]))->source_label,
                'reference_number' => $this->generateRefCode('TPU'),
                'status'           => 'success',
            ]);

            TopUp::create([
                'transaction_id' => $tx->id,
                'account_id'     => $account->id,
                'amount'         => $request->amount,
                'source'         => $request->source,
                'reference_code' => $request->reference_code,
            ]);
        });

        return redirect()->route('accounts.show', $account)
            ->with('success', 'Top up Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil.');
    }

    /**
     * Riwayat top up user.
     */
    public function index()
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        $topUps = TopUp::whereIn('account_id', $accountIds)
            ->with(['account', 'transaction'])
            ->latest()
            ->paginate(20);

        return view('top_ups.index', compact('topUps'));
    }

    /**
     * Detail top up.
     */
    public function show(TopUp $topUp)
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        if (!$accountIds->contains($topUp->account_id) && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $topUp->load(['account.user', 'transaction']);

        return view('top_ups.show', compact('topUp'));
    }

    // -------------------------------------------------------------------------
    // Helper
    // -------------------------------------------------------------------------

    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8)) . mt_rand(100, 999);
        } while (Transaction::where('reference_number', $code)->exists());

        return $code;
    }
}

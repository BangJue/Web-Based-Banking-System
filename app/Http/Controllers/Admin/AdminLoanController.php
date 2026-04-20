<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminLoanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', Loan::STATUS_PENDING);

        $loans = Loan::with(['account.user'])
            ->where('status', $status)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.loans.index', compact('loans'));
    }

    public function show(Loan $loan)
    {
        $loan->load(['account.user']);
        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return back()->with('error', 'Pinjaman ini sudah diproses.');
        }

        try {
            DB::transaction(function () use ($loan) {

                // 🔒 Lock account biar aman
                $account = $loan->account()->lockForUpdate()->first();

                // Generate reference
                $ref = $this->generateRefCode('LOAN');

                // 1. Update loan
                $loan->update([
                    'status'       => Loan::STATUS_ACTIVE,
                    'disbursed_at' => now(),
                    'due_date'     => now()->addMonths($loan->tenor_months),
                ]);

                // 2. Tambah saldo
                $balanceBefore = $account->balance;
                $account->increment('balance', $loan->principal);
                $account->refresh();

                // 3. Catat transaksi (WAJIB lengkap)
                Transaction::create([
                    'account_id'       => $account->id,
                    'type'             => 'loan_disbursement',
                    'amount'           => $loan->principal,
                    'balance_before'   => $balanceBefore,
                    'balance_after'    => $account->balance,
                    'description'      => "Pencairan Pinjaman ID #{$loan->id}",
                    'status'           => 'success',
                    'reference_number' => $ref, // 🔥 INI YANG SEBELUMNYA HILANG
                ]);
            });

            return redirect()->route('admin.loans.index')
                ->with('success', "Pinjaman #{$loan->id} berhasil disetujui dan saldo telah dikirim.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Loan $loan)
    {
        if ($loan->status !== Loan::STATUS_PENDING) {
            return back()->with('error', 'Pinjaman ini sudah diproses.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $loan->update([
            'status'           => Loan::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.loans.index')
            ->with('success', "Pinjaman #{$loan->id} ditolak.");
    }

    // 🔥 helper biar konsisten seluruh sistem
    private function generateRefCode(string $prefix): string
    {
        do {
            $code = $prefix . strtoupper(substr(uniqid(), -8));
        } while (Transaction::where('reference_number', $code)->exists());

        return $code;
    }
}
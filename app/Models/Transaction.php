<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Tipe-tipe transaksi yang tersedia.
     */
    const TYPE_TRANSFER_OUT  = 'transfer_out';
    const TYPE_TRANSFER_IN   = 'transfer_in';
    const TYPE_TOP_UP        = 'top_up';
    const TYPE_BILL_PAYMENT  = 'bill_payment';
    const TYPE_LOAN_DISBURSE = 'loan_disbursement';
    const TYPE_LOAN_PAYMENT  = 'loan_payment';
    const TYPE_ADMIN_FEE     = 'admin_fee';
    const TYPE_DEPOSIT       = 'deposit';
    const TYPE_WITHDRAWAL    = 'withdrawal';

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'reference_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount'         => 'integer',
            'balance_before' => 'integer',
            'balance_after'  => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transfer(): HasOne
    {
        return $this->hasOne(Transfer::class);
    }

    public function topUp(): HasOne
    {
        return $this->hasOne(TopUp::class);
    }

    public function billPayment(): HasOne
    {
        return $this->hasOne(BillPayment::class);
    }

    public function loanPayment(): HasOne
    {
        return $this->hasOne(LoanPayment::class);
    }

    public function savingsBookEntry(): HasOne
    {
        return $this->hasOne(SavingsBookEntry::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeCredit($query)
    {
        return $query->whereIn('type', [
            self::TYPE_TRANSFER_IN,
            self::TYPE_TOP_UP,
            self::TYPE_LOAN_DISBURSE,
            self::TYPE_DEPOSIT,
        ]);
    }

    public function scopeDebit($query)
    {
        return $query->whereIn('type', [
            self::TYPE_TRANSFER_OUT,
            self::TYPE_BILL_PAYMENT,
            self::TYPE_LOAN_PAYMENT,
            self::TYPE_ADMIN_FEE,
            self::TYPE_WITHDRAWAL,
        ]);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function isCredit(): bool
    {
        return in_array($this->type, [
            self::TYPE_TRANSFER_IN,
            self::TYPE_TOP_UP,
            self::TYPE_LOAN_DISBURSE,
            self::TYPE_DEPOSIT,
        ]);
    }

    public function isDebit(): bool
    {
        return !$this->isCredit();
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_TRANSFER_OUT  => 'Transfer Keluar',
            self::TYPE_TRANSFER_IN   => 'Transfer Masuk',
            self::TYPE_TOP_UP        => 'Top Up',
            self::TYPE_BILL_PAYMENT  => 'Pembayaran Tagihan',
            self::TYPE_LOAN_DISBURSE => 'Pencairan Pinjaman',
            self::TYPE_LOAN_PAYMENT  => 'Cicilan Pinjaman',
            self::TYPE_ADMIN_FEE     => 'Biaya Admin',
            self::TYPE_DEPOSIT       => 'Setor Tunai',
            self::TYPE_WITHDRAWAL    => 'Tarik Tunai',
            default                  => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}

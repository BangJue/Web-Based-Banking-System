<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'loan_id',
        'amount',
        'principal_paid',
        'interest_paid',
        'remaining_after',
        'installment_number',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'             => 'integer',
            'principal_paid'     => 'integer',
            'interest_paid'      => 'integer',
            'remaining_after'    => 'integer',
            'installment_number' => 'integer',
            'paid_at'            => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedPrincipalAttribute(): string
    {
        return 'Rp ' . number_format($this->principal_paid, 0, ',', '.');
    }

    public function getFormattedInterestAttribute(): string
    {
        return 'Rp ' . number_format($this->interest_paid, 0, ',', '.');
    }
}

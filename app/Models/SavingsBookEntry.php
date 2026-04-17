<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavingsBookEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'savings_book_id',
        'transaction_id',
        'entry_date',
        'description',
        'debit',
        'credit',
        'balance',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'debit'      => 'integer',
            'credit'     => 'integer',
            'balance'    => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function savingsBook(): BelongsTo
    {
        return $this->belongsTo(SavingsBook::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    /**
     * Apakah mutasi ini bertipe kredit (uang masuk ke rekening).
     */
    public function isDebit(): bool
    {
        return $this->debit > 0;
    }

    public function isCredit(): bool
    {
        return $this->credit > 0;
    }

    public function getFormattedDebitAttribute(): string
    {
        return $this->debit > 0
            ? 'Rp ' . number_format($this->debit, 0, ',', '.')
            : '-';
    }

    public function getFormattedCreditAttribute(): string
    {
        return $this->credit > 0
            ? 'Rp ' . number_format($this->credit, 0, ',', '.')
            : '-';
    }

    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }
}

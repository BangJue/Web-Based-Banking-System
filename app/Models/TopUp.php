<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUp extends Model
{
    use HasFactory;

    const SOURCE_ATM            = 'atm';
    const SOURCE_MINIMARKET     = 'minimarket';
    const SOURCE_MOBILE_BANKING = 'mobile_banking';
    const SOURCE_INTERNET       = 'internet_banking';
    const SOURCE_TELLER         = 'teller';
    const SOURCE_TRANSFER       = 'transfer';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'source',
        'reference_code',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            self::SOURCE_ATM            => 'ATM',
            self::SOURCE_MINIMARKET     => 'Minimarket',
            self::SOURCE_MOBILE_BANKING => 'Mobile Banking',
            self::SOURCE_INTERNET       => 'Internet Banking',
            self::SOURCE_TELLER         => 'Teller Bank',
            self::SOURCE_TRANSFER       => 'Transfer Bank Lain',
            default                     => ucwords(str_replace('_', ' ', $this->source)),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUp extends Model
{
    use HasFactory;

    // Sesuaikan dengan ENUM di bank.sql
    const SOURCE_ATM            = 'atm';
    const SOURCE_MINIMARKET     = 'minimarket';
    const SOURCE_MOBILE_BANKING = 'mobile_banking';
    const SOURCE_TRANSFER       = 'transfer_bank'; // Di DB: transfer_bank
    const SOURCE_ADMIN          = 'admin';

    protected $fillable = [
        'transaction_id',
        'account_id',
        'amount',
        'channel',   // Di DB: channel
        'reference', // Di DB: reference
        'status',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->channel) {
            self::SOURCE_ATM            => 'ATM',
            self::SOURCE_MINIMARKET     => 'Minimarket',
            self::SOURCE_MOBILE_BANKING => 'Mobile Banking',
            self::SOURCE_TRANSFER       => 'Transfer Bank',
            default                     => ucwords(str_replace('_', ' ', $this->channel)),
        };
    }
}
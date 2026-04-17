<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;

    const METHOD_ONLINE = 'online';
    const METHOD_RTGS   = 'rtgs';
    const METHOD_SKNBI  = 'sknbi';
    const METHOD_BIFAST = 'bifast';

    const ADMIN_FEES = [
        self::METHOD_ONLINE => 0,
        self::METHOD_RTGS   => 25000,
        self::METHOD_SKNBI  => 2900,
        self::METHOD_BIFAST => 2500,
    ];

    protected $fillable = [
        'transaction_id',
        'from_account_id',
        'to_account_id',
        'amount',
        'admin_fee',
        'method',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'amount'    => 'integer',
            'admin_fee' => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function getTotalAmountAttribute(): int
    {
        return $this->amount + $this->admin_fee;
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            self::METHOD_ONLINE => 'Online (Gratis)',
            self::METHOD_RTGS   => 'RTGS',
            self::METHOD_SKNBI  => 'SKNBI',
            self::METHOD_BIFAST => 'BI-FAST',
            default             => strtoupper($this->method),
        };
    }

    public static function getFeeByMethod(string $method): int
    {
        return self::ADMIN_FEES[$method] ?? 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'account_type',
        'balance',
        'currency',
        'status',
        'pin',
        'opened_at',
    ];

    protected $hidden = [
        'pin',
    ];

    protected function casts(): array
    {
        return [
            'balance'   => 'integer',
            'opened_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Transfer yang dikirim dari rekening ini.
     */
    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_account_id');
    }

    /**
     * Transfer yang diterima rekening ini.
     */
    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_account_id');
    }

    public function topUps(): HasMany
    {
        return $this->hasMany(TopUp::class);
    }

    public function billPayments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function savingsBook(): HasOne
    {
        return $this->hasOne(SavingsBook::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Saldo diformat ke Rupiah.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }

    /**
     * Label tipe rekening (human-readable).
     */
    public function getAccountTypeLabelAttribute(): string
    {
        return match ($this->account_type) {
            'tabungan' => 'Tabungan',
            'giro'     => 'Giro',
            'deposito' => 'Deposito',
            default    => ucfirst($this->account_type),
        };
    }
}

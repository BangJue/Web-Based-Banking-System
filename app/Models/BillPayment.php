<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'account_id',
        'bill_id',
        'customer_number',
        'customer_name',
        'amount',
        'admin_fee',
        'period',
        'status',
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

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function getTotalAmountAttribute(): int
    {
        return $this->amount + $this->admin_fee;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'success' => 'Berhasil',
            'pending' => 'Menunggu',
            'failed'  => 'Gagal',
            default   => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'success' => 'green',
            'pending' => 'yellow',
            'failed'  => 'red',
            default   => 'gray',
        };
    }
}

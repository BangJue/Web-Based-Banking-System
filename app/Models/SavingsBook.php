<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavingsBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'book_number',
        'issued_at',
        'last_printed',
    ];

    protected function casts(): array
    {
        return [
            'issued_at'    => 'date',
            'last_printed' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(SavingsBookEntry::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    /**
     * Entri yang belum dicetak (setelah last_printed).
     */
    public function getUnprintedEntriesQuery()
    {
        $query = $this->entries()->orderBy('entry_date');

        if ($this->last_printed) {
            $query->where('created_at', '>', $this->last_printed);
        }

        return $query;
    }

    public function getLastBalanceAttribute(): int
    {
        return $this->entries()->latest('entry_date')->value('balance') ?? 0;
    }
}

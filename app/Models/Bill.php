<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_code',
        'bill_name',
        'category',
        'icon',
        'admin_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function payments(): HasMany
    {
        return $this->hasMany(BillPayment::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'listrik'    => 'Listrik',
            'air'        => 'Air / PDAM',
            'telepon'    => 'Telepon',
            'internet'   => 'Internet',
            'bpjs'       => 'BPJS',
            'pajak'      => 'Pajak',
            'pendidikan' => 'Pendidikan',
            'lainnya'    => 'Lainnya',
            default      => ucfirst($this->category),
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'listrik'    => '⚡',
            'air'        => '💧',
            'telepon'    => '📞',
            'internet'   => '🌐',
            'bpjs'       => '🏥',
            'pajak'      => '🏛️',
            'pendidikan' => '📚',
            default      => '📄',
        };
    }
}

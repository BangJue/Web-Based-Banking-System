<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string $photo
 * @property bool $is_active
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'nik',
        'birth_date',
        'gender',
        'address',
        'photo',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'birth_date'        => 'date',
            'is_active'         => 'boolean',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Semua transaksi dari semua rekening milik user ini.
     */
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Account::class);
    }

    // -------------------------------------------------------------------------
    // Helpers / Accessors
    // -------------------------------------------------------------------------

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * URL Foto profil.
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-avatar.png');
    }

    /**
     * Total saldo gabungan semua rekening aktif.
     */
    public function getTotalBalanceAttribute(): int
    {
        // Gunakan casting ke int agar lebih aman saat operasi matematika
        return (int) $this->accounts()
            ->where('status', 'active')
            ->sum('balance');
    }

    /**
     * Rekening aktif pertama (shortcut untuk fitur transfer cepat).
     */
    public function getActiveAccountAttribute(): ?Account
    {
        return $this->accounts()->where('status', 'active')->first();
    }
}
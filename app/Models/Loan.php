<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    class Loan extends Model
    {
        use HasFactory;

        const STATUS_PENDING   = 'pending';
        const STATUS_ACTIVE    = 'active';
        const STATUS_PAID_OFF  = 'paid_off';
        const STATUS_OVERDUE   = 'overdue';
        const STATUS_REJECTED  = 'rejected';

        protected $fillable = [
            'account_id',
            'principal',
            'interest_rate',
            'tenor_months',
            'monthly_installment',
            'total_debt',
            'remaining_debt',
            'paid_installments',
            'status',
            'purpose',
            'rejection_reason',
            'disbursed_at',
            'due_date',
        ];

        protected function casts(): array
        {
            return [
                'principal'            => 'integer',
                'interest_rate'        => 'decimal:2',
                'monthly_installment'  => 'integer',
                'total_debt'           => 'integer',
                'remaining_debt'       => 'integer',
                'paid_installments'    => 'integer',
                'disbursed_at'         => 'date',
                'due_date'             => 'date',
            ];
        }

        // -------------------------------------------------------------------------
        // Relationships
        // -------------------------------------------------------------------------

        public function account(): BelongsTo
        {
            return $this->belongsTo(Account::class);
        }

        public function loanPayments(): HasMany
        {
            return $this->hasMany(LoanPayment::class);
        }

        // -------------------------------------------------------------------------
        // Scopes
        // -------------------------------------------------------------------------

        public function scopeActive($query)
        {
            return $query->where('status', self::STATUS_ACTIVE);
        }

        public function scopePending($query)
        {
            return $query->where('status', self::STATUS_PENDING);
        }

        public function scopeOverdue($query)
        {
            return $query->where('status', self::STATUS_OVERDUE);
        }

        // -------------------------------------------------------------------------
        // Helpers / Accessors
        // -------------------------------------------------------------------------

        public function isActive(): bool
        {
            return $this->status === self::STATUS_ACTIVE;
        }

        public function isPending(): bool
        {
            return $this->status === self::STATUS_PENDING;
        }

        public function isPaidOff(): bool
        {
            return $this->status === self::STATUS_PAID_OFF;
        }

        public function getRemainingInstallmentsAttribute(): int
        {
            return $this->tenor_months - $this->paid_installments;
        }

        public function getProgressPercentAttribute(): float
        {
            if ($this->tenor_months === 0) {
                return 0;
            }
            return round(($this->paid_installments / $this->tenor_months) * 100, 1);
        }

        public function getStatusLabelAttribute(): string
        {
            return match ($this->status) {
                self::STATUS_PENDING   => 'Menunggu Persetujuan',
                self::STATUS_ACTIVE    => 'Aktif',
                self::STATUS_PAID_OFF  => 'Lunas',
                self::STATUS_OVERDUE   => 'Jatuh Tempo',
                self::STATUS_REJECTED  => 'Ditolak',
                default                => ucfirst($this->status),
            };
        }

        public function getStatusColorAttribute(): string
        {
            return match ($this->status) {
                self::STATUS_PENDING  => 'yellow',
                self::STATUS_ACTIVE   => 'blue',
                self::STATUS_PAID_OFF => 'green',
                self::STATUS_OVERDUE  => 'red',
                self::STATUS_REJECTED => 'gray',
                default               => 'gray',
            };
        }

        /**
         * Hitung cicilan flat (helper statis — dipakai juga di controller simulate).
         */
        public static function calculateMonthlyInstallment(
            int $principal,
            float $interestRate,
            int $tenorMonths
        ): array {
            $totalInterest       = ($principal * ($interestRate / 100)) * ($tenorMonths / 12);
            $totalDebt           = $principal + $totalInterest;
            $monthlyInstallment  = (int) ceil($totalDebt / $tenorMonths);

            return [
                'monthly_installment' => $monthlyInstallment,
                'total_interest'      => (int) $totalInterest,
                'total_debt'          => (int) $totalDebt,
            ];
        }
    }

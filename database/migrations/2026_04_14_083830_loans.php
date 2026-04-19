<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->bigInteger('principal')->comment('Pokok pinjaman dalam Rupiah');
            $table->decimal('interest_rate', 5, 2)->comment('Bunga per tahun dalam persen, mis: 12.00');
            $table->unsignedTinyInteger('tenor_months')->comment('Tenor dalam bulan');
            $table->bigInteger('monthly_installment')->comment('Cicilan per bulan (flat)');
            $table->bigInteger('total_debt')->comment('Total hutang = pokok + bunga total');
            $table->bigInteger('remaining_debt')->comment('Sisa hutang yang belum dibayar');
            $table->unsignedTinyInteger('paid_installments')->default(0)->comment('Jumlah cicilan yang sudah dibayar');
            $table->enum('status', [
                'pending',
                'active',
                'paid_off',
                'overdue',
                'rejected',
            ])->default('pending');
            $table->string('purpose')->nullable()->comment('Tujuan pinjaman');
            $table->text('rejection_reason')->nullable();
            $table->date('disbursed_at')->nullable()->comment('Tanggal dana dicairkan');
            $table->date('due_date')->nullable()->comment('Tanggal jatuh tempo cicilan per bulan');
            $table->timestamps();

            $table->index(['account_id', 'status']);
        });

        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->bigInteger('amount')->comment('Total yang dibayarkan');
            $table->bigInteger('principal_paid')->comment('Porsi pokok yang dibayar');
            $table->bigInteger('interest_paid')->comment('Porsi bunga yang dibayar');
            $table->bigInteger('remaining_after')->comment('Sisa hutang setelah pembayaran ini');
            $table->unsignedTinyInteger('installment_number')->comment('Angsuran ke-berapa');
            $table->timestamp('paid_at')->useCurrent();
            $table->timestamps();

            $table->index(['loan_id', 'paid_at']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
        Schema::dropIfExists('loans');
    }
};
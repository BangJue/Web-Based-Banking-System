<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('related_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->enum('type', [
                'transfer_in',
                'transfer_out',
                'top_up',
                'withdrawal',
                'bill_payment',
                'va_payment',
                'qr_payment',
                'loan_disbursement',
                'loan_payment',
            ]);
            $table->bigInteger('amount')->comment('Jumlah transaksi dalam Rupiah');
            $table->bigInteger('balance_before')->comment('Saldo sebelum transaksi');
            $table->bigInteger('balance_after')->comment('Saldo setelah transaksi');
            $table->string('reference_code', 30)->unique()->comment('Kode referensi unik transaksi');
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'reversed'])->default('success');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['account_id', 'created_at']);
            $table->index('reference_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
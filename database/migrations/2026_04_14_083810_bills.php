<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code', 20)->unique()->comment('Kode unik tagihan mis: PLN, BPJS, PDAM');
            $table->string('bill_name')->comment('Nama tagihan untuk ditampilkan');
            $table->enum('category', [
                'listrik',
                'air',
                'telepon',
                'internet',
                'bpjs',
                'pajak',
                'pendidikan',
                'lainnya',
            ])->default('lainnya');
            $table->string('icon')->nullable()->comment('Nama ikon atau path gambar');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('bill_id')->constrained('bills')->cascadeOnDelete();
            $table->string('customer_number')->comment('No pelanggan / ID pelanggan tagihan');
            $table->string('customer_name')->nullable();
            $table->bigInteger('amount')->comment('Jumlah tagihan dalam Rupiah');
            $table->bigInteger('admin_fee')->default(0);
            $table->string('period')->nullable()->comment('Periode tagihan mis: 2025-01');
            $table->enum('status', ['pending', 'success', 'failed'])->default('success');
            $table->timestamps();

            $table->index(['account_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
        Schema::dropIfExists('bills');
    }
};
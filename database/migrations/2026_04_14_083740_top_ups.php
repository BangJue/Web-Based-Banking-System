<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('top_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->bigInteger('amount')->comment('Jumlah top up dalam Rupiah');
            $table->enum('channel', [
                'transfer_bank',
                'atm',
                'minimarket',
                'mobile_banking',
                'admin',
            ])->default('transfer_bank');
            $table->string('reference')->nullable()->comment('Referensi dari channel pembayaran');
            $table->enum('status', ['pending', 'success', 'failed'])->default('success');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('top_ups');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('account_number', 20)->unique();
            $table->enum('account_type', ['tabungan', 'giro', 'deposito'])->default('tabungan');
            $table->bigInteger('balance')->default(0)->comment('Saldo dalam Rupiah (satuan)');
            $table->string('currency', 3)->default('IDR');
            $table->enum('status', ['active', 'inactive', 'blocked', 'closed'])->default('active');
            $table->string('pin', 225)->nullable()->comment('PIN terenkripsi untuk transaksi');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
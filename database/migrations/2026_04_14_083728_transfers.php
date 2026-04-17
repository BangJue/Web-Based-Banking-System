<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('from_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('to_account_id')->constrained('accounts')->cascadeOnDelete();
            $table->bigInteger('amount')->comment('Jumlah transfer dalam Rupiah');
            $table->string('note')->nullable()->comment('Berita/catatan transfer');
            $table->enum('method', ['online', 'rtgs', 'sknbi', 'bifast'])->default('online');
            $table->bigInteger('admin_fee')->default(0)->comment('Biaya administrasi transfer');
            $table->timestamps();

            $table->index(['from_account_id', 'created_at']);
            $table->index(['to_account_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
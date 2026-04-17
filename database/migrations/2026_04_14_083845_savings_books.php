<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->unique()->constrained('accounts')->cascadeOnDelete();
            $table->string('book_number', 20)->unique()->comment('Nomor buku tabungan fisik');
            $table->date('issued_at')->comment('Tanggal buku diterbitkan');
            $table->timestamp('last_printed')->nullable()->comment('Terakhir kali cetak mutasi');
            $table->timestamps();
        });

        Schema::create('savings_book_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_book_id')->constrained('savings_books')->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->date('entry_date');
            $table->string('description');
            $table->bigInteger('debit')->default(0)->comment('Uang masuk ke rekening');
            $table->bigInteger('credit')->default(0)->comment('Uang keluar dari rekening');
            $table->bigInteger('balance')->comment('Saldo setelah transaksi ini');
            $table->timestamps();

            $table->index(['savings_book_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('savings_book_entries');
        Schema::dropIfExists('savings_books');
    }
};
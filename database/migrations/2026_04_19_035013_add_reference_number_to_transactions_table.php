<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        // Buat nullable dulu supaya tidak error Duplicate Entry ''
        $table->string('reference_number')->nullable()->after('id');
    });

    // OPSIONAL: Isi data lama dengan nilai random agar bisa di-set UNIQUE nanti
    // DB::table('transactions')->update(['reference_number' => DB::raw('id')]);

    Schema::table('transactions', function (Blueprint $table) {
        // Jika semua data sudah terisi/tabel kosong, baru aktifkan unique
        // $table->unique('reference_number'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};

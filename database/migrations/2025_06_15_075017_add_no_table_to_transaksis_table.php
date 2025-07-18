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
        // Menggunakan Schema::table() untuk mengubah tabel yang ada.
        Schema::table('transaksis', function (Blueprint $table) {
            // Menambahkan kolom no_table setelah kolom pelanggan_id
            // Dibuat nullable() karena transaksi takeaway mungkin tidak memiliki nomor meja.
            $table->string('no_table')->nullable()->after('pelanggan_id');
        });
    }

    /**
     * Reverse the migrations.
     * Ini akan dijalankan jika Anda melakukan rollback.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Menghapus kolom jika migrasi dibatalkan.
            $table->dropColumn('no_table');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            // user_id adalah ID pegawai (kasir) yang memproses
            $table->foreignId('user_id')->constrained('users');
            // pelanggan_id adalah ID customer, boleh null jika tidak terdaftar
            $table->foreignId('pelanggan_id')->nullable()->constrained('pelanggans');
            $table->string('nomor_transaksi')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->decimal('pajak', 12, 2)->default(0);
            $table->enum('status', ['lunas', 'menunggu'])->default('menunggu');
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->timestamp('tanggal_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
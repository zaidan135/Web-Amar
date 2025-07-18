<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pelanggan_id',
        'no_table',
        'nomor_transaksi',
        'total_harga',
        'pajak',
        'status',
        'tanggal_transaksi',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'tanggal_pembayaran' => 'datetime',
    ];

    /**
     * Transaksi ini diproses oleh seorang User (Pegawai).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaksi ini milik seorang Pelanggan.
     */
    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    /**
     * Transaksi ini memiliki banyak item detail.
     */
    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
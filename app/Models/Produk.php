<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DetailTransaksi;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['kategori_id', 'nama', 'harga', 'status'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
    
    public function detailTransaksis(): BelongsTo
    {
        return $this->belongsTo(DetailTransaksi::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama',
        'status',
    ];

    /**
     * Relasi ke model Produk.
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
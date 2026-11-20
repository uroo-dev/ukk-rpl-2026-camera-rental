<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    protected $table = 'alat';
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'harga_sewa',
        'stok',
        'deskripsi',
        'gambar',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    // public function keranjang(): HasMany
    // {
    //     return $this->hasMany(Keranjang::class);
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use SoftDeletes;

    protected $table = 'peminjaman';
    protected $fillable = [
        'kode', 
        'user_id',
        'tgl_pinjam', 
        'tgl_kembali',
        'status',
        'subtotal',
        'nama_bukti',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }
}

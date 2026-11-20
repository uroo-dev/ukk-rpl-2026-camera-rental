<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $table = 'pengembalian' ;
    protected $fillable = [
        'peminjaman_id',
        'tgl_dikembalikan',
        'terlambat',
        'denda', 
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes;
    
    protected $table = 'pelanggan' ;
    protected $fillable = [
        'user_id',
        'jenis_kelamin',    
        'no_hp',
        'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function keranajang(): HasMany 
    // {
    //     return $this->hasMany(Keranjang::class);    
    // }
}

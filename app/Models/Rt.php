<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rt extends Model
{
    protected $table = 'rts';

    protected $fillable = [
        'dusun_id',
        'nama_rt',
        'ketua_rt',
    ];

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class);
    }
}

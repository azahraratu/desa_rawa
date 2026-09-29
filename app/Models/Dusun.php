<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dusun extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_dusun',
    ];

    public function kepalaDusun(): HasOne
    {
        return $this->hasOne(User::class, 'dusun_id')->where('role', 'kadus');
    }

    public function warga(): HasMany
    {
        return $this->hasMany(Warga::class);
    }

    public function rts(): HasMany
    {
        return $this->hasMany(Rt::class);
    }
}

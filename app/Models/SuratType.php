<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratType extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'persyaratan',
        'perlu_lampiran',
        'label_lampiran',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'perlu_lampiran' => 'boolean',
        ];
    }

    public function suratRequests(): HasMany
    {
        return $this->hasMany(SuratRequest::class);
    }
}

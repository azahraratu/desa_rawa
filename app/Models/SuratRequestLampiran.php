<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratRequestLampiran extends Model
{
    protected $fillable = [
        'surat_request_id',
        'file_path',
        'nama_asli',
    ];

    public function suratRequest(): BelongsTo
    {
        return $this->belongsTo(SuratRequest::class);
    }
}

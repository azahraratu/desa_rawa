<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Riwayat/log setiap perubahan status permohonan surat.
 * Juga dipakai sebagai jejak notifikasi WhatsApp yang dikirim pada tiap tahap.
 */
class SuratRequestLog extends Model
{
    protected $fillable = [
        'surat_request_id',
        'status',
        'keterangan',
        'wa_terkirim',
    ];

    protected function casts(): array
    {
        return [
            'wa_terkirim' => 'boolean',
        ];
    }

    public function suratRequest(): BelongsTo
    {
        return $this->belongsTo(SuratRequest::class);
    }
}

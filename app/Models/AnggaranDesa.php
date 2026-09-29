<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranDesa extends Model
{
    use HasFactory;

    const JENIS_PENDAPATAN = 'pendapatan';

    const JENIS_BELANJA = 'belanja';

    protected $fillable = [
        'tahun',
        'jenis',
        'kategori',
        'keterangan',
        'jumlah_anggaran',
        'jumlah_realisasi',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'jumlah_anggaran' => 'decimal:2',
            'jumlah_realisasi' => 'decimal:2',
        ];
    }

    public static function labelJenis(): array
    {
        return [
            self::JENIS_PENDAPATAN => 'Pendapatan',
            self::JENIS_BELANJA => 'Belanja',
        ];
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /** Persentase realisasi terhadap anggaran, dibatasi maksimal 100% untuk tampilan progress bar. */
    public function persentaseRealisasi(): float
    {
        if ((float) $this->jumlah_anggaran <= 0) {
            return 0;
        }

        return min(100, round(((float) $this->jumlah_realisasi / (float) $this->jumlah_anggaran) * 100, 1));
    }

    /** Daftar tahun yang sudah memiliki data anggaran, terbaru lebih dulu. */
    public static function tahunTersedia()
    {
        return self::query()->distinct()->orderByDesc('tahun')->pluck('tahun');
    }
}

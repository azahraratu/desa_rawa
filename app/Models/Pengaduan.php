<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengaduan extends Model
{
    use HasFactory;

    const STATUS_BARU = 'baru';

    const STATUS_DIPROSES = 'diproses';

    const STATUS_SELESAI = 'selesai';

    public static function labelStatus(): array
    {
        return [
            self::STATUS_BARU => 'Baru',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_SELESAI => 'Selesai Ditanggapi',
        ];
    }

    protected $fillable = [
        'user_id',       // pelapor (warga login) - bisa juga diinput oleh kadus
        'judul',
        'kategori',      // infrastruktur | sosial | lainnya
        'deskripsi',
        'foto_path',
        'status',
        'dilaporkan_oleh_kadus',
    ];

    protected function casts(): array
    {
        return [
            'dilaporkan_oleh_kadus' => 'boolean',
        ];
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tanggapans(): HasMany
    {
        return $this->hasMany(PengaduanTanggapan::class)->orderBy('created_at');
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_BARU => 'bg-secondary',
            self::STATUS_DIPROSES => 'bg-warning text-dark',
            self::STATUS_SELESAI => 'bg-success',
            default => 'bg-secondary',
        };
    }

    /**
     * ID dusun asal pengaduan ini. Pelapor bisa berupa akun Kepala Dusun sendiri
     * (dusun_id tersimpan langsung di tabel users), atau akun Warga (dusun_id
     * TIDAK tersimpan di users, harus ditelusuri lewat data Warga miliknya).
     */
    public function dusunAsal(): ?int
    {
        if ($this->relationLoaded('pelapor') ? $this->pelapor?->dusun_id : $this->pelapor()->value('dusun_id')) {
            return $this->pelapor->dusun_id;
        }

        return $this->pelapor?->warga?->dusun_id;
    }

    /**
     * Scope: hanya pengaduan yang berasal dari dusun tertentu - baik yang
     * dilaporkan langsung oleh Kepala Dusun (dusun_id ada di akun sendiri)
     * maupun oleh Warga (dusun_id ditelusuri lewat data Warga miliknya).
     * Sebelumnya dashboard Kadus hanya mengecek pelapor->dusun_id secara
     * langsung, sehingga pengaduan yang diinput WARGA sendiri tidak pernah
     * terhitung/terlihat oleh Kepala Dusun di dusun tersebut.
     */
    public function scopeDariDusun($query, int $dusunId)
    {
        return $query->where(function ($q) use ($dusunId) {
            $q->whereHas('pelapor', fn ($qq) => $qq->where('dusun_id', $dusunId))
                ->orWhereHas('pelapor.warga', fn ($qq) => $qq->where('dusun_id', $dusunId));
        });
    }
}

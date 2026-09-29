<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratRequest extends Model
{
    use HasFactory;

    const STATUS_DIAJUKAN = 'diajukan';

    const STATUS_DIPROSES = 'diproses';

    const STATUS_DITANDATANGANI = 'ditandatangani';

    const STATUS_SELESAI = 'selesai';

    const STATUS_DITOLAK = 'ditolak';

    public static function labelStatus(): array
    {
        return [
            self::STATUS_DIAJUKAN => 'Diajukan',
            self::STATUS_DIPROSES => 'Sedang Diproses',
            self::STATUS_DITANDATANGANI => 'Ditandatangani',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
        ];
    }

    protected $fillable = [
        'kode_permohonan',
        'user_id',        // pemohon (yang login & mengajukan)
        'warga_id',       // subjek surat (bisa diri sendiri atau anggota keluarga 1 KK)
        'surat_type_id',
        'alasan',
        'lampiran_path',
        'status',
        'catatan_admin',
        'file_path',
        'diproses_oleh',
        'dibuat_oleh_kadus', // true jika dibuatkan oleh kepala dusun atas nama warga
    ];

    protected function casts(): array
    {
        return [
            'dibuat_oleh_kadus' => 'boolean',
        ];
    }

    // ==== Relasi ====

    public function pemohon(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class);
    }

    public function suratType(): BelongsTo
    {
        return $this->belongsTo(SuratType::class);
    }

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SuratRequestLog::class)->orderBy('created_at');
    }

    public function lampirans(): HasMany
    {
        return $this->hasMany(SuratRequestLampiran::class);
    }

    // ==== Helper ====

    public function labelStatusSaatIni(): string
    {
        return self::labelStatus()[$this->status] ?? $this->status;
    }

    public function badgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DIAJUKAN => 'bg-secondary',
            self::STATUS_DIPROSES => 'bg-warning text-dark',
            self::STATUS_DITANDATANGANI => 'bg-info text-dark',
            self::STATUS_SELESAI => 'bg-success',
            self::STATUS_DITOLAK => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Nomor WhatsApp yang benar untuk notifikasi & ditampilkan ke Admin.
     *
     * Ketika surat DIBUATKAN oleh Kepala Dusun (dibuat_oleh_kadus = true) dan
     * warga yang bersangkutan belum punya akun sendiri, kolom `user_id` (pemohon)
     * berisi akun Kepala Dusun - BUKAN warga subjek surat. Sebelumnya notifikasi
     * & tampilan Admin memakai `pemohon->no_hp` secara langsung, sehingga nomor
     * WhatsApp milik Kepala Dusun sendiri yang terpakai/tertampil, bukan nomor
     * warga yang sebenarnya mengajukan surat tersebut. Di sini kita utamakan
     * nomor HP milik warga (subjek surat), baru jatuh ke nomor pemohon jika
     * warga tidak/belum punya nomor HP tercatat.
     */
    public function nomorWhatsappTujuan(): ?string
    {
        $noHpWarga = trim((string) ($this->warga?->no_hp ?? ''));

        return $noHpWarga !== '' ? $noHpWarga : $this->pemohon?->no_hp;
    }
}

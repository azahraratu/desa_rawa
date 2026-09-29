<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Jejak audit untuk seluruh aksi penting dalam sistem (dibuat/diubah/dihapusnya
 * data warga, permohonan surat, akun, percobaan login, dsb). Diperlukan sebagai
 * bentuk akuntabilitas & transparansi karena sistem ini mengelola data kependudukan.
 */
class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'causer_id',
        'causer_nama',
        'causer_role',
        'aksi',
        'subjek_type',
        'subjek_id',
        'keterangan',
        'data_perubahan',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'data_perubahan' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Catat satu entri audit log. Dipanggil secara eksplisit dari controller/observer
     * pada titik-titik penting, bukan otomatis untuk semua model, agar jejak yang
     * tercatat benar-benar relevan dan mudah ditelusuri.
     */
    public static function catat(string $aksi, ?Model $subjek = null, ?string $keterangan = null, ?array $dataPerubahan = null): self
    {
        $user = Auth::user();

        return self::create([
            'causer_id' => $user?->id,
            'causer_nama' => $user?->name,
            'causer_role' => $user?->role,
            'aksi' => $aksi,
            'subjek_type' => $subjek ? get_class($subjek) : null,
            'subjek_id' => $subjek?->id,
            'keterangan' => $keterangan,
            'data_perubahan' => $dataPerubahan,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 250),
            'created_at' => now(),
        ]);
    }
}

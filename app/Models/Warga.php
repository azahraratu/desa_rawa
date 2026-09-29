<?php

namespace App\Models;

use App\Traits\EncryptsSensitiveFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Data kependudukan warga, diinput/diunggah oleh Kepala Dusun.
 * Data ini menjadi syarat verifikasi sebelum warga bisa registrasi akun.
 *
 * NIK & No. KK disimpan terenkripsi (lihat App\Traits\EncryptsSensitiveFields).
 * JANGAN pernah melakukan `where('nik', $value)` langsung - gunakan
 * `Warga::cariBerdasarkan('nik', $value)` karena kolom di database berisi ciphertext.
 */
class Warga extends Model
{
    use EncryptsSensitiveFields, HasFactory;

    protected $table = 'warga';

    /** Peta kolom sensitif -> kolom blind-index (hash) pasangannya. */
    protected array $blindIndexed = [
        'nik' => 'nik_hash',
        'no_kk' => 'no_kk_hash',
    ];

    protected $fillable = [
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'status_hubungan_keluarga',
        'tempat_lahir',
        'nama_ayah',
        'nama_ibu',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'dusun_id',
        'rt_id',
        'status_ekonomi',
        'status_pernikahan',
        'status_pendidikan',
        'pekerjaan',
        'status_kependudukan',
        'tanggal_meninggal',
        'alamat_tujuan_pindah',
        'tanggal_pindah',
        'diinput_oleh',
    ];

    /** Jangan pernah ikut ter-serialize ke JSON/array secara tidak sengaja. */
    protected $hidden = [
        'nik_hash',
        'no_kk_hash',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_meninggal' => 'date',
            'tanggal_pindah' => 'date',
        ];
    }

    // ==== Accessor dekripsi ====

    public function getNikAttribute(): ?string
    {
        return $this->getEncryptedAttribute('nik');
    }

    public function getNoKkAttribute(): ?string
    {
        return $this->getEncryptedAttribute('no_kk');
    }

    // ==== Relasi ====

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function diinputOlehUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diinput_oleh');
    }

    /** Akun login yang terhubung dengan data warga ini (jika sudah registrasi) */
    public function akun(): HasOne
    {
        return $this->hasOne(User::class, 'warga_id');
    }

    public function sudahRegistrasi(): bool
    {
        return $this->akun()->exists();
    }

    /** Password awal dibangkitkan dari tanggal lahir, format ddmmyyyy */
    public function passwordAwal(): string
    {
        return $this->tanggal_lahir->format('dmY');
    }

    public static function labelHubunganKeluarga(): array
    {
        return [
            'kepala_keluarga' => 'Kepala Keluarga',
            'suami' => 'Suami',
            'istri' => 'Istri',
            'anak' => 'Anak',
            'menantu' => 'Menantu',
            'cucu' => 'Cucu',
            'orang_tua' => 'Orang Tua',
            'mertua' => 'Mertua',
            'famili_lain' => 'Famili Lain',
            'lainnya' => 'Lainnya',
        ];
    }

    public function labelHubunganKeluargaSaya(): string
    {
        return self::labelHubunganKeluarga()[$this->status_hubungan_keluarga] ?? $this->status_hubungan_keluarga;
    }

    /**
     * Ambil seluruh anggota keluarga (1 KK) yang masih hidup, tanpa perlu
     * mendekripsi seluruh tabel: dicari lewat no_kk_hash milik warga ini.
     */
    public function anggotaKeluarga()
    {
        return static::where('no_kk_hash', $this->no_kk_hash)
            ->where('status_kependudukan', 'hidup')
            ->orderBy('nama')
            ->get();
    }
}

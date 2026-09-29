<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

/**
 * Menyediakan enkripsi at-rest untuk kolom data pribadi sensitif (NIK, No. KK, dll)
 * disertai *blind-index* (HMAC-SHA256) agar kolom tetap bisa dicari dengan exact-match
 * (mis. verifikasi registrasi, login) TANPA perlu menyimpan/mencari nilai aslinya
 * dalam bentuk plain text di database.
 *
 * Jika file database dicuri/bocor, penyerang tidak akan langsung mendapatkan NIK/KK
 * asli warga - hanya ciphertext AES-256 (memakai APP_KEY) dan hash satu-arah.
 *
 * Model yang memakai trait ini WAJIB mendefinisikan property, contoh:
 *   protected array $blindIndexed = ['nik' => 'nik_hash', 'no_kk' => 'no_kk_hash'];
 *
 * Cara pakai pencarian:
 *   Warga::cariBerdasarkan('nik', $nikInput)->first();
 */
trait EncryptsSensitiveFields
{
    public static function bootEncryptsSensitiveFields(): void
    {
        static::saving(function ($model) {
            foreach ($model->blindIndexed ?? [] as $field => $hashField) {
                $rawValue = $model->attributes[$field] ?? null;

                if ($model->isDirty($field) && ! empty($rawValue) && ! $model->sudahTerenkripsi($rawValue)) {
                    $model->attributes[$hashField] = static::buatBlindIndex($rawValue);
                    $model->attributes[$field] = Crypt::encryptString($rawValue);
                }
            }
        });
    }

    /**
     * Deteksi sederhana agar nilai yang sudah terenkripsi tidak dienkripsi ulang
     * (mis. ketika model di-save() kembali tanpa mengubah field tsb secara eksplisit).
     */
    protected function sudahTerenkripsi(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function getEncryptedAttribute(string $field): ?string
    {
        $value = $this->attributes[$field] ?? null;

        if (! $value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            // Fallback: nilai lama yang belum sempat terenkripsi (mis. hasil seeding manual).
            return $value;
        }
    }

    public static function buatBlindIndex(string $value): string
    {
        $normalized = preg_replace('/\s+/', '', trim($value));

        return hash_hmac('sha256', $normalized, config('app.key'));
    }

    public function scopeCariBerdasarkan($query, string $field, string $value)
    {
        $hashField = $this->blindIndexed[$field] ?? null;

        abort_if(! $hashField, 500, "Field `{$field}` tidak dikonfigurasi sebagai blind-index pada model ".static::class);

        return $query->where($hashField, static::buatBlindIndex($value));
    }
}

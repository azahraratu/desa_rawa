<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validasi unique untuk kolom yang dienkripsi (nik/no_kk). Rule bawaan Laravel
 * `unique:table,column` TIDAK bisa dipakai karena kolom di database berisi
 * ciphertext, sementara input dari form berupa plain text - keduanya tidak
 * akan pernah cocok sehingga validasi unique bawaan jadi tidak berfungsi
 * (bisa lolos data duplikat). Rule ini mencocokkan lewat kolom *_hash (blind-index).
 */
class UniqueEncrypted implements ValidationRule
{
    /**
     * @param  class-string  $model  Contoh: \App\Models\Warga::class
     * @param  string  $field  Nama field bisnis, contoh: 'nik' (bukan nama kolom hash)
     * @param  int|null  $ignoreId  ID record yang dikecualikan dari pengecekan (untuk form edit)
     */
    public function __construct(
        protected string $model,
        protected string $field,
        protected ?int $ignoreId = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = $this->model::cariBerdasarkan($this->field, (string) $value);

        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }

        if ($query->exists()) {
            $fail("{$attribute} sudah terdaftar sebelumnya di sistem.");
        }
    }
}

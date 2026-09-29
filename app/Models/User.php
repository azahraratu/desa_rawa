<?php

namespace App\Models;

use App\Traits\EncryptsSensitiveFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use EncryptsSensitiveFields, HasFactory, Notifiable;

    /** Peta kolom sensitif -> kolom blind-index (hash) pasangannya. */
    protected array $blindIndexed = [
        'nik' => 'nik_hash',
    ];

    protected $fillable = [
        'name',
        'nik',
        'email',
        'no_hp',
        'password',
        'role', // admin | kadus | warga
        'dusun_id',
        'warga_id',
        'must_change_password',
        'is_active',
        'created_by',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'nik_hash',
    ];

    protected function casts(): array
    {
        return [
            'must_change_password' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // ==== Accessor dekripsi ====

    public function getNikAttribute(): ?string
    {
        return $this->getEncryptedAttribute('nik');
    }

    // ==== Relasi ====

    /** Dusun yang dipimpin, khusus role kadus */
    public function dusunDipimpin(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'dusun_id');
    }

    /** Data kependudukan terkait, khusus role warga */
    public function warga(): BelongsTo
    {
        return $this->belongsTo(Warga::class, 'warga_id');
    }

    public function pembuatAkun(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function suratRequests(): HasMany
    {
        return $this->hasMany(SuratRequest::class, 'user_id');
    }

    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'user_id');
    }

    // ==== Helper Role ====

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKadus(): bool
    {
        return $this->role === 'kadus';
    }

    public function isWarga(): bool
    {
        return $this->role === 'warga';
    }

    public function catatLoginBerhasil(string $ip): void
    {
        $this->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ])->save();
    }
}

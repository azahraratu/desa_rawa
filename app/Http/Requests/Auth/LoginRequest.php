<?php

namespace App\Http\Requests\Auth;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Login untuk role Kepala Dusun & Warga, memakai NIK + password.
 * Dilindungi rate-limiting (5x percobaan gagal / menit per kombinasi NIK+IP)
 * mengikuti pola standar Laravel (Breeze/Fortify) agar tahan brute-force.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return ['nik' => 'NIK'];
    }

    /**
     * Coba autentikasi. Melempar ValidationException jika gagal / terkena rate-limit.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::cariBerdasarkan('nik', $this->string('nik'))->first();

        $valid = $user
            && $user->is_active
            && in_array($user->role, ['kadus', 'warga'], true)
            && Hash::check($this->string('password'), $user->password);

        if (! $valid) {
            RateLimiter::hit($this->throttleKey(), 60);

            AuditLog::catat('login_failed', null, "Percobaan login gagal untuk NIK berakhiran ...".substr($this->string('nik'), -4));

            throw ValidationException::withMessages([
                'nik' => 'NIK atau password yang Anda masukkan salah, atau akun tidak aktif.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Auth::login($user, $this->boolean('remember'));

        $user->catatLoginBerhasil($this->ip());
        AuditLog::catat('login', $user, 'Login berhasil.');
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nik' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('nik')).'|'.$this->ip());
    }
}

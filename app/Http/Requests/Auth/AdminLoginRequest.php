<?php

namespace App\Http\Requests\Auth;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Login khusus Admin/Perangkat Desa, memakai Email + password (terpisah dari
 * login NIK milik Kepala Dusun & Warga). Dipisah agar akun Admin - yang memiliki
 * akses paling luas - tidak berbagi alur/permukaan serangan dengan akun warga.
 */
class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->string('email'))->where('role', 'admin')->first();

        if (! $user || ! $user->is_active || ! Auth::validate(['email' => $this->string('email'), 'password' => $this->string('password'), 'role' => 'admin'])) {
            RateLimiter::hit($this->throttleKey(), 60);

            AuditLog::catat('login_failed', null, "Percobaan login admin gagal untuk email: {$this->string('email')}");

            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah, atau akun tidak aktif.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        Auth::login($user, $this->boolean('remember'));

        $user->catatLoginBerhasil($this->ip());
        AuditLog::catat('login', $user, 'Login admin berhasil.');
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login gagal. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}

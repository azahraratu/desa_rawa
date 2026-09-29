<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterWargaRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function form()
    {
        return view('auth.register');
    }

    /**
     * Registrasi hanya berhasil apabila data (NIK + No KK) sudah lebih dulu
     * diunggah oleh Kepala Dusun ke tabel `warga`, dan warga tersebut belum
     * pernah mendaftar akun sebelumnya. Ini mencegah pihak di luar warga
     * terdaftar Desa Rawa membuat akun (sesuai proposal bab 1.1 & 1.3).
     *
     * Dibatasi rate-limit untuk mencegah percobaan brute-force menerka
     * kombinasi NIK+KK milik warga lain.
     */
    public function submit(RegisterWargaRequest $request)
    {
        $throttleKey = 'register|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 8)) {
            $detik = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'nik' => "Terlalu banyak percobaan. Coba lagi dalam {$detik} detik.",
            ]);
        }

        $data = $request->validated();

        $warga = Warga::cariBerdasarkan('nik', $data['nik'])->first();

        if (! $warga || $warga->no_kk !== $data['no_kk']) {
            RateLimiter::hit($throttleKey, 300);

            throw ValidationException::withMessages([
                'nik' => 'Data tidak ditemukan. Pastikan NIK dan Nomor KK sesuai data yang telah didaftarkan oleh Kepala Dusun Anda. Jika Anda warga baru, silakan hubungi Kepala Dusun terlebih dahulu.',
            ]);
        }

        if ($warga->status_kependudukan === 'meninggal') {
            throw ValidationException::withMessages([
                'nik' => 'Data kependudukan berstatus meninggal dunia, registrasi tidak dapat diproses.',
            ]);
        }

        if ($warga->sudahRegistrasi()) {
            throw ValidationException::withMessages([
                'nik' => 'NIK ini sudah terdaftar. Silakan login, atau gunakan menu lupa password.',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $passwordAwal = $warga->passwordAwal();

        $user = User::create([
            'name' => $warga->nama,
            'nik' => $warga->nik,
            'no_hp' => $data['no_hp'],
            'password' => Hash::make($passwordAwal),
            'role' => 'warga',
            'warga_id' => $warga->id,
            'must_change_password' => true,
            'is_active' => true,
        ]);

        // Sinkronkan nomor HP terbaru ke data kependudukan juga.
        $warga->update(['no_hp' => $data['no_hp']]);

        AuditLog::catat('created', $user, "Registrasi mandiri akun warga: {$warga->nama}.");

        session()->put('kredensial_registrasi', [
            'nama' => $warga->nama,
            'nik' => $warga->nik,
            'password' => $passwordAwal,
            'ditampilkan_pada' => now()->timestamp,
        ]);

        return redirect()->route('register.success');
    }

    /**
     * Halaman khusus menampilkan kredensial hasil registrasi, terpisah dari
     * halaman login, dengan batas waktu tampil 5 menit (bisa di-screenshot
     * orang tua/wali warga sebelum otomatis disembunyikan).
     */
    public function sukses()
    {
        $kredensial = session('kredensial_registrasi');

        if (! $kredensial) {
            return redirect()->route('login');
        }

        $detikBerlalu = now()->timestamp - $kredensial['ditampilkan_pada'];
        $sisaDetik = max(0, 300 - $detikBerlalu);

        if ($sisaDetik <= 0) {
            session()->forget('kredensial_registrasi');

            return redirect()->route('login')
                ->with('status', 'Waktu tampil kredensial sudah habis. Silakan login menggunakan NIK & password yang sudah Anda catat sebelumnya.');
        }

        return view('auth.registrasi-sukses', [
            'kredensial' => $kredensial,
            'sisaDetik' => $sisaDetik,
        ]);
    }

    /** Warga menekan tombol "sudah saya catat" - hapus kredensial dari sesi lebih awal. */
    public function suksesSelesai()
    {
        session()->forget('kredensial_registrasi');

        return redirect()->route('login')->with('status', 'Silakan login menggunakan NIK & password yang sudah dicatat.');
    }
}

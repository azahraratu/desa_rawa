<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function form()
    {
        // Tidak lagi auto-redirect paksa jika sudah login - beberapa orang memakai
        // beberapa tab/peran berbeda di browser yang sama, jadi tetap tampilkan
        // form login dengan pilihan "lanjut ke dashboard" atau "ganti akun".
        return view('auth.login', ['sudahLogin' => Auth::user()]);
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        AuditLog::catat('logout', Auth::user(), 'Logout.');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    protected function redirectByRole()
    {
        $user = Auth::user();

        if ($user->must_change_password) {
            return redirect()->route('password.force.form');
        }

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kadus' => redirect()->route('kadus.dashboard'),
            default => redirect()->route('warga.dashboard'),
        };
    }

    public function forceChangeForm()
    {
        return view('auth.force-change-password');
    }

    public function forceChangeSubmit(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ], [], ['password' => 'Kata sandi']);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        AuditLog::catat('updated', $user, 'Password berhasil diganti oleh pemilik akun.');

        return $this->redirectByRole()->with('status', 'Password berhasil diubah.');
    }
}

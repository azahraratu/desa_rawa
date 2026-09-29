<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Menu Profil - berlaku untuk semua peran (Admin, Kepala Dusun, Warga).
 * Fungsinya: melengkapi data akun (No. HP & Email), dan mengganti password
 * secara mandiri tanpa perlu minta bantuan Admin.
 */
class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user()->load(['dusunDipimpin', 'warga']);

        return view('profile.index', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'no_hp' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+]*$/'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka.',
        ], [
            'no_hp' => 'Nomor HP/WhatsApp',
            'email' => 'Email',
        ]);

        $user->update($data);

        AuditLog::catat('updated', $user, 'Melengkapi/memperbarui data profil sendiri.');

        return back()->with('status', 'Data profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
        ], [], [
            'current_password' => 'Kata sandi saat ini',
            'password' => 'Kata sandi baru',
        ]);

        $user->update([
            'password' => Hash::make($request->string('password')),
        ]);

        AuditLog::catat('updated', $user, 'Password diganti mandiri lewat menu Profil.');

        return back()->with('status', 'Password berhasil diganti.');
    }
}

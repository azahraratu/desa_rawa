<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;

/**
 * Login khusus Admin/Perangkat Desa - sengaja dipisah total dari login NIK
 * (Kadus & Warga) di /login. Diakses lewat /admin/login, memakai email + password.
 */
class AdminAuthController extends Controller
{
    public function form()
    {
        return view('auth.admin-login', ['sudahLogin' => auth()->user()]);
    }

    public function login(AdminLoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (auth()->user()->must_change_password) {
            return redirect()->route('password.force.form');
        }

        return redirect()->route('admin.dashboard');
    }
}

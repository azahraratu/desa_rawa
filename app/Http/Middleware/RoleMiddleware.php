<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware Role-Based Access Control (RBAC).
 *
 * Membatasi akses route berdasarkan kolom `role` pada user yang sedang login.
 * Digunakan pada route dengan syntax: ->middleware('role:admin,kadus')
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Paksa ganti password default (hasil generate dari tanggal lahir)
        // sebelum bisa mengakses halaman lain, kecuali sedang menuju halaman ganti password itu sendiri.
        if ($user->must_change_password && ! $request->routeIs('password.force.*')) {
            return redirect()->route('password.force.form');
        }

        return $next($request);
    }
}

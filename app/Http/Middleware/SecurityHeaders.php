<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan header keamanan standar pada setiap response HTTP.
 * Penting untuk sistem yang mengelola data kependudukan agar terlindung dari
 * clickjacking, MIME-sniffing, dan kebocoran referrer ke situs pihak ketiga.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        if (! $request->hasHeader('X-Robots-Tag')) {
            // Cegah mesin pencari mengindeks halaman internal desa (bukan situs publik pemasaran).
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}

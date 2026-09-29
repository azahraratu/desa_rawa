<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // FIX: Pada beberapa environment (bug yang sudah dilaporkan resmi ke
        // laravel/framework - issue #54666), binding container 'files' kadang
        // tidak otomatis terdaftar di struktur project minimal Laravel 11+,
        // menyebabkan error "Target class [files] does not exist" saat
        // EventServiceProvider bawaan framework mencoba boot. Didaftarkan manual
        // di sini sebagai jaring pengaman, dijalankan di register() supaya
        // tersedia sebelum provider manapun mulai boot().
        $this->app->bind('files', function () {
            return new Filesystem;
        });
    }

    public function boot(): void
    {
        // Paksa seluruh URL yang digenerate memakai HTTPS ketika aplikasi berjalan
        // di lingkungan produksi (penting karena aplikasi ini menangani data pribadi).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}

<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KadusController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\SuratController as AdminSuratController;
use App\Http\Controllers\Admin\WargaController as AdminWargaController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Kadus\DashboardController as KadusDashboardController;
use App\Http\Controllers\Kadus\DataWargaController;
use App\Http\Controllers\Kadus\PengaduanController as KadusPengaduanController;
use App\Http\Controllers\Kadus\SuratController as KadusSuratController;
use App\Http\Controllers\Warga\DashboardController as WargaDashboardController;
use App\Http\Controllers\Warga\PengaduanController as WargaPengaduanController;
use App\Http\Controllers\Warga\SuratController as WargaSuratController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $tahunAnggaran = \App\Models\AnggaranDesa::tahunTersedia()->first();
    $itemAnggaran = $tahunAnggaran
        ? \App\Models\AnggaranDesa::where('tahun', $tahunAnggaran)->orderBy('jenis')->orderBy('kategori')->get()
        : collect();

    $dashboardRoute = null;
    if (Auth::check()) {
        $dashboardRoute = match (Auth::user()->role) {
            'admin' => route('admin.dashboard'),
            'kadus' => route('kadus.dashboard'),
            default => route('warga.dashboard'),
        };
    }

    return view('welcome', [
        'desa' => config('services.desa'),
        'tahunAnggaran' => $tahunAnggaran,
        'pendapatanAnggaran' => $itemAnggaran->where('jenis', 'pendapatan'),
        'belanjaAnggaran' => $itemAnggaran->where('jenis', 'belanja'),
        'dashboardRoute' => $dashboardRoute,
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Autentikasi - Warga & Kepala Dusun (login via NIK)
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'form'])->name('register');
Route::post('/register', [RegisterController::class, 'submit'])
    ->middleware('throttle:10,1')
    ->name('register.submit');
Route::get('/registrasi-berhasil', [RegisterController::class, 'sukses'])->name('register.success');
Route::post('/registrasi-berhasil/selesai', [RegisterController::class, 'suksesSelesai'])->name('register.success.selesai');

Route::get('/login', [LoginController::class, 'form'])->name('login');
Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:10,1')
    ->name('login.submit');
// Idealnya logout selalu lewat POST (form + CSRF, lihat tombol "Keluar" di sidebar).
// GET tetap diterima sebagai jaring pengaman supaya link lama/bookmark/sesi yang
// sudah kedaluwarsa tidak menampilkan error "Method Not Allowed" ke pengguna.
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Autentikasi - Admin (login terpisah via Email)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'form'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/ganti-password-wajib', [LoginController::class, 'forceChangeForm'])->name('password.force.form');
    Route::post('/ganti-password-wajib', [LoginController::class, 'forceChangeSubmit'])->name('password.force.submit');

    // Menu Profil - tersedia untuk semua peran (Admin, Kepala Dusun, Warga):
    // melengkapi data akun (No. HP/Email) & ganti password secara mandiri.
    Route::get('/profil', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profil', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});

/*
|--------------------------------------------------------------------------
| Area Warga
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/beranda', [WargaDashboardController::class, 'index'])->name('dashboard');

    Route::get('/surat', [WargaSuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/ajukan', [WargaSuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [WargaSuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{surat}', [WargaSuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{surat}/unduh', [WargaSuratController::class, 'download'])->name('surat.download');

    Route::get('/pengaduan', [WargaPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/buat', [WargaPengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [WargaPengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{pengaduan}', [WargaPengaduanController::class, 'show'])->name('pengaduan.show');
});

/*
|--------------------------------------------------------------------------
| Area Kepala Dusun
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kadus'])->prefix('kadus')->name('kadus.')->group(function () {
    Route::get('/beranda', [KadusDashboardController::class, 'index'])->name('dashboard');

    Route::get('/warga', [DataWargaController::class, 'index'])->name('warga.index');
    Route::get('/warga/tambah', [DataWargaController::class, 'create'])->name('warga.create');
    Route::post('/warga', [DataWargaController::class, 'store'])->name('warga.store');
    Route::get('/warga/{warga}/ubah', [DataWargaController::class, 'edit'])->name('warga.edit');
    Route::put('/warga/{warga}', [DataWargaController::class, 'update'])->name('warga.update');
    Route::post('/warga/{warga}/tandai-meninggal', [DataWargaController::class, 'tandaiMeninggal'])->name('warga.meninggal');
    Route::post('/warga/{warga}/tandai-pindah', [DataWargaController::class, 'tandaiPindah'])->name('warga.pindah');
    Route::post('/warga/{warga}/jadikan-profil-saya', [DataWargaController::class, 'jadikanProfilSaya'])->name('warga.jadikan-profil-saya');
    Route::get('/warga-import', [DataWargaController::class, 'importForm'])->name('warga.import.form');
    Route::post('/warga-import', [DataWargaController::class, 'importSubmit'])->name('warga.import.submit');
    Route::get('/warga-import/template', [DataWargaController::class, 'downloadTemplate'])->name('warga.import.template');

    Route::get('/rt', [\App\Http\Controllers\Kadus\RtController::class, 'index'])->name('rt.index');
    Route::get('/rt/tambah', [\App\Http\Controllers\Kadus\RtController::class, 'create'])->name('rt.create');
    Route::post('/rt', [\App\Http\Controllers\Kadus\RtController::class, 'store'])->name('rt.store');
    Route::get('/rt/{rt}/ubah', [\App\Http\Controllers\Kadus\RtController::class, 'edit'])->name('rt.edit');
    Route::put('/rt/{rt}', [\App\Http\Controllers\Kadus\RtController::class, 'update'])->name('rt.update');
    Route::delete('/rt/{rt}', [\App\Http\Controllers\Kadus\RtController::class, 'destroy'])->name('rt.destroy');

    Route::get('/surat', [KadusSuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/buat', [KadusSuratController::class, 'create'])->name('surat.create');
    Route::post('/surat', [KadusSuratController::class, 'store'])->name('surat.store');
    Route::get('/surat/{surat}', [KadusSuratController::class, 'show'])->name('surat.show');
    Route::get('/surat/{surat}/unduh', [KadusSuratController::class, 'download'])->name('surat.download');

    Route::get('/pengaduan', [KadusPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/buat', [KadusPengaduanController::class, 'create'])->name('pengaduan.create');
    Route::post('/pengaduan', [KadusPengaduanController::class, 'store'])->name('pengaduan.store');
    Route::get('/pengaduan/{pengaduan}', [KadusPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{pengaduan}/tanggapi', [KadusPengaduanController::class, 'tanggapi'])->name('pengaduan.tanggapi');
});

/*
|--------------------------------------------------------------------------
| Area Admin / Perangkat Desa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/surat', [AdminSuratController::class, 'index'])->name('surat.index');
    Route::get('/surat/{surat}', [AdminSuratController::class, 'show'])->name('surat.show');
    Route::post('/surat/{surat}/proses', [AdminSuratController::class, 'proses'])->name('surat.proses');
    Route::post('/surat/{surat}/tandatangani', [AdminSuratController::class, 'tandaTangani'])->name('surat.tandatangani');
    Route::post('/surat/{surat}/selesaikan', [AdminSuratController::class, 'selesaikan'])->name('surat.selesaikan');
    Route::post('/surat/{surat}/tolak', [AdminSuratController::class, 'tolak'])->name('surat.tolak');
    Route::get('/surat/{surat}/unduh', [AdminSuratController::class, 'download'])->name('surat.download');

    Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::post('/pengaduan/{pengaduan}/tanggapi', [AdminPengaduanController::class, 'tanggapi'])->name('pengaduan.tanggapi');

    Route::get('/warga', [AdminWargaController::class, 'index'])->name('warga.index');
    Route::get('/warga/unduh-semua', [AdminWargaController::class, 'exportSemua'])->name('warga.export-semua');
    Route::get('/warga/dusun/{dusun}', [AdminWargaController::class, 'perDusun'])->name('warga.dusun');
    Route::get('/warga/dusun/{dusun}/unduh', [AdminWargaController::class, 'exportDusun'])->name('warga.dusun.export');
    Route::post('/warga/{warga}/reset-password', [AdminWargaController::class, 'resetPassword'])->name('warga.reset-password');
    Route::post('/warga/{warga}/tandai-pindah', [AdminWargaController::class, 'tandaiPindah'])->name('warga.pindah');

    Route::get('/dusun', [\App\Http\Controllers\Admin\DusunController::class, 'index'])->name('dusun.index');
    Route::get('/dusun/tambah', [\App\Http\Controllers\Admin\DusunController::class, 'create'])->name('dusun.create');
    Route::post('/dusun', [\App\Http\Controllers\Admin\DusunController::class, 'store'])->name('dusun.store');
    Route::get('/dusun/{dusun}/ubah', [\App\Http\Controllers\Admin\DusunController::class, 'edit'])->name('dusun.edit');
    Route::put('/dusun/{dusun}', [\App\Http\Controllers\Admin\DusunController::class, 'update'])->name('dusun.update');
    Route::delete('/dusun/{dusun}', [\App\Http\Controllers\Admin\DusunController::class, 'destroy'])->name('dusun.destroy');

    Route::get('/arsip', [\App\Http\Controllers\Admin\ArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/lihat', [\App\Http\Controllers\Admin\ArsipController::class, 'show'])->name('arsip.show');
    Route::get('/arsip/unduh-excel', [\App\Http\Controllers\Admin\ArsipController::class, 'exportExcel'])->name('arsip.excel');
    Route::get('/arsip/unduh-pdf', [\App\Http\Controllers\Admin\ArsipController::class, 'exportPdf'])->name('arsip.pdf');

    Route::get('/kepala-dusun', [KadusController::class, 'index'])->name('kadus.index');
    Route::get('/kepala-dusun/tambah', [KadusController::class, 'create'])->name('kadus.create');
    Route::post('/kepala-dusun', [KadusController::class, 'store'])->name('kadus.store');
    Route::post('/kepala-dusun/{kadus}/toggle-aktif', [KadusController::class, 'toggleAktif'])->name('kadus.toggle');
    Route::post('/kepala-dusun/{kadus}/reset-password', [KadusController::class, 'resetPassword'])->name('kadus.reset-password');

    Route::get('/audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit.index');

    Route::get('/laporan', [LaporanController::class, 'form'])->name('laporan.form');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    Route::get('/laporan-pengaduan', [\App\Http\Controllers\Admin\PengaduanLaporanController::class, 'form'])->name('pengaduan.laporan.form');
    Route::get('/laporan-pengaduan/cetak', [\App\Http\Controllers\Admin\PengaduanLaporanController::class, 'cetak'])->name('pengaduan.laporan.cetak');

    Route::get('/rekapitulasi', [\App\Http\Controllers\Admin\RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
    Route::get('/rekapitulasi/cetak', [\App\Http\Controllers\Admin\RekapitulasiController::class, 'cetak'])->name('rekapitulasi.cetak');

    Route::get('/surat/{surat}/lampiran/{lampiran}', [AdminSuratController::class, 'unduhLampiran'])->name('surat.lampiran');

    Route::get('/anggaran', [\App\Http\Controllers\Admin\AnggaranController::class, 'index'])->name('anggaran.index');
    Route::get('/anggaran/tambah', [\App\Http\Controllers\Admin\AnggaranController::class, 'create'])->name('anggaran.create');
    Route::post('/anggaran', [\App\Http\Controllers\Admin\AnggaranController::class, 'store'])->name('anggaran.store');
    Route::get('/anggaran/{anggaran}/ubah', [\App\Http\Controllers\Admin\AnggaranController::class, 'edit'])->name('anggaran.edit');
    Route::put('/anggaran/{anggaran}', [\App\Http\Controllers\Admin\AnggaranController::class, 'update'])->name('anggaran.update');
    Route::delete('/anggaran/{anggaran}', [\App\Http\Controllers\Admin\AnggaranController::class, 'destroy'])->name('anggaran.destroy');
});

Route::get('/lupa-password', function () {
    return view('auth.lupa-password');
})->name('password.lupa');

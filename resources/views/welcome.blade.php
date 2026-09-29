<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelayanan Desa Rawa - Kabupaten Ciamis</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon-180.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg" style="background:rgba(255,255,255,.9); backdrop-filter:blur(8px); position:sticky; top:0; z-index:50; box-shadow:var(--shadow-soft);">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('img/logo-ciamis.png') }}" alt="Logo" style="width:32px;height:auto;">
            <span class="fw-bold brand-font" style="color:var(--primary-800);">Desa Rawa</span>
        </a>
        <div class="d-flex gap-2 align-items-center">
            @auth
                <a href="{{ $dashboardRoute }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--primary-600);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="small fw-semibold d-none d-sm-inline" style="color:var(--primary-800);">{{ Str::limit(auth()->user()->name, 18) }}</span>
                </a>
                <a href="{{ $dashboardRoute }}" class="btn btn-success btn-sm">Ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm">Login Warga</a>
                <a href="{{ route('register') }}" class="btn btn-success btn-sm">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

<header class="landing-hero">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="hero-badge mb-3" style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:.4rem .9rem;border-radius:999px;font-size:.78rem;">
                                <i class="bi bi-geo-alt-fill"></i> Kecamatan Lumbung, Kabupaten Ciamis, Jawa Barat
                            </div>
                            <h1 class="fw-bold display-5 mb-3">Sistem Informasi Pelayanan Administrasi Desa Rawa</h1>
                            <p class="lead" style="color:rgba(255,255,255,.8);">Layanan pengajuan surat online, pengaduan masyarakat, dan data kependudukan yang aman, transparan, dan bisa diakses kapan saja — mendukung Ciamis Sabilulungan menuju desa digital.</p>
                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('register') }}" class="btn btn-gold btn-lg"><i class="bi bi-person-plus me-1"></i> Daftar Akun Warga</a>
                                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
                            </div>
                        </div>
                        <div class="col-lg-5 text-center d-none d-lg-block">
                            <img src="{{ asset('img/logo-ciamis.png') }}" alt="Logo Kabupaten Ciamis" style="width:220px; filter:drop-shadow(0 20px 40px rgba(0,0,0,.35));">
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="hero-badge mb-3" style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:.4rem .9rem;border-radius:999px;font-size:.78rem;">
                                <i class="bi bi-whatsapp"></i> Pantau Status Lewat WhatsApp
                            </div>
                            <h1 class="fw-bold display-5 mb-3">Ajukan Surat Tanpa Antre ke Kantor Desa</h1>
                            <p class="lead" style="color:rgba(255,255,255,.8);">Cukup dari HP, ajukan surat keterangan, dapatkan notifikasi WhatsApp di setiap tahap, lalu unduh PDF resmi begitu selesai diproses.</p>
                            <div class="d-flex gap-2 mt-4">
                                <a href="{{ route('register') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text me-1"></i> Mulai Ajukan Surat</a>
                            </div>
                        </div>
                        <div class="col-lg-5 text-center d-none d-lg-block">
                            <i class="bi bi-file-earmark-check" style="font-size:220px;color:rgba(255,255,255,.15);"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container py-5">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="hero-badge mb-3" style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);padding:.4rem .9rem;border-radius:999px;font-size:.78rem;">
                                <i class="bi bi-shield-check"></i> Transparan &amp; Akuntabel
                            </div>
                            <h1 class="fw-bold display-5 mb-3">Anggaran Desa Terbuka untuk Warga</h1>
                            <p class="lead" style="color:rgba(255,255,255,.8);">Lihat besaran dan pemanfaatan anggaran desa setiap tahun langsung di halaman ini, sesuai semangat Ciamis Sabilulungan yang transparan.</p>
                            <div class="d-flex gap-2 mt-4">
                                <a href="#anggaran" class="btn btn-gold btn-lg"><i class="bi bi-bar-chart-fill me-1"></i> Lihat Anggaran Desa</a>
                            </div>
                        </div>
                        <div class="col-lg-5 text-center d-none d-lg-block">
                            <i class="bi bi-pie-chart-fill" style="font-size:220px;color:rgba(255,255,255,.15);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>
</header>

<section class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color:var(--primary-800);">Layanan Utama</h2>
        <p class="text-muted">Semua kebutuhan administrasi desa, dalam satu sistem.</p>
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                <h5>Pengajuan Surat Online</h5>
                <p class="text-muted small mb-0">Ajukan 11+ jenis surat administrasi untuk diri sendiri maupun anggota keluarga, tanpa perlu datang ke kantor desa.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-whatsapp"></i></div>
                <h5>Notifikasi WhatsApp</h5>
                <p class="text-muted small mb-0">Pantau status permohonan Anda secara real-time langsung lewat WhatsApp di setiap tahapan proses.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-megaphone-fill"></i></div>
                <h5>Pengaduan Masyarakat</h5>
                <p class="text-muted small mb-0">Laporkan permasalahan di lingkungan Anda lengkap dengan foto, dan dapatkan tanggapan resmi.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <h5>Data Terenkripsi</h5>
                <p class="text-muted small mb-0">NIK & data kependudukan disimpan terenkripsi, menjaga privasi seluruh warga Desa Rawa.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-archive-fill"></i></div>
                <h5>Arsip & Laporan</h5>
                <p class="text-muted small mb-0">Rekap data bulanan siap diunduh dalam format Excel & PDF, per dusun maupun keseluruhan desa.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-signpost-split-fill"></i></div>
                <h5>4 Dusun Terintegrasi</h5>
                <p class="text-muted small mb-0">Data kependudukan dikelola rapi per dusun oleh Kepala Dusun masing-masing wilayah.</p>
            </div>
        </div>
    </div>
</section>

<section style="background:var(--primary-50);" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:var(--primary-800);">Profil Desa Rawa</h2>
            <p class="text-muted">Mengenal lebih dekat desa kami.</p>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-3"><i class="bi bi-book-fill me-2" style="color:var(--primary-600);"></i>Sejarah Singkat</h5>
                    <p class="text-muted">{{ $desa['sejarah'] }}</p>

                    <hr>

                    <h5 class="fw-bold mb-3 mt-3"><i class="bi bi-flag-fill me-2" style="color:var(--gold-500);"></i>Visi</h5>
                    <p class="text-muted">{{ $desa['visi'] }}</p>

                    <h5 class="fw-bold mb-3 mt-3"><i class="bi bi-list-check me-2" style="color:var(--ciamis-magenta-600);"></i>Misi</h5>
                    <ul class="text-muted mb-0">
                        @foreach(explode(';', $desa['misi']) as $poin)
                            @if(trim($poin) !== '')
                            <li>{{ trim($poin) }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100 text-center mb-4">
                    <img src="{{ asset($desa['foto_kepala_desa']) }}" alt="Foto Kepala Desa" class="rounded-circle mx-auto mb-3" style="width:140px;height:140px;object-fit:cover;border:4px solid var(--gold-400);">
                    <h6 class="fw-bold mb-0">{{ $desa['kepala_desa'] }}</h6>
                    <p class="text-muted small mb-0">Kepala Desa Rawa</p>
                    <p class="text-muted small mt-2 mb-0"><i class="bi bi-geo-alt-fill me-1"></i>{{ $desa['alamat_kantor'] }}</p>
                </div>

                <div class="card p-2">
                    <div class="ratio ratio-4x3">
                        <iframe
                            src="{{ $desa['maps_embed_url'] ?: 'https://www.google.com/maps?q='.urlencode($desa['alamat_kantor'].', '.$desa['kecamatan'].', '.$desa['kabupaten'].', '.$desa['provinsi']).'&output=embed' }}"
                            style="border:0; border-radius:10px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Kantor Desa Rawa"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="anggaran" class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color:var(--primary-800);">Transparansi Anggaran Desa</h2>
        <p class="text-muted">Sesuai motto keterbukaan kami, berikut anggaran desa {{ $tahunAnggaran ? "tahun {$tahunAnggaran}" : '' }} beserta pemanfaatannya.</p>
    </div>

    @if(!$tahunAnggaran)
        <div class="card p-5 text-center text-muted">
            <i class="bi bi-bar-chart fs-1 mb-2"></i>
            <p class="mb-0">Data anggaran desa untuk tahun berjalan belum tersedia. Admin dapat menambahkannya lewat menu Anggaran Desa.</p>
        </div>
    @else
        @php
            $totalPendapatan = $pendapatanAnggaran->sum('jumlah_anggaran');
            $totalBelanja = $belanjaAnggaran->sum('jumlah_anggaran');
            $totalRealisasi = $belanjaAnggaran->sum('jumlah_realisasi');
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="text-muted small mb-1">Total Pendapatan</div>
                    <div class="fw-bold fs-4" style="color:var(--primary-700);">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="text-muted small mb-1">Total Belanja (Rencana)</div>
                    <div class="fw-bold fs-4" style="color:var(--ciamis-magenta-600);">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 text-center h-100">
                    <div class="text-muted small mb-1">Realisasi Belanja</div>
                    <div class="fw-bold fs-4" style="color:var(--gold-600);">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <h6 class="fw-bold mb-3">Rincian Pemanfaatan Belanja</h6>
            @forelse($belanjaAnggaran as $item)
                @php $persen = (float) $item->jumlah_anggaran > 0 ? min(100, round(((float) $item->jumlah_realisasi / (float) $item->jumlah_anggaran) * 100, 1)) : 0; @endphp
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-semibold">{{ $item->kategori }}</span>
                        <span class="text-muted">Rp {{ number_format($item->jumlah_anggaran, 0, ',', '.') }}</span>
                    </div>
                    @if($item->keterangan)
                        <div class="small text-muted mb-1">{{ $item->keterangan }}</div>
                    @endif
                    <div class="progress" style="height:8px;">
                        <div class="progress-bar" style="width:{{ $persen }}%; background:var(--primary-600);"></div>
                    </div>
                </div>
            @empty
                <p class="text-muted small mb-0">Belum ada rincian belanja untuk tahun ini.</p>
            @endforelse
        </div>
    @endif
</section>

<footer class="py-4 text-center small text-muted border-top">
    &copy; {{ date('Y') }} Pemerintah Desa Rawa, Kecamatan Lumbung, Kabupaten Ciamis, Jawa Barat.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

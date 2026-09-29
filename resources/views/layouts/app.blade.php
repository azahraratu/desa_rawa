<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Pelayanan Desa Rawa')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon-180.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

<!-- Modal konfirmasi global (dipakai lewat atribut data-confirm pada <form>) -->
<div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="globalConfirmTitle">Konfirmasi</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="globalConfirmBody">Apakah Anda yakin?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="globalConfirmBtn">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

@auth
<div class="d-flex app-shell">
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>
    <nav class="sidebar" id="sidebar">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="sidebar-brand mb-0">
                <img src="{{ asset('img/logo-ciamis.png') }}" alt="Logo Kabupaten Ciamis" style="width:34px;height:auto;">
                <div class="brand-text link-label">
                    <strong>Desa Rawa</strong>
                    <span>Kec. Lumbung · Kab. Ciamis</span>
                </div>
            </a>
            <button type="button" class="sidebar-collapse-btn d-none d-lg-flex" id="sidebarCollapseBtn" title="Ciutkan/Perluas Menu" onclick="toggleSidebarCollapse()">
                <i class="bi bi-chevron-double-left"></i>
            </button>
        </div>

        <ul class="nav flex-column flex-grow-1">
            @php $user = auth()->user(); @endphp

            @if($user->role === 'warga')
                <div class="sidebar-section-label">Menu Warga</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('warga.dashboard') ? 'active' : '' }}" href="{{ route('warga.dashboard') }}" title="Beranda"><i class="bi bi-grid-1x2-fill"></i><span class="link-label">Beranda</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('warga.surat.*') ? 'active' : '' }}" href="{{ route('warga.surat.index') }}" title="Permohonan Surat"><i class="bi bi-file-earmark-text-fill"></i><span class="link-label">Permohonan Surat</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('warga.pengaduan.*') ? 'active' : '' }}" href="{{ route('warga.pengaduan.index') }}" title="Pengaduan"><i class="bi bi-megaphone-fill"></i><span class="link-label">Pengaduan</span></a></li>
            @elseif($user->role === 'kadus')
                <div class="sidebar-section-label">Menu Kepala Dusun</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.dashboard') ? 'active' : '' }}" href="{{ route('kadus.dashboard') }}" title="Beranda"><i class="bi bi-grid-1x2-fill"></i><span class="link-label">Beranda</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.warga.*') ? 'active' : '' }}" href="{{ route('kadus.warga.index') }}" title="Data Warga"><i class="bi bi-people-fill"></i><span class="link-label">Data Warga</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.rt.*') ? 'active' : '' }}" href="{{ route('kadus.rt.index') }}" title="Kelola RT"><i class="bi bi-signpost-2-fill"></i><span class="link-label">Kelola RT</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.surat.index') || request()->routeIs('kadus.surat.show') ? 'active' : '' }}" href="{{ route('kadus.surat.index') }}" title="Surat yang Saya Buatkan"><i class="bi bi-file-earmark-text-fill"></i><span class="link-label">Surat yang Saya Buatkan</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.surat.create') ? 'active' : '' }}" href="{{ route('kadus.surat.create') }}" title="Buatkan Surat"><i class="bi bi-file-earmark-plus-fill"></i><span class="link-label">Buatkan Surat</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.pengaduan.index') || request()->routeIs('kadus.pengaduan.show') ? 'active' : '' }}" href="{{ route('kadus.pengaduan.index') }}" title="Pengaduan Warga"><i class="bi bi-chat-square-text-fill"></i><span class="link-label">Pengaduan Warga</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('kadus.pengaduan.create') ? 'active' : '' }}" href="{{ route('kadus.pengaduan.create') }}" title="Laporkan Keluhan"><i class="bi bi-megaphone-fill"></i><span class="link-label">Laporkan Keluhan</span></a></li>
            @elseif($user->role === 'admin')
                <div class="sidebar-section-label">Ringkasan</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard"><i class="bi bi-grid-1x2-fill"></i><span class="link-label">Dashboard</span></a></li>

                <div class="sidebar-section-label">Layanan</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}" href="{{ route('admin.surat.index') }}" title="Permohonan Surat"><i class="bi bi-file-earmark-text-fill"></i><span class="link-label">Permohonan Surat</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}" href="{{ route('admin.pengaduan.index') }}" title="Pengaduan"><i class="bi bi-megaphone-fill"></i><span class="link-label">Pengaduan</span></a></li>

                <div class="sidebar-section-label">Kependudukan</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.warga.*') ? 'active' : '' }}" href="{{ route('admin.warga.index') }}" title="Data Kependudukan"><i class="bi bi-people-fill"></i><span class="link-label">Data Kependudukan</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dusun.*') ? 'active' : '' }}" href="{{ route('admin.dusun.index') }}" title="Kelola Dusun"><i class="bi bi-signpost-split-fill"></i><span class="link-label">Kelola Dusun</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.kadus.*') ? 'active' : '' }}" href="{{ route('admin.kadus.index') }}" title="Akun Kepala Dusun"><i class="bi bi-person-badge-fill"></i><span class="link-label">Akun Kepala Dusun</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.rekapitulasi.*') ? 'active' : '' }}" href="{{ route('admin.rekapitulasi.index') }}" title="Rekapitulasi"><i class="bi bi-bar-chart-fill"></i><span class="link-label">Rekapitulasi</span></a></li>

                <div class="sidebar-section-label">Administrasi</div>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.anggaran.*') ? 'active' : '' }}" href="{{ route('admin.anggaran.index') }}" title="Anggaran Desa"><i class="bi bi-cash-coin"></i><span class="link-label">Anggaran Desa</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.arsip.*') ? 'active' : '' }}" href="{{ route('admin.arsip.index') }}" title="Arsip & Laporan Bulanan"><i class="bi bi-archive-fill"></i><span class="link-label">Arsip & Laporan Bulanan</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.pengaduan.laporan.*') ? 'active' : '' }}" href="{{ route('admin.pengaduan.laporan.form') }}" title="Cetak Rekap Pengaduan"><i class="bi bi-printer-fill"></i><span class="link-label">Cetak Rekap Pengaduan</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" href="{{ route('admin.laporan.form') }}" title="Cetak Laporan Surat"><i class="bi bi-printer-fill"></i><span class="link-label">Cetak Laporan Surat</span></a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}" href="{{ route('admin.audit.index') }}" title="Audit Log"><i class="bi bi-clock-history"></i><span class="link-label">Audit Log</span></a></li>
            @endif
        </ul>

        <div class="sidebar-user-card">
            <a href="{{ route('profile.index') }}" class="d-flex align-items-center gap-2 mb-2 user-summary text-decoration-none" title="Profil Saya">
                <div class="avatar">{{ strtoupper(substr($user->name,0,1)) }}</div>
                <div class="text-truncate user-meta">
                    <div class="text-white small fw-semibold text-truncate" style="max-width:150px;">{{ $user->name }}</div>
                    <div class="text-white-50" style="font-size:.68rem;">{{ ucfirst($user->role) }}</div>
                </div>
            </a>
            <div class="d-flex gap-2 user-actions">
                <a href="{{ route('profile.index') }}" class="btn btn-outline-light btn-sm flex-fill" title="Profil Saya">
                    <i class="bi bi-person-circle"></i> <span class="link-label">Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-fill"
                      data-confirm="Anda akan keluar dari akun ini. Lanjutkan?"
                      data-confirm-title="Konfirmasi Keluar"
                      data-confirm-button="Ya, Keluar"
                      data-confirm-class="btn-danger">
                    @csrf
                    <button class="btn btn-outline-light btn-sm w-100" type="submit" title="Keluar">
                        <i class="bi bi-box-arrow-right"></i> <span class="link-label">Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="topbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-secondary sidebar-toggle-btn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
                <div>
                    <div class="page-eyebrow">Sistem Pelayanan Desa Rawa</div>
                    <div class="page-title">@yield('page-title', 'Dashboard')</div>
                </div>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i>{{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <main class="page-body">
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('status') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('kredensial_baru'))
                @php $kred = session('kredensial_baru'); @endphp
                <div class="credential-box mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-key-fill fs-5"></i>
                        <strong>Kredensial Akun Baru — Segera Sampaikan Secara Aman</strong>
                    </div>
                    <div class="cred-row"><span class="cred-label">Nama</span><span class="cred-value">{{ $kred['nama'] }}</span></div>
                    <div class="cred-row"><span class="cred-label">NIK (Login)</span><span class="cred-value">{{ $kred['nik'] }}</span></div>
                    <div class="cred-row"><span class="cred-label">Password Sementara</span><span class="cred-value">{{ $kred['password'] }}</span></div>
                    <div class="small text-white-50 mt-2"><i class="bi bi-info-circle me-1"></i>Password ini hanya ditampilkan sekali. Wajib diganti oleh pemilik akun saat login pertama.</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@else
    @yield('content')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('show');
    document.getElementById('sidebarBackdrop')?.classList.toggle('show');
}

/* --- Fungsi Collapse Sidebar (desktop): ciutkan jadi mode ikon saja, tersimpan di localStorage --- */
function toggleSidebarCollapse() {
    const sidebar = document.getElementById('sidebar');
    if (!sidebar) return;
    const collapsed = sidebar.classList.toggle('sidebar-collapsed');
    try { localStorage.setItem('desa_sidebar_collapsed', collapsed ? '1' : '0'); } catch (e) {}
}
(function () {
    try {
        if (localStorage.getItem('desa_sidebar_collapsed') === '1') {
            document.getElementById('sidebar')?.classList.add('sidebar-collapsed');
        }
    } catch (e) {}
})();

/* --- Modal konfirmasi global: form apa pun dengan atribut data-confirm akan menampilkan
       modal konfirmasi ini dulu sebelum benar-benar submit (dipakai untuk konfirmasi
       logout, proses surat, tanggapi pengaduan, dsb) --- */
(function () {
    let pendingForm = null;
    const modalEl = document.getElementById('globalConfirmModal');
    const bodyEl = document.getElementById('globalConfirmBody');
    const titleEl = document.getElementById('globalConfirmTitle');
    const btnEl = document.getElementById('globalConfirmBtn');
    let modalInstance = null;

    document.addEventListener('submit', function (e) {
        const form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === '1') return;

        e.preventDefault();
        pendingForm = form;
        bodyEl.textContent = form.dataset.confirm;
        titleEl.textContent = form.dataset.confirmTitle || 'Konfirmasi';
        btnEl.className = 'btn ' + (form.dataset.confirmClass || 'btn-success');
        btnEl.textContent = form.dataset.confirmButton || 'Ya, Lanjutkan';

        modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();
    });

    btnEl?.addEventListener('click', function () {
        if (!pendingForm) return;
        pendingForm.dataset.confirmed = '1';
        modalInstance?.hide();
        (pendingForm.requestSubmit ? pendingForm.requestSubmit() : pendingForm.submit());
        pendingForm = null;
    });
})();

/* --- Validasi ukuran lampiran di sisi client: file yang terlalu besar TIDAK dikirim
       ke server sama sekali, dicegat dulu sebelum submit --- */
function cekUkuranLampiran(form) {
    const inputs = form.querySelectorAll('input[type="file"]');
    let pesanError = '';

    inputs.forEach(function (input) {
        const maxMb = parseFloat(input.dataset.maxMb || '4');
        const maxBytes = maxMb * 1024 * 1024;
        Array.from(input.files || []).forEach(function (file) {
            if (file.size > maxBytes) {
                pesanError = 'File "' + file.name + '" berukuran ' + (file.size / 1024 / 1024).toFixed(1) +
                    ' MB, melebihi batas maksimal ' + maxMb + ' MB. Silakan pilih file lain yang lebih kecil.';
            }
        });
    });

    let box = form.querySelector('.js-lampiran-error');
    if (!box) {
        box = document.createElement('div');
        box.className = 'alert alert-danger small js-lampiran-error mt-2';
        const submitBtn = form.querySelector('button[type="submit"]');
        (submitBtn?.parentNode || form).insertBefore(box, submitBtn || null);
    }

    if (pesanError) {
        box.textContent = pesanError;
        box.classList.remove('d-none');
        return false;
    }

    box.classList.add('d-none');
    return true;
}

document.addEventListener('change', function (e) {
    if (e.target.matches('input[type="file"]')) {
        cekUkuranLampiran(e.target.closest('form'));
    }
}, true);

document.addEventListener('submit', function (e) {
    const form = e.target;
    if (form.querySelector('input[type="file"]') && !cekUkuranLampiran(form)) {
        e.preventDefault();
    }
}, true);

/* --- Lokalisasi pesan validasi bawaan browser (HTML5) ke Bahasa Indonesia.
       Sebelumnya pesan seperti "Please lengthen this text..." selalu muncul dalam
       Bahasa Inggris karena itu bawaan browser, bukan dari Laravel. --- */
(function () {
    function pesanUntuk(el) {
        if (el.dataset.invalidMsg) return el.dataset.invalidMsg;
        const v = el.validity;
        if (v.valueMissing) return 'Kolom ini wajib diisi.';
        if (v.typeMismatch) return el.type === 'email' ? 'Masukkan alamat email yang valid.' : 'Format yang Anda masukkan tidak valid.';
        if (v.tooShort) return 'Minimal ' + el.minLength + ' karakter (saat ini ' + el.value.length + ' karakter).';
        if (v.tooLong) return 'Maksimal ' + el.maxLength + ' karakter.';
        if (v.rangeUnderflow) return 'Nilai minimal adalah ' + el.min + '.';
        if (v.rangeOverflow) return 'Nilai maksimal adalah ' + el.max + '.';
        if (v.patternMismatch) return el.title || 'Format yang Anda masukkan tidak sesuai.';
        if (v.badInput) return 'Masukan tidak valid.';
        return 'Nilai pada kolom ini tidak valid.';
    }

    document.addEventListener('invalid', function (e) {
        e.target.setCustomValidity('');
        if (!e.target.validity.valid) {
            e.target.setCustomValidity(pesanUntuk(e.target));
        }
    }, true);

    ['input', 'change'].forEach(function (evt) {
        document.addEventListener(evt, function (e) {
            if (e.target.willValidate) e.target.setCustomValidity('');
        }, true);
    });
})();
</script>
@stack('scripts')
</body>
</html>

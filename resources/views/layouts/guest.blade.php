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
</head>
<body>

<!-- Modal konfirmasi global (dipakai lewat atribut data-confirm pada <form>), sama seperti di layouts.app -->
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

<div class="auth-shell">
    <div class="auth-hero">
        <div class="hero-badge"><i class="bi bi-shield-check"></i> Layanan Resmi Pemerintah Desa</div>
        <img src="{{ asset('img/logo-ciamis.png') }}" alt="Logo Kabupaten Ciamis" style="width:56px;height:auto;margin-bottom:1.25rem;">
        <h1>@yield('hero-title', 'Sistem Pelayanan Administrasi Desa Rawa')</h1>
        <p class="lead-text">@yield('hero-subtitle', 'Kecamatan Lumbung, Kabupaten Ciamis, Jawa Barat — melayani permohonan surat, pengaduan warga, dan data kependudukan secara digital, cepat, dan transparan.')</p>
        <div class="hero-stats">
            <div><div class="num">11+</div><div class="lbl">Jenis Layanan Surat</div></div>
            <div><div class="num">4</div><div class="lbl">Dusun Terlayani</div></div>
            <div><div class="num">24/7</div><div class="lbl">Akses Online</div></div>
        </div>
    </div>

    <div class="auth-form-panel">
        <div class="form-wrap">
            @if(session('status'))
                <div class="alert alert-success small">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* --- Modal konfirmasi global, sama seperti di layouts.app --- */
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

/* --- Lokalisasi pesan validasi bawaan browser (HTML5) ke Bahasa Indonesia.
       Ini yang memperbaiki popup seperti "Please lengthen this text..." pada
       form ganti password yang sebelumnya selalu berbahasa Inggris. --- */
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
</body>
</html>

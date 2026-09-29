<?php $__env->startSection('title', 'Registrasi Berhasil'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-icon-badge"><i class="bi bi-check-circle-fill"></i></div>
<h4 class="fw-bold mb-1">Registrasi Berhasil!</h4>
<p class="text-muted small mb-4">Catat atau screenshot kredensial di bawah ini sekarang. Halaman ini akan otomatis menyembunyikan password setelah waktu habis.</p>

<div class="credential-box mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-key-fill fs-5"></i>
            <strong>Kredensial Login Anda</strong>
        </div>
        <div id="timer-badge" class="badge bg-light text-dark px-3 py-2" style="font-family:'Lexend',monospace; font-size:.95rem;">05:00</div>
    </div>
    <div class="cred-row"><span class="cred-label">Nama</span><span class="cred-value"><?php echo e($kredensial['nama']); ?></span></div>
    <div class="cred-row"><span class="cred-label">NIK (untuk Login)</span><span class="cred-value"><?php echo e($kredensial['nik']); ?></span></div>
    <div class="cred-row">
        <span class="cred-label">Password Awal</span>
        <span class="cred-value" id="password-value"><?php echo e($kredensial['password']); ?></span>
    </div>
    <div class="small text-white-50 mt-2" id="hint-text">
        <i class="bi bi-info-circle me-1"></i>Password ini adalah tanggal lahir Anda (format DDMMYYYY). Wajib diganti saat login pertama.
    </div>
</div>

<div class="alert alert-warning small" id="alert-waktu">
    <i class="bi bi-stopwatch me-1"></i> Sisa waktu tampil: <strong><span id="timer-text">5:00</span></strong> menit. Setelah itu, password tidak akan ditampilkan lagi di sini — Anda tetap bisa login normal menggunakan password ini.
</div>

<form method="POST" action="<?php echo e(route('register.success.selesai')); ?>">
    <?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-success w-100 py-2">Sudah Saya Catat, Lanjut ke Login <i class="bi bi-arrow-right ms-1"></i></button>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
let sisaDetik = <?php echo e($sisaDetik); ?>;
const timerBadge = document.getElementById('timer-badge');
const timerText = document.getElementById('timer-text');
const passwordEl = document.getElementById('password-value');
const alertWaktu = document.getElementById('alert-waktu');

function formatWaktu(detik) {
    const m = Math.floor(detik / 60).toString().padStart(2, '0');
    const s = (detik % 60).toString().padStart(2, '0');
    return m + ':' + s;
}

const interval = setInterval(() => {
    sisaDetik--;
    if (sisaDetik <= 0) {
        clearInterval(interval);
        timerBadge.innerText = '00:00';
        timerText.innerText = '0:00';
        passwordEl.innerText = '•••••••••';
        passwordEl.style.userSelect = 'none';
        alertWaktu.classList.remove('alert-warning');
        alertWaktu.classList.add('alert-danger');
        alertWaktu.innerHTML = '<i class="bi bi-x-circle me-1"></i> Waktu tampil sudah habis. Password tetap sama seperti yang tadi tercatat &mdash; silakan langsung login. Jika belum sempat dicatat, hubungi Kepala Dusun/Admin untuk reset password.';
        return;
    }
    timerBadge.innerText = formatWaktu(sisaDetik);
    timerText.innerText = formatWaktu(sisaDetik);
    if (sisaDetik <= 30) {
        timerBadge.classList.add('bg-danger', 'text-white');
        timerBadge.classList.remove('bg-light', 'text-dark');
    }
}, 1000);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/auth/registrasi-sukses.blade.php ENDPATH**/ ?>
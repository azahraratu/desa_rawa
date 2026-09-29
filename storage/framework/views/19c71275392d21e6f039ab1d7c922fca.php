<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<?php if(isset($sudahLogin) && $sudahLogin): ?>
<div class="alert alert-warning small mb-4">
    <div class="d-flex align-items-start gap-2">
        <i class="bi bi-info-circle-fill mt-1"></i>
        <div class="flex-grow-1">
            <div>Anda sudah login sebagai <strong><?php echo e($sudahLogin->name); ?></strong> (<?php echo e(ucfirst($sudahLogin->role)); ?>) di browser ini.</div>
            <div class="d-flex gap-2 mt-2">
                <a href="<?php echo e(match($sudahLogin->role) { 'admin' => route('admin.dashboard'), 'kadus' => route('kadus.dashboard'), default => route('warga.dashboard') }); ?>" class="btn btn-sm btn-success">Lanjut ke Dashboard Saya</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" data-confirm="Anda akan keluar dari akun <?php echo e($sudahLogin->name); ?> saat ini. Lanjutkan?" data-confirm-title="Konfirmasi Keluar" data-confirm-button="Ya, Keluar" data-confirm-class="btn-danger">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-outline-secondary">Keluar & Login Akun Lain</button>
                </form>
            </div>
        </div>
    </div>
</div>
<p class="text-muted small mb-3">Atau, login dengan akun NIK lain di bawah ini (akan menggantikan sesi login saat ini):</p>
<?php endif; ?>

<div class="form-icon-badge"><i class="bi bi-fingerprint"></i></div>
<h4 class="fw-bold mb-1">Masuk ke Akun Anda</h4>
<p class="text-muted small mb-4">Untuk Warga & Kepala Dusun. Gunakan NIK sebagai username.</p>

<form method="POST" action="<?php echo e(route('login.submit')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
        <input type="text" name="nik" class="form-control" maxlength="16" value="<?php echo e(old('nik')); ?>" required autofocus placeholder="16 digit NIK">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Password">
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">Ingat saya di perangkat ini</label>
    </div>
    <div class="text-end mb-3">
        <a href="<?php echo e(route('password.lupa')); ?>" class="small">Lupa password?</a>
    </div>
    <button type="submit" class="btn btn-success w-100 py-2">Masuk <i class="bi bi-arrow-right ms-1"></i></button>

    <div class="text-center mt-4">
        <p class="small mb-1">Belum punya akun warga? <a href="<?php echo e(route('register')); ?>" class="fw-semibold">Daftar di sini</a></p>
        <p class="small text-muted mb-0"><a href="<?php echo e(url('/')); ?>"><i class="bi bi-arrow-left"></i> Kembali ke beranda</a></p>
    </div>

    <hr class="my-4">
    <p class="text-center small text-muted mb-0">
        Anda Admin/Perangkat Desa? <a href="<?php echo e(route('admin.login')); ?>" class="fw-semibold">Login khusus Admin <i class="bi bi-box-arrow-in-right"></i></a>
    </p>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/auth/login.blade.php ENDPATH**/ ?>
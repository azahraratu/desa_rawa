<?php $__env->startSection('title', 'Login Admin'); ?>
<?php $__env->startSection('hero-title', 'Panel Administrasi Desa Rawa'); ?>
<?php $__env->startSection('hero-subtitle', 'Khusus Admin & Perangkat Desa. Akses penuh untuk mengelola permohonan surat, pengaduan, data kependudukan, dan arsip laporan resmi.'); ?>

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
                    <button class="btn btn-sm btn-outline-secondary">Keluar & Login Admin Lain</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="form-icon-badge" style="background:var(--gold-100); color:var(--gold-500);"><i class="bi bi-shield-lock-fill"></i></div>
<h4 class="fw-bold mb-1">Login Administrator</h4>
<p class="text-muted small mb-4">Akses khusus perangkat desa. Bukan untuk warga/kepala dusun.</p>

<form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">Alamat Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus placeholder="admin@desarawa.go.id">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required placeholder="Password">
    </div>
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label small" for="remember">Ingat saya di perangkat ini</label>
    </div>
    <button type="submit" class="btn btn-gold w-100 py-2 fw-bold">Masuk sebagai Admin <i class="bi bi-arrow-right ms-1"></i></button>

    <p class="text-center small text-muted mt-4 mb-0">
        <a href="<?php echo e(route('login')); ?>"><i class="bi bi-arrow-left"></i> Login Warga / Kepala Dusun</a>
    </p>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/auth/admin-login.blade.php ENDPATH**/ ?>
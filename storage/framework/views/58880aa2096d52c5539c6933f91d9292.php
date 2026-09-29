<?php $__env->startSection('title', 'Ganti Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-icon-badge"><i class="bi bi-shield-lock"></i></div>
<h4 class="fw-bold mb-1">Amankan Akun Anda</h4>
<p class="text-muted small mb-4">Demi keamanan, Anda wajib mengganti password awal sebelum melanjutkan.</p>

<form method="POST" action="<?php echo e(route('password.force.submit')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control" required minlength="8" placeholder="Minimal 8 karakter, huruf besar/kecil & angka">
    </div>
    <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
    </div>
    <button type="submit" class="btn btn-success w-100 py-2">Simpan Password Baru</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/auth/force-change-password.blade.php ENDPATH**/ ?>
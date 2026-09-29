<?php $__env->startSection('title', 'Registrasi Akun Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="form-icon-badge"><i class="bi bi-person-plus-fill"></i></div>
<h4 class="fw-bold mb-1">Daftar Akun Warga</h4>
<p class="text-muted small mb-3">Registrasi hanya berhasil jika data Anda sudah diunggah oleh Kepala Dusun.</p>

<div class="alert alert-info small d-flex gap-2">
    <i class="bi bi-info-circle-fill mt-1"></i>
    <div>Belum terdaftar di data desa? Hubungi <strong>Kepala Dusun</strong> Anda terlebih dahulu untuk didata.</div>
</div>

<form method="POST" action="<?php echo e(route('register.submit')); ?>">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
        <label class="form-label">NIK (sesuai KTP)</label>
        <input type="text" name="nik" class="form-control" maxlength="16" value="<?php echo e(old('nik')); ?>" required placeholder="16 digit NIK">
    </div>
    <div class="mb-3">
        <label class="form-label">Nomor Kartu Keluarga (KK)</label>
        <input type="text" name="no_kk" class="form-control" maxlength="16" value="<?php echo e(old('no_kk')); ?>" required placeholder="16 digit No. KK">
    </div>
    <div class="mb-3">
        <label class="form-label">Nomor WhatsApp Aktif</label>
        <input type="text" name="no_hp" class="form-control" value="<?php echo e(old('no_hp')); ?>" required placeholder="08xxxxxxxxxx">
        <div class="form-text">Dipakai untuk menerima notifikasi status permohonan Anda.</div>
    </div>
    <button type="submit" class="btn btn-success w-100 py-2">Daftar Sekarang</button>

    <p class="text-center small mt-4 mb-0">Sudah punya akun? <a href="<?php echo e(route('login')); ?>" class="fw-semibold">Masuk di sini</a></p>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/auth/register.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Laporkan Keluhan Warga'); ?>
<?php $__env->startSection('page-title', 'Laporkan Keluhan Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:700px;">
    <div class="alert alert-info small">Sampaikan keluhan/laporan atas nama warga yang kesulitan menggunakan aplikasi secara langsung.</div>
    <form method="POST" action="<?php echo e(route('kadus.pengaduan.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
                <option value="infrastruktur">Infrastruktur</option>
                <option value="sosial">Sosial Kemasyarakatan</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Lampiran Foto (opsional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*" data-max-mb="4">
        </div>
        <button type="submit" class="btn btn-success w-100">Kirim Laporan</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/pengaduan/create.blade.php ENDPATH**/ ?>
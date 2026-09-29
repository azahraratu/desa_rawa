<?php $__env->startSection('title', 'Tambah Data Warga'); ?>
<?php $__env->startSection('page-title', 'Tambah Data Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4">
    <form method="POST" action="<?php echo e(route('kadus.warga.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('kadus.warga._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Data Warga</button>
            <a href="<?php echo e(route('kadus.warga.index')); ?>" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/warga/create.blade.php ENDPATH**/ ?>
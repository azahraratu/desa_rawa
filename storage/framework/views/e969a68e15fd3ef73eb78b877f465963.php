<?php $__env->startSection('title', 'Tambah Data Anggaran'); ?>
<?php $__env->startSection('page-title', 'Tambah Data Anggaran Desa'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="<?php echo e(route('admin.anggaran.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('admin.anggaran._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Data Anggaran</button>
            <a href="<?php echo e(route('admin.anggaran.index')); ?>" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/anggaran/create.blade.php ENDPATH**/ ?>
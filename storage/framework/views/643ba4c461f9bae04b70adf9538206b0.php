<?php $__env->startSection('title', 'Tambah Dusun'); ?>
<?php $__env->startSection('page-title', 'Tambah Dusun Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="<?php echo e(route('admin.dusun.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Nama Dusun</label>
            <input type="text" name="nama_dusun" class="form-control" required value="<?php echo e(old('nama_dusun')); ?>" placeholder="Contoh: Dusun Bojong">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan Dusun</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/dusun/create.blade.php ENDPATH**/ ?>
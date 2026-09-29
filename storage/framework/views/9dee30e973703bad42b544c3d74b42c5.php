<?php $__env->startSection('title', 'Tambah RT'); ?>
<?php $__env->startSection('page-title', 'Tambah RT Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="<?php echo e(route('kadus.rt.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Nama/Nomor RT</label>
            <input type="text" name="nama_rt" class="form-control" required value="<?php echo e(old('nama_rt')); ?>" placeholder="Contoh: 01 atau 20">
        </div>
        <div class="mb-3">
            <label class="form-label">Ketua RT (opsional)</label>
            <input type="text" name="ketua_rt" class="form-control" value="<?php echo e(old('ketua_rt')); ?>">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan RT</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/rt/create.blade.php ENDPATH**/ ?>
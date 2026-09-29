<?php $__env->startSection('title', 'Kelola RT'); ?>
<?php $__env->startSection('page-title', 'Kelola RT (Rukun Tetangga)'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('error')): ?>
<div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Kelola daftar RT di dusun Anda. Warga dapat dikelompokkan per RT untuk memudahkan pendataan.</p>
    <a href="<?php echo e(route('kadus.rt.create')); ?>" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah RT</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>RT</th><th>Ketua RT</th><th>Jumlah Warga Aktif</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold">RT <?php echo e($rt->nama_rt); ?></td>
                    <td><?php echo e($rt->ketua_rt ?? '-'); ?></td>
                    <td><?php echo e($rt->warga_count); ?></td>
                    <td class="d-flex gap-1">
                        <a href="<?php echo e(route('kadus.rt.edit', $rt)); ?>" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="<?php echo e(route('kadus.rt.destroy', $rt)); ?>" onsubmit="return confirm('Hapus RT <?php echo e($rt->nama_rt); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada RT terdaftar. Tambahkan RT dulu sebelum mengelompokkan data warga.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/rt/index.blade.php ENDPATH**/ ?>
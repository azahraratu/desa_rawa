<?php $__env->startSection('title', 'Kelola Dusun'); ?>
<?php $__env->startSection('page-title', 'Kelola Dusun & Akun Kepala Dusun'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('error')): ?>
<div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Tambah/ubah/hapus dusun, dan kelola akun Kepala Dusun-nya langsung dari sini.</p>
    <a href="<?php echo e(route('admin.dusun.create')); ?>" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah Dusun</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Nama Dusun</th><th>Warga Aktif</th><th>Kepala Dusun</th><th>Aksi Dusun</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $dusuns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($d->nama_dusun); ?></td>
                    <td><?php echo e($d->warga_count); ?></td>
                    <td>
                        <?php if($d->kepalaDusun): ?>
                            <?php echo e($d->kepalaDusun->name); ?>

                            <?php if($d->kepalaDusun->is_active): ?>
                                <span class="badge bg-success-subtle text-success-emphasis ms-1">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-1">Nonaktif</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Belum ada akun</span>
                        <?php endif; ?>
                    </td>
                    <td class="d-flex gap-1">
                        <a href="<?php echo e(route('admin.dusun.edit', $d)); ?>" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="<?php echo e(route('admin.dusun.destroy', $d)); ?>" onsubmit="return confirm('Hapus dusun <?php echo e($d->nama_dusun); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                    <td class="d-flex gap-1">
                        <?php if($d->kepalaDusun): ?>
                            <form method="POST" action="<?php echo e(route('admin.kadus.reset-password', $d->kepalaDusun)); ?>" onsubmit="return confirm('Reset password akun <?php echo e($d->kepalaDusun->name); ?>?')">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-outline-secondary" title="Reset Password"><i class="bi bi-key-fill"></i></button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.kadus.toggle', $d->kepalaDusun)); ?>" onsubmit="return confirm('Ubah status aktif akun <?php echo e($d->kepalaDusun->name); ?>?')">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm <?php echo e($d->kepalaDusun->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>" title="<?php echo e($d->kepalaDusun->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                    <i class="bi <?php echo e($d->kepalaDusun->is_active ? 'bi-slash-circle' : 'bi-check-circle'); ?>"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('admin.kadus.create', ['dusun_id' => $d->id, 'balik_ke' => 'dusun'])); ?>" class="btn btn-sm btn-gold">
                                <i class="bi bi-person-plus-fill me-1"></i>Buat Akun
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data dusun.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/dusun/index.blade.php ENDPATH**/ ?>
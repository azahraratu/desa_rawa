<?php $__env->startSection('title', 'Akun Kepala Dusun'); ?>
<?php $__env->startSection('page-title', 'Kelola Akun Kepala Dusun'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <p class="text-muted mb-0">
        Hanya Admin yang dapat membuat akun Kepala Dusun. Role sudah terkunci sejak akun dibuat.
        Bisa juga dikelola langsung dari halaman <a href="<?php echo e(route('admin.dusun.index')); ?>">Kelola Dusun</a>.
    </p>
    <a href="<?php echo e(route('admin.kadus.create')); ?>" class="btn btn-success"><i class="bi bi-person-plus-fill me-1"></i>Buat Akun Kepala Dusun</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Nama</th><th>NIK</th><th>Dusun</th><th>No HP</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $kadusList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($k->name); ?></td>
                    <td><?php echo e($k->nik); ?></td>
                    <td><?php echo e($k->dusunDipimpin->nama_dusun ?? '-'); ?></td>
                    <td><?php echo e($k->no_hp); ?></td>
                    <td>
                        <?php if($k->is_active): ?>
                            <span class="badge bg-success-subtle text-success-emphasis">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="d-flex gap-1">
                        <form method="POST" action="<?php echo e(route('admin.kadus.reset-password', $k)); ?>" onsubmit="return confirm('Reset password akun <?php echo e($k->name); ?>?')">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-outline-secondary" title="Reset Password"><i class="bi bi-key-fill"></i></button>
                        </form>
                        <form method="POST" action="<?php echo e(route('admin.kadus.toggle', $k)); ?>" onsubmit="return confirm('Ubah status aktif akun <?php echo e($k->name); ?>?')">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm <?php echo e($k->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?>" title="<?php echo e($k->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                <i class="bi <?php echo e($k->is_active ? 'bi-slash-circle' : 'bi-check-circle'); ?>"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada akun Kepala Dusun.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/kadus/index.blade.php ENDPATH**/ ?>
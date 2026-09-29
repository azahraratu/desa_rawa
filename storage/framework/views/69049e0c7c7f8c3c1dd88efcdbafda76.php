<?php $__env->startSection('title', 'Audit Log'); ?>
<?php $__env->startSection('page-title', 'Jejak Audit Sistem'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <div class="btn-group flex-wrap">
        <a href="<?php echo e(route('admin.audit.index')); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(!request('aksi') ? 'active' : ''); ?>">Semua</a>
        <?php $__currentLoopData = $daftarAksi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.audit.index', ['aksi' => $aksi])); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(request('aksi') === $aksi ? 'active' : ''); ?>"><?php echo e(ucfirst(str_replace('_',' ',$aksi))); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Waktu</th><th>Aksi</th><th>Pelaku</th><th>Objek</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="small text-muted text-nowrap"><?php echo e($log->created_at->format('d M Y, H:i:s')); ?></td>
                    <td><span class="badge bg-light text-dark border"><?php echo e(str_replace('_',' ',$log->aksi)); ?></span></td>
                    <td><?php echo e($log->causer_nama ?? 'Sistem'); ?> <span class="text-muted small">(<?php echo e($log->causer_role ?? '-'); ?>)</span></td>
                    <td class="small"><?php echo e(class_basename($log->subjek_type ?? '')); ?> #<?php echo e($log->subjek_id); ?></td>
                    <td class="small"><?php echo e($log->keterangan); ?></td>
                    <td class="small text-muted"><?php echo e($log->ip_address); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada catatan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
    <?php echo e($logs->links('pagination::bootstrap-5')); ?>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/audit/index.blade.php ENDPATH**/ ?>
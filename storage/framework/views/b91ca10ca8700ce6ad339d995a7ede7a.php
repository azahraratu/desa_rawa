<?php $__env->startSection('title', 'Permohonan Surat'); ?>
<?php $__env->startSection('page-title', 'Kelola Permohonan Surat'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-3">
    <div class="btn-group">
        <a href="<?php echo e(route('admin.surat.index')); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(!request('status') ? 'active' : ''); ?>">Semua</a>
        <?php $__currentLoopData = \App\Models\SuratRequest::labelStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.surat.index', ['status' => $key])); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(request('status') === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Pemohon</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $suratRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($s->kode_permohonan); ?></td>
                    <td><?php echo e($s->suratType->nama); ?></td>
                    <td><?php echo e($s->warga->nama); ?></td>
                    <td><?php echo e($s->pemohon->name); ?> <?php echo e($s->dibuat_oleh_kadus ? '(via Kadus)' : ''); ?></td>
                    <td><?php echo e($s->created_at->translatedFormat('d M Y')); ?></td>
                    <td><span class="badge <?php echo e($s->badgeClass()); ?>"><?php echo e($s->labelStatusSaatIni()); ?></span></td>
                    <td><a href="<?php echo e(route('admin.surat.show', $s)); ?>" class="btn btn-sm btn-outline-secondary">Kelola</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada permohonan surat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($suratRequests->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/surat/index.blade.php ENDPATH**/ ?>
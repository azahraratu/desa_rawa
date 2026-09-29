<?php $__env->startSection('title', 'Surat yang Saya Buatkan'); ?>
<?php $__env->startSection('page-title', 'Riwayat Surat yang Saya Buatkan untuk Warga'); ?>

<?php $__env->startSection('content'); ?>
<div class="alert alert-info small">
    <i class="bi bi-info-circle me-1"></i> Halaman ini hanya menampilkan surat yang <strong>Anda buatkan sendiri</strong> untuk warga.
    Permohonan yang diajukan warga secara mandiri lewat akunnya bersifat pribadi dan tidak ditampilkan/diberitahukan ke Kepala Dusun.
</div>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="btn-group flex-wrap">
        <a href="<?php echo e(route('kadus.surat.index')); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(!request('status') ? 'active' : ''); ?>">Semua</a>
        <?php $__currentLoopData = \App\Models\SuratRequest::labelStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('kadus.surat.index', ['status' => $key])); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(request('status') === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <a href="<?php echo e(route('kadus.surat.create')); ?>" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Buatkan Surat</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $suratRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($s->kode_permohonan); ?></td>
                    <td><?php echo e($s->suratType->nama); ?></td>
                    <td><?php echo e($s->warga->nama); ?></td>
                    <td><?php echo e($s->created_at->translatedFormat('d M Y')); ?></td>
                    <td><span class="badge <?php echo e($s->badgeClass()); ?>"><?php echo e($s->labelStatusSaatIni()); ?></span></td>
                    <td><a href="<?php echo e(route('kadus.surat.show', $s)); ?>" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Anda belum pernah membuatkan surat untuk warga.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($suratRequests->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/surat/index.blade.php ENDPATH**/ ?>
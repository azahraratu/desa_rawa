<?php $__env->startSection('title', 'Pengaduan Warga'); ?>
<?php $__env->startSection('page-title', 'Pengaduan Warga di Dusun Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="btn-group">
        <a href="<?php echo e(route('kadus.pengaduan.index')); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(!request('status') ? 'active' : ''); ?>">Semua</a>
        <?php $__currentLoopData = \App\Models\Pengaduan::labelStatus(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('kadus.pengaduan.index', ['status' => $key])); ?>" class="btn btn-sm btn-outline-secondary <?php echo e(request('status') === $key ? 'active' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <a href="<?php echo e(route('kadus.pengaduan.create')); ?>" class="btn btn-sm btn-success"><i class="bi bi-megaphone-fill me-1"></i>Laporkan Keluhan Warga</a>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold"><?php echo e($p->judul); ?></h6>
                <span class="badge <?php echo e($p->badgeClass()); ?>"><?php echo e(\App\Models\Pengaduan::labelStatus()[$p->status]); ?></span>
            </div>
            <p class="small text-muted mb-1"><?php echo e($p->pelapor->name); ?> <?php echo e($p->dilaporkan_oleh_kadus ? '(dilaporkan oleh Anda)' : '(dilaporkan langsung oleh warga)'); ?> &middot; <?php echo e($p->created_at->translatedFormat('d M Y')); ?></p>
            <p class="small mb-2"><?php echo e(Str::limit($p->deskripsi, 100)); ?></p>
            <a href="<?php echo e(route('kadus.pengaduan.show', $p)); ?>" class="btn btn-sm btn-success mt-auto">Lihat &amp; Tanggapi</a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12"><div class="card p-4 text-center text-muted">Belum ada pengaduan dari warga di dusun Anda.</div></div>
    <?php endif; ?>
</div>
<div class="mt-3"><?php echo e($pengaduans->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/pengaduan/index.blade.php ENDPATH**/ ?>
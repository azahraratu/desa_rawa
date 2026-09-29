<?php $__env->startSection('title', 'Pengaduan'); ?>
<?php $__env->startSection('page-title', 'Pengaduan Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Riwayat Pengaduan</h5>
    <a href="<?php echo e(route('warga.pengaduan.create')); ?>" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Buat Pengaduan</a>
</div>

<div class="row g-3">
    <?php $__empty_1 = true; $__currentLoopData = $pengaduans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold"><?php echo e($p->judul); ?></h6>
                <span class="badge <?php echo e($p->badgeClass()); ?>"><?php echo e(\App\Models\Pengaduan::labelStatus()[$p->status]); ?></span>
            </div>
            <p class="small text-muted mb-1"><?php echo e(ucfirst($p->kategori)); ?> &middot; <?php echo e($p->created_at->translatedFormat('d M Y')); ?></p>
            <p class="small mb-2"><?php echo e(Str::limit($p->deskripsi, 100)); ?></p>
            <a href="<?php echo e(route('warga.pengaduan.show', $p)); ?>" class="btn btn-sm btn-outline-secondary mt-auto">Lihat Detail & Tanggapan</a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12"><div class="card p-4 text-center text-muted">Belum ada pengaduan.</div></div>
    <?php endif; ?>
</div>
<div class="mt-3"><?php echo e($pengaduans->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/warga/pengaduan/index.blade.php ENDPATH**/ ?>
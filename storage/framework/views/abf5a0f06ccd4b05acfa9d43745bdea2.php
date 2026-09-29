<?php $__env->startSection('title', 'Beranda Kepala Dusun'); ?>
<?php $__env->startSection('page-title', 'Beranda - '.$dusun->nama_dusun); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">Total Warga Aktif</div><div class="stat-value"><?php echo e($ringkasan['total_warga']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
            <div><div class="stat-label">Sudah Punya Akun</div><div class="stat-value"><?php echo e($ringkasan['warga_terdaftar_akun']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-danger">
            <div class="stat-icon"><i class="bi bi-emoji-frown-fill"></i></div>
            <div><div class="stat-label">Warga Meninggal</div><div class="stat-value"><?php echo e($ringkasan['warga_meninggal']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('kadus.pengaduan.index')); ?>" class="text-decoration-none">
            <div class="stat-card tone-magenta">
                <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
                <div>
                    <div class="stat-label">Pengaduan dari Warga</div>
                    <div class="stat-value"><?php echo e($ringkasan['pengaduan_dilaporkan']); ?></div>
                    <?php if($ringkasan['pengaduan_belum_ditanggapi'] > 0): ?>
                        <span class="badge bg-danger mt-1"><?php echo e($ringkasan['pengaduan_belum_ditanggapi']); ?> belum ditanggapi</span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="<?php echo e(route('kadus.warga.create')); ?>" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-person-plus fs-1 text-success"></i>
                <h6 class="mt-2 mb-0">Tambah Data Warga</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('kadus.surat.create')); ?>" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-file-earmark-plus fs-1 text-primary"></i>
                <h6 class="mt-2 mb-0">Buatkan Surat untuk Warga</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('kadus.pengaduan.index')); ?>" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-chat-square-text fs-1 text-warning"></i>
                <h6 class="mt-2 mb-0">Lihat Pengaduan Warga</h6>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?php echo e(route('kadus.pengaduan.create')); ?>" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-megaphone fs-1 text-danger"></i>
                <h6 class="mt-2 mb-0">Laporkan Keluhan Warga</h6>
            </div>
        </a>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3">Data Warga Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>NIK</th><th>Nama</th><th>No KK</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $wargaTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($w->nik); ?></td>
                    <td><?php echo e($w->nama); ?></td>
                    <td><?php echo e($w->no_kk); ?></td>
                    <td><?php echo $w->sudahRegistrasi() ? '<span class="badge bg-success">Sudah Daftar Akun</span>' : '<span class="badge bg-secondary">Belum Daftar</span>'; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data warga.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <a href="<?php echo e(route('kadus.warga.index')); ?>" class="btn btn-sm btn-outline-secondary mt-3" style="width:fit-content;">Lihat Semua Data Warga</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/dashboard.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Anggaran Desa'); ?>
<?php $__env->startSection('page-title', 'Transparansi Anggaran Desa'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 small text-muted">Tahun Anggaran</label>
        <select name="tahun" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
            <?php if($tahunTersedia->isEmpty()): ?>
                <option value="<?php echo e($tahun); ?>"><?php echo e($tahun); ?></option>
            <?php endif; ?>
            <?php $__currentLoopData = $tahunTersedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($t); ?>" <?php echo e((int) $t === (int) $tahun ? 'selected' : ''); ?>><?php echo e($t); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <a href="<?php echo e(route('admin.anggaran.create')); ?>" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah Data Anggaran</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div><div class="stat-label">Total Pendapatan <?php echo e($tahun); ?></div><div class="stat-value" style="font-size:1.15rem;">Rp <?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div><div class="stat-label">Total Belanja (Rencana) <?php echo e($tahun); ?></div><div class="stat-value" style="font-size:1.15rem;">Rp <?php echo e(number_format($totalBelanja, 0, ',', '.')); ?></div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card tone-magenta">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div><div class="stat-label">Realisasi Belanja <?php echo e($tahun); ?></div><div class="stat-value" style="font-size:1.15rem;">Rp <?php echo e(number_format($totalRealisasiBelanja, 0, ',', '.')); ?></div></div>
        </div>
    </div>
</div>

<div class="card p-3 mb-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-arrow-down-circle text-success me-1"></i>Pendapatan</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pendapatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($a->kategori); ?></td>
                    <td class="small text-muted"><?php echo e($a->keterangan ?: '-'); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($a->jumlah_anggaran, 0, ',', '.')); ?></td>
                    <td class="d-flex gap-1">
                        <a href="<?php echo e(route('admin.anggaran.edit', $a)); ?>" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="<?php echo e(route('admin.anggaran.destroy', $a)); ?>" onsubmit="return confirm('Hapus data anggaran <?php echo e($a->kategori); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data pendapatan untuk tahun ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-arrow-up-circle text-danger me-1"></i>Belanja & Pemanfaatan</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Bidang / Kategori</th><th>Pemanfaatan</th><th class="text-end">Anggaran</th><th class="text-end">Realisasi</th><th style="width:140px;">Progres</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $belanja; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="fw-semibold"><?php echo e($a->kategori); ?></td>
                    <td class="small text-muted"><?php echo e($a->keterangan ?: '-'); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($a->jumlah_anggaran, 0, ',', '.')); ?></td>
                    <td class="text-end">Rp <?php echo e(number_format($a->jumlah_realisasi, 0, ',', '.')); ?></td>
                    <td>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-success" style="width:<?php echo e($a->persentaseRealisasi()); ?>%"></div>
                        </div>
                        <div class="small text-muted mt-1"><?php echo e($a->persentaseRealisasi()); ?>%</div>
                    </td>
                    <td class="d-flex gap-1">
                        <a href="<?php echo e(route('admin.anggaran.edit', $a)); ?>" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="<?php echo e(route('admin.anggaran.destroy', $a)); ?>" onsubmit="return confirm('Hapus data anggaran <?php echo e($a->kategori); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data belanja untuk tahun ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<p class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i>Data pada halaman ini juga ditampilkan secara terbuka kepada warga di halaman beranda publik, sebagai bentuk transparansi anggaran desa.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/anggaran/index.blade.php ENDPATH**/ ?>
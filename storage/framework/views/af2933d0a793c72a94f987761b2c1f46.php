<?php $__env->startSection('title', 'Arsip & Laporan Bulanan'); ?>
<?php $__env->startSection('page-title', 'Arsip & Laporan Bulanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-calendar3 me-1"></i> Pilih Periode (seperti mutasi rekening)</h6>
    <form method="GET" action="<?php echo e(route('admin.arsip.show')); ?>" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-select" required>
                <?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $namaBulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($i + 1); ?>" <?php echo e(now()->month == $i + 1 ? 'selected' : ''); ?>><?php echo e($namaBulan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-select" required>
                <?php for($t = now()->year; $t >= now()->year - 3; $t--): ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(now()->year == $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Dusun (opsional)</label>
            <select name="dusun_id" class="form-select">
                <option value="">Semua Dusun</option>
                <?php $__currentLoopData = $dusuns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>"><?php echo e($d->nama_dusun); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100"><i class="bi bi-search me-1"></i>Lihat</button>
        </div>
    </form>
</div>

<h6 class="fw-bold mb-3">Arsip 12 Bulan Terakhir</h6>
<div class="row g-3">
    <?php $__currentLoopData = $periodeCepat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-4 col-lg-3">
        <a href="<?php echo e(route('admin.arsip.show', ['bulan' => $p['bulan'], 'tahun' => $p['tahun']])); ?>" class="text-decoration-none">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-folder2-open"></i></div>
                <h6 class="mb-2"><?php echo e($p['label']); ?></h6>
                <div class="d-flex justify-content-between small text-muted">
                    <span><i class="bi bi-file-earmark-text"></i> <?php echo e($p['total_surat']); ?> surat</span>
                    <span><i class="bi bi-person-plus"></i> <?php echo e($p['total_warga_baru']); ?> warga</span>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/arsip/index.blade.php ENDPATH**/ ?>
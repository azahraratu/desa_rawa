<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div><div class="stat-label">Total Permohonan Surat</div><div class="stat-value"><?php echo e($ringkasan['total_surat']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-gold">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-label">Menunggu Diproses</div><div class="stat-value"><?php echo e($ringkasan['surat_menunggu']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-magenta">
            <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
            <div><div class="stat-label">Pengaduan Baru</div><div class="stat-value"><?php echo e($ringkasan['total_pengaduan_baru']); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">Total Warga Aktif</div><div class="stat-value"><?php echo e($ringkasan['total_warga']); ?></div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-emoji-smile-fill"></i></div>
            <div><div class="stat-label">Kelahiran Tahun Ini</div><div class="stat-value"><?php echo e($kelahiranTahunIni); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-danger">
            <div class="stat-icon"><i class="bi bi-emoji-frown-fill"></i></div>
            <div><div class="stat-label">Kematian Tahun Ini</div><div class="stat-value"><?php echo e($kematianTahunIni); ?></div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold">Jumlah & Jenis Surat Diterbitkan</h6>
            <canvas id="chartSurat" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="fw-bold">Warga per Dusun</h6>
            <canvas id="chartDusun" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="fw-bold">Tingkat Pendidikan</h6>
            <canvas id="chartPendidikan" height="220"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Permohonan Surat Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Kode</th><th>Jenis</th><th>Atas Nama</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $permohonanTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="<?php echo e(route('admin.surat.show', $s)); ?>"><?php echo e($s->kode_permohonan); ?></a></td>
                            <td><?php echo e($s->suratType->nama); ?></td>
                            <td><?php echo e($s->warga->nama); ?></td>
                            <td><span class="badge <?php echo e($s->badgeClass()); ?>"><?php echo e($s->labelStatusSaatIni()); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Pengaduan Terbaru</h6>
            <?php $__empty_1 = true; $__currentLoopData = $pengaduanTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                <div>
                    <div class="small fw-semibold"><?php echo e($p->judul); ?></div>
                    <div class="small text-muted"><?php echo e($p->pelapor->name); ?></div>
                </div>
                <span class="badge <?php echo e($p->badgeClass()); ?> align-self-start"><?php echo e(ucfirst($p->status)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted small mb-0">Belum ada pengaduan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const palet = ['#147544', '#d6a419', '#9e0b84', '#2f7fb5', '#d64545', '#58d68d', '#8e44ad', '#e67e22', '#16a085', '#c0392b'];

new Chart(document.getElementById('chartSurat'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($suratPerJenis->pluck('label')); ?>,
        datasets: [{ label: 'Jumlah', data: <?php echo json_encode($suratPerJenis->pluck('total')); ?>, backgroundColor: palet }]
    },
    options: { plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('chartDusun'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($wargaPerDusun->pluck('label')); ?>,
        datasets: [{ data: <?php echo json_encode($wargaPerDusun->pluck('total')); ?>, backgroundColor: palet }]
    }
});

new Chart(document.getElementById('chartPendidikan'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($pendidikan->pluck('label')); ?>,
        datasets: [{ data: <?php echo json_encode($pendidikan->pluck('total')); ?>, backgroundColor: palet }]
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>
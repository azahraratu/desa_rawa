<?php $__env->startSection('title', 'Rekapitulasi'); ?>
<?php $__env->startSection('page-title', 'Rekapitulasi Data untuk Rapat/Musyawarah'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-end mb-3">
    <a href="<?php echo e(route('admin.rekapitulasi.cetak')); ?>" target="_blank" class="btn btn-gold"><i class="bi bi-printer-fill me-1"></i>Cetak Rekapitulasi</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">Total Warga Aktif</div><div class="stat-value"><?php echo e($totalWarga); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div><div class="stat-label">Surat Tahun Ini</div><div class="stat-value"><?php echo e($totalSuratTahunIni); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-gold">
            <div class="stat-icon"><i class="bi bi-signpost-split-fill"></i></div>
            <div><div class="stat-label">Pindah Domisili</div><div class="stat-value"><?php echo e($totalPindah); ?></div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-danger">
            <div class="stat-icon"><i class="bi bi-emoji-frown-fill"></i></div>
            <div><div class="stat-label">Meninggal Tercatat</div><div class="stat-value"><?php echo e($totalMeninggal); ?></div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="fw-bold">Tingkat Pendidikan Terakhir</h6>
            <canvas id="chartPendidikan" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="fw-bold">Status Ekonomi</h6>
            <canvas id="chartEkonomi" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6 class="fw-bold">Status Pernikahan</h6>
            <canvas id="chartPernikahan" height="220"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Jumlah Warga per Dusun</h6>
            <table class="table table-sm mb-0">
                <thead><tr><th>Dusun</th><th>Jumlah</th></tr></thead>
                <tbody>
                    <?php $__currentLoopData = $perDusun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><td><?php echo e($d->nama_dusun); ?></td><td><?php echo e($d->warga_count); ?></td></tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Jenis Kelamin</h6>
            <table class="table table-sm mb-0">
                <tr><td>Laki-laki</td><td><?php echo e($jenisKelamin['L'] ?? 0); ?></td></tr>
                <tr><td>Perempuan</td><td><?php echo e($jenisKelamin['P'] ?? 0); ?></td></tr>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const palette = ['#147544', '#d6a419', '#9e0b84', '#2f7fb5', '#d64545', '#58d68d', '#8e44ad', '#e67e22'];

new Chart(document.getElementById('chartPendidikan'), {
    type: 'pie',
    data: { labels: <?php echo json_encode($pendidikan->keys()); ?>, datasets: [{ data: <?php echo json_encode($pendidikan->values()); ?>, backgroundColor: palette }] }
});
new Chart(document.getElementById('chartEkonomi'), {
    type: 'doughnut',
    data: { labels: <?php echo json_encode($ekonomi->keys()); ?>, datasets: [{ data: <?php echo json_encode($ekonomi->values()); ?>, backgroundColor: ['#147544','#d6a419','#d64545'] }] }
});
new Chart(document.getElementById('chartPernikahan'), {
    type: 'bar',
    data: { labels: <?php echo json_encode($pernikahan->keys()); ?>, datasets: [{ data: <?php echo json_encode($pernikahan->values()); ?>, backgroundColor: ['#147544','#2f7fb5','#d6a419','#9e0b84'] }] },
    options: { plugins: { legend: { display: false } } }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/rekapitulasi/index.blade.php ENDPATH**/ ?>
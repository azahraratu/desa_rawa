<?php $__env->startSection('title', 'Detail Permohonan Surat'); ?>
<?php $__env->startSection('page-title', 'Detail Permohonan Surat'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="mb-3"><?php echo e($surat->suratType->nama); ?></h5>
            <table class="table table-borderless table-sm mb-0">
                <tr><td class="text-muted" style="width:160px;">Kode Permohonan</td><td>: <?php echo e($surat->kode_permohonan); ?></td></tr>
                <tr><td class="text-muted">Atas Nama</td><td>: <?php echo e($surat->warga->nama); ?> (<?php echo e($surat->warga->nik); ?>)</td></tr>
                <tr><td class="text-muted">Dibuatkan Oleh</td><td>: Anda (Kepala Dusun), atas nama warga</td></tr>
                <tr><td class="text-muted">Alasan</td><td>: <?php echo e($surat->alasan); ?></td></tr>
                <tr><td class="text-muted">Tanggal Ajukan</td><td>: <?php echo e($surat->created_at->translatedFormat('d F Y, H:i')); ?></td></tr>
                <tr><td class="text-muted">Status</td><td>: <span class="badge <?php echo e($surat->badgeClass()); ?>"><?php echo e($surat->labelStatusSaatIni()); ?></span></td></tr>
                <?php if($surat->status === 'ditolak'): ?>
                <tr><td class="text-muted">Alasan Penolakan</td><td class="text-danger">: <?php echo e($surat->catatan_admin); ?></td></tr>
                <?php endif; ?>
                <?php if($surat->lampirans->count() > 0): ?>
                <tr><td class="text-muted align-top">Lampiran</td><td>:
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $surat->lampirans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lampiran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($lampiran->nama_asli ?? 'Lampiran '.$loop->iteration); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </td></tr>
                <?php endif; ?>
            </table>

            <?php if(in_array($surat->status, ['ditandatangani','selesai']) && $surat->file_path): ?>
            <a href="<?php echo e(route('kadus.surat.download', $surat)); ?>" class="btn btn-success mt-3">
                <i class="bi bi-download me-1"></i> Unduh Dokumen Surat (PDF)
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Riwayat & Notifikasi</h6>
            <ul class="list-unstyled mb-0">
                <?php $__currentLoopData = $surat->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="timeline-item">
                    <div class="fw-semibold small"><?php echo e(\App\Models\SuratRequest::labelStatus()[$log->status] ?? $log->status); ?></div>
                    <div class="text-muted small"><?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></div>
                    <div class="small mt-1"><?php echo e($log->keterangan); ?></div>
                    <div class="small mt-1">
                        <?php if($log->wa_terkirim): ?>
                            <span class="text-success"><i class="bi bi-whatsapp"></i> Notifikasi WA terkirim</span>
                        <?php else: ?>
                            <span class="text-muted"><i class="bi bi-whatsapp"></i> WA gagal/simulasi</span>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>

<a href="<?php echo e(route('kadus.surat.index')); ?>" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali ke daftar</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/surat/show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Kelola Permohonan Surat'); ?>
<?php $__env->startSection('page-title', 'Kelola Permohonan Surat'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="mb-3"><?php echo e($surat->suratType->nama); ?></h5>
            <table class="table table-borderless table-sm mb-3">
                <tr><td class="text-muted" style="width:180px;">Kode Permohonan</td><td>: <?php echo e($surat->kode_permohonan); ?></td></tr>
                <tr><td class="text-muted">Atas Nama</td><td>: <?php echo e($surat->warga->nama); ?> (<?php echo e($surat->warga->nik); ?>)</td></tr>
                <tr><td class="text-muted">Dusun</td><td>: <?php echo e($surat->warga->dusun->nama_dusun); ?></td></tr>
                <tr><td class="text-muted">Pemohon Akun</td><td>: <?php echo e($surat->pemohon->name); ?> <?php echo e($surat->dibuat_oleh_kadus ? '(diajukan oleh Kepala Dusun)' : ''); ?></td></tr>
                <tr>
                    <td class="text-muted">No. WhatsApp</td>
                    <td>
                        : <?php echo e($surat->nomorWhatsappTujuan() ?? '-'); ?>

                        <?php if($surat->nomorWhatsappTujuan() && $surat->nomorWhatsappTujuan() === $surat->pemohon->no_hp && $surat->nomorWhatsappTujuan() !== $surat->warga->no_hp): ?>
                            <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">Nomor akun pemohon (warga belum punya No. HP tercatat)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><td class="text-muted">Alasan/Keperluan</td><td>: <?php echo e($surat->alasan); ?></td></tr>
                <tr><td class="text-muted">Persyaratan</td><td>: <?php echo e($surat->suratType->persyaratan); ?></td></tr>
                <?php if($surat->lampirans->count() > 0): ?>
                <tr><td class="text-muted align-top">Lampiran</td><td>:
                    <ul class="mb-0 ps-3 ">
                        <?php $__currentLoopData = $surat->lampirans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lampiran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('admin.surat.lampiran', [$surat, $lampiran])); ?>" target="_blank"><i class="bi bi-paperclip"></i> <?php echo e($lampiran->nama_asli ?? 'Lampiran '.$loop->iteration); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </td></tr>
                <?php endif; ?>
                <tr><td class="text-muted">Status Saat Ini</td><td>: <span class="badge <?php echo e($surat->badgeClass()); ?>"><?php echo e($surat->labelStatusSaatIni()); ?></span></td></tr>
            </table>

            <div class="d-flex flex-wrap gap-2">
                <?php if($surat->status === 'diajukan'): ?>
                <form method="POST" action="<?php echo e(route('admin.surat.proses', $surat)); ?>" data-confirm="Mulai proses permohonan surat &quot;<?php echo e($surat->suratType->nama); ?>&quot; atas nama <?php echo e($surat->warga->nama); ?>?" data-confirm-title="Konfirmasi Proses Surat" data-confirm-button="Ya, Proses">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-warning"><i class="bi bi-play-circle me-1"></i>Proses Permohonan</button>
                </form>
                <?php endif; ?>

                <?php if($surat->status === 'diproses'): ?>
                <form method="POST" action="<?php echo e(route('admin.surat.tandatangani', $surat)); ?>" data-confirm="Tandatangani &amp; terbitkan PDF untuk surat ini? Pastikan seluruh data sudah benar." data-confirm-title="Konfirmasi Tandatangani" data-confirm-button="Ya, Tandatangani">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-info"><i class="bi bi-pen me-1"></i>Tandatangani & Buat PDF</button>
                </form>
                <?php endif; ?>

                <?php if($surat->status === 'ditandatangani'): ?>
                <form method="POST" action="<?php echo e(route('admin.surat.selesaikan', $surat)); ?>" data-confirm="Tandai permohonan surat ini sebagai selesai?" data-confirm-title="Konfirmasi Selesaikan" data-confirm-button="Ya, Selesaikan">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Tandai Selesai</button>
                </form>
                <?php endif; ?>

                <?php if($surat->file_path): ?>
                <a href="<?php echo e(route('admin.surat.download', $surat)); ?>" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Unduh PDF</a>
                <?php endif; ?>

                <?php if(!in_array($surat->status, ['selesai','ditolak'])): ?>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                    <i class="bi bi-x-circle me-1"></i>Tolak
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Riwayat & Notifikasi WhatsApp</h6>
            <ul class="list-unstyled mb-0">
                <?php $__currentLoopData = $surat->logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="mb-3 pb-3 border-bottom">
                    <div class="fw-semibold small"><?php echo e(\App\Models\SuratRequest::labelStatus()[$log->status] ?? $log->status); ?></div>
                    <div class="text-muted small"><?php echo e($log->created_at->translatedFormat('d M Y, H:i')); ?></div>
                    <div class="small"><?php echo e($log->keterangan); ?></div>
                    <div class="small mt-1">
                        <?php if($log->wa_terkirim): ?>
                            <span class="text-success"><i class="bi bi-whatsapp"></i> Terkirim</span>
                        <?php else: ?>
                            <span class="text-muted"><i class="bi bi-whatsapp"></i> Gagal/Simulasi</span>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.surat.tolak', $surat)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h6 class="modal-title">Tolak Permohonan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a href="<?php echo e(route('admin.surat.index')); ?>" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/surat/show.blade.php ENDPATH**/ ?>
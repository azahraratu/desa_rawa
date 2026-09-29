<?php $__env->startSection('title', 'Detail Pengaduan'); ?>
<?php $__env->startSection('page-title', 'Detail & Tanggapi Pengaduan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between">
                <h5><?php echo e($pengaduan->judul); ?></h5>
                <span class="badge <?php echo e($pengaduan->badgeClass()); ?>"><?php echo e(\App\Models\Pengaduan::labelStatus()[$pengaduan->status]); ?></span>
            </div>
            <p class="small text-muted">Pelapor: <?php echo e($pengaduan->pelapor->name); ?> <?php echo e($pengaduan->dilaporkan_oleh_kadus ? '(dilaporkan oleh Anda)' : '(dilaporkan langsung oleh warga)'); ?> &middot; <?php echo e($pengaduan->created_at->translatedFormat('d F Y, H:i')); ?></p>
            <p><?php echo e($pengaduan->deskripsi); ?></p>
            <?php if($pengaduan->foto_path): ?>
            <img src="<?php echo e(asset('storage/'.$pengaduan->foto_path)); ?>" class="img-fluid rounded mt-2" alt="Foto pengaduan">
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Riwayat Tanggapan</h6>
            <?php $__empty_1 = true; $__currentLoopData = $pengaduan->tanggapans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="mb-3 pb-3 border-bottom">
                <div class="small text-muted"><?php echo e($t->admin->name); ?> &middot; <?php echo e($t->created_at->translatedFormat('d M Y, H:i')); ?></div>
                <div><?php echo e($t->tanggapan); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted small">Belum ada tanggapan. Warga belum tahu keluhannya sudah dikerjakan atau belum - segera beri tanggapan di bawah ini.</p>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('kadus.pengaduan.tanggapi', $pengaduan)); ?>" class="mt-3" data-confirm="Kirim tanggapan ini dan perbarui status pengaduan?" data-confirm-title="Konfirmasi Kirim Tanggapan" data-confirm-button="Ya, Kirim">
                <?php echo csrf_field(); ?>
                <label class="form-label small">Tulis Tanggapan</label>
                <textarea name="tanggapan" class="form-control mb-2" rows="3" required></textarea>
                <select name="status" class="form-select mb-2" required>
                    <option value="diproses">Tandai: Sedang Diproses</option>
                    <option value="selesai">Tandai: Selesai</option>
                </select>
                <button type="submit" class="btn btn-success w-100">Kirim Tanggapan</button>
            </form>
        </div>
    </div>
</div>
<a href="<?php echo e(route('kadus.pengaduan.index')); ?>" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali</a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/kadus/pengaduan/show.blade.php ENDPATH**/ ?>
<?php $a = $anggaran ?? null; ?>

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
        <input type="number" name="tahun" class="form-control" required min="2000" max="2100" value="<?php echo e(old('tahun', $a->tahun ?? now()->year)); ?>">
    </div>
    <div class="col-md-8">
        <label class="form-label">Jenis <span class="text-danger">*</span></label>
        <select name="jenis" class="form-select" required>
            <?php $__currentLoopData = \App\Models\AnggaranDesa::labelJenis(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(old('jenis', $a->jenis ?? '') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Kategori / Bidang <span class="text-danger">*</span></label>
        <input type="text" name="kategori" class="form-control" required value="<?php echo e(old('kategori', $a->kategori ?? '')); ?>" placeholder="Contoh: Dana Desa (DD), Bidang Pembangunan, Pendapatan Asli Desa">
    </div>
    <div class="col-12">
        <label class="form-label">Keterangan Pemanfaatan <span class="text-muted small fw-normal">(opsional)</span></label>
        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Perbaikan jalan lingkungan RT 03, pembangunan drainase, dll."><?php echo e(old('keterangan', $a->keterangan ?? '')); ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jumlah Anggaran (Rp) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="jumlah_anggaran" class="form-control" required value="<?php echo e(old('jumlah_anggaran', $a->jumlah_anggaran ?? '')); ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Jumlah Realisasi (Rp) <span class="text-muted small fw-normal">(opsional, isi bila sudah terealisasi)</span></label>
        <input type="number" step="0.01" min="0" name="jumlah_realisasi" class="form-control" value="<?php echo e(old('jumlah_realisasi', $a->jumlah_realisasi ?? 0)); ?>">
    </div>
</div>
<?php /**PATH C:\laragon\www\sistem-desa-rawaa\resources\views/admin/anggaran/_form.blade.php ENDPATH**/ ?>
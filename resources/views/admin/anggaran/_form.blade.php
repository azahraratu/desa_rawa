@php $a = $anggaran ?? null; @endphp

<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Tahun Anggaran <span class="text-danger">*</span></label>
        <input type="number" name="tahun" class="form-control" required min="2000" max="2100" value="{{ old('tahun', $a->tahun ?? now()->year) }}">
    </div>
    <div class="col-md-8">
        <label class="form-label">Jenis <span class="text-danger">*</span></label>
        <select name="jenis" class="form-select" required>
            @foreach(\App\Models\AnggaranDesa::labelJenis() as $val => $label)
                <option value="{{ $val }}" {{ old('jenis', $a->jenis ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Kategori / Bidang <span class="text-danger">*</span></label>
        <input type="text" name="kategori" class="form-control" required value="{{ old('kategori', $a->kategori ?? '') }}" placeholder="Contoh: Dana Desa (DD), Bidang Pembangunan, Pendapatan Asli Desa">
    </div>
    <div class="col-12">
        <label class="form-label">Keterangan Pemanfaatan <span class="text-muted small fw-normal">(opsional)</span></label>
        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Perbaikan jalan lingkungan RT 03, pembangunan drainase, dll.">{{ old('keterangan', $a->keterangan ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jumlah Anggaran (Rp) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="jumlah_anggaran" class="form-control" required value="{{ old('jumlah_anggaran', $a->jumlah_anggaran ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Jumlah Realisasi (Rp) <span class="text-muted small fw-normal">(opsional, isi bila sudah terealisasi)</span></label>
        <input type="number" step="0.01" min="0" name="jumlah_realisasi" class="form-control" value="{{ old('jumlah_realisasi', $a->jumlah_realisasi ?? 0) }}">
    </div>
</div>

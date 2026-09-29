@extends('layouts.app')

@section('title', 'Buatkan Surat untuk Warga')
@section('page-title', 'Buatkan Permohonan Surat untuk Warga')

@section('content')
<div class="card p-4" style="max-width:700px;">
    <div class="alert alert-info small">Gunakan fitur ini untuk membantu warga yang kesulitan mengoperasikan aplikasi. Permohonan akan tercatat sebagai diajukan oleh Kepala Dusun.</div>
    <form method="POST" action="{{ route('kadus.surat.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Pilih Warga</label>
            <select name="warga_id" class="form-select" required>
                <option value="">-- Pilih Warga --</option>
                @foreach($wargaDusun as $w)
                    <option value="{{ $w->id }}">{{ $w->nama }} ({{ $w->nik }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Jenis Surat</label>
            <select name="surat_type_id" id="surat_type_id" class="form-select" required onchange="tampilkanLampiran()">
                <option value="">-- Pilih Jenis Surat --</option>
                @foreach($suratTypes as $type)
                    <option value="{{ $type->id }}" data-perlu-lampiran="{{ $type->perlu_lampiran ? '1' : '0' }}" data-label-lampiran="{{ $type->label_lampiran }}">{{ $type->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 d-none" id="box-lampiran">
            <label class="form-label" id="label-lampiran">Unggah Lampiran</label>
            <input type="file" name="lampiran[]" id="input-lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple data-max-mb="4">
            <div class="form-text">Bisa pilih lebih dari 1 file sekaligus (tahan Ctrl saat memilih). Format JPG/PNG/PDF, maksimal 4 MB per file.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Keperluan / Alasan</label>
            <textarea name="alasan" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Ajukan Permohonan</button>
    </form>
</div>

@push('scripts')
<script>
function tampilkanLampiran() {
    const select = document.getElementById('surat_type_id');
    const opt = select.options[select.selectedIndex];
    const perlu = opt.getAttribute('data-perlu-lampiran') === '1';
    const label = opt.getAttribute('data-label-lampiran');
    const box = document.getElementById('box-lampiran');
    const input = document.getElementById('input-lampiran');
    if (perlu) {
        document.getElementById('label-lampiran').innerText = 'Unggah Lampiran: ' + label + ' (wajib)';
        box.classList.remove('d-none');
        input.setAttribute('required', 'required');
    } else {
        box.classList.add('d-none');
        input.removeAttribute('required');
    }
}
</script>
@endpush
@endsection

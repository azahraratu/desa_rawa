@extends('layouts.app')

@section('title', 'Ajukan Permohonan Surat')
@section('page-title', 'Ajukan Permohonan Surat')

@section('content')
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="{{ route('warga.surat.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Surat untuk siapa?</label>
            <select name="warga_id" class="form-select" required>
                <option value="{{ auth()->user()->warga_id }}">{{ auth()->user()->warga->nama }} (Saya Sendiri)</option>
                @foreach($anggotaKeluarga as $anggota)
                    <option value="{{ $anggota->id }}">{{ $anggota->nama }} ({{ $anggota->nik }})</option>
                @endforeach
            </select>
            <div class="form-text">Hanya dapat mengajukan untuk anggota dalam satu Kartu Keluarga (KK) yang sama.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Surat</label>
            <select name="surat_type_id" id="surat_type_id" class="form-select" required onchange="tampilkanPersyaratan()">
                <option value="">-- Pilih Jenis Surat --</option>
                @foreach($suratTypes as $type)
                    <option value="{{ $type->id }}"
                        data-persyaratan="{{ $type->persyaratan }}"
                        data-perlu-lampiran="{{ $type->perlu_lampiran ? '1' : '0' }}"
                        data-label-lampiran="{{ $type->label_lampiran }}">
                        {{ $type->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="alert alert-secondary small d-none" id="box-persyaratan">
            <strong>Persyaratan:</strong> <span id="isi-persyaratan"></span>
        </div>

        <div class="mb-3 d-none" id="box-lampiran">
            <label class="form-label" id="label-lampiran">Unggah Lampiran</label>
            <input type="file" name="lampiran[]" id="input-lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" multiple data-max-mb="4">
            <div class="form-text">Bisa pilih <strong>lebih dari 1 file sekaligus</strong> (tahan Ctrl saat memilih file). Format JPG/PNG/PDF, maksimal 4 MB per file.</div>
        </div>

        <div class="mb-3">
            <label class="form-label">Keperluan / Alasan Pengajuan</label>
            <textarea name="alasan" class="form-control" rows="4" required placeholder="Jelaskan keperluan surat ini secara singkat"></textarea>
        </div>

        <div class="alert alert-info small">
            <i class="bi bi-whatsapp me-1"></i> Notifikasi status permohonan (teks) akan dikirim ke WhatsApp Anda di setiap tahap. Lampiran/dokumen yang diunggah <strong>tidak</strong> ikut terkirim lewat WhatsApp &mdash; silakan cek statusnya langsung di aplikasi ini.
        </div>

        <button type="submit" class="btn btn-success w-100">Ajukan Permohonan</button>
    </form>
</div>

@push('scripts')
<script>
function tampilkanPersyaratan() {
    const select = document.getElementById('surat_type_id');
    const opt = select.options[select.selectedIndex];
    const persyaratan = opt.getAttribute('data-persyaratan');
    const perluLampiran = opt.getAttribute('data-perlu-lampiran') === '1';
    const labelLampiran = opt.getAttribute('data-label-lampiran');

    const boxPersyaratan = document.getElementById('box-persyaratan');
    if (persyaratan) {
        document.getElementById('isi-persyaratan').innerText = persyaratan;
        boxPersyaratan.classList.remove('d-none');
    } else {
        boxPersyaratan.classList.add('d-none');
    }

    const boxLampiran = document.getElementById('box-lampiran');
    const inputLampiran = document.getElementById('input-lampiran');
    if (perluLampiran) {
        document.getElementById('label-lampiran').innerText = 'Unggah Lampiran: ' + labelLampiran + ' (wajib)';
        boxLampiran.classList.remove('d-none');
        inputLampiran.setAttribute('required', 'required');
    } else {
        boxLampiran.classList.add('d-none');
        inputLampiran.removeAttribute('required');
    }
}
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Import Data Warga')
@section('page-title', 'Import Data Warga (Excel)')

@section('content')
<div class="card p-4" style="max-width:750px;">
    <div class="d-flex align-items-start gap-3 mb-3">
        <div class="tile-icon" style="background:var(--gold-100); color:var(--gold-500);"><i class="bi bi-file-earmark-arrow-down-fill"></i></div>
        <div>
            <h6 class="mb-1">1. Unduh Template Excel Dulu</h6>
            <p class="small text-muted mb-2">Template sudah berisi nama kolom yang benar, 1 baris contoh, dan panduan pengisian lengkap di sheet ke-2.</p>
            <a href="{{ route('kadus.warga.import.template') }}" class="btn btn-gold btn-sm"><i class="bi bi-download me-1"></i>Unduh Template Excel</a>
        </div>
    </div>

    <hr>

    <h6 class="mb-2">2. Panduan Singkat Pengisian</h6>
    <ul class="small text-muted">
        <li>Kolom: <strong>NIK, No KK, Nama, Jenis Kelamin (L/P), Status Hubungan Keluarga, RT, Tempat Lahir, Tanggal Lahir (YYYY-MM-DD), Nama Ayah, Nama Ibu, Alamat, No HP, Status Ekonomi, Status Pernikahan, Status Pendidikan, Pekerjaan</strong></li>
        <li>Kolom RT diisi nomor RT saja (contoh: 05). Kalau RT itu belum ada, sistem otomatis membuatkannya.</li>
        <li>NIK & No KK harus 16 digit. Kolomnya sudah diformat sebagai Teks di template supaya angka 0 di depan tidak hilang.</li>
        <li>Status Ekonomi: <code>mampu</code> / <code>kurang_mampu</code> / <code>tidak_mampu</code></li>
        <li>Status Pernikahan: <code>belum_menikah</code> / <code>menikah</code> / <code>cerai_hidup</code> / <code>cerai_mati</code></li>
        <li>Status Pendidikan: <code>tidak_sekolah</code> / <code>sd</code> / <code>smp</code> / <code>sma</code> / <code>diploma</code> / <code>s1</code> / <code>s2</code> / <code>s3</code></li>
        <li>Jika NIK sudah pernah diimport, data akan otomatis diperbarui (bukan duplikat).</li>
        <li>Panduan lengkap juga ada di sheet <strong>"Panduan Pengisian"</strong> pada file template.</li>
    </ul>

    <hr>

    <h6 class="mb-2">3. Unggah File yang Sudah Diisi</h6>
    <form method="POST" action="{{ route('kadus.warga.import.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">File Excel (.xlsx)</label>
            <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required data-max-mb="5">
        </div>

        @if(session('detail_gagal'))
        <div class="alert alert-warning small">
            <strong>Beberapa baris gagal diimport:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach(session('detail_gagal') as $pesan)
                    <li>{{ $pesan }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <button type="submit" class="btn btn-success w-100">Import Sekarang</button>
    </form>
</div>
@endsection

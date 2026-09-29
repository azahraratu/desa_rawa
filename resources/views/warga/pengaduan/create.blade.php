@extends('layouts.app')

@section('title', 'Buat Pengaduan')
@section('page-title', 'Buat Pengaduan')

@section('content')
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="{{ route('warga.pengaduan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Judul Pengaduan</label>
            <input type="text" name="judul" class="form-control" required value="{{ old('judul') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
                <option value="infrastruktur">Infrastruktur (jalan, jembatan, drainase, dll)</option>
                <option value="sosial">Sosial Kemasyarakatan</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Jelaskan permasalahan secara detail">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Lampiran Foto (opsional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*" data-max-mb="4">
        </div>
        <button type="submit" class="btn btn-success w-100">Kirim Pengaduan</button>
    </form>
</div>
@endsection

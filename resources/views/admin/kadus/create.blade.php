@extends('layouts.app')

@section('title', 'Buat Akun Kepala Dusun')
@section('page-title', 'Buat Akun Kepala Dusun Baru')

@section('content')
<div class="mb-3">
    @if($balikKe === 'dusun')
        <a href="{{ route('admin.dusun.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali ke Kelola Dusun</a>
    @else
        <a href="{{ route('admin.kadus.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali ke Akun Kepala Dusun</a>
    @endif
</div>

<div class="card p-4" style="max-width:600px;">
    @if($dusuns->isEmpty())
        <div class="alert alert-warning small mb-0">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Semua dusun yang ada sudah memiliki akun Kepala Dusun aktif. Kalau mau menambah Kepala Dusun baru,
            <a href="{{ route('admin.dusun.create') }}">tambahkan dusun baru dulu</a>, atau nonaktifkan/hapus
            akun Kepala Dusun lama lewat halaman <a href="{{ route('admin.kadus.index') }}">Akun Kepala Dusun</a>.
        </div>
    @else
        <div class="alert alert-info small">Password sementara akan dibuat otomatis secara acak dan hanya ditampilkan satu kali setelah akun dibuat. Segera sampaikan ke Kepala Dusun yang bersangkutan secara langsung/aman.</div>
        <form method="POST" action="{{ route('admin.kadus.store') }}">
            @csrf
            <input type="hidden" name="balik_ke" value="{{ $balikKe }}">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">NIK</label>
                <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" required value="{{ old('no_hp') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Ditugaskan di Dusun</label>
                <select name="dusun_id" class="form-select" required>
                    <option value="">-- Pilih Dusun --</option>
                    @foreach($dusuns as $d)
                        <option value="{{ $d->id }}" {{ (int) old('dusun_id', $dusunTerpilih) === $d->id ? 'selected' : '' }}>{{ $d->nama_dusun }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Buat Akun</button>
        </form>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Laporan Pengaduan')
@section('page-title', 'Cetak Rekap Pengaduan per Periode')

@section('content')
<div class="card p-4" style="max-width:600px;">
    <form method="GET" action="{{ route('admin.pengaduan.laporan.cetak') }}" target="_blank">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" required value="{{ now()->startOfMonth()->format('Y-m-d') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" required value="{{ now()->format('Y-m-d') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-success w-100 mt-4"><i class="bi bi-printer me-1"></i> Tampilkan & Cetak Rekap Pengaduan</button>
    </form>
</div>
@endsection

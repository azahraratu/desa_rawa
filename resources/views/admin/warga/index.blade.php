@extends('layouts.app')

@section('title', 'Data Kependudukan')
@section('page-title', 'Data Kependudukan per Dusun')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <p class="text-muted mb-0">Pilih dusun untuk melihat data kependudukan secara terpisah & rapi per wilayah.</p>
    <a href="{{ route('admin.warga.export-semua') }}" class="btn btn-gold"><i class="bi bi-file-earmark-excel-fill me-1"></i>Unduh Semua Data (Excel)</a>
</div>

<div class="row g-3">
    @foreach($dusuns as $dusun)
    <div class="col-md-6 col-lg-4">
        <div class="feature-tile">
            <div class="d-flex justify-content-between align-items-start">
                <div class="tile-icon"><i class="bi bi-signpost-split-fill"></i></div>
                @if($dusun->warga_meninggal_count > 0)
                <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $dusun->warga_meninggal_count }} meninggal tercatat</span>
                @endif
            </div>
            <h5 class="mt-2 mb-1">{{ $dusun->nama_dusun }}</h5>
            <p class="text-muted small mb-2">
                Kepala Dusun: {{ $dusun->kepalaDusun->name ?? 'Belum ada akun' }}
            </p>
            <div class="d-flex align-items-baseline gap-2 mb-3">
                <span class="fs-3 fw-bold" style="color:var(--primary-700);">{{ $dusun->warga_count }}</span>
                <span class="text-muted small">warga aktif</span>
            </div>
            <a href="{{ route('admin.warga.dusun', $dusun) }}" class="btn btn-success w-100">
                <i class="bi bi-eye-fill me-1"></i> Lihat Data Dusun Ini
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection

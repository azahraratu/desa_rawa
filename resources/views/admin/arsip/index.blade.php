@extends('layouts.app')

@section('title', 'Arsip & Laporan Bulanan')
@section('page-title', 'Arsip & Laporan Bulanan')

@section('content')
<div class="card p-4 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-calendar3 me-1"></i> Pilih Periode (seperti mutasi rekening)</h6>
    <form method="GET" action="{{ route('admin.arsip.show') }}" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-select" required>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                    <option value="{{ $i + 1 }}" {{ now()->month == $i + 1 ? 'selected' : '' }}>{{ $namaBulan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-select" required>
                @for($t = now()->year; $t >= now()->year - 3; $t--)
                    <option value="{{ $t }}" {{ now()->year == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Dusun (opsional)</label>
            <select name="dusun_id" class="form-select">
                <option value="">Semua Dusun</option>
                @foreach($dusuns as $d)
                    <option value="{{ $d->id }}">{{ $d->nama_dusun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100"><i class="bi bi-search me-1"></i>Lihat</button>
        </div>
    </form>
</div>

<h6 class="fw-bold mb-3">Arsip 12 Bulan Terakhir</h6>
<div class="row g-3">
    @foreach($periodeCepat as $p)
    <div class="col-md-4 col-lg-3">
        <a href="{{ route('admin.arsip.show', ['bulan' => $p['bulan'], 'tahun' => $p['tahun']]) }}" class="text-decoration-none">
            <div class="feature-tile">
                <div class="tile-icon"><i class="bi bi-folder2-open"></i></div>
                <h6 class="mb-2">{{ $p['label'] }}</h6>
                <div class="d-flex justify-content-between small text-muted">
                    <span><i class="bi bi-file-earmark-text"></i> {{ $p['total_surat'] }} surat</span>
                    <span><i class="bi bi-person-plus"></i> {{ $p['total_warga_baru'] }} warga</span>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection

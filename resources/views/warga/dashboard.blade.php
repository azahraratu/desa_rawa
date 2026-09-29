@extends('layouts.app')

@section('title', 'Beranda Warga')
@section('page-title', 'Beranda')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
            <div><div class="stat-label">Total Permohonan Surat</div><div class="stat-value">{{ $ringkasan['total_surat'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-gold">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-label">Sedang Diproses</div><div class="stat-value">{{ $ringkasan['surat_diproses'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-label">Selesai</div><div class="stat-value">{{ $ringkasan['surat_selesai'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-magenta">
            <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
            <div><div class="stat-label">Total Pengaduan</div><div class="stat-value">{{ $ringkasan['total_pengaduan'] }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <a href="{{ route('warga.surat.create') }}" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-file-earmark-plus fs-1 text-success"></i>
                <h5 class="mt-2 mb-0">Ajukan Permohonan Surat</h5>
                <p class="small text-muted mb-0">Untuk diri sendiri atau anggota keluarga</p>
            </div>
        </a>
    </div>
    <div class="col-md-6">
        <a href="{{ route('warga.pengaduan.create') }}" class="text-decoration-none">
            <div class="card p-4 text-center h-100">
                <i class="bi bi-megaphone fs-1 text-danger"></i>
                <h5 class="mt-2 mb-0">Sampaikan Pengaduan</h5>
                <p class="small text-muted mb-0">Laporkan permasalahan di lingkungan Anda</p>
            </div>
        </a>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3">Permohonan Surat Terbaru</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($suratRequests as $s)
                <tr>
                    <td>{{ $s->kode_permohonan }}</td>
                    <td>{{ $s->suratType->nama }}</td>
                    <td>{{ $s->warga->nama }}</td>
                    <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                    <td><a href="{{ route('warga.surat.show', $s) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada permohonan surat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Anggaran Desa')
@section('page-title', 'Transparansi Anggaran Desa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 small text-muted">Tahun Anggaran</label>
        <select name="tahun" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
            @if($tahunTersedia->isEmpty())
                <option value="{{ $tahun }}">{{ $tahun }}</option>
            @endif
            @foreach($tahunTersedia as $t)
                <option value="{{ $t }}" {{ (int) $t === (int) $tahun ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.anggaran.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah Data Anggaran</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div><div class="stat-label">Total Pendapatan {{ $tahun }}</div><div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <div><div class="stat-label">Total Belanja (Rencana) {{ $tahun }}</div><div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card tone-magenta">
            <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
            <div><div class="stat-label">Realisasi Belanja {{ $tahun }}</div><div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($totalRealisasiBelanja, 0, ',', '.') }}</div></div>
        </div>
    </div>
</div>

<div class="card p-3 mb-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-arrow-down-circle text-success me-1"></i>Pendapatan</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kategori</th><th>Keterangan</th><th class="text-end">Jumlah</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($pendapatan as $a)
                <tr>
                    <td class="fw-semibold">{{ $a->kategori }}</td>
                    <td class="small text-muted">{{ $a->keterangan ?: '-' }}</td>
                    <td class="text-end">Rp {{ number_format($a->jumlah_anggaran, 0, ',', '.') }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.anggaran.edit', $a) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="{{ route('admin.anggaran.destroy', $a) }}" onsubmit="return confirm('Hapus data anggaran {{ $a->kategori }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data pendapatan untuk tahun ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card p-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-arrow-up-circle text-danger me-1"></i>Belanja & Pemanfaatan</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Bidang / Kategori</th><th>Pemanfaatan</th><th class="text-end">Anggaran</th><th class="text-end">Realisasi</th><th style="width:140px;">Progres</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($belanja as $a)
                <tr>
                    <td class="fw-semibold">{{ $a->kategori }}</td>
                    <td class="small text-muted">{{ $a->keterangan ?: '-' }}</td>
                    <td class="text-end">Rp {{ number_format($a->jumlah_anggaran, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($a->jumlah_realisasi, 0, ',', '.') }}</td>
                    <td>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-success" style="width:{{ $a->persentaseRealisasi() }}%"></div>
                        </div>
                        <div class="small text-muted mt-1">{{ $a->persentaseRealisasi() }}%</div>
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.anggaran.edit', $a) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="{{ route('admin.anggaran.destroy', $a) }}" onsubmit="return confirm('Hapus data anggaran {{ $a->kategori }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">Belum ada data belanja untuk tahun ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<p class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i>Data pada halaman ini juga ditampilkan secara terbuka kepada warga di halaman beranda publik, sebagai bentuk transparansi anggaran desa.</p>
@endsection

@extends('layouts.app')

@section('title', 'Arsip '.$labelPeriode)
@section('page-title', 'Arsip Periode: '.$labelPeriode)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('admin.arsip.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Ganti Periode</a>
        @if($dusunTerpilih)
            <span class="badge bg-success-subtle text-success-emphasis ms-2">{{ $dusunTerpilih->nama_dusun }}</span>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.arsip.excel', ['bulan' => $bulan, 'tahun' => $tahun, 'dusun_id' => $dusunId]) }}" class="btn btn-gold btn-sm"><i class="bi bi-file-earmark-excel-fill me-1"></i>Unduh Excel</a>
        <a href="{{ route('admin.arsip.pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'dusun_id' => $dusunId]) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-earmark-pdf-fill me-1"></i>Unduh PDF</a>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach($ringkasanDusun as $d)
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-signpost-split-fill"></i></div>
            <div>
                <div class="stat-label">{{ $d->nama_dusun }}</div>
                <div class="stat-value">{{ $d->warga_count }}</div>
                <div class="small text-muted">+{{ $d->warga_baru_count }} baru &middot; {{ $d->kematian_count }} meninggal</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<ul class="nav nav-pills mb-3" id="arsipTab">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-surat">Surat ({{ $suratRequests->count() }})</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-warga">Warga Baru ({{ $wargaBaru->count() }})</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-pengaduan">Pengaduan ({{ $pengaduans->count() }})</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-kematian">Kematian ({{ $kematian->count() }})</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active card p-3" id="tab-surat">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Kode</th><th>Jenis</th><th>Atas Nama</th><th>Dusun</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($suratRequests as $s)
                    <tr>
                        <td>{{ $s->kode_permohonan }}</td><td>{{ $s->suratType->nama }}</td><td>{{ $s->warga->nama }}</td>
                        <td>{{ $s->warga->dusun->nama_dusun }}</td>
                        <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                        <td class="small text-muted">{{ $s->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade card p-3" id="tab-warga">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>NIK</th><th>Nama</th><th>Dusun</th><th>Diinput Pada</th></tr></thead>
                <tbody>
                    @forelse($wargaBaru as $w)
                    <tr><td>{{ $w->nik }}</td><td>{{ $w->nama }}</td><td>{{ $w->dusun->nama_dusun }}</td><td class="small text-muted">{{ $w->created_at->format('d M Y, H:i') }}</td></tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade card p-3" id="tab-pengaduan">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Judul</th><th>Kategori</th><th>Pelapor</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @forelse($pengaduans as $p)
                    <tr><td>{{ $p->judul }}</td><td>{{ ucfirst($p->kategori) }}</td><td>{{ $p->pelapor->name }}</td><td><span class="badge {{ $p->badgeClass() }}">{{ ucfirst($p->status) }}</span></td><td class="small text-muted">{{ $p->created_at->format('d M Y') }}</td></tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade card p-3" id="tab-kematian">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Nama</th><th>NIK</th><th>Dusun</th><th>Tanggal Meninggal</th></tr></thead>
                <tbody>
                    @forelse($kematian as $w)
                    <tr><td>{{ $w->nama }}</td><td>{{ $w->nik }}</td><td>{{ $w->dusun->nama_dusun }}</td><td class="small text-muted">{{ $w->tanggal_meninggal->format('d M Y') }}</td></tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data kematian pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

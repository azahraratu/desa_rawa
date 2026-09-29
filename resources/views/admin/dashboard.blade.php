@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

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
            <div><div class="stat-label">Menunggu Diproses</div><div class="stat-value">{{ $ringkasan['surat_menunggu'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-magenta">
            <div class="stat-icon"><i class="bi bi-megaphone-fill"></i></div>
            <div><div class="stat-label">Pengaduan Baru</div><div class="stat-value">{{ $ringkasan['total_pengaduan_baru'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">Total Warga Aktif</div><div class="stat-value">{{ $ringkasan['total_warga'] }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-emoji-smile-fill"></i></div>
            <div><div class="stat-label">Kelahiran Tahun Ini</div><div class="stat-value">{{ $kelahiranTahunIni }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-danger">
            <div class="stat-icon"><i class="bi bi-emoji-frown-fill"></i></div>
            <div><div class="stat-label">Kematian Tahun Ini</div><div class="stat-value">{{ $kematianTahunIni }}</div></div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h6 class="fw-bold">Jumlah & Jenis Surat Diterbitkan</h6>
            <canvas id="chartSurat" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="fw-bold">Warga per Dusun</h6>
            <canvas id="chartDusun" height="220"></canvas>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="fw-bold">Tingkat Pendidikan</h6>
            <canvas id="chartPendidikan" height="220"></canvas>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Permohonan Surat Terbaru</h6>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Kode</th><th>Jenis</th><th>Atas Nama</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($permohonanTerbaru as $s)
                        <tr>
                            <td><a href="{{ route('admin.surat.show', $s) }}">{{ $s->kode_permohonan }}</a></td>
                            <td>{{ $s->suratType->nama }}</td>
                            <td>{{ $s->warga->nama }}</td>
                            <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Pengaduan Terbaru</h6>
            @forelse($pengaduanTerbaru as $p)
            <div class="mb-2 pb-2 border-bottom d-flex justify-content-between">
                <div>
                    <div class="small fw-semibold">{{ $p->judul }}</div>
                    <div class="small text-muted">{{ $p->pelapor->name }}</div>
                </div>
                <span class="badge {{ $p->badgeClass() }} align-self-start">{{ ucfirst($p->status) }}</span>
            </div>
            @empty
            <p class="text-muted small mb-0">Belum ada pengaduan.</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const palet = ['#147544', '#d6a419', '#9e0b84', '#2f7fb5', '#d64545', '#58d68d', '#8e44ad', '#e67e22', '#16a085', '#c0392b'];

new Chart(document.getElementById('chartSurat'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($suratPerJenis->pluck('label')) !!},
        datasets: [{ label: 'Jumlah', data: {!! json_encode($suratPerJenis->pluck('total')) !!}, backgroundColor: palet }]
    },
    options: { plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('chartDusun'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($wargaPerDusun->pluck('label')) !!},
        datasets: [{ data: {!! json_encode($wargaPerDusun->pluck('total')) !!}, backgroundColor: palet }]
    }
});

new Chart(document.getElementById('chartPendidikan'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($pendidikan->pluck('label')) !!},
        datasets: [{ data: {!! json_encode($pendidikan->pluck('total')) !!}, backgroundColor: palet }]
    }
});
</script>
@endpush

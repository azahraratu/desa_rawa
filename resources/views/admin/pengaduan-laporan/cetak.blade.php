<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengaduan - {{ $desa['nama'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; }
        @media print { .no-print { display: none; } body { padding: 0; } }
        table th, table td { font-size: .85rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-0">REKAPITULASI PENGADUAN MASYARAKAT</h4>
        <h5 class="fw-bold">{{ $desa['nama'] }}, KEC. {{ strtoupper($desa['kecamatan']) }}, KAB. {{ strtoupper($desa['kabupaten']) }}</h5>
        <p class="mb-0">Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="no-print text-end mb-3">
        <button onclick="window.print()" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="fw-bold">Rekap per Kategori</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Kategori</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse($rekapPerKategori as $kategori => $jumlah)
                    <tr><td>{{ ucfirst($kategori) }}</td><td>{{ $jumlah }}</td></tr>
                    @empty
                    <tr><td colspan="2" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <h6 class="fw-bold">Rekap per Status</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @forelse($rekapPerStatus as $status => $jumlah)
                    <tr><td>{{ ucfirst($status) }}</td><td>{{ $jumlah }}</td></tr>
                    @empty
                    <tr><td colspan="2" class="text-center">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <h6 class="fw-bold">Detail Pengaduan</h6>
    <table class="table table-bordered table-sm">
        <thead><tr><th>No</th><th>Judul</th><th>Kategori</th><th>Pelapor</th><th>Tanggal</th><th>Status</th><th>Tanggapan Terakhir</th></tr></thead>
        <tbody>
            @forelse($pengaduans as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ ucfirst($p->kategori) }}</td>
                <td>{{ $p->pelapor->name ?? '-' }}</td>
                <td>{{ $p->created_at->format('d-m-Y') }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ optional($p->tanggapans->last())->tanggapan ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center">Tidak ada pengaduan pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-5">
        <div class="col-6"></div>
        <div class="col-6 text-center">
            <p>{{ $desa['kabupaten'] }}, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Kepala Desa {{ $desa['nama'] }}</p>
            <br><br><br>
            <p class="fw-bold text-decoration-underline">{{ $desa['kepala_desa'] }}</p>
        </div>
    </div>
</div>
</body>
</html>

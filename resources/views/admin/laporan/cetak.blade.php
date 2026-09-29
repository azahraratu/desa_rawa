<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi - {{ $desa['nama'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
        table th, table td { font-size: .85rem; }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center mb-4">
        <h4 class="fw-bold mb-0">LAPORAN REKAPITULASI PELAYANAN ADMINISTRASI</h4>
        <h5 class="fw-bold">{{ $desa['nama'] }}, KEC. {{ strtoupper($desa['kecamatan']) }}, KAB. {{ strtoupper($desa['kabupaten']) }}</h5>
        <p class="mb-0">Periode: {{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="no-print text-end mb-3">
        <button onclick="window.print()" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
    </div>

    <h6 class="fw-bold mt-4">A. Rekapitulasi Jumlah Penduduk per Dusun</h6>
    <table class="table table-bordered table-sm">
        <thead><tr><th>Dusun</th><th>Jumlah Warga Aktif</th></tr></thead>
        <tbody>
            @foreach($totalWargaPerDusun as $row)
            <tr><td>{{ $row->nama_dusun }}</td><td>{{ $row->total }}</td></tr>
            @endforeach
            <tr class="fw-bold"><td>Total</td><td>{{ $totalWarga }}</td></tr>
        </tbody>
    </table>

    <h6 class="fw-bold mt-4">B. Rekapitulasi Jenis Surat Diterbitkan</h6>
    <table class="table table-bordered table-sm">
        <thead><tr><th>Jenis Surat</th><th>Jumlah</th></tr></thead>
        <tbody>
            @forelse($rekapPerJenis as $jenis => $jumlah)
            <tr><td>{{ $jenis }}</td><td>{{ $jumlah }}</td></tr>
            @empty
            <tr><td colspan="2" class="text-center">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h6 class="fw-bold mt-4">C. Detail Permohonan Surat</h6>
    <table class="table table-bordered table-sm">
        <thead><tr><th>Kode</th><th>Jenis</th><th>Atas Nama</th><th>Dusun</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($suratRequests as $s)
            <tr>
                <td>{{ $s->kode_permohonan }}</td>
                <td>{{ $s->suratType->nama }}</td>
                <td>{{ $s->warga->nama }}</td>
                <td>{{ $s->warga->dusun->nama_dusun }}</td>
                <td>{{ $s->created_at->format('d-m-Y') }}</td>
                <td>{{ $s->labelStatusSaatIni() }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h6 class="fw-bold mt-4">D. Data Kematian pada Periode Ini</h6>
    <table class="table table-bordered table-sm">
        <thead><tr><th>Nama</th><th>NIK</th><th>Dusun</th><th>Tanggal Meninggal</th></tr></thead>
        <tbody>
            @forelse($kematian as $w)
            <tr><td>{{ $w->nama }}</td><td>{{ $w->nik }}</td><td>{{ $w->dusun->nama_dusun }}</td><td>{{ $w->tanggal_meninggal->format('d-m-Y') }}</td></tr>
            @empty
            <tr><td colspan="4" class="text-center">Tidak ada data kematian pada periode ini.</td></tr>
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

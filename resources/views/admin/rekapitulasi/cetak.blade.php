<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data Kependudukan - {{ $desa['nama'] }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; }
        @media print { .no-print { display: none; } body { padding: 0; } }
        table th, table td { font-size: .9rem; }
        h4, h5 { margin: 0; }
    </style>
</head>
<body>
<div class="container">
    <div class="text-center mb-4">
        <h4 class="fw-bold">REKAPITULASI DATA KEPENDUDUKAN</h4>
        <h5 class="fw-bold">{{ $desa['nama'] }}, KEC. {{ strtoupper($desa['kecamatan']) }}, KAB. {{ strtoupper($desa['kabupaten']) }}</h5>
        <p class="mb-0">Untuk keperluan rapat/musyawarah desa - dicetak {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="no-print text-end mb-3">
        <button onclick="window.print()" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
    </div>

    <table class="table table-bordered mb-4">
        <tr><td class="fw-bold" style="width:60%;">Total Warga Aktif</td><td>{{ $totalWarga }} jiwa</td></tr>
        <tr><td class="fw-bold">Total Tercatat Pindah Domisili</td><td>{{ $totalPindah }} jiwa</td></tr>
        <tr><td class="fw-bold">Total Tercatat Meninggal Dunia</td><td>{{ $totalMeninggal }} jiwa</td></tr>
        <tr><td class="fw-bold">Laki-laki / Perempuan</td><td>{{ $jenisKelamin['L'] ?? 0 }} / {{ $jenisKelamin['P'] ?? 0 }}</td></tr>
        <tr><td class="fw-bold">Total Permohonan Surat Tahun {{ now()->year }}</td><td>{{ $totalSuratTahunIni }}</td></tr>
    </table>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="fw-bold">A. Jumlah Warga per Dusun</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Dusun</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($perDusun as $d)
                    <tr><td>{{ $d->nama_dusun }}</td><td>{{ $d->warga_count }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <h6 class="fw-bold">B. Status Pernikahan</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($pernikahan as $label => $jumlah)
                    <tr><td>{{ $label }}</td><td>{{ $jumlah }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-6">
            <h6 class="fw-bold">C. Tingkat Pendidikan Terakhir</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Pendidikan</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($pendidikan as $label => $jumlah)
                    <tr><td>{{ $label }}</td><td>{{ $jumlah }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-6">
            <h6 class="fw-bold">D. Status Ekonomi</h6>
            <table class="table table-bordered table-sm">
                <thead><tr><th>Status</th><th>Jumlah</th></tr></thead>
                <tbody>
                    @foreach($ekonomi as $label => $jumlah)
                    <tr><td>{{ $label }}</td><td>{{ $jumlah }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Arsip {{ $labelPeriode }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: 'Times New Roman', serif; font-size: 12px; color: #000; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop h3 { margin: 2px 0; font-size: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { border: 1px solid #333; padding: 4px 6px; font-size: 11px; }
        th { background: #e3f6ea; }
        h4 { border-bottom: 1px solid #999; padding-bottom: 3px; }
    </style>
</head>
<body>
    <div class="kop">
        <h3>PEMERINTAH KABUPATEN {{ strtoupper($desa['kabupaten']) }}</h3>
        <h3>KECAMATAN {{ strtoupper($desa['kecamatan']) }} - {{ strtoupper($desa['nama']) }}</h3>
        <p>ARSIP & LAPORAN REKAPITULASI PERIODE: {{ strtoupper($labelPeriode) }}</p>
    </div>

    <h4>A. Jumlah Warga per Dusun</h4>
    <table>
        <tr><th>Dusun</th><th>Jumlah Warga Aktif</th></tr>
        @foreach($totalWargaPerDusun as $d)
        <tr><td>{{ $d->nama_dusun }}</td><td>{{ $d->warga_count }}</td></tr>
        @endforeach
    </table>

    <h4>B. Rekap Jenis Surat Diterbitkan</h4>
    <table>
        <tr><th>Jenis Surat</th><th>Jumlah</th></tr>
        @forelse($rekapPerJenis as $jenis => $jumlah)
        <tr><td>{{ $jenis }}</td><td>{{ $jumlah }}</td></tr>
        @empty
        <tr><td colspan="2">Tidak ada data.</td></tr>
        @endforelse
    </table>

    <h4>C. Detail Permohonan Surat</h4>
    <table>
        <tr><th>Kode</th><th>Jenis</th><th>Atas Nama</th><th>Dusun</th><th>Tanggal</th><th>Status</th></tr>
        @forelse($suratRequests as $s)
        <tr><td>{{ $s->kode_permohonan }}</td><td>{{ $s->suratType->nama }}</td><td>{{ $s->warga->nama }}</td><td>{{ $s->warga->dusun->nama_dusun }}</td><td>{{ $s->created_at->format('d-m-Y') }}</td><td>{{ $s->labelStatusSaatIni() }}</td></tr>
        @empty
        <tr><td colspan="6">Tidak ada data.</td></tr>
        @endforelse
    </table>

    <h4>D. Data Kematian Periode Ini</h4>
    <table>
        <tr><th>Nama</th><th>NIK</th><th>Dusun</th><th>Tanggal Meninggal</th></tr>
        @forelse($kematian as $w)
        <tr><td>{{ $w->nama }}</td><td>{{ $w->nik }}</td><td>{{ $w->dusun->nama_dusun }}</td><td>{{ $w->tanggal_meninggal->format('d-m-Y') }}</td></tr>
        @empty
        <tr><td colspan="4">Tidak ada data kematian pada periode ini.</td></tr>
        @endforelse
    </table>

    <p style="margin-top:30px; font-size:10px; color:#555;">Dokumen ini digenerate otomatis oleh Sistem Pelayanan Desa Rawa pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.</p>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $surat->suratType->nama }}</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2cm 2.5cm; }
        body { font-family: 'Times New Roman', serif; font-size: 13px; line-height: 1.6; color: #000; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 20px; }
        .kop h2, .kop h3 { margin: 0; }
        .kop h2 { font-size: 16px; text-transform: uppercase; }
        .kop h3 { font-size: 20px; text-transform: uppercase; font-weight: bold; }
        .kop p { margin: 2px 0; font-size: 11px; }
        .judul { text-align: center; margin: 20px 0; }
        .judul h4 { text-decoration: underline; text-transform: uppercase; margin: 0; font-size: 14px; }
        .judul p { margin: 2px 0; }
        table.data { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table.data td { padding: 3px 6px; vertical-align: top; }
        table.data td.label { width: 200px; }
        .isi { text-align: justify; margin: 20px 0; }
        .ttd { margin-top: 50px; width: 100%; }
        .ttd .kanan { float: right; text-align: center; width: 280px; }
        .ttd .nama-ttd { text-decoration: underline; font-weight: bold; margin-top: 70px; }
        .clearfix::after { content: ""; display: table; clear: both; }
    </style>
</head>
<body>

    <div class="kop">
        <h2>PEMERINTAH KABUPATEN {{ strtoupper($desa['kabupaten']) }}</h2>
        <h2>KECAMATAN {{ strtoupper($desa['kecamatan']) }}</h2>
        <h3>{{ strtoupper($desa['nama']) }}</h3>
        <p>{{ $desa['alamat_kantor'] }}</p>
    </div>

    <div class="judul">
        <h4>{{ strtoupper($surat->suratType->nama) }}</h4>
        <p>Nomor: {{ $nomorSurat }}</p>
    </div>

    <div class="isi">
        <p>Yang bertanda tangan di bawah ini, Kepala Desa {{ $desa['nama'] }}, Kecamatan {{ $desa['kecamatan'] }}, Kabupaten {{ $desa['kabupaten'] }}, dengan ini menerangkan bahwa:</p>
    </div>

    <table class="data">
        <tr><td class="label">Nama Lengkap</td><td>: {{ $warga->nama }}</td></tr>
        <tr><td class="label">NIK</td><td>: {{ $warga->nik }}</td></tr>
        <tr><td class="label">Tempat, Tanggal Lahir</td><td>: {{ $warga->tempat_lahir }}, {{ $warga->tanggal_lahir->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="label">Jenis Kelamin</td><td>: {{ $warga->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
        <tr><td class="label">Pekerjaan</td><td>: {{ $warga->pekerjaan ?: 'Tidak/Belum Bekerja' }}</td></tr>
        <tr><td class="label">Alamat</td><td>: {{ $warga->alamat }}, {{ $warga->dusun->nama_dusun }}, Desa {{ $desa['nama'] }}</td></tr>
        @if($warga->nama_ayah || $warga->nama_ibu)
        <tr><td class="label">Nama Orang Tua</td><td>: {{ $warga->nama_ayah ?? '-' }} (Ayah) / {{ $warga->nama_ibu ?? '-' }} (Ibu)</td></tr>
        @endif
    </table>

    <div class="isi">
        <p>
            Adalah benar warga Desa {{ $desa['nama'] }} yang terdaftar dalam data kependudukan desa. Surat keterangan ini
            diterbitkan untuk keperluan: <strong>{{ $surat->alasan }}</strong>.
        </p>
        <p>
            Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="ttd clearfix">
        <div class="kanan">
            <p>{{ $desa['kabupaten'] }}, {{ $tanggalCetak }}</p>
            <p>Kepala Desa {{ $desa['nama'] }}</p>
            <p class="nama-ttd">{{ $desa['kepala_desa'] }}</p>
        </div>
    </div>

</body>
</html>

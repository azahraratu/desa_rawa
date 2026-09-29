@php $w = $warga ?? null; @endphp

<div class="alert alert-secondary small d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-info-circle"></i>
    <span>Kolom bertanda <span class="text-danger fw-bold">*</span> wajib diisi. Kolom lainnya bersifat opsional dan boleh dikosongkan.</span>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NIK <span class="text-danger">*</span></label>
        <input type="text" name="nik" class="form-control" maxlength="16" required value="{{ old('nik', $w->nik ?? '') }}">
        <div class="form-text">
            <strong>Untuk bayi baru lahir:</strong> daftarkan di sini <em>setelah</em> NIK resmi terbit dari Disdukcapil
            (setelah proses Akta Kelahiran selesai). Gunakan No. KK yang <strong>sama dengan orang tua</strong> agar
            otomatis terhubung sebagai satu keluarga. Alur lengkap: Warga ajukan "Surat Keterangan Kelahiran" lewat
            akun mereka (sebagai syarat ke Disdukcapil) &rarr; Disdukcapil terbitkan Akta & NIK &rarr; baru daftarkan bayi di sini.
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
        <input type="text" name="no_kk" class="form-control" maxlength="16" required value="{{ old('no_kk', $w->no_kk ?? '') }}">
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control" required value="{{ old('nama', $w->nama ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="L" {{ old('jenis_kelamin', $w->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $w->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="col-md-8">
        <label class="form-label">Status Hubungan dalam Keluarga (sesuai KK) <span class="text-danger">*</span></label>
        <select name="status_hubungan_keluarga" class="form-select" required>
            @foreach(\App\Models\Warga::labelHubunganKeluarga() as $val => $label)
                <option value="{{ $val }}" {{ old('status_hubungan_keluarga', $w->status_hubungan_keluarga ?? 'anak') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div class="form-text">Setiap KK sebaiknya hanya punya 1 Kepala Keluarga. Ini membantu identifikasi struktur keluarga sesuai kartu keluarga asli.</div>
    </div>
    <div class="col-md-4">
        <label class="form-label">RT <span class="text-muted small fw-normal">(opsional)</span></label>
        <select name="rt_id" class="form-select">
            <option value="">- Belum ditentukan -</option>
            @foreach(\Illuminate\Support\Facades\Auth::user()->dusunDipimpin->rts as $rt)
                <option value="{{ $rt->id }}" {{ (int) old('rt_id', $w->rt_id ?? 0) === $rt->id ? 'selected' : '' }}>RT {{ $rt->nama_rt }}</option>
            @endforeach
        </select>
        @if(\Illuminate\Support\Facades\Auth::user()->dusunDipimpin->rts->isEmpty())
            <div class="form-text text-danger">Belum ada RT. <a href="{{ route('kadus.rt.create') }}">Tambah RT dulu</a>.</div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Tempat Lahir <span class="text-muted small fw-normal">(opsional)</span></label>
        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $w->tempat_lahir ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date" name="tanggal_lahir" class="form-control" required value="{{ old('tanggal_lahir', isset($w) ? $w->tanggal_lahir->format('Y-m-d') : '') }}">
        <div class="form-text">Akan menjadi password awal login warga (format DDMMYYYY).</div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Ayah Kandung <span class="text-muted small fw-normal">(opsional)</span></label>
        <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $w->nama_ayah ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Ibu Kandung <span class="text-muted small fw-normal">(opsional)</span></label>
        <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $w->nama_ibu ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat <span class="text-muted small fw-normal">(opsional)</span></label>
        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $w->alamat ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor HP / WhatsApp <span class="text-muted small fw-normal">(opsional)</span></label>
        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $w->no_hp ?? '') }}">
        <div class="form-text">Diisi nomor milik warga yang bersangkutan (bukan nomor Kepala Dusun), supaya notifikasi WhatsApp permohonan surat terkirim ke orang yang tepat.</div>
    </div>
    <div class="col-md-6">
        <label class="form-label">Pekerjaan <span class="text-danger">*</span></label>
        <input type="text" name="pekerjaan" class="form-control" required value="{{ old('pekerjaan', $w->pekerjaan ?? '') }}" placeholder="Contoh: Petani, Wiraswasta, Tidak/Belum Bekerja">
        <div class="form-text">Wajib diisi agar muncul dengan benar pada dokumen surat yang diterbitkan (mis. Surat Keterangan Usaha). Isi "Tidak/Belum Bekerja" bila memang belum bekerja.</div>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status Ekonomi <span class="text-danger">*</span></label>
        <select name="status_ekonomi" class="form-select" required>
            @foreach(['mampu' => 'Mampu', 'kurang_mampu' => 'Kurang Mampu', 'tidak_mampu' => 'Tidak Mampu'] as $val => $label)
                <option value="{{ $val }}" {{ old('status_ekonomi', $w->status_ekonomi ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Status Pernikahan <span class="text-danger">*</span></label>
        <select name="status_pernikahan" class="form-select" required>
            @foreach(['belum_menikah' => 'Belum Menikah', 'menikah' => 'Menikah', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'] as $val => $label)
                <option value="{{ $val }}" {{ old('status_pernikahan', $w->status_pernikahan ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
        <select name="status_pendidikan" class="form-select" required>
            @foreach(['tidak_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA/SMK', 'diploma' => 'Diploma', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3'] as $val => $label)
                <option value="{{ $val }}" {{ old('status_pendidikan', $w->status_pendidikan ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

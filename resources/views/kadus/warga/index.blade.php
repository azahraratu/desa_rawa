@extends('layouts.app')

@section('title', 'Data Warga')
@section('page-title', 'Data Kependudukan')

@section('content')
@unless(auth()->user()->warga_id)
<div class="alert alert-info small d-flex align-items-start gap-2">
    <i class="bi bi-info-circle-fill mt-1"></i>
    <div>Anda belum menautkan profil pribadi. Cari data diri Anda di tabel bawah (atau tambahkan dulu lewat "Tambah Warga"), lalu klik tombol <i class="bi bi-person-check"></i> di baris data Anda supaya bisa mengajukan surat untuk diri sendiri.</div>
</div>
@endunless

@if($rts->isNotEmpty())
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="fw-bold mb-0">Lihat per RT</h6>
    @if(request('rt_id'))
        <a href="{{ route('kadus.warga.index') }}" class="small">Tampilkan Semua RT &times;</a>
    @endif
</div>
<div class="row g-2 mb-3">
    @foreach($rts as $rt)
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('kadus.warga.index', ['rt_id' => $rt->id]) }}" class="text-decoration-none">
            <div class="card p-3 text-center h-100 {{ (string) request('rt_id') === (string) $rt->id ? 'border-success border-2' : '' }}">
                <div class="fw-bold" style="color:var(--primary-700);">RT {{ $rt->nama_rt }}</div>
                <div class="small text-muted">{{ $rt->warga_count }} warga</div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-warning small">Anda belum punya data RT. <a href="{{ route('kadus.rt.create') }}">Tambah RT dulu</a> supaya warga bisa dikelompokkan.</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <form method="GET" id="form-cari-warga" class="d-flex gap-2 flex-wrap">
        <input type="hidden" name="rt_id" value="{{ request('rt_id') }}">
        <input type="text" name="cari" id="input-cari-warga" class="form-control" placeholder="Ketik nama atau nomor RT..." value="{{ request('cari') }}" style="width:220px;" autocomplete="off">
        <select name="status" class="form-select" style="width:170px;" onchange="this.form.submit()">
            <option value="hidup" {{ request('status','hidup')==='hidup'?'selected':'' }}>Warga Hidup</option>
            <option value="meninggal" {{ request('status')==='meninggal'?'selected':'' }}>Sudah Meninggal</option>
            <option value="pindah" {{ request('status')==='pindah'?'selected':'' }}>Sudah Pindah</option>
        </select>
    </form>
    <div>
        <a href="{{ route('kadus.warga.import.form') }}" class="btn btn-outline-success me-2"><i class="bi bi-upload me-1"></i>Import Excel</a>
        <a href="{{ route('kadus.warga.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah Warga</a>
    </div>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>NIK</th><th>Nama</th><th>RT</th><th>No KK</th><th>JK</th><th>Hub. Keluarga</th><th>Status</th><th>Akun</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($warga as $w)
                <tr>
                    <td>{{ $w->nik }}</td>
                    <td>
                        {{ $w->nama }}
                        @if(auth()->user()->warga_id === $w->id)
                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle ms-1"><i class="bi bi-person-check-fill"></i> Profil Saya</span>
                        @endif
                    </td>
                    <td>{{ $w->rt ? 'RT '.$w->rt->nama_rt : '-' }}</td>
                    <td>{{ $w->no_kk }}</td>
                    <td>{{ $w->jenis_kelamin }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $w->labelHubunganKeluargaSaya() }}</span></td>
                    <td>
                        @if($w->status_kependudukan === 'meninggal')
                            <span class="badge bg-secondary">Meninggal</span>
                        @elseif($w->status_kependudukan === 'pindah')
                            <span class="badge bg-info text-dark">Pindah</span>
                        @else
                            <span class="badge bg-success">Hidup</span>
                        @endif
                    </td>
                    <td>
                        @if($w->sudahRegistrasi())
                            <span class="badge bg-info text-dark">Terdaftar</span>
                        @else
                            <span class="badge bg-light text-dark border">Belum</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('kadus.warga.edit', $w) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        @if($w->status_kependudukan === 'hidup')
                        @if(auth()->user()->warga_id !== $w->id)
                        <form method="POST" action="{{ route('kadus.warga.jadikan-profil-saya', $w) }}" onsubmit="return confirm('Jadikan {{ $w->nama }} sebagai profil pribadi Anda? Anda akan bisa mengajukan surat untuk diri sendiri lewat data ini.')">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary" title="Jadikan Profil Saya"><i class="bi bi-person-check"></i></button>
                        </form>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalPindah{{ $w->id }}">Pindah</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalMeninggal{{ $w->id }}">Meninggal</button>
                        @endif
                    </td>
                </tr>

                <div class="modal fade" id="modalMeninggal{{ $w->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('kadus.warga.meninggal', $w) }}">
                                @csrf
                                <div class="modal-header">
                                    <h6 class="modal-title">Tandai {{ $w->nama }} Meninggal Dunia</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Tanggal Meninggal</label>
                                    <input type="date" name="tanggal_meninggal" class="form-control" required max="{{ date('Y-m-d') }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalPindah{{ $w->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('kadus.warga.pindah', $w) }}">
                                @csrf
                                <div class="modal-header">
                                    <h6 class="modal-title">Tandai {{ $w->nama }} Pindah Domisili</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Tanggal Pindah</label>
                                    <input type="date" name="tanggal_pindah" class="form-control mb-3" required max="{{ date('Y-m-d') }}">
                                    <label class="form-label">Alamat Tujuan (opsional)</label>
                                    <input type="text" name="alamat_tujuan_pindah" class="form-control" placeholder="Contoh: Kec. X, Kab. Y">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data warga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $warga->links() }}</div>
</div>

@push('scripts')
<script>
// Pencarian otomatis (tanpa perlu klik tombol) - submit form 500ms setelah berhenti mengetik.
(function () {
    const input = document.getElementById('input-cari-warga');
    const form = document.getElementById('form-cari-warga');
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 500);
    });
})();
</script>
@endpush
@endsection

@extends('layouts.app')

@section('title', 'Data Warga - '.$dusun->nama_dusun)
@section('page-title', $dusun->nama_dusun.(request('rt_id') && $rts->firstWhere('id', (int) request('rt_id')) ? ' - RT '.$rts->firstWhere('id', (int) request('rt_id'))->nama_rt : ''))

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.warga.index') }}" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Kembali ke Semua Dusun</a>
</div>

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card tone-primary">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">Warga Aktif</div><div class="stat-value">{{ $statistik['total_hidup'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-info">
            <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
            <div><div class="stat-label">Sudah Daftar Akun</div><div class="stat-value">{{ $statistik['sudah_daftar_akun'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-gold">
            <div class="stat-icon"><i class="bi bi-signpost-split-fill"></i></div>
            <div><div class="stat-label">Pindah Domisili</div><div class="stat-value">{{ $statistik['total_pindah'] }}</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card tone-danger">
            <div class="stat-icon"><i class="bi bi-emoji-frown-fill"></i></div>
            <div><div class="stat-label">Meninggal Tercatat</div><div class="stat-value">{{ $statistik['total_meninggal'] }}</div></div>
        </div>
    </div>
</div>

@if($rts->isNotEmpty())
<div class="d-flex justify-content-between align-items-center mb-2">
    <h6 class="fw-bold mb-0">Lihat per RT</h6>
    @if(request('rt_id'))
        <a href="{{ route('admin.warga.dusun', $dusun) }}" class="small">Tampilkan Semua RT &times;</a>
    @endif
</div>
<div class="row g-2 mb-4">
    @foreach($rts as $rt)
    <div class="col-6 col-md-3 col-lg-2">
        <a href="{{ route('admin.warga.dusun', [$dusun, 'rt_id' => $rt->id]) }}" class="text-decoration-none">
            <div class="card p-3 text-center h-100 {{ (string) request('rt_id') === (string) $rt->id ? 'border-success border-2' : '' }}">
                <div class="fw-bold" style="color:var(--primary-700);">RT {{ $rt->nama_rt }}</div>
                <div class="small text-muted">{{ $rt->warga_count }} warga</div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endif

<div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <form method="GET" id="form-cari-warga" class="d-flex gap-2 flex-wrap">
            <input type="hidden" name="rt_id" value="{{ request('rt_id') }}">
            <input type="text" name="cari" id="input-cari-warga" class="form-control" placeholder="Ketik nama atau nomor RT..." value="{{ request('cari') }}" style="width:220px;" autocomplete="off">
            <select name="status" class="form-select" style="width:160px;" onchange="this.form.submit()">
                <option value="hidup" {{ request('status', 'hidup') === 'hidup' ? 'selected' : '' }}>Warga Hidup</option>
                <option value="meninggal" {{ request('status') === 'meninggal' ? 'selected' : '' }}>Sudah Meninggal</option>
                <option value="pindah" {{ request('status') === 'pindah' ? 'selected' : '' }}>Sudah Pindah</option>
            </select>
        </form>
        <a href="{{ route('admin.warga.dusun.export', $dusun) }}" class="btn btn-gold btn-sm"><i class="bi bi-file-earmark-excel-fill me-1"></i>Unduh Excel Dusun Ini</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>NIK</th><th>Nama</th><th>RT</th><th>No KK</th><th>JK</th><th>Hub. Keluarga</th><th>Status</th><th>Akun</th><th></th></tr></thead>
            <tbody>
                @forelse($warga as $w)
                <tr>
                    <td>{{ $w->nik }}</td>
                    <td>{{ $w->nama }}</td>
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
                        @if($w->sudahRegistrasi())
                        <form method="POST" action="{{ route('admin.warga.reset-password', $w) }}" onsubmit="return confirm('Reset password akun {{ $w->nama }}? Password lama akan langsung tidak berlaku.')">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary" title="Reset Password Akun"><i class="bi bi-key-fill"></i></button>
                        </form>
                        @endif
                        @if($w->status_kependudukan === 'hidup')
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalPindah{{ $w->id }}">Pindah</button>
                        @endif
                    </td>
                </tr>

                <div class="modal fade" id="modalPindah{{ $w->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.warga.pindah', $w) }}">
                                @csrf
                                <div class="modal-header">
                                    <h6 class="modal-title">Tandai {{ $w->nama }} Pindah Domisili</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Tanggal Pindah</label>
                                    <input type="date" name="tanggal_pindah" class="form-control mb-3" required max="{{ date('Y-m-d') }}">
                                    <label class="form-label">Alamat Tujuan (opsional)</label>
                                    <input type="text" name="alamat_tujuan_pindah" class="form-control">
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
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $warga->links() }}</div>
</div>

@push('scripts')
<script>
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

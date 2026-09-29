@extends('layouts.app')

@section('title', 'Kelola Dusun')
@section('page-title', 'Kelola Dusun & Akun Kepala Dusun')

@section('content')
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Tambah/ubah/hapus dusun, dan kelola akun Kepala Dusun-nya langsung dari sini.</p>
    <a href="{{ route('admin.dusun.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah Dusun</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Nama Dusun</th><th>Warga Aktif</th><th>Kepala Dusun</th><th>Aksi Dusun</th></tr></thead>
            <tbody>
                @forelse($dusuns as $d)
                <tr>
                    <td class="fw-semibold">{{ $d->nama_dusun }}</td>
                    <td>{{ $d->warga_count }}</td>
                    <td>
                        @if($d->kepalaDusun)
                            {{ $d->kepalaDusun->name }}
                            @if($d->kepalaDusun->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis ms-1">Aktif</span>
                            @else
                                <span class="badge bg-secondary ms-1">Nonaktif</span>
                            @endif
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Belum ada akun</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('admin.dusun.edit', $d) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="{{ route('admin.dusun.destroy', $d) }}" onsubmit="return confirm('Hapus dusun {{ $d->nama_dusun }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                    <td class="d-flex gap-1">
                        @if($d->kepalaDusun)
                            <form method="POST" action="{{ route('admin.kadus.reset-password', $d->kepalaDusun) }}" onsubmit="return confirm('Reset password akun {{ $d->kepalaDusun->name }}?')">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary" title="Reset Password"><i class="bi bi-key-fill"></i></button>
                            </form>
                            <form method="POST" action="{{ route('admin.kadus.toggle', $d->kepalaDusun) }}" onsubmit="return confirm('Ubah status aktif akun {{ $d->kepalaDusun->name }}?')">
                                @csrf
                                <button class="btn btn-sm {{ $d->kepalaDusun->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $d->kepalaDusun->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="bi {{ $d->kepalaDusun->is_active ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('admin.kadus.create', ['dusun_id' => $d->id, 'balik_ke' => 'dusun']) }}" class="btn btn-sm btn-gold">
                                <i class="bi bi-person-plus-fill me-1"></i>Buat Akun
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data dusun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Akun Kepala Dusun')
@section('page-title', 'Kelola Akun Kepala Dusun')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <p class="text-muted mb-0">
        Hanya Admin yang dapat membuat akun Kepala Dusun. Role sudah terkunci sejak akun dibuat.
        Bisa juga dikelola langsung dari halaman <a href="{{ route('admin.dusun.index') }}">Kelola Dusun</a>.
    </p>
    <a href="{{ route('admin.kadus.create') }}" class="btn btn-success"><i class="bi bi-person-plus-fill me-1"></i>Buat Akun Kepala Dusun</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Nama</th><th>NIK</th><th>Dusun</th><th>No HP</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($kadusList as $k)
                <tr>
                    <td>{{ $k->name }}</td>
                    <td>{{ $k->nik }}</td>
                    <td>{{ $k->dusunDipimpin->nama_dusun ?? '-' }}</td>
                    <td>{{ $k->no_hp }}</td>
                    <td>
                        @if($k->is_active)
                            <span class="badge bg-success-subtle text-success-emphasis">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                        <form method="POST" action="{{ route('admin.kadus.reset-password', $k) }}" onsubmit="return confirm('Reset password akun {{ $k->name }}?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary" title="Reset Password"><i class="bi bi-key-fill"></i></button>
                        </form>
                        <form method="POST" action="{{ route('admin.kadus.toggle', $k) }}" onsubmit="return confirm('Ubah status aktif akun {{ $k->name }}?')">
                            @csrf
                            <button class="btn btn-sm {{ $k->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $k->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                <i class="bi {{ $k->is_active ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada akun Kepala Dusun.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Audit Log')
@section('page-title', 'Jejak Audit Sistem')

@section('content')
<div class="mb-3">
    <div class="btn-group flex-wrap">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('aksi') ? 'active' : '' }}">Semua</a>
        @foreach($daftarAksi as $aksi)
            <a href="{{ route('admin.audit.index', ['aksi' => $aksi]) }}" class="btn btn-sm btn-outline-secondary {{ request('aksi') === $aksi ? 'active' : '' }}">{{ ucfirst(str_replace('_',' ',$aksi)) }}</a>
        @endforeach
    </div>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Waktu</th><th>Aksi</th><th>Pelaku</th><th>Objek</th><th>Keterangan</th><th>IP</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td class="small text-muted text-nowrap">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                    <td><span class="badge bg-light text-dark border">{{ str_replace('_',' ',$log->aksi) }}</span></td>
                    <td>{{ $log->causer_nama ?? 'Sistem' }} <span class="text-muted small">({{ $log->causer_role ?? '-' }})</span></td>
                    <td class="small">{{ class_basename($log->subjek_type ?? '') }} #{{ $log->subjek_id }}</td>
                    <td class="small">{{ $log->keterangan }}</td>
                    <td class="small text-muted">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada catatan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
    {{ $logs->links('pagination::bootstrap-5') }}
</div>
</div>
@endsection

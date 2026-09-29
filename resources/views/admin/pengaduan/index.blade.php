@extends('layouts.app')

@section('title', 'Pengaduan')
@section('page-title', 'Kelola Pengaduan Masyarakat')

@section('content')
<div class="mb-3">
    <div class="btn-group">
        <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(\App\Models\Pengaduan::labelStatus() as $key => $label)
            <a href="{{ route('admin.pengaduan.index', ['status' => $key]) }}" class="btn btn-sm btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="row g-3">
    @forelse($pengaduans as $p)
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">{{ $p->judul }}</h6>
                <span class="badge {{ $p->badgeClass() }}">{{ \App\Models\Pengaduan::labelStatus()[$p->status] }}</span>
            </div>
            <p class="small text-muted mb-1">{{ $p->pelapor->name }} {{ $p->dilaporkan_oleh_kadus ? '(via Kepala Dusun)' : '' }} &middot; {{ $p->created_at->translatedFormat('d M Y') }}</p>
            <p class="small mb-2">{{ Str::limit($p->deskripsi, 100) }}</p>
            <a href="{{ route('admin.pengaduan.show', $p) }}" class="btn btn-sm btn-success mt-auto">Tanggapi</a>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card p-4 text-center text-muted">Tidak ada pengaduan.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $pengaduans->links() }}</div>
@endsection

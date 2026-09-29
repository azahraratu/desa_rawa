@extends('layouts.app')

@section('title', 'Pengaduan Warga')
@section('page-title', 'Pengaduan Warga di Dusun Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="btn-group">
        <a href="{{ route('kadus.pengaduan.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(\App\Models\Pengaduan::labelStatus() as $key => $label)
            <a href="{{ route('kadus.pengaduan.index', ['status' => $key]) }}" class="btn btn-sm btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
    <a href="{{ route('kadus.pengaduan.create') }}" class="btn btn-sm btn-success"><i class="bi bi-megaphone-fill me-1"></i>Laporkan Keluhan Warga</a>
</div>

<div class="row g-3">
    @forelse($pengaduans as $p)
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">{{ $p->judul }}</h6>
                <span class="badge {{ $p->badgeClass() }}">{{ \App\Models\Pengaduan::labelStatus()[$p->status] }}</span>
            </div>
            <p class="small text-muted mb-1">{{ $p->pelapor->name }} {{ $p->dilaporkan_oleh_kadus ? '(dilaporkan oleh Anda)' : '(dilaporkan langsung oleh warga)' }} &middot; {{ $p->created_at->translatedFormat('d M Y') }}</p>
            <p class="small mb-2">{{ Str::limit($p->deskripsi, 100) }}</p>
            <a href="{{ route('kadus.pengaduan.show', $p) }}" class="btn btn-sm btn-success mt-auto">Lihat &amp; Tanggapi</a>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card p-4 text-center text-muted">Belum ada pengaduan dari warga di dusun Anda.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $pengaduans->links() }}</div>
@endsection

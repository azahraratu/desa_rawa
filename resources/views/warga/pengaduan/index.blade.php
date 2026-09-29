@extends('layouts.app')

@section('title', 'Pengaduan')
@section('page-title', 'Pengaduan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Riwayat Pengaduan</h5>
    <a href="{{ route('warga.pengaduan.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Buat Pengaduan</a>
</div>

<div class="row g-3">
    @forelse($pengaduans as $p)
    <div class="col-md-6">
        <div class="card p-3 h-100">
            <div class="d-flex justify-content-between">
                <h6 class="fw-bold">{{ $p->judul }}</h6>
                <span class="badge {{ $p->badgeClass() }}">{{ \App\Models\Pengaduan::labelStatus()[$p->status] }}</span>
            </div>
            <p class="small text-muted mb-1">{{ ucfirst($p->kategori) }} &middot; {{ $p->created_at->translatedFormat('d M Y') }}</p>
            <p class="small mb-2">{{ Str::limit($p->deskripsi, 100) }}</p>
            <a href="{{ route('warga.pengaduan.show', $p) }}" class="btn btn-sm btn-outline-secondary mt-auto">Lihat Detail & Tanggapan</a>
        </div>
    </div>
    @empty
    <div class="col-12"><div class="card p-4 text-center text-muted">Belum ada pengaduan.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $pengaduans->links() }}</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Pengaduan')
@section('page-title', 'Detail Pengaduan')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <div class="d-flex justify-content-between">
                <h5>{{ $pengaduan->judul }}</h5>
                <span class="badge {{ $pengaduan->badgeClass() }}">{{ \App\Models\Pengaduan::labelStatus()[$pengaduan->status] }}</span>
            </div>
            <p class="small text-muted">{{ ucfirst($pengaduan->kategori) }} &middot; {{ $pengaduan->created_at->translatedFormat('d F Y, H:i') }}</p>
            <p>{{ $pengaduan->deskripsi }}</p>
            @if($pengaduan->foto_path)
            <img src="{{ asset('storage/'.$pengaduan->foto_path) }}" class="img-fluid rounded mt-2" alt="Foto pengaduan">
            @endif
        </div>
    </div>
    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Tanggapan Petugas Desa</h6>
            @forelse($pengaduan->tanggapans as $t)
            <div class="mb-3 pb-3 border-bottom">
                <div class="small text-muted">{{ $t->admin->name }} &middot; {{ $t->created_at->translatedFormat('d M Y, H:i') }}</div>
                <div>{{ $t->tanggapan }}</div>
            </div>
            @empty
            <p class="text-muted small mb-0">Belum ada tanggapan dari petugas desa.</p>
            @endforelse
        </div>
    </div>
</div>
<a href="{{ route('warga.pengaduan.index') }}" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection

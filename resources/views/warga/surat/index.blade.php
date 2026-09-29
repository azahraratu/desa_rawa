@extends('layouts.app')

@section('title', 'Permohonan Surat')
@section('page-title', 'Permohonan Surat Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Riwayat Permohonan Surat</h5>
    <a href="{{ route('warga.surat.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Ajukan Surat Baru</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Tanggal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($suratRequests as $s)
                <tr>
                    <td>{{ $s->kode_permohonan }}</td>
                    <td>{{ $s->suratType->nama }}</td>
                    <td>{{ $s->warga->nama }}</td>
                    <td>{{ $s->created_at->translatedFormat('d M Y') }}</td>
                    <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                    <td><a href="{{ route('warga.surat.show', $s) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada permohonan surat. <a href="{{ route('warga.surat.create') }}">Ajukan sekarang</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $suratRequests->links() }}</div>
</div>
@endsection

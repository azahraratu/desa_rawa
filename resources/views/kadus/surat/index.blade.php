@extends('layouts.app')

@section('title', 'Surat yang Saya Buatkan')
@section('page-title', 'Riwayat Surat yang Saya Buatkan untuk Warga')

@section('content')
<div class="alert alert-info small">
    <i class="bi bi-info-circle me-1"></i> Halaman ini hanya menampilkan surat yang <strong>Anda buatkan sendiri</strong> untuk warga.
    Permohonan yang diajukan warga secara mandiri lewat akunnya bersifat pribadi dan tidak ditampilkan/diberitahukan ke Kepala Dusun.
</div>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <div class="btn-group flex-wrap">
        <a href="{{ route('kadus.surat.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(\App\Models\SuratRequest::labelStatus() as $key => $label)
            <a href="{{ route('kadus.surat.index', ['status' => $key]) }}" class="btn btn-sm btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
    <a href="{{ route('kadus.surat.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Buatkan Surat</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($suratRequests as $s)
                <tr>
                    <td>{{ $s->kode_permohonan }}</td>
                    <td>{{ $s->suratType->nama }}</td>
                    <td>{{ $s->warga->nama }}</td>
                    <td>{{ $s->created_at->translatedFormat('d M Y') }}</td>
                    <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                    <td><a href="{{ route('kadus.surat.show', $s) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Anda belum pernah membuatkan surat untuk warga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $suratRequests->links() }}</div>
</div>
@endsection

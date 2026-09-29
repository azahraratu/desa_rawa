@extends('layouts.app')

@section('title', 'Permohonan Surat')
@section('page-title', 'Kelola Permohonan Surat')

@section('content')
<div class="mb-3">
    <div class="btn-group">
        <a href="{{ route('admin.surat.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(\App\Models\SuratRequest::labelStatus() as $key => $label)
            <a href="{{ route('admin.surat.index', ['status' => $key]) }}" class="btn btn-sm btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Kode</th><th>Jenis Surat</th><th>Atas Nama</th><th>Pemohon</th><th>Tanggal</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($suratRequests as $s)
                <tr>
                    <td>{{ $s->kode_permohonan }}</td>
                    <td>{{ $s->suratType->nama }}</td>
                    <td>{{ $s->warga->nama }}</td>
                    <td>{{ $s->pemohon->name }} {{ $s->dibuat_oleh_kadus ? '(via Kadus)' : '' }}</td>
                    <td>{{ $s->created_at->translatedFormat('d M Y') }}</td>
                    <td><span class="badge {{ $s->badgeClass() }}">{{ $s->labelStatusSaatIni() }}</span></td>
                    <td><a href="{{ route('admin.surat.show', $s) }}" class="btn btn-sm btn-outline-secondary">Kelola</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada permohonan surat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $suratRequests->links() }}</div>
</div>
@endsection

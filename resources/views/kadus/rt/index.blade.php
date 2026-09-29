@extends('layouts.app')

@section('title', 'Kelola RT')
@section('page-title', 'Kelola RT (Rukun Tetangga)')

@section('content')
@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted mb-0">Kelola daftar RT di dusun Anda. Warga dapat dikelompokkan per RT untuk memudahkan pendataan.</p>
    <a href="{{ route('kadus.rt.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Tambah RT</a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>RT</th><th>Ketua RT</th><th>Jumlah Warga Aktif</th><th></th></tr></thead>
            <tbody>
                @forelse($rts as $rt)
                <tr>
                    <td class="fw-semibold">RT {{ $rt->nama_rt }}</td>
                    <td>{{ $rt->ketua_rt ?? '-' }}</td>
                    <td>{{ $rt->warga_count }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('kadus.rt.edit', $rt) }}" class="btn btn-sm btn-outline-secondary">Ubah</a>
                        <form method="POST" action="{{ route('kadus.rt.destroy', $rt) }}" onsubmit="return confirm('Hapus RT {{ $rt->nama_rt }}?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada RT terdaftar. Tambahkan RT dulu sebelum mengelompokkan data warga.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

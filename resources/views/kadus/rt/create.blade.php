@extends('layouts.app')

@section('title', 'Tambah RT')
@section('page-title', 'Tambah RT Baru')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('kadus.rt.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama/Nomor RT</label>
            <input type="text" name="nama_rt" class="form-control" required value="{{ old('nama_rt') }}" placeholder="Contoh: 01 atau 20">
        </div>
        <div class="mb-3">
            <label class="form-label">Ketua RT (opsional)</label>
            <input type="text" name="ketua_rt" class="form-control" value="{{ old('ketua_rt') }}">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan RT</button>
    </form>
</div>
@endsection

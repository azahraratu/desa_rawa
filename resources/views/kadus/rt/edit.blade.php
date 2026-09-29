@extends('layouts.app')

@section('title', 'Ubah RT')
@section('page-title', 'Ubah Data RT')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('kadus.rt.update', $rt) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama/Nomor RT</label>
            <input type="text" name="nama_rt" class="form-control" required value="{{ old('nama_rt', $rt->nama_rt) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Ketua RT (opsional)</label>
            <input type="text" name="ketua_rt" class="form-control" value="{{ old('ketua_rt', $rt->ketua_rt) }}">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
    </form>
</div>
@endsection

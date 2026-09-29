@extends('layouts.app')

@section('title', 'Ubah Dusun')
@section('page-title', 'Ubah Data Dusun')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('admin.dusun.update', $dusun) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama Dusun</label>
            <input type="text" name="nama_dusun" class="form-control" required value="{{ old('nama_dusun', $dusun->nama_dusun) }}">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
    </form>
</div>
@endsection

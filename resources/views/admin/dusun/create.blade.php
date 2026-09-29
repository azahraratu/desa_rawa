@extends('layouts.app')

@section('title', 'Tambah Dusun')
@section('page-title', 'Tambah Dusun Baru')

@section('content')
<div class="card p-4" style="max-width:500px;">
    <form method="POST" action="{{ route('admin.dusun.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Dusun</label>
            <input type="text" name="nama_dusun" class="form-control" required value="{{ old('nama_dusun') }}" placeholder="Contoh: Dusun Bojong">
        </div>
        <button type="submit" class="btn btn-success w-100">Simpan Dusun</button>
    </form>
</div>
@endsection

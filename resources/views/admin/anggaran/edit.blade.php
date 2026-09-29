@extends('layouts.app')

@section('title', 'Ubah Data Anggaran')
@section('page-title', 'Ubah Data Anggaran Desa')

@section('content')
<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('admin.anggaran.update', $anggaran) }}">
        @csrf
        @method('PUT')
        @include('admin.anggaran._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('admin.anggaran.index') }}" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
@endsection

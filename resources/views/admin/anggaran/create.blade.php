@extends('layouts.app')

@section('title', 'Tambah Data Anggaran')
@section('page-title', 'Tambah Data Anggaran Desa')

@section('content')
<div class="card p-4" style="max-width:700px;">
    <form method="POST" action="{{ route('admin.anggaran.store') }}">
        @csrf
        @include('admin.anggaran._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Data Anggaran</button>
            <a href="{{ route('admin.anggaran.index') }}" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
@endsection

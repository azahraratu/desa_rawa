@extends('layouts.app')

@section('title', 'Tambah Data Warga')
@section('page-title', 'Tambah Data Warga')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ route('kadus.warga.store') }}">
        @csrf
        @include('kadus.warga._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Data Warga</button>
            <a href="{{ route('kadus.warga.index') }}" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
@endsection

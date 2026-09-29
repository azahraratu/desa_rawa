@extends('layouts.app')

@section('title', 'Ubah Data Warga')
@section('page-title', 'Ubah Data Warga')

@section('content')
<div class="card p-4">
    <form method="POST" action="{{ route('kadus.warga.update', $warga) }}">
        @csrf
        @method('PUT')
        @include('kadus.warga._form')
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('kadus.warga.index') }}" class="btn btn-outline-secondary">Batal / Kembali</a>
        </div>
    </form>
</div>
@endsection

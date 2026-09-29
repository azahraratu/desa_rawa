@extends('layouts.guest')

@section('title', 'Lupa Password')

@section('content')
<div class="form-icon-badge"><i class="bi bi-question-circle"></i></div>
<h4 class="fw-bold mb-1">Lupa Password?</h4>
<p class="text-muted small mb-4">Sistem ini tidak mengirim reset password lewat email/SMS otomatis. Password Anda dapat direset langsung oleh petugas berikut:</p>

<div class="card p-3 mb-3">
    <div class="d-flex gap-2 align-items-start">
        <i class="bi bi-person-badge-fill fs-4" style="color:var(--primary-600);"></i>
        <div>
            <strong>Warga</strong>
            <p class="small text-muted mb-0">Hubungi <strong>Kepala Dusun</strong> Anda untuk meminta reset password. Kepala Dusun (atau Admin) dapat men-generate password baru untuk Anda melalui sistem.</p>
        </div>
    </div>
</div>

<div class="card p-3 mb-4">
    <div class="d-flex gap-2 align-items-start">
        <i class="bi bi-shield-lock-fill fs-4" style="color:var(--gold-500);"></i>
        <div>
            <strong>Kepala Dusun</strong>
            <p class="small text-muted mb-0">Hubungi <strong>Admin Desa</strong> untuk meminta reset password akun Kepala Dusun Anda.</p>
        </div>
    </div>
</div>

<a href="{{ route('login') }}" class="btn btn-success w-100">Kembali ke Halaman Login</a>
@endsection

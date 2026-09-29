@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="avatar" style="width:56px;height:56px;font-size:1.4rem;border-radius:14px;background:linear-gradient(135deg, var(--primary-400), var(--primary-500));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="fw-bold fs-5">{{ $user->name }}</div>
                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
            <hr>
            <dl class="row mb-0 small">
                @if($user->nik)
                    <dt class="col-5 text-muted">NIK</dt>
                    <dd class="col-7">{{ $user->nik }}</dd>
                @endif
                @if($user->isKadus() && $user->dusunDipimpin)
                    <dt class="col-5 text-muted">Dusun</dt>
                    <dd class="col-7">{{ $user->dusunDipimpin->nama_dusun }}</dd>
                @endif
                @if($user->isWarga() && $user->warga)
                    <dt class="col-5 text-muted">Sesuai Data Warga</dt>
                    <dd class="col-7">{{ $user->warga->nama }}</dd>
                    <dt class="col-5 text-muted">Dusun</dt>
                    <dd class="col-7">{{ $user->warga->dusun->nama_dusun ?? '-' }}</dd>
                @endif
                <dt class="col-5 text-muted">Bergabung Sejak</dt>
                <dd class="col-7">{{ $user->created_at->translatedFormat('d F Y') }}</dd>
            </dl>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold mb-1"><i class="bi bi-person-lines-fill me-1"></i> Lengkapi Data Akun</h6>
            <p class="text-muted small mb-3">Nomor HP/WhatsApp dipakai untuk menerima notifikasi status permohonan surat. Email bersifat opsional, dapat ditautkan untuk keperluan pemulihan akun di kemudian hari.</p>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 081234567890">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-muted small fw-normal">(opsional)</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="nama@email.com">
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Simpan Data</button>
            </form>
        </div>

        <div class="card p-4">
            <h6 class="fw-bold mb-1"><i class="bi bi-shield-lock-fill me-1"></i> Ganti Password</h6>
            <p class="text-muted small mb-3">Gunakan kombinasi huruf besar, huruf kecil, dan angka, minimal 8 karakter.</p>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" required minlength="8" data-invalid-msg="Password baru minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8" data-invalid-msg="Konfirmasi password tidak boleh kosong dan harus sama dengan password baru.">
                    </div>
                </div>
                <button type="submit" class="btn btn-success mt-3">Ganti Password</button>
            </form>
        </div>
    </div>
</div>
@endsection

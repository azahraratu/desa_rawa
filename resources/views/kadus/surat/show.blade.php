@extends('layouts.app')

@section('title', 'Detail Permohonan Surat')
@section('page-title', 'Detail Permohonan Surat')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="mb-3">{{ $surat->suratType->nama }}</h5>
            <table class="table table-borderless table-sm mb-0">
                <tr><td class="text-muted" style="width:160px;">Kode Permohonan</td><td>: {{ $surat->kode_permohonan }}</td></tr>
                <tr><td class="text-muted">Atas Nama</td><td>: {{ $surat->warga->nama }} ({{ $surat->warga->nik }})</td></tr>
                <tr><td class="text-muted">Dibuatkan Oleh</td><td>: Anda (Kepala Dusun), atas nama warga</td></tr>
                <tr><td class="text-muted">Alasan</td><td>: {{ $surat->alasan }}</td></tr>
                <tr><td class="text-muted">Tanggal Ajukan</td><td>: {{ $surat->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                <tr><td class="text-muted">Status</td><td>: <span class="badge {{ $surat->badgeClass() }}">{{ $surat->labelStatusSaatIni() }}</span></td></tr>
                @if($surat->status === 'ditolak')
                <tr><td class="text-muted">Alasan Penolakan</td><td class="text-danger">: {{ $surat->catatan_admin }}</td></tr>
                @endif
                @if($surat->lampirans->count() > 0)
                <tr><td class="text-muted align-top">Lampiran</td><td>:
                    <ul class="mb-0 ps-3">
                        @foreach($surat->lampirans as $lampiran)
                        <li>{{ $lampiran->nama_asli ?? 'Lampiran '.$loop->iteration }}</li>
                        @endforeach
                    </ul>
                </td></tr>
                @endif
            </table>

            @if(in_array($surat->status, ['ditandatangani','selesai']) && $surat->file_path)
            <a href="{{ route('kadus.surat.download', $surat) }}" class="btn btn-success mt-3">
                <i class="bi bi-download me-1"></i> Unduh Dokumen Surat (PDF)
            </a>
            @endif
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Riwayat & Notifikasi</h6>
            <ul class="list-unstyled mb-0">
                @foreach($surat->logs as $log)
                <li class="timeline-item">
                    <div class="fw-semibold small">{{ \App\Models\SuratRequest::labelStatus()[$log->status] ?? $log->status }}</div>
                    <div class="text-muted small">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</div>
                    <div class="small mt-1">{{ $log->keterangan }}</div>
                    <div class="small mt-1">
                        @if($log->wa_terkirim)
                            <span class="text-success"><i class="bi bi-whatsapp"></i> Notifikasi WA terkirim</span>
                        @else
                            <span class="text-muted"><i class="bi bi-whatsapp"></i> WA gagal/simulasi</span>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<a href="{{ route('kadus.surat.index') }}" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali ke daftar</a>
@endsection

@extends('layouts.app')

@section('title', 'Kelola Permohonan Surat')
@section('page-title', 'Kelola Permohonan Surat')

@section('content')
<div class="row g-3">
    <div class="col-md-7">
        <div class="card p-4">
            <h5 class="mb-3">{{ $surat->suratType->nama }}</h5>
            <table class="table table-borderless table-sm mb-3">
                <tr><td class="text-muted" style="width:180px;">Kode Permohonan</td><td>: {{ $surat->kode_permohonan }}</td></tr>
                <tr><td class="text-muted">Atas Nama</td><td>: {{ $surat->warga->nama }} ({{ $surat->warga->nik }})</td></tr>
                <tr><td class="text-muted">Dusun</td><td>: {{ $surat->warga->dusun->nama_dusun }}</td></tr>
                <tr><td class="text-muted">Pemohon Akun</td><td>: {{ $surat->pemohon->name }} {{ $surat->dibuat_oleh_kadus ? '(diajukan oleh Kepala Dusun)' : '' }}</td></tr>
                <tr>
                    <td class="text-muted">No. WhatsApp</td>
                    <td>
                        : {{ $surat->nomorWhatsappTujuan() ?? '-' }}
                        @if($surat->nomorWhatsappTujuan() && $surat->nomorWhatsappTujuan() === $surat->pemohon->no_hp && $surat->nomorWhatsappTujuan() !== $surat->warga->no_hp)
                            <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">Nomor akun pemohon (warga belum punya No. HP tercatat)</span>
                        @endif
                    </td>
                </tr>
                <tr><td class="text-muted">Alasan/Keperluan</td><td>: {{ $surat->alasan }}</td></tr>
                <tr><td class="text-muted">Persyaratan</td><td>: {{ $surat->suratType->persyaratan }}</td></tr>
                @if($surat->lampirans->count() > 0)
                <tr><td class="text-muted align-top">Lampiran</td><td>:
                    <ul class="mb-0 ps-3 ">
                        @foreach($surat->lampirans as $lampiran)
                        <li><a href="{{ route('admin.surat.lampiran', [$surat, $lampiran]) }}" target="_blank"><i class="bi bi-paperclip"></i> {{ $lampiran->nama_asli ?? 'Lampiran '.$loop->iteration }}</a></li>
                        @endforeach
                    </ul>
                </td></tr>
                @endif
                <tr><td class="text-muted">Status Saat Ini</td><td>: <span class="badge {{ $surat->badgeClass() }}">{{ $surat->labelStatusSaatIni() }}</span></td></tr>
            </table>

            <div class="d-flex flex-wrap gap-2">
                @if($surat->status === 'diajukan')
                <form method="POST" action="{{ route('admin.surat.proses', $surat) }}" data-confirm="Mulai proses permohonan surat &quot;{{ $surat->suratType->nama }}&quot; atas nama {{ $surat->warga->nama }}?" data-confirm-title="Konfirmasi Proses Surat" data-confirm-button="Ya, Proses">
                    @csrf
                    <button class="btn btn-warning"><i class="bi bi-play-circle me-1"></i>Proses Permohonan</button>
                </form>
                @endif

                @if($surat->status === 'diproses')
                <form method="POST" action="{{ route('admin.surat.tandatangani', $surat) }}" data-confirm="Tandatangani &amp; terbitkan PDF untuk surat ini? Pastikan seluruh data sudah benar." data-confirm-title="Konfirmasi Tandatangani" data-confirm-button="Ya, Tandatangani">
                    @csrf
                    <button class="btn btn-info"><i class="bi bi-pen me-1"></i>Tandatangani & Buat PDF</button>
                </form>
                @endif

                @if($surat->status === 'ditandatangani')
                <form method="POST" action="{{ route('admin.surat.selesaikan', $surat) }}" data-confirm="Tandai permohonan surat ini sebagai selesai?" data-confirm-title="Konfirmasi Selesaikan" data-confirm-button="Ya, Selesaikan">
                    @csrf
                    <button class="btn btn-success"><i class="bi bi-check-circle me-1"></i>Tandai Selesai</button>
                </form>
                @endif

                @if($surat->file_path)
                <a href="{{ route('admin.surat.download', $surat) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i>Unduh PDF</a>
                @endif

                @if(!in_array($surat->status, ['selesai','ditolak']))
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                    <i class="bi bi-x-circle me-1"></i>Tolak
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">Riwayat & Notifikasi WhatsApp</h6>
            <ul class="list-unstyled mb-0">
                @foreach($surat->logs as $log)
                <li class="mb-3 pb-3 border-bottom">
                    <div class="fw-semibold small">{{ \App\Models\SuratRequest::labelStatus()[$log->status] ?? $log->status }}</div>
                    <div class="text-muted small">{{ $log->created_at->translatedFormat('d M Y, H:i') }}</div>
                    <div class="small">{{ $log->keterangan }}</div>
                    <div class="small mt-1">
                        @if($log->wa_terkirim)
                            <span class="text-success"><i class="bi bi-whatsapp"></i> Terkirim</span>
                        @else
                            <span class="text-muted"><i class="bi bi-whatsapp"></i> Gagal/Simulasi</span>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.surat.tolak', $surat) }}">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title">Tolak Permohonan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea name="alasan_penolakan" class="form-control" rows="3" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<a href="{{ route('admin.surat.index') }}" class="btn btn-link mt-3 ps-0"><i class="bi bi-arrow-left"></i> Kembali</a>
@endsection

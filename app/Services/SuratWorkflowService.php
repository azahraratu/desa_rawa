<?php

namespace App\Services;

use App\Models\SuratRequest;
use App\Models\User;

/**
 * Mengelola perpindahan status permohonan surat beserta pencatatan log
 * dan pengiriman notifikasi WhatsApp pada setiap tahapan, sesuai proposal:
 * "setiap tahapan proses permohonan surat dikirimkan sebagai notifikasi
 * ke nomor WhatsApp warga, sementara dokumen surat tetap diunduh melalui aplikasi."
 */
class SuratWorkflowService
{
    public function __construct(protected FonnteService $fonnte) {}

    public function ajukan(SuratRequest $surat): void
    {
        $surat->update(['status' => SuratRequest::STATUS_DIAJUKAN]);

        $this->catatDanNotifikasi(
            $surat,
            SuratRequest::STATUS_DIAJUKAN,
            'Permohonan surat berhasil diajukan dan menunggu diproses oleh admin desa.'
        );
    }

    public function proses(SuratRequest $surat, User $admin): void
    {
        $surat->update([
            'status' => SuratRequest::STATUS_DIPROSES,
            'diproses_oleh' => $admin->id,
        ]);

        $this->catatDanNotifikasi(
            $surat,
            SuratRequest::STATUS_DIPROSES,
            'Permohonan surat Anda sedang diproses oleh petugas Desa Rawa.'
        );
    }

    public function tandaTangani(SuratRequest $surat): void
    {
        $surat->update(['status' => SuratRequest::STATUS_DITANDATANGANI]);

        $this->catatDanNotifikasi(
            $surat,
            SuratRequest::STATUS_DITANDATANGANI,
            'Surat Anda telah ditandatangani oleh pihak desa dan sedang disiapkan untuk diunduh.'
        );
    }

    public function selesaikan(SuratRequest $surat): void
    {
        $surat->update(['status' => SuratRequest::STATUS_SELESAI]);

        $this->catatDanNotifikasi(
            $surat,
            SuratRequest::STATUS_SELESAI,
            'Surat Anda sudah selesai diproses dan dapat diunduh melalui aplikasi Sistem Pelayanan Desa Rawa.'
        );
    }

    public function tolak(SuratRequest $surat, string $alasanPenolakan): void
    {
        $surat->update([
            'status' => SuratRequest::STATUS_DITOLAK,
            'catatan_admin' => $alasanPenolakan,
        ]);

        $this->catatDanNotifikasi(
            $surat,
            SuratRequest::STATUS_DITOLAK,
            "Mohon maaf, permohonan surat Anda ditolak.\nAlasan: {$alasanPenolakan}"
        );
    }

    protected function catatDanNotifikasi(SuratRequest $surat, string $status, string $pesanNotifikasi): void
    {
        $surat->loadMissing('pemohon', 'warga', 'suratType');

        $namaSubjek = $surat->warga?->nama ?? $surat->pemohon?->name;
        $noHp = $surat->nomorWhatsappTujuan();

        $pesanLengkap = "*Notifikasi Sistem Pelayanan Desa Rawa*\n\n".
            "Permohonan: {$surat->suratType?->nama}\n".
            "Atas nama: {$namaSubjek}\n".
            "Kode: {$surat->kode_permohonan}\n".
            "Status: {$surat->labelStatusSaatIni()}\n\n".
            $pesanNotifikasi;

        $terkirim = $this->fonnte->kirim($noHp, $pesanLengkap);

        $surat->logs()->create([
            'status' => $status,
            'keterangan' => $pesanNotifikasi,
            'wa_terkirim' => $terkirim,
        ]);
    }
}

<?php

namespace App\Services;

use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service pengiriman notifikasi WhatsApp melalui Fonnte (https://fonnte.com).
 *
 * Cara pakai:
 *   1. Daftar akun di fonnte.com, scan QR device WhatsApp.
 *   2. Salin token device, isi ke .env => FONNTE_TOKEN=xxxxx
 *   3. Set FONNTE_ENABLED=true
 *
 * Selama token belum diisi / FONNTE_ENABLED=false, pesan hanya akan dicatat
 * ke tabel whatsapp_logs (mode simulasi) sehingga alur aplikasi tetap bisa diuji
 * tanpa kuota Fonnte.
 */
class FonnteService
{
    public function kirim(?string $noHp, string $pesan): bool
    {
        $noHp = $this->normalisasiNomor($noHp);

        if (! $noHp) {
            Log::warning('FonnteService: nomor HP kosong/tidak valid, notifikasi dibatalkan.');

            return false;
        }

        $token = config('services.fonnte.token');
        $enabled = config('services.fonnte.enabled');

        // Mode simulasi: token belum disetel, hanya dicatat ke log agar alur tetap jalan.
        if (! $enabled || empty($token) || $token === 'isi-token-fonnte-kamu-disini') {
            WhatsappLog::create([
                'no_hp' => $noHp,
                'pesan' => $pesan,
                'status' => 'simulasi',
                'response' => 'FONNTE_TOKEN belum dikonfigurasi - pesan tidak benar-benar terkirim.',
            ]);

            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->post(config('services.fonnte.api_url'), [
                'target' => $noHp,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            $sukses = $response->successful();

            WhatsappLog::create([
                'no_hp' => $noHp,
                'pesan' => $pesan,
                'status' => $sukses ? 'terkirim' : 'gagal',
                'response' => $response->body(),
            ]);

            return $sukses;
        } catch (\Throwable $e) {
            Log::error('FonnteService gagal mengirim pesan: '.$e->getMessage());

            WhatsappLog::create([
                'no_hp' => $noHp,
                'pesan' => $pesan,
                'status' => 'gagal',
                'response' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Ubah nomor ke format internasional tanpa "+" (contoh: 6281234567890)
     */
    protected function normalisasiNomor(?string $noHp): ?string
    {
        if (! $noHp) {
            return null;
        }

        $noHp = preg_replace('/[^0-9]/', '', $noHp);

        if (str_starts_with($noHp, '0')) {
            $noHp = '62'.substr($noHp, 1);
        } elseif (str_starts_with($noHp, '8')) {
            $noHp = '62'.$noHp;
        }

        return $noHp;
    }
}

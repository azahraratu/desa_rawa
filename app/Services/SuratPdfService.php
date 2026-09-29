<?php

namespace App\Services;

use App\Models\SuratRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Menghasilkan dokumen PDF surat menggunakan template standar kop desa
 * (resources/views/surat/template.blade.php), lalu menyimpannya ke storage
 * sehingga dapat diunduh warga melalui aplikasi.
 */
class SuratPdfService
{
    public function generate(SuratRequest $surat): string
    {
        $surat->loadMissing('warga.dusun', 'suratType', 'pemohon');

        $nomorSurat = $this->buatNomorSurat($surat);

        $pdf = Pdf::loadView('surat.template', [
            'surat' => $surat,
            'warga' => $surat->warga,
            'nomorSurat' => $nomorSurat,
            'desa' => config('services.desa'),
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ])->setPaper('a4');

        $namaFile = 'surat/'.$surat->kode_permohonan.'-'.Str::slug($surat->suratType->kode).'.pdf';

        Storage::disk('public')->put($namaFile, $pdf->output());

        $surat->update([
            'file_path' => $namaFile,
            'nomor_surat' => $nomorSurat,
        ]);

        return $namaFile;
    }

    protected function buatNomorSurat(SuratRequest $surat): string
    {
        $urutan = str_pad((string) $surat->id, 3, '0', STR_PAD_LEFT);
        $romawiBulan = $this->romawiBulan((int) now()->format('n'));

        return "470/{$urutan}/{$surat->suratType->kode}/{$romawiBulan}/".now()->format('Y');
    }

    protected function romawiBulan(int $bulan): string
    {
        $romawi = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        return $romawi[$bulan - 1] ?? 'I';
    }
}

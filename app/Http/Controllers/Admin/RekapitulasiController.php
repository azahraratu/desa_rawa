<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dusun;
use App\Models\SuratRequest;
use App\Models\Warga;
use Illuminate\Support\Facades\Auth;

/**
 * Halaman rekapitulasi data kependudukan untuk keperluan temuan rapat/musyawarah
 * desa: total warga, sebaran tingkat pendidikan, dan status ekonomi, per dusun
 * maupun keseluruhan desa.
 */
class RekapitulasiController extends Controller
{
    public function index()
    {
        return view('admin.rekapitulasi.index', $this->kumpulkanData());
    }

    public function cetak()
    {
        return view('admin.rekapitulasi.cetak', $this->kumpulkanData() + [
            'desa' => config('services.desa'),
        ]);
    }

    protected function kumpulkanData(): array
    {
        $totalWarga = Warga::where('status_kependudukan', 'hidup')->count();
        $totalMeninggal = Warga::where('status_kependudukan', 'meninggal')->count();
        $totalPindah = Warga::where('status_kependudukan', 'pindah')->count();

        $perDusun = Dusun::withCount([
            'warga' => fn ($q) => $q->where('status_kependudukan', 'hidup'),
        ])->orderBy('nama_dusun')->get();

        $labelPendidikan = [
            'tidak_sekolah' => 'Tidak Sekolah', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA/SMK',
            'diploma' => 'Diploma', 's1' => 'S1', 's2' => 'S2', 's3' => 'S3',
        ];
        $pendidikan = Warga::where('status_kependudukan', 'hidup')
            ->selectRaw('status_pendidikan, count(*) as total')
            ->groupBy('status_pendidikan')
            ->pluck('total', 'status_pendidikan')
            ->mapWithKeys(fn ($v, $k) => [($labelPendidikan[$k] ?? $k) => $v]);

        $labelEkonomi = ['mampu' => 'Mampu', 'kurang_mampu' => 'Kurang Mampu', 'tidak_mampu' => 'Tidak Mampu'];
        $ekonomi = Warga::where('status_kependudukan', 'hidup')
            ->selectRaw('status_ekonomi, count(*) as total')
            ->groupBy('status_ekonomi')
            ->pluck('total', 'status_ekonomi')
            ->mapWithKeys(fn ($v, $k) => [($labelEkonomi[$k] ?? $k) => $v]);

        $labelPernikahan = ['belum_menikah' => 'Belum Menikah', 'menikah' => 'Menikah', 'cerai_hidup' => 'Cerai Hidup', 'cerai_mati' => 'Cerai Mati'];
        $pernikahan = Warga::where('status_kependudukan', 'hidup')
            ->selectRaw('status_pernikahan, count(*) as total')
            ->groupBy('status_pernikahan')
            ->pluck('total', 'status_pernikahan')
            ->mapWithKeys(fn ($v, $k) => [($labelPernikahan[$k] ?? $k) => $v]);

        $jenisKelamin = Warga::where('status_kependudukan', 'hidup')
            ->selectRaw('jenis_kelamin, count(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $totalSuratTahunIni = SuratRequest::whereYear('created_at', now()->year)->count();

        return compact(
            'totalWarga', 'totalMeninggal', 'totalPindah', 'perDusun',
            'pendidikan', 'ekonomi', 'pernikahan', 'jenisKelamin', 'totalSuratTahunIni'
        );
    }
}

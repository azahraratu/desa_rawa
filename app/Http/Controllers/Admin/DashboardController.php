<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\SuratRequest;
use App\Models\Warga;

class DashboardController extends Controller
{
    public function index()
    {
        $ringkasan = [
            'total_surat' => SuratRequest::count(),
            'surat_menunggu' => SuratRequest::where('status', SuratRequest::STATUS_DIAJUKAN)->count(),
            'total_pengaduan_baru' => Pengaduan::where('status', Pengaduan::STATUS_BARU)->count(),
            'total_warga' => Warga::where('status_kependudukan', 'hidup')->count(),
        ];

        // Jumlah & jenis surat yang diterbitkan (grafik)
        $suratPerJenis = SuratRequest::selectRaw('surat_types.nama as label, count(*) as total')
            ->join('surat_types', 'surat_types.id', '=', 'surat_requests.surat_type_id')
            ->groupBy('surat_types.nama')
            ->orderByDesc('total')
            ->get();

        // Data kependudukan per dusun
        $wargaPerDusun = Warga::selectRaw('dusuns.nama_dusun as label, count(*) as total')
            ->join('dusuns', 'dusuns.id', '=', 'warga.dusun_id')
            ->where('warga.status_kependudukan', 'hidup')
            ->groupBy('dusuns.nama_dusun')
            ->orderBy('dusuns.nama_dusun')
            ->get();

        // Statistik tingkat pendidikan
        $pendidikan = Warga::selectRaw('status_pendidikan as label, count(*) as total')
            ->where('status_kependudukan', 'hidup')
            ->groupBy('status_pendidikan')
            ->get();

        // Kelahiran & kematian tahun berjalan (berdasarkan tanggal lahir/meninggal ASLI,
        // bukan kapan datanya diinput ke sistem - supaya tidak salah hitung data lama
        // yang baru saja didaftarkan/diimpor sebagai "kelahiran baru").
        $kelahiranTahunIni = Warga::whereYear('tanggal_lahir', now()->year)
            ->where('status_kependudukan', 'hidup')
            ->count();
        $kematianTahunIni = Warga::whereYear('tanggal_meninggal', now()->year)->count();

        $permohonanTerbaru = SuratRequest::with(['suratType', 'warga'])
            ->latest()
            ->take(8)
            ->get();

        $pengaduanTerbaru = Pengaduan::with('pelapor')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'ringkasan',
            'suratPerJenis',
            'wargaPerDusun',
            'pendidikan',
            'kelahiranTahunIni',
            'kematianTahunIni',
            'permohonanTerbaru',
            'pengaduanTerbaru',
        ));
    }
}

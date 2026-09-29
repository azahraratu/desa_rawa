<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use App\Models\Warga;
use Illuminate\Http\Request;

/**
 * Rekapitulasi laporan (jumlah & jenis surat, data kematian, jumlah warga, dll)
 * yang dapat dicetak sebagai dokumen laporan, sesuai proposal 1.6.3.
 */
class LaporanController extends Controller
{
    public function form()
    {
        return view('admin.laporan.form');
    }

    public function cetak(Request $request)
    {
        $data = $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
        ]);

        $suratRequests = SuratRequest::with(['suratType', 'warga.dusun'])
            ->whereBetween('created_at', [$data['dari'].' 00:00:00', $data['sampai'].' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $rekapPerJenis = $suratRequests->groupBy(fn ($s) => $s->suratType->nama)
            ->map->count();

        $kematian = Warga::whereBetween('tanggal_meninggal', [$data['dari'], $data['sampai']])->get();

        $totalWarga = Warga::where('status_kependudukan', 'hidup')->count();
        $totalWargaPerDusun = Warga::selectRaw('dusuns.nama_dusun, count(*) as total')
            ->join('dusuns', 'dusuns.id', '=', 'warga.dusun_id')
            ->where('warga.status_kependudukan', 'hidup')
            ->groupBy('dusuns.nama_dusun')
            ->get();

        return view('admin.laporan.cetak', [
            'dari' => $data['dari'],
            'sampai' => $data['sampai'],
            'suratRequests' => $suratRequests,
            'rekapPerJenis' => $rekapPerJenis,
            'kematian' => $kematian,
            'totalWarga' => $totalWarga,
            'totalWargaPerDusun' => $totalWargaPerDusun,
            'desa' => config('services.desa'),
        ]);
    }
}

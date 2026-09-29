<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanLaporanController extends Controller
{
    public function form()
    {
        return view('admin.pengaduan-laporan.form');
    }

    public function cetak(Request $request)
    {
        $data = $request->validate([
            'dari' => ['required', 'date'],
            'sampai' => ['required', 'date', 'after_or_equal:dari'],
        ]);

        $pengaduans = Pengaduan::with('pelapor', 'tanggapans')
            ->whereBetween('created_at', [$data['dari'].' 00:00:00', $data['sampai'].' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $rekapPerKategori = $pengaduans->groupBy('kategori')->map->count();
        $rekapPerStatus = $pengaduans->groupBy('status')->map->count();

        return view('admin.pengaduan-laporan.cetak', [
            'dari' => $data['dari'],
            'sampai' => $data['sampai'],
            'pengaduans' => $pengaduans,
            'rekapPerKategori' => $rekapPerKategori,
            'rekapPerStatus' => $rekapPerStatus,
            'desa' => config('services.desa'),
        ]);
    }
}

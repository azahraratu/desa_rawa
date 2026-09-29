<?php

namespace App\Http\Controllers\Kadus;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dusun = $user->dusunDipimpin;

        $ringkasan = [
            'total_warga' => $dusun->warga()->where('status_kependudukan', 'hidup')->count(),
            'warga_terdaftar_akun' => $dusun->warga()->whereHas('akun')->count(),
            'warga_meninggal' => $dusun->warga()->where('status_kependudukan', 'meninggal')->count(),
            'pengaduan_dilaporkan' => \App\Models\Pengaduan::dariDusun($dusun->id)->count(),
            'pengaduan_belum_ditanggapi' => \App\Models\Pengaduan::dariDusun($dusun->id)->where('status', \App\Models\Pengaduan::STATUS_BARU)->count(),
        ];

        $wargaTerbaru = $dusun->warga()->latest()->take(8)->get();

        return view('kadus.dashboard', compact('dusun', 'ringkasan', 'wargaTerbaru'));
    }
}

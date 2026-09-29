<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $suratRequests = $user->suratRequests()
            ->with(['suratType', 'warga'])
            ->latest()
            ->take(5)
            ->get();

        $pengaduans = $user->pengaduans()->latest()->take(5)->get();

        $ringkasan = [
            'total_surat' => $user->suratRequests()->count(),
            'surat_diproses' => $user->suratRequests()->whereIn('status', ['diajukan', 'diproses', 'ditandatangani'])->count(),
            'surat_selesai' => $user->suratRequests()->where('status', 'selesai')->count(),
            'total_pengaduan' => $user->pengaduans()->count(),
        ];

        return view('warga.dashboard', compact('suratRequests', 'pengaduans', 'ringkasan'));
    }
}

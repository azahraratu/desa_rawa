<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with('pelapor')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $pengaduans = $query->paginate(15)->withQueryString();

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load(['pelapor', 'tanggapans.admin']);

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'tanggapan' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:diproses,selesai'],
        ]);

        $pengaduan->tanggapans()->create([
            'admin_id' => Auth::id(),
            'tanggapan' => $request->tanggapan,
        ]);

        $pengaduan->update(['status' => $request->status]);

        AuditLog::catat('updated', $pengaduan, "Admin menanggapi pengaduan '{$pengaduan->judul}'.");

        return back()->with('status', 'Tanggapan berhasil dikirim.');
    }
}

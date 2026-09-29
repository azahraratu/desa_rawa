<?php

namespace App\Http\Controllers\Kadus;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    /**
     * Daftar pengaduan yang berasal dari warga di dusun ini - sebelumnya Kepala
     * Dusun sama sekali tidak punya akses untuk melihat keluhan yang dilaporkan
     * langsung oleh warganya sendiri (hanya bisa membuat laporan atas nama warga).
     */
    public function index(Request $request)
    {
        $dusunId = Auth::user()->dusun_id;

        $query = Pengaduan::with('pelapor')->dariDusun($dusunId)->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $pengaduans = $query->paginate(15)->withQueryString();

        return view('kadus.pengaduan.index', compact('pengaduans'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $this->authorize('view', $pengaduan);

        $pengaduan->load(['pelapor', 'tanggapans.admin']);

        return view('kadus.pengaduan.show', compact('pengaduan'));
    }

    public function tanggapi(Request $request, Pengaduan $pengaduan)
    {
        $this->authorize('respond', $pengaduan);

        $request->validate([
            'tanggapan' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'in:diproses,selesai'],
        ]);

        $pengaduan->tanggapans()->create([
            'admin_id' => Auth::id(),
            'tanggapan' => $request->tanggapan,
        ]);

        $pengaduan->update(['status' => $request->status]);

        AuditLog::catat('updated', $pengaduan, "Kepala Dusun menanggapi pengaduan '{$pengaduan->judul}'.");

        return back()->with('status', 'Tanggapan berhasil dikirim.');
    }

    public function create()
    {
        return view('kadus.pengaduan.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'in:infrastruktur,sosial,lainnya'],
            'deskripsi' => ['required', 'string', 'max:2000'],
            'foto' => ['nullable', 'image', 'max:4096'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'user_id' => Auth::id(),
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
            'deskripsi' => $data['deskripsi'],
            'foto_path' => $fotoPath,
            'status' => Pengaduan::STATUS_BARU,
            'dilaporkan_oleh_kadus' => true,
        ]);

        return redirect()->route('kadus.dashboard')
            ->with('status', 'Laporan keluhan warga berhasil disampaikan ke admin desa.');
    }
}

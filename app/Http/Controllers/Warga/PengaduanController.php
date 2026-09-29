<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = Auth::user()->pengaduans()->with('tanggapans')->latest()->paginate(10);

        return view('warga.pengaduan.index', compact('pengaduans'));
    }

    public function create()
    {
        return view('warga.pengaduan.create');
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

        $pengaduan = Pengaduan::create([
            'user_id' => Auth::id(),
            'judul' => $data['judul'],
            'kategori' => $data['kategori'],
            'deskripsi' => $data['deskripsi'],
            'foto_path' => $fotoPath,
            'status' => Pengaduan::STATUS_BARU,
        ]);

        AuditLog::catat('created', $pengaduan, "Warga membuat pengaduan: {$pengaduan->judul}.");

        return redirect()->route('warga.pengaduan.index')
            ->with('status', 'Pengaduan berhasil dikirim. Pihak desa akan menindaklanjuti laporan Anda.');
    }

    public function show(Pengaduan $pengaduan)
    {
        $this->authorize('view', $pengaduan);
        $pengaduan->load('tanggapans.admin');

        return view('warga.pengaduan.show', compact('pengaduan'));
    }
}

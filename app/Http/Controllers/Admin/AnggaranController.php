<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggaranDesa;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggaranController extends Controller
{
    public function index(Request $request)
    {
        $tahunTersedia = AnggaranDesa::tahunTersedia();
        $tahun = (int) $request->get('tahun', $tahunTersedia->first() ?? now()->year);

        $items = AnggaranDesa::where('tahun', $tahun)->orderBy('jenis')->orderBy('kategori')->get();
        $pendapatan = $items->where('jenis', AnggaranDesa::JENIS_PENDAPATAN);
        $belanja = $items->where('jenis', AnggaranDesa::JENIS_BELANJA);

        $totalPendapatan = $pendapatan->sum('jumlah_anggaran');
        $totalBelanja = $belanja->sum('jumlah_anggaran');
        $totalRealisasiBelanja = $belanja->sum('jumlah_realisasi');

        return view('admin.anggaran.index', compact(
            'tahunTersedia', 'tahun', 'pendapatan', 'belanja',
            'totalPendapatan', 'totalBelanja', 'totalRealisasiBelanja'
        ));
    }

    public function create()
    {
        return view('admin.anggaran.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['dibuat_oleh'] = Auth::id();

        $anggaran = AnggaranDesa::create($data);

        AuditLog::catat('created', $anggaran, "Menambahkan data anggaran '{$anggaran->kategori}' tahun {$anggaran->tahun}.");

        return redirect()->route('admin.anggaran.index', ['tahun' => $anggaran->tahun])
            ->with('status', 'Data anggaran berhasil ditambahkan.');
    }

    public function edit(AnggaranDesa $anggaran)
    {
        return view('admin.anggaran.edit', compact('anggaran'));
    }

    public function update(Request $request, AnggaranDesa $anggaran)
    {
        $anggaran->update($this->validated($request));

        AuditLog::catat('updated', $anggaran, "Memperbarui data anggaran '{$anggaran->kategori}' tahun {$anggaran->tahun}.");

        return redirect()->route('admin.anggaran.index', ['tahun' => $anggaran->tahun])
            ->with('status', 'Data anggaran berhasil diperbarui.');
    }

    public function destroy(AnggaranDesa $anggaran)
    {
        $tahun = $anggaran->tahun;
        AuditLog::catat('deleted', $anggaran, "Menghapus data anggaran '{$anggaran->kategori}' tahun {$anggaran->tahun}.");
        $anggaran->delete();

        return redirect()->route('admin.anggaran.index', ['tahun' => $tahun])
            ->with('status', 'Data anggaran berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tahun' => ['required', 'integer', 'digits:4', 'min:2000', 'max:2100'],
            'jenis' => ['required', 'in:pendapatan,belanja'],
            'kategori' => ['required', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:2000'],
            'jumlah_anggaran' => ['required', 'numeric', 'min:0'],
            'jumlah_realisasi' => ['nullable', 'numeric', 'min:0'],
        ], [], [
            'tahun' => 'Tahun',
            'jenis' => 'Jenis',
            'kategori' => 'Kategori',
            'keterangan' => 'Keterangan',
            'jumlah_anggaran' => 'Jumlah Anggaran',
            'jumlah_realisasi' => 'Jumlah Realisasi',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDusunRequest;
use App\Models\AuditLog;
use App\Models\Dusun;
use Illuminate\Http\Request;

class DusunController extends Controller
{
    public function index()
    {
        $dusuns = Dusun::withCount([
            'warga' => fn ($q) => $q->where('status_kependudukan', 'hidup'),
        ])->with('kepalaDusun')->orderBy('nama_dusun')->get();

        return view('admin.dusun.index', compact('dusuns'));
    }

    public function create()
    {
        return view('admin.dusun.create');
    }

    public function store(StoreDusunRequest $request)
    {
        $dusun = Dusun::create($request->validated());

        AuditLog::catat('created', $dusun, "Admin menambahkan dusun baru: {$dusun->nama_dusun}.");

        return redirect()->route('admin.dusun.index')->with('status', "Dusun \"{$dusun->nama_dusun}\" berhasil ditambahkan.");
    }

    public function edit(Dusun $dusun)
    {
        return view('admin.dusun.edit', compact('dusun'));
    }

    public function update(StoreDusunRequest $request, Dusun $dusun)
    {
        $dusun->update($request->validated());

        AuditLog::catat('updated', $dusun, "Admin mengubah nama dusun menjadi: {$dusun->nama_dusun}.");

        return redirect()->route('admin.dusun.index')->with('status', 'Dusun berhasil diperbarui.');
    }

    /** Hapus dusun hanya diperbolehkan jika tidak ada warga/kadus terkait, untuk mencegah data yatim. */
    public function destroy(Dusun $dusun)
    {
        if ($dusun->warga()->exists()) {
            return back()->with('error', "Dusun \"{$dusun->nama_dusun}\" masih memiliki data warga, tidak dapat dihapus. Pindahkan/hapus data warga terlebih dahulu.");
        }

        if ($dusun->kepalaDusun()->exists()) {
            return back()->with('error', "Dusun \"{$dusun->nama_dusun}\" masih memiliki akun Kepala Dusun, nonaktifkan akun tersebut terlebih dahulu.");
        }

        $nama = $dusun->nama_dusun;
        $dusun->delete();

        AuditLog::catat('deleted', null, "Admin menghapus dusun: {$nama}.");

        return redirect()->route('admin.dusun.index')->with('status', "Dusun \"{$nama}\" berhasil dihapus.");
    }
}

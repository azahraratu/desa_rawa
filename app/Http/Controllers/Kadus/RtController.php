<?php

namespace App\Http\Controllers\Kadus;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kadus\StoreRtRequest;
use App\Models\AuditLog;
use App\Models\Rt;
use Illuminate\Support\Facades\Auth;

/**
 * Kepala Dusun mengelola daftar RT (Rukun Tetangga) di dalam dusun yang
 * dipimpinnya. Data warga kemudian dapat dikelompokkan per RT.
 */
class RtController extends Controller
{
    public function index()
    {
        $rts = Rt::where('dusun_id', Auth::user()->dusun_id)
            ->withCount(['warga' => fn ($q) => $q->where('status_kependudukan', 'hidup')])
            ->orderByRaw('CAST(nama_rt AS UNSIGNED), nama_rt')
            ->get();

        return view('kadus.rt.index', compact('rts'));
    }

    public function create()
    {
        return view('kadus.rt.create');
    }

    public function store(StoreRtRequest $request)
    {
        $rt = Rt::create($request->validated() + ['dusun_id' => Auth::user()->dusun_id]);

        AuditLog::catat('created', $rt, "Kepala Dusun menambahkan RT baru: {$rt->nama_rt}.");

        return redirect()->route('kadus.rt.index')->with('status', "RT {$rt->nama_rt} berhasil ditambahkan.");
    }

    public function edit(Rt $rt)
    {
        $this->authorizeAkses($rt);

        return view('kadus.rt.edit', compact('rt'));
    }

    public function update(StoreRtRequest $request, Rt $rt)
    {
        $this->authorizeAkses($rt);

        $rt->update($request->validated());

        AuditLog::catat('updated', $rt, "Kepala Dusun mengubah data RT {$rt->nama_rt}.");

        return redirect()->route('kadus.rt.index')->with('status', 'RT berhasil diperbarui.');
    }

    public function destroy(Rt $rt)
    {
        $this->authorizeAkses($rt);

        if ($rt->warga()->exists()) {
            return back()->with('error', "RT {$rt->nama_rt} masih memiliki data warga, pindahkan dulu warganya ke RT lain sebelum menghapus.");
        }

        $nama = $rt->nama_rt;
        $rt->delete();

        AuditLog::catat('deleted', null, "Kepala Dusun menghapus RT {$nama}.");

        return redirect()->route('kadus.rt.index')->with('status', "RT {$nama} berhasil dihapus.");
    }

    protected function authorizeAkses(Rt $rt): void
    {
        abort_unless($rt->dusun_id === Auth::user()->dusun_id, 403);
    }
}

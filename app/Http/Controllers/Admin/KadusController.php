<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKadusRequest;
use App\Models\AuditLog;
use App\Models\Dusun;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Hanya Admin yang dapat membuat akun Kepala Dusun (tidak ada registrasi mandiri
 * untuk role ini). Role sudah ditentukan/dikunci sejak akun dibuat, sehingga
 * tidak mungkin ada pihak luar mengklaim diri sebagai Kepala Dusun.
 */
class KadusController extends Controller
{
    public function index()
    {
        $kadusList = User::where('role', 'kadus')->with('dusunDipimpin')->orderBy('name')->get();
        $dusunTanpaKadus = Dusun::whereDoesntHave('kepalaDusun')->orderBy('nama_dusun')->get();

        return view('admin.kadus.index', compact('kadusList', 'dusunTanpaKadus'));
    }

    public function create(Request $request)
    {
        $dusuns = Dusun::whereDoesntHave('kepalaDusun')->orderBy('nama_dusun')->get();
        $dusunTerpilih = $request->integer('dusun_id') ?: null;
        $balikKe = $request->get('balik_ke', 'kadus');

        return view('admin.kadus.create', compact('dusuns', 'dusunTerpilih', 'balikKe'));
    }

    public function store(StoreKadusRequest $request)
    {
        $data = $request->validated();

        $passwordSementara = Str::password(12, symbols: true);

        $kadus = User::create([
            'name' => $data['name'],
            'nik' => $data['nik'],
            'no_hp' => $data['no_hp'],
            'dusun_id' => $data['dusun_id'],
            'password' => Hash::make($passwordSementara),
            'role' => 'kadus',
            'must_change_password' => true,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        AuditLog::catat('created', $kadus, "Admin membuat akun Kepala Dusun: {$kadus->name}.");

        $tujuan = $request->get('balik_ke') === 'dusun'
            ? redirect()->route('admin.dusun.index')
            : redirect()->route('admin.kadus.index');

        return $tujuan->with([
            'status' => 'Akun Kepala Dusun berhasil dibuat.',
            'kredensial_baru' => [
                'nama' => $kadus->name,
                'nik' => $data['nik'],
                'password' => $passwordSementara,
            ],
        ]);
    }

    /** Nonaktifkan/aktifkan kembali akun Kepala Dusun tanpa menghapus data historisnya. */
    public function toggleAktif(User $kadus)
    {
        abort_unless($kadus->role === 'kadus', 404);

        $kadus->update(['is_active' => ! $kadus->is_active]);

        AuditLog::catat('updated', $kadus, ($kadus->is_active ? 'Mengaktifkan' : 'Menonaktifkan')." akun Kepala Dusun {$kadus->name}.");

        return back()->with('status', 'Status akun berhasil diperbarui.');
    }

    /** Reset password Kepala Dusun (mis. lupa password), memaksa ganti password saat login berikutnya. */
    public function resetPassword(User $kadus)
    {
        abort_unless($kadus->role === 'kadus', 404);

        $passwordBaru = Str::password(12, symbols: true);

        $kadus->update([
            'password' => Hash::make($passwordBaru),
            'must_change_password' => true,
        ]);

        AuditLog::catat('updated', $kadus, "Admin me-reset password akun Kepala Dusun {$kadus->name}.");

        return back()->with([
            'status' => 'Password berhasil direset.',
            'kredensial_baru' => [
                'nama' => $kadus->name,
                'nik' => $kadus->nik,
                'password' => $passwordBaru,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WargaExport;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Dusun;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class WargaController extends Controller
{
    /** Ringkasan seluruh dusun - kartu per dusun sebagai pintu masuk ke halaman detail masing-masing. */
    public function index()
    {
        $dusuns = Dusun::withCount([
            'warga' => fn ($q) => $q->where('status_kependudukan', 'hidup'),
            'warga as warga_meninggal_count' => fn ($q) => $q->where('status_kependudukan', 'meninggal'),
        ])->with('kepalaDusun')->orderBy('nama_dusun')->get();

        return view('admin.warga.index', compact('dusuns'));
    }

    /** Halaman khusus data kependudukan satu dusun, terpisah rapi dari dusun lain. */
    public function perDusun(Request $request, Dusun $dusun)
    {
        $query = $dusun->warga()->with(['akun', 'rt'])->orderBy('nama');

        if ($cari = $request->get('cari')) {
            $kemungkinanRt = trim(preg_replace('/[^0-9]/', '', $cari));

            $query->where(function ($q) use ($cari, $kemungkinanRt) {
                $q->where('nama', 'like', "%{$cari}%");
                if ($kemungkinanRt !== '') {
                    $q->orWhereHas('rt', fn ($r) => $r->where('nama_rt', 'like', "%{$kemungkinanRt}%"));
                }
            });
        }

        if ($rtId = $request->get('rt_id')) {
            $query->where('rt_id', $rtId);
        }

        if ($request->get('status') === 'meninggal') {
            $query->where('status_kependudukan', 'meninggal');
        } elseif ($request->get('status') === 'pindah') {
            $query->where('status_kependudukan', 'pindah');
        } else {
            $query->where('status_kependudukan', 'hidup');
        }

        $warga = $query->paginate(15)->withQueryString();
        $rts = $dusun->rts()
            ->withCount(['warga' => fn ($q) => $q->where('status_kependudukan', 'hidup')])
            ->orderByRaw('CAST(nama_rt AS UNSIGNED), nama_rt')
            ->get();

        $statistik = [
            'total_hidup' => $dusun->warga()->where('status_kependudukan', 'hidup')->count(),
            'total_meninggal' => $dusun->warga()->where('status_kependudukan', 'meninggal')->count(),
            'total_pindah' => $dusun->warga()->where('status_kependudukan', 'pindah')->count(),
            'sudah_daftar_akun' => $dusun->warga()->whereHas('akun')->count(),
            'diinput_bulan_ini' => $dusun->warga()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        return view('admin.warga.dusun', compact('dusun', 'warga', 'statistik', 'rts'));
    }

    /**
     * Admin tidak dapat melihat password asli warga (password disimpan dalam
     * bentuk hash satu-arah, sesuai praktik keamanan standar - bahkan Admin
     * sistem pun tidak seharusnya bisa membacanya). Sebagai gantinya, Admin
     * dapat men-generate password baru yang ditampilkan sekali untuk
     * disampaikan ke warga, sama seperti alur reset password Kepala Dusun.
     */
    public function resetPassword(Warga $warga)
    {
        $akun = $warga->akun;

        if (! $akun) {
            return back()->with('error', "{$warga->nama} belum memiliki akun (belum registrasi), tidak ada password untuk direset.");
        }

        $passwordBaru = Str::password(12, symbols: true);

        $akun->update([
            'password' => Hash::make($passwordBaru),
            'must_change_password' => true,
        ]);

        AuditLog::catat('updated', $akun, "Admin me-reset password akun warga {$warga->nama}.");

        return back()->with([
            'status' => 'Password berhasil direset.',
            'kredensial_baru' => [
                'nama' => $warga->nama,
                'nik' => $warga->nik,
                'password' => $passwordBaru,
            ],
        ]);
    }

    /** Menandai warga sebagai pindah domisili (Admin dapat melakukan ini di semua dusun). */
    public function tandaiPindah(Request $request, Warga $warga)
    {
        $request->validate([
            'tanggal_pindah' => ['required', 'date', 'before_or_equal:today'],
            'alamat_tujuan_pindah' => ['nullable', 'string', 'max:255'],
        ]);

        $warga->update([
            'status_kependudukan' => 'pindah',
            'tanggal_pindah' => $request->tanggal_pindah,
            'alamat_tujuan_pindah' => $request->alamat_tujuan_pindah,
        ]);

        AuditLog::catat('updated', $warga, "Admin menandai {$warga->nama} pindah domisili.");

        return back()->with('status', "Data {$warga->nama} telah ditandai pindah domisili.");
    }

    public function exportDusun(Dusun $dusun)
    {
        AuditLog::catat('login', null, "Admin mengunduh Excel data warga Dusun {$dusun->nama_dusun}.");

        return Excel::download(new WargaExport($dusun->id), 'data-warga-'.str($dusun->nama_dusun)->slug().'.xlsx');
    }

    public function exportSemua()
    {
        AuditLog::catat('login', null, 'Admin mengunduh Excel seluruh data warga.');

        return Excel::download(new WargaExport, 'data-warga-desa-rawa-lengkap.xlsx');
    }
}

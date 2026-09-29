<?php

namespace App\Http\Controllers\Kadus;

use App\Exports\WargaImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kadus\StoreWargaRequest;
use App\Imports\WargaImport;
use App\Models\AuditLog;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DataWargaController extends Controller
{
    public function index(Request $request)
    {
        $dusun = Auth::user()->dusunDipimpin;

        $query = $dusun->warga()->with('rt')->orderBy('nama');

        if ($request->get('status') === 'meninggal') {
            $query->where('status_kependudukan', 'meninggal');
        } elseif ($request->get('status') === 'pindah') {
            $query->where('status_kependudukan', 'pindah');
        } else {
            $query->where('status_kependudukan', 'hidup');
        }

        if ($rtId = $request->get('rt_id')) {
            $query->where('rt_id', $rtId);
        }

        if ($cari = $request->get('cari')) {
            // Pencarian gabungan: cocokkan ke nama ATAU nomor RT (mis. ketik "RT 20" atau "20").
            $kemungkinanRt = trim(preg_replace('/[^0-9]/', '', $cari));

            $query->where(function ($q) use ($cari, $kemungkinanRt) {
                $q->where('nama', 'like', "%{$cari}%");
                if ($kemungkinanRt !== '') {
                    $q->orWhereHas('rt', fn ($r) => $r->where('nama_rt', 'like', "%{$kemungkinanRt}%"));
                }
            });
        }

        $warga = $query->paginate(15)->withQueryString();
        $rts = Auth::user()->dusunDipimpin->rts()
            ->withCount(['warga' => fn ($q) => $q->where('status_kependudukan', 'hidup')])
            ->orderByRaw('CAST(nama_rt AS UNSIGNED), nama_rt')
            ->get();

        return view('kadus.warga.index', compact('warga', 'rts'));
    }

    public function create()
    {
        return view('kadus.warga.create');
    }

    public function store(StoreWargaRequest $request)
    {
        $data = $request->validated();

        $data['dusun_id'] = Auth::user()->dusun_id;
        $data['diinput_oleh'] = Auth::id();
        $data['status_kependudukan'] = 'hidup';

        $warga = Warga::create($data);

        AuditLog::catat('created', $warga, "Data warga baru ditambahkan: {$warga->nama}.");

        return redirect()->route('kadus.warga.index')
            ->with('status', 'Data warga berhasil ditambahkan. Warga kini dapat melakukan registrasi akun.');
    }

    public function edit(Warga $warga)
    {
        $this->authorizeAkses($warga);

        return view('kadus.warga.edit', compact('warga'));
    }

    public function update(StoreWargaRequest $request, Warga $warga)
    {
        $this->authorizeAkses($warga);

        $warga->update($request->validated());

        AuditLog::catat('updated', $warga, "Data warga diperbarui: {$warga->nama}.");

        return redirect()->route('kadus.warga.index')->with('status', 'Data warga berhasil diperbarui.');
    }

    /** Menandai warga sebagai telah meninggal dunia. */
    public function tandaiMeninggal(Request $request, Warga $warga)
    {
        $this->authorizeAkses($warga);

        $request->validate([
            'tanggal_meninggal' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $warga->update([
            'status_kependudukan' => 'meninggal',
            'tanggal_meninggal' => $request->tanggal_meninggal,
        ]);

        AuditLog::catat('updated', $warga, "{$warga->nama} ditandai meninggal dunia.");

        return back()->with('status', "Data {$warga->nama} telah ditandai meninggal dunia.");
    }

    /** Menandai warga sebagai telah pindah domisili keluar dari Desa Rawa. */
    public function tandaiPindah(Request $request, Warga $warga)
    {
        $this->authorizeAkses($warga);

        $request->validate([
            'tanggal_pindah' => ['required', 'date', 'before_or_equal:today'],
            'alamat_tujuan_pindah' => ['nullable', 'string', 'max:255'],
        ]);

        $warga->update([
            'status_kependudukan' => 'pindah',
            'tanggal_pindah' => $request->tanggal_pindah,
            'alamat_tujuan_pindah' => $request->alamat_tujuan_pindah,
        ]);

        AuditLog::catat('updated', $warga, "{$warga->nama} ditandai pindah domisili.");

        return back()->with('status', "Data {$warga->nama} telah ditandai pindah domisili.");
    }

    public function importForm()
    {
        return view('kadus.warga.import');
    }

    public function downloadTemplate()
    {
        return Excel::download(new WargaImportTemplateExport, 'template-import-data-warga.xlsx');
    }

    /**
     * Import data warga secara massal melalui file Excel (.xlsx), memakai
     * template resmi (lihat WargaImportTemplateExport / tombol unduh template).
     */
    public function importSubmit(Request $request)
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $import = new WargaImport(Auth::user()->dusun_id, Auth::id());

        Excel::import($import, $request->file('file_excel'));

        AuditLog::catat('created', null, "Import Excel data warga: {$import->berhasil} berhasil, {$import->gagal} gagal.");

        $pesan = "Import selesai: {$import->berhasil} data berhasil, {$import->gagal} gagal/dilewati.";

        if ($import->gagal > 0) {
            return back()->with('status', $pesan)->with('detail_gagal', array_slice($import->pesanGagal, 0, 15));
        }

        return back()->with('status', $pesan);
    }

    /**
     * Kepala Dusun menautkan akunnya ke salah satu data warga di dusunnya
     * sendiri sebagai "profil pribadi" - supaya bisa mengajukan permohonan
     * surat untuk dirinya sendiri (bukan cuma untuk warga lain).
     */
    public function jadikanProfilSaya(Warga $warga)
    {
        $this->authorizeAkses($warga);

        $kadus = Auth::user();
        $kadus->update(['warga_id' => $warga->id]);

        AuditLog::catat('updated', $kadus, "Kepala Dusun menautkan profil pribadi ke data warga: {$warga->nama}.");

        return back()->with('status', "Berhasil! Profil pribadi Anda ditautkan ke data {$warga->nama}. Sekarang Anda bisa mengajukan surat untuk diri sendiri lewat menu Buatkan Surat.");
    }

    protected function authorizeAkses(Warga $warga): void
    {
        abort_unless($warga->dusun_id === Auth::user()->dusun_id, 403);
    }
}

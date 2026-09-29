<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ArsipBulananExport;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Dusun;
use App\Models\Pengaduan;
use App\Models\SuratRequest;
use App\Models\Warga;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Arsip & Laporan Bulanan - mirip tampilan "mutasi rekening": Admin memilih
 * periode (bulan & tahun), opsional filter per dusun, lalu bisa melihat
 * rekapnya langsung di halaman atau mengunduhnya sebagai Excel/PDF.
 */
class ArsipController extends Controller
{
    public function index()
    {
        $dusuns = Dusun::orderBy('nama_dusun')->get();

        // 12 bulan terakhir untuk daftar "arsip cepat", lengkap dengan jumlah data per periode.
        $periodeCepat = collect(range(0, 11))->map(function ($i) {
            $tanggal = now()->subMonths($i);
            $dari = $tanggal->copy()->startOfMonth()->format('Y-m-d');
            $sampai = $tanggal->copy()->endOfMonth()->format('Y-m-d');

            return [
                'tahun' => $tanggal->year,
                'bulan' => $tanggal->month,
                'label' => $tanggal->translatedFormat('F Y'),
                'total_surat' => SuratRequest::whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"])->count(),
                'total_warga_baru' => Warga::whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"])->count(),
                'total_pengaduan' => Pengaduan::whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"])->count(),
            ];
        });

        return view('admin.arsip.index', compact('dusuns', 'periodeCepat'));
    }

    public function show(Request $request)
    {
        $data = $this->validasiPeriode($request);
        [$dari, $sampai, $labelPeriode] = $this->rentangTanggal($data['tahun'], $data['bulan']);

        $dusunId = $data['dusun_id'] ?? null;

        $suratQuery = SuratRequest::with(['suratType', 'warga.dusun'])
            ->whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"]);
        $wargaBaruQuery = Warga::with('dusun')
            ->whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"]);
        $kematianQuery = Warga::with('dusun')->whereBetween('tanggal_meninggal', [$dari, $sampai]);
        $pengaduanQuery = Pengaduan::with('pelapor')
            ->whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"]);

        if ($dusunId) {
            $suratQuery->whereHas('warga', fn ($q) => $q->where('dusun_id', $dusunId));
            $wargaBaruQuery->where('dusun_id', $dusunId);
            $kematianQuery->where('dusun_id', $dusunId);
        }

        $ringkasanDusun = Dusun::withCount([
            'warga' => fn ($q) => $q->where('status_kependudukan', 'hidup'),
            'warga as warga_baru_count' => fn ($q) => $q->whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"]),
            'warga as kematian_count' => fn ($q) => $q->whereBetween('tanggal_meninggal', [$dari, $sampai]),
        ])->when($dusunId, fn ($q) => $q->where('id', $dusunId))->orderBy('nama_dusun')->get();

        $dusuns = Dusun::orderBy('nama_dusun')->get();
        $dusunTerpilih = $dusunId ? Dusun::find($dusunId) : null;

        AuditLog::catat('login', null, "Admin melihat arsip periode {$labelPeriode}".($dusunTerpilih ? " (Dusun {$dusunTerpilih->nama_dusun})" : '').'.');

        return view('admin.arsip.show', [
            'tahun' => $data['tahun'],
            'bulan' => $data['bulan'],
            'labelPeriode' => $labelPeriode,
            'dusunId' => $dusunId,
            'dusuns' => $dusuns,
            'dusunTerpilih' => $dusunTerpilih,
            'ringkasanDusun' => $ringkasanDusun,
            'suratRequests' => $suratQuery->latest()->get(),
            'wargaBaru' => $wargaBaruQuery->latest()->get(),
            'kematian' => $kematianQuery->get(),
            'pengaduans' => $pengaduanQuery->latest()->get(),
        ]);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->validasiPeriode($request);
        [$dari, $sampai, $labelPeriode] = $this->rentangTanggal($data['tahun'], $data['bulan']);

        AuditLog::catat('login', null, "Admin mengunduh arsip Excel periode {$labelPeriode}.");

        $namaFile = 'arsip-desa-rawa-'.str_replace(' ', '-', strtolower($labelPeriode)).'.xlsx';

        return Excel::download(
            new ArsipBulananExport($dari, $sampai, $labelPeriode, $data['dusun_id'] ?? null),
            $namaFile
        );
    }

    public function exportPdf(Request $request)
    {
        $data = $this->validasiPeriode($request);
        [$dari, $sampai, $labelPeriode] = $this->rentangTanggal($data['tahun'], $data['bulan']);
        $dusunId = $data['dusun_id'] ?? null;

        $suratRequests = SuratRequest::with(['suratType', 'warga.dusun'])
            ->when($dusunId, fn ($q) => $q->whereHas('warga', fn ($w) => $w->where('dusun_id', $dusunId)))
            ->whereBetween('created_at', ["{$dari} 00:00:00", "{$sampai} 23:59:59"])
            ->get();

        $rekapPerJenis = $suratRequests->groupBy(fn ($s) => $s->suratType->nama)->map->count();

        $kematian = Warga::with('dusun')
            ->when($dusunId, fn ($q) => $q->where('dusun_id', $dusunId))
            ->whereBetween('tanggal_meninggal', [$dari, $sampai])->get();

        $totalWargaPerDusun = Dusun::withCount(['warga' => fn ($q) => $q->where('status_kependudukan', 'hidup')])
            ->when($dusunId, fn ($q) => $q->where('id', $dusunId))
            ->orderBy('nama_dusun')->get();

        AuditLog::catat('login', null, "Admin mengunduh arsip PDF periode {$labelPeriode}.");

        $pdf = Pdf::loadView('admin.laporan.pdf-arsip', [
            'labelPeriode' => $labelPeriode,
            'suratRequests' => $suratRequests,
            'rekapPerJenis' => $rekapPerJenis,
            'kematian' => $kematian,
            'totalWargaPerDusun' => $totalWargaPerDusun,
            'desa' => config('services.desa'),
        ])->setPaper('a4');

        return $pdf->download('arsip-desa-rawa-'.str_replace(' ', '-', strtolower($labelPeriode)).'.pdf');
    }

    protected function validasiPeriode(Request $request): array
    {
        return $request->validate([
            'tahun' => ['required', 'integer', 'min:2020', 'max:'.(now()->year + 1)],
            'bulan' => ['required', 'integer', 'min:1', 'max:12'],
            'dusun_id' => ['nullable', 'exists:dusuns,id'],
        ]);
    }

    protected function rentangTanggal(int $tahun, int $bulan): array
    {
        $awal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        return [$awal->format('Y-m-d'), $akhir->format('Y-m-d'), $awal->translatedFormat('F Y')];
    }
}

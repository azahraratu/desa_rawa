<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SuratRequest;
use App\Services\SuratPdfService;
use App\Services\SuratWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratRequest::with(['suratType', 'warga', 'pemohon'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $suratRequests = $query->paginate(15)->withQueryString();

        return view('admin.surat.index', compact('suratRequests'));
    }

    public function show(SuratRequest $surat)
    {
        $surat->load(['suratType', 'warga.dusun', 'pemohon', 'logs', 'lampirans']);

        return view('admin.surat.show', compact('surat'));
    }

    public function proses(SuratRequest $surat, SuratWorkflowService $workflow)
    {
        $workflow->proses($surat, Auth::user());

        AuditLog::catat('updated', $surat, "Admin memulai proses permohonan {$surat->kode_permohonan}.");

        return back()->with('status', 'Permohonan surat mulai diproses. Notifikasi WhatsApp telah dikirim ke pemohon.');
    }

    /** Tandatangani sekaligus generate dokumen PDF otomatis sesuai format standar. */
    public function tandaTangani(SuratRequest $surat, SuratWorkflowService $workflow, SuratPdfService $pdfService)
    {
        $pdfService->generate($surat);
        $workflow->tandaTangani($surat);

        AuditLog::catat('updated', $surat, "Admin menandatangani & menerbitkan PDF untuk {$surat->kode_permohonan}.");

        return back()->with('status', 'Surat berhasil ditandatangani & dokumen PDF telah dibuat otomatis.');
    }

    public function selesaikan(SuratRequest $surat, SuratWorkflowService $workflow)
    {
        $workflow->selesaikan($surat);

        AuditLog::catat('updated', $surat, "Permohonan {$surat->kode_permohonan} ditandai selesai.");

        return back()->with('status', 'Permohonan surat ditandai selesai. Warga dapat mengunduh dokumennya sekarang.');
    }

    public function tolak(Request $request, SuratRequest $surat, SuratWorkflowService $workflow)
    {
        $request->validate(['alasan_penolakan' => ['required', 'string', 'max:500']]);

        $workflow->tolak($surat, $request->alasan_penolakan);

        AuditLog::catat('updated', $surat, "Permohonan {$surat->kode_permohonan} ditolak: {$request->alasan_penolakan}");

        return back()->with('status', 'Permohonan surat ditolak, notifikasi telah dikirim ke pemohon.');
    }

    public function download(SuratRequest $surat)
    {
        if (! $surat->file_path || ! Storage::disk('public')->exists($surat->file_path)) {
            abort(404, 'Dokumen belum tersedia.');
        }

        return Storage::disk('public')->download($surat->file_path, $surat->kode_permohonan.'.pdf');
    }

    public function unduhLampiran(SuratRequest $surat, \App\Models\SuratRequestLampiran $lampiran)
    {
        abort_unless($lampiran->surat_request_id === $surat->id, 404);

        if (! Storage::disk('public')->exists($lampiran->file_path)) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        return Storage::disk('public')->download($lampiran->file_path, $lampiran->nama_asli ?: basename($lampiran->file_path));
    }
}

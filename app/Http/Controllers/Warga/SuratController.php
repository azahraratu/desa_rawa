<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SuratRequest;
use App\Models\SuratType;
use App\Models\Warga;
use App\Services\SuratWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuratController extends Controller
{
    public function index()
    {
        $suratRequests = Auth::user()->suratRequests()
            ->with(['suratType', 'warga'])
            ->latest()
            ->paginate(10);

        return view('warga.surat.index', compact('suratRequests'));
    }

    public function create()
    {
        $user = Auth::user();
        $suratTypes = SuratType::where('aktif', true)->orderBy('nama')->get();

        // Anggota keluarga dalam satu KK, termasuk diri sendiri, agar warga
        // bisa mengajukan surat untuk anggota keluarga lain (sesuai proposal 1.6.1).
        // Dicari lewat no_kk_hash (blind-index), bukan membandingkan nilai terdekripsi.
        $anggotaKeluarga = Warga::where('no_kk_hash', $user->warga->no_kk_hash)
            ->where('id', '!=', $user->warga_id)
            ->where('status_kependudukan', 'hidup')
            ->orderBy('nama')
            ->get();

        return view('warga.surat.create', compact('suratTypes', 'anggotaKeluarga'));
    }

    public function store(Request $request, SuratWorkflowService $workflow)
    {
        $user = Auth::user();

        $suratType = SuratType::findOrFail($request->input('surat_type_id'));

        $data = $request->validate([
            'surat_type_id' => ['required', 'exists:surat_types,id'],
            'warga_id' => ['required', 'exists:warga,id'],
            'alasan' => ['required', 'string', 'max:1000'],
            'lampiran' => [$suratType->perlu_lampiran ? 'required' : 'nullable', 'array', 'min:1'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'lampiran.required' => "Lampiran \"{$suratType->label_lampiran}\" wajib diunggah untuk jenis surat ini.",
        ]);

        // Pastikan subjek surat memang anggota keluarga (satu KK) pemohon - dicek lewat blind-index.
        $subjek = Warga::where('id', $data['warga_id'])
            ->where('no_kk_hash', $user->warga->no_kk_hash)
            ->firstOrFail();

        $surat = SuratRequest::create([
            'kode_permohonan' => 'KP-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'user_id' => $user->id,
            'warga_id' => $subjek->id,
            'surat_type_id' => $data['surat_type_id'],
            'alasan' => $data['alasan'],
            'status' => SuratRequest::STATUS_DIAJUKAN,
            'dibuat_oleh_kadus' => false,
        ]);

        foreach ($request->file('lampiran', []) as $berkas) {
            $surat->lampirans()->create([
                'file_path' => $berkas->store('lampiran-surat', 'public'),
                'nama_asli' => $berkas->getClientOriginalName(),
            ]);
        }

        $workflow->ajukan($surat);

        AuditLog::catat('created', $surat, "Warga mengajukan permohonan {$surat->suratType->nama} untuk {$subjek->nama}.");

        return redirect()->route('warga.surat.show', $surat)
            ->with('status', 'Permohonan surat berhasil diajukan. Notifikasi WhatsApp telah dikirim.');
    }

    public function show(SuratRequest $surat)
    {
        $this->authorize('view', $surat);

        $surat->load(['suratType', 'warga', 'logs']);

        return view('warga.surat.show', compact('surat'));
    }

    public function download(SuratRequest $surat)
    {
        $this->authorize('download', $surat);

        if (! $surat->file_path || ! Storage::disk('public')->exists($surat->file_path)) {
            abort(404, 'Dokumen surat belum tersedia.');
        }

        return Storage::disk('public')->download($surat->file_path, $surat->kode_permohonan.'.pdf');
    }
}

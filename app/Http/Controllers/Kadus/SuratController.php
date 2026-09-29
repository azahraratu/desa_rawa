<?php

namespace App\Http\Controllers\Kadus;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use App\Models\SuratType;
use App\Models\User;
use App\Models\Warga;
use App\Services\SuratWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Kepala Dusun dapat membuatkan permohonan surat atas nama warga yang
 * kesulitan menggunakan aplikasi (proposal 1.6.2). Tanggung jawab
 * administratif tercatat berada pada Kepala Dusun (dibuat_oleh_kadus = true).
 */
class SuratController extends Controller
{
    /**
     * Daftar permohonan surat yang DIBUATKAN oleh Kepala Dusun untuk warganya.
     * Sengaja TIDAK menampilkan permohonan yang diajukan warga secara mandiri
     * lewat akunnya sendiri - itu bersifat pribadi milik warga tersebut, Kepala
     * Dusun tidak diberi notifikasi maupun akses lihat/unduh atas surat itu.
     */
    public function index(Request $request)
    {
        $dusunId = Auth::user()->dusun_id;

        $query = SuratRequest::with(['suratType', 'warga'])
            ->where('dibuat_oleh_kadus', true)
            ->whereHas('warga', fn ($q) => $q->where('dusun_id', $dusunId))
            ->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $suratRequests = $query->paginate(15)->withQueryString();

        return view('kadus.surat.index', compact('suratRequests'));
    }

    public function show(SuratRequest $surat)
    {
        $this->authorizeAksesKadus($surat);

        $surat->load(['suratType', 'warga', 'pemohon', 'logs', 'lampirans']);

        return view('kadus.surat.show', compact('surat'));
    }

    public function download(SuratRequest $surat)
    {
        $this->authorizeAksesKadus($surat);

        if (! $surat->file_path || ! \Illuminate\Support\Facades\Storage::disk('public')->exists($surat->file_path)) {
            abort(404, 'Dokumen surat belum tersedia.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($surat->file_path, $surat->kode_permohonan.'.pdf');
    }

    /** Kepala Dusun hanya boleh mengakses surat yang ia buatkan sendiri, di dusunnya. */
    protected function authorizeAksesKadus(SuratRequest $surat): void
    {
        abort_unless(
            $surat->dibuat_oleh_kadus && $surat->warga->dusun_id === Auth::user()->dusun_id,
            403,
            'Permohonan surat pribadi warga tidak dapat diakses oleh Kepala Dusun.'
        );
    }

    public function create()
    {
        $kadus = Auth::user();
        $dusunId = $kadus->dusun_id;
        $suratTypes = SuratType::where('aktif', true)->orderBy('nama')->get();

        $wargaDusun = Warga::where('dusun_id', $dusunId)
            ->where('status_kependudukan', 'hidup')
            ->when($kadus->warga_id, fn ($q) => $q->where('id', '!=', $kadus->warga_id))
            ->orderBy('nama')
            ->get();

        $profilSaya = $kadus->warga; // null jika belum ditautkan

        return view('kadus.surat.create', compact('suratTypes', 'wargaDusun', 'profilSaya'));
    }

    public function store(Request $request, SuratWorkflowService $workflow)
    {
        $kadus = Auth::user();

        $suratType = SuratType::findOrFail($request->input('surat_type_id'));

        $data = $request->validate([
            'warga_id' => ['required', 'exists:warga,id'],
            'surat_type_id' => ['required', 'exists:surat_types,id'],
            'alasan' => ['required', 'string', 'max:1000'],
            'lampiran' => [$suratType->perlu_lampiran ? 'required' : 'nullable', 'array', 'min:1'],
            'lampiran.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ], [
            'lampiran.required' => "Lampiran \"{$suratType->label_lampiran}\" wajib diunggah untuk jenis surat ini.",
        ]);

        $warga = Warga::where('id', $data['warga_id'])->where('dusun_id', $kadus->dusun_id)->firstOrFail();

        $untukDiriSendiri = $warga->id === $kadus->warga_id;

        // Permohonan tetap tercatat atas "pemohon" (user_id). Karena warga mungkin
        // belum punya akun, gunakan akun kadus sebagai pemohon administratif,
        // namun subjek surat (warga_id) tetap warga yang bersangkutan.
        $pemohonUserId = $untukDiriSendiri ? $kadus->id : ($warga->akun?->id ?? $kadus->id);

        $keterangan = $untukDiriSendiri
            ? ' (Diajukan sendiri oleh Kepala Dusun.)'
            : ' (Diajukan oleh Kepala Dusun atas nama warga.)';

        $surat = SuratRequest::create([
            'kode_permohonan' => 'KP-'.now()->format('Ymd').'-'.Str::upper(Str::random(5)),
            'user_id' => $pemohonUserId,
            'warga_id' => $warga->id,
            'surat_type_id' => $data['surat_type_id'],
            'alasan' => $data['alasan'].$keterangan,
            'status' => SuratRequest::STATUS_DIAJUKAN,
            'dibuat_oleh_kadus' => true,
        ]);

        foreach ($request->file('lampiran', []) as $berkas) {
            $surat->lampirans()->create([
                'file_path' => $berkas->store('lampiran-surat', 'public'),
                'nama_asli' => $berkas->getClientOriginalName(),
            ]);
        }

        $workflow->ajukan($surat);

        return redirect()->route('kadus.surat.show', $surat)
            ->with('status', $untukDiriSendiri
                ? 'Permohonan surat untuk diri sendiri berhasil diajukan.'
                : "Permohonan surat untuk {$warga->nama} berhasil diajukan.");
    }
}

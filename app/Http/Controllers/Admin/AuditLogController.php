<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Halaman transparansi: Admin dapat menelusuri seluruh jejak aktivitas penting
 * dalam sistem (siapa mengubah apa, kapan, dari IP mana). Berguna sebagai bukti
 * akuntabilitas ketika sistem diaudit/diajukan ke tingkat kabupaten.
 */
class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::latest('created_at');

        if ($aksi = $request->get('aksi')) {
            $query->where('aksi', $aksi);
        }

        $logs = $query->paginate(30)->withQueryString();
        $daftarAksi = AuditLog::select('aksi')->distinct()->pluck('aksi');

        return view('admin.audit.index', compact('logs', 'daftarAksi'));
    }
}

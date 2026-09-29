<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah opsi status 'pindah' pada enum status_kependudukan (MySQL).
        DB::statement("ALTER TABLE warga MODIFY COLUMN status_kependudukan ENUM('hidup','meninggal','pindah') NOT NULL DEFAULT 'hidup'");

        Schema::table('warga', function (Blueprint $table) {
            $table->string('alamat_tujuan_pindah')->nullable()->after('tanggal_meninggal');
            $table->date('tanggal_pindah')->nullable()->after('alamat_tujuan_pindah');
        });
    }

    public function down(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->dropColumn(['alamat_tujuan_pindah', 'tanggal_pindah']);
        });

        DB::statement("ALTER TABLE warga MODIFY COLUMN status_kependudukan ENUM('hidup','meninggal') NOT NULL DEFAULT 'hidup'");
    }
};

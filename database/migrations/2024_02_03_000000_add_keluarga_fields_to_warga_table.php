<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->enum('status_hubungan_keluarga', [
                'kepala_keluarga', 'suami', 'istri', 'anak', 'menantu',
                'cucu', 'orang_tua', 'mertua', 'famili_lain', 'lainnya',
            ])->default('anak')->after('jenis_kelamin');

            $table->string('nama_ayah')->nullable()->after('tempat_lahir');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
        });
    }

    public function down(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->dropColumn(['status_hubungan_keluarga', 'nama_ayah', 'nama_ibu']);
        });
    }
};

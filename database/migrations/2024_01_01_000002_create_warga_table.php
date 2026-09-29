<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warga', function (Blueprint $table) {
            $table->id();

            // NIK & No. KK adalah data pribadi sensitif -> disimpan terenkripsi.
            // nik_hash / no_kk_hash adalah blind-index (HMAC) untuk pencarian exact-match
            // tanpa perlu mendekripsi seluruh tabel setiap query.
            $table->text('nik')->comment('Terenkripsi');
            $table->string('nik_hash', 64)->unique();
            $table->text('no_kk')->comment('Terenkripsi');
            $table->string('no_kk_hash', 64)->index();

            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->foreignId('dusun_id')->constrained('dusuns')->cascadeOnDelete();
            $table->enum('status_ekonomi', ['mampu', 'kurang_mampu', 'tidak_mampu'])->default('mampu');
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai_hidup', 'cerai_mati'])->default('belum_menikah');
            $table->enum('status_pendidikan', ['tidak_sekolah', 'sd', 'smp', 'sma', 'diploma', 's1', 's2', 's3'])->default('sd');
            $table->string('pekerjaan')->nullable();
            $table->enum('status_kependudukan', ['hidup', 'meninggal'])->default('hidup');
            $table->date('tanggal_meninggal')->nullable();
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warga');
    }
};

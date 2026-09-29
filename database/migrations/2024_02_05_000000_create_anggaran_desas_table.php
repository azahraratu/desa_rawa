<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Data anggaran/APBDes per tahun, supaya warga bisa melihat besaran & realisasi
 * anggaran desa (mendukung motto transparansi di homepage).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggaran_desas', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('tahun');
            $table->enum('jenis', ['pendapatan', 'belanja']);
            $table->string('kategori', 150); // mis. "Dana Desa (DD)", "Bidang Pembangunan"
            $table->text('keterangan')->nullable(); // penjelasan pemanfaatan
            $table->decimal('jumlah_anggaran', 16, 2)->default(0);
            $table->decimal('jumlah_realisasi', 16, 2)->default(0);
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamps();

            $table->foreign('dibuat_oleh')->references('id')->on('users')->nullOnDelete();
            $table->index(['tahun', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggaran_desas');
    }
};

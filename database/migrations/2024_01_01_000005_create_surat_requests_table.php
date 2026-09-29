<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_requests', function (Blueprint $table) {
            $table->id();
            $table->string('kode_permohonan', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('warga_id')->constrained('warga')->cascadeOnDelete();
            $table->foreignId('surat_type_id')->constrained('surat_types')->cascadeOnDelete();
            $table->text('alasan');
            $table->enum('status', ['diajukan', 'diproses', 'ditandatangani', 'selesai', 'ditolak'])->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('dibuat_oleh_kadus')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_requests');
    }
};

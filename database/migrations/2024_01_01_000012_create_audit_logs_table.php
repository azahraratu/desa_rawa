<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('causer_id')->nullable()->comment('User yang melakukan aksi');
            $table->string('causer_nama')->nullable();
            $table->string('causer_role')->nullable();
            $table->string('aksi', 30)->comment('created | updated | deleted | login | login_failed | logout | proses_khusus');
            $table->string('subjek_type')->nullable()->comment('Nama model, contoh: App\\Models\\Warga');
            $table->unsignedBigInteger('subjek_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('data_perubahan')->nullable()->comment('Ringkasan field yang berubah (tanpa data sensitif mentah)');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index(['subjek_type', 'subjek_id']);
            $table->index('causer_id');
            $table->index('aksi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

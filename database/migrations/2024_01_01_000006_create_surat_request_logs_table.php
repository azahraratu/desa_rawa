<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_request_id')->constrained('surat_requests')->cascadeOnDelete();
            $table->string('status');
            $table->text('keterangan')->nullable();
            $table->boolean('wa_terkirim')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_request_logs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dusun_id')->constrained('dusuns')->cascadeOnDelete();
            $table->string('nama_rt', 20);
            $table->string('ketua_rt')->nullable();
            $table->timestamps();

            $table->unique(['dusun_id', 'nama_rt']);
        });

        Schema::table('warga', function (Blueprint $table) {
            $table->foreignId('rt_id')->nullable()->after('dusun_id')->constrained('rts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('warga', function (Blueprint $table) {
            $table->dropConstrainedForeignId('rt_id');
        });

        Schema::dropIfExists('rts');
    }
};

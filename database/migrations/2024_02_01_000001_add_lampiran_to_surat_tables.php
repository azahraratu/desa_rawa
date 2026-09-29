<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->boolean('perlu_lampiran')->default(false)->after('persyaratan');
            $table->string('label_lampiran')->nullable()->after('perlu_lampiran');
        });

        Schema::table('surat_requests', function (Blueprint $table) {
            $table->string('lampiran_path')->nullable()->after('alasan');
        });
    }

    public function down(): void
    {
        Schema::table('surat_types', function (Blueprint $table) {
            $table->dropColumn(['perlu_lampiran', 'label_lampiran']);
        });

        Schema::table('surat_requests', function (Blueprint $table) {
            $table->dropColumn('lampiran_path');
        });
    }
};

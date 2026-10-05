<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaturan_sekolah', function (Blueprint $table) {
            $table->json('struktur_organisasi')->nullable()->after('gambar_struktur');
        });
    }

    public function down(): void
    {
        Schema::table('pengaturan_sekolah', function (Blueprint $table) {
            $table->dropColumn('struktur_organisasi');
        });
    }
};

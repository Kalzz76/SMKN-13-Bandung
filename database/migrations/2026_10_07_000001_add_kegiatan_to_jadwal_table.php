<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreignId('id_mapel')->nullable()->change();
            $table->foreignId('id_ruangan')->nullable()->change();
            $table->boolean('is_kegiatan')->default(false)->after('hari');
            $table->string('nama_kegiatan')->nullable()->after('is_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn(['is_kegiatan', 'nama_kegiatan']);
            $table->foreignId('id_mapel')->nullable(false)->change();
            $table->foreignId('id_ruangan')->nullable(false)->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kelas') && !Schema::hasColumn('kelas', 'struktur')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->json('struktur')->nullable()->after('id_wali_kelas');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kelas') && Schema::hasColumn('kelas', 'struktur')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropColumn('struktur');
            });
        }
    }
};

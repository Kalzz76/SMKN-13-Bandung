<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('guru') && !Schema::hasColumn('guru', 'id_mapel')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->foreignId('id_mapel')->nullable()->after('jabatan')->constrained('mapel')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('guru') && Schema::hasColumn('guru', 'id_mapel')) {
            Schema::table('guru', function (Blueprint $table) {
                $table->dropForeign(['id_mapel']);
                $table->dropColumn('id_mapel');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mapel')) {
            if (!Schema::hasColumn('mapel', 'jenis')) {
                Schema::table('mapel', function (Blueprint $table) {
                    $table->string('jenis')->default('Umum')->after('nama');
                });
            }

            if (Schema::hasColumn('mapel', 'kelompok')) {
                DB::statement("ALTER TABLE mapel MODIFY COLUMN kelompok VARCHAR(255) NULL DEFAULT NULL");
                DB::table('mapel')->whereNull('jenis')->orWhere('jenis', '')->update([
                    'jenis' => DB::raw('kelompok'),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('mapel') && Schema::hasColumn('mapel', 'jenis') && Schema::hasColumn('mapel', 'kelompok')) {
            Schema::table('mapel', function (Blueprint $table) {
                $table->dropColumn('jenis');
            });
        }
    }
};

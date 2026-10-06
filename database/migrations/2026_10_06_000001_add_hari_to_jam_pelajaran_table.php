<?php

use Database\Seeders\JamPelajaranSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->string('hari')->nullable()->after('id');
            $table->string('keterangan')->nullable()->after('nama');
        });

        DB::table('jam_pelajaran')->delete();

        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->unique(['hari', 'urutan']);
        });

        (new JamPelajaranSeeder())->run();
    }

    public function down(): void
    {
        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->dropUnique(['hari', 'urutan']);
        });

        Schema::table('jam_pelajaran', function (Blueprint $table) {
            $table->dropColumn(['hari', 'keterangan']);
        });
    }
};

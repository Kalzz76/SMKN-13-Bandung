<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
            $table->foreign('id_kelas')->references('id')->on('kelas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
            $table->foreign('id_kelas')->references('id')->on('kelas')->restrictOnDelete();
        });
    }
};

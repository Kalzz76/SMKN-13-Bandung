<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')->constrained('kelas')->restrictOnDelete();
            $table->foreignId('id_mapel')->constrained('mapel')->restrictOnDelete();
            $table->foreignId('id_guru')->constrained('guru')->restrictOnDelete();
            $table->foreignId('id_ruangan')->constrained('ruangan')->restrictOnDelete();
            $table->string('hari');
            $table->integer('jam_ke_mulai');
            $table->integer('jam_ke_selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal')->constrained('jadwal')->cascadeOnDelete();
            $table->foreignId('id_siswa')->constrained('siswa')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('status');
            $table->string('keterangan')->nullable();
            $table->foreignId('id_guru_pengisi')->constrained('guru')->cascadeOnDelete();
            $table->unique(['id_jadwal', 'id_siswa', 'tanggal']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa');
    }
};

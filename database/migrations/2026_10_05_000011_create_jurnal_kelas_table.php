<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurnal_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal')->constrained('jadwal')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('materi');
            $table->foreignId('id_guru')->constrained('guru')->cascadeOnDelete();
            $table->unique(['id_jadwal', 'tanggal']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurnal_kelas');
    }
};

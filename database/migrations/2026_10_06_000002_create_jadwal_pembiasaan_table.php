<?php

use Database\Seeders\JadwalPembiasaanSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pembiasaan', function (Blueprint $table) {
            $table->id();
            $table->string('hari');
            $table->unsignedTinyInteger('sesi');
            $table->string('kelompok', 1);
            $table->string('kegiatan');
            $table->string('lokasi')->nullable();
            $table->timestamps();

            $table->unique(['hari', 'sesi', 'kelompok']);
        });

        (new JadwalPembiasaanSeeder())->run();
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pembiasaan');
    }
};

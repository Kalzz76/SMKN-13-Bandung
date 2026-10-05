<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sekolah');
            $table->string('npsn')->nullable();
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('social_media')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->string('logo')->nullable();
            $table->string('slogan')->nullable();
            $table->text('deskripsi_singkat')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->string('nama_kepsek')->nullable();
            $table->string('foto_kepsek')->nullable();
            $table->string('judul_sambutan')->nullable();
            $table->text('sambutan')->nullable();
            $table->string('gambar_struktur')->nullable();
            $table->decimal('lat_sekolah', 10, 8)->nullable();
            $table->decimal('long_sekolah', 11, 8)->nullable();
            $table->integer('radius_meter')->default(100);
            $table->string('jam_masuk')->default('07:00');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sekolah');
    }
};

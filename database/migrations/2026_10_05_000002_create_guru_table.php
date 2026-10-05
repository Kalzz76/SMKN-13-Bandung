<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jenis')->default('Guru');
            $table->string('jabatan')->nullable();
            $table->string('mapel_utama')->nullable();
            $table->string('foto')->nullable();
            $table->boolean('tampil_publik')->default(true);
            $table->string('kode_barcode')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};

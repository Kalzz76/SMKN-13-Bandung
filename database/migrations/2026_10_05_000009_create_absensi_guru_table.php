<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->constrained('guru')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->string('status');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('jarak_meter')->nullable();
            $table->string('metode');
            $table->text('alasan')->nullable();
            $table->foreignId('id_user_input')->nullable()->constrained('users')->nullOnDelete();
            $table->unique(['id_guru', 'tanggal']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->string('status_validasi')->default('disetujui')->after('keterangan');
            $table->string('diisi_oleh')->default('guru')->after('status_validasi');
        });

        Schema::create('pengajuan_izin_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_guru')->constrained('guru')->cascadeOnDelete();
            $table->string('jenis_izin'); // Sakit, Izin, Terlambat, Cuti, Tugas Luar
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('jam_estimasi')->nullable(); // Untuk jenis Terlambat
            $table->text('alasan');
            $table->string('bukti')->nullable();
            $table->string('status')->default('Menunggu'); // Menunggu, Disetujui, Ditolak
            $table->text('catatan_admin')->nullable();
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin_guru');

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->dropColumn(['status_validasi', 'diisi_oleh']);
        });
    }
};

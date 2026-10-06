<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'nisn')) {
                $table->string('nisn')->nullable()->after('nis');
            }
            if (!Schema::hasColumn('siswa', 'jabatan')) {
                $table->string('jabatan')->nullable()->default('Anggota')->after('nama');
            }
            if (!Schema::hasColumn('siswa', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('jabatan')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('siswa', 'jabatan')) {
                $table->dropColumn('jabatan');
            }
            if (Schema::hasColumn('siswa', 'nisn')) {
                $table->dropColumn('nisn');
            }
        });
    }
};

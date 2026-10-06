<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeri_id')->constrained('galeri')->cascadeOnDelete();
            $table->string('foto');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        $existingGaleri = DB::table('galeri')->get();
        foreach ($existingGaleri as $g) {
            if (!empty($g->foto)) {
                DB::table('galeri_foto')->insert([
                    'galeri_id' => $g->id,
                    'foto' => $g->foto,
                    'urutan' => 0,
                    'created_at' => $g->created_at ?? now(),
                    'updated_at' => $g->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_foto');
    }
};

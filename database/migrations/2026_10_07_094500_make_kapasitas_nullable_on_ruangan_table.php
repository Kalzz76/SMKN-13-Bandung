<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ruangan') && Schema::hasColumn('ruangan', 'kapasitas')) {
            DB::statement("ALTER TABLE ruangan MODIFY COLUMN kapasitas INT NULL DEFAULT 36");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ruangan') && Schema::hasColumn('ruangan', 'kapasitas')) {
            DB::statement("ALTER TABLE ruangan MODIFY COLUMN kapasitas INT NOT NULL DEFAULT 36");
        }
    }
};

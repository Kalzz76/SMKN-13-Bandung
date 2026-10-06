<?php

namespace App\Providers;

use App\Models\PengaturanSekolah;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            static $pengaturan = null;
            if ($pengaturan === null && Schema::hasTable('pengaturan_sekolah')) {
                $pengaturan = PengaturanSekolah::first();
            }
            $view->with('sitePengaturan', $pengaturan);
        });
    }
}

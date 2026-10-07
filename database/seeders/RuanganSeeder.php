<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruanganList = [
            ['nama' => 'RUANG 20', 'kode' => 'R.20'],
            ['nama' => 'RUANG 22 LAB INSTRUMEN BARU', 'kode' => 'R.22'],
            ['nama' => 'RUANG 24 LAB SEKAT', 'kode' => 'R.24'],
            ['nama' => 'RUANG 25 LAB ATG', 'kode' => 'R.ATG'],
            ['nama' => 'RUANG 28 LAB ORGANIK', 'kode' => 'R.28'],
            ['nama' => 'RUANG 29', 'kode' => 'R.29'],
            ['nama' => 'RUANG 30', 'kode' => 'R.30'],
            ['nama' => 'RUANG 32', 'kode' => 'R.32'],
            ['nama' => 'RUANG 31 LAB MIKRO', 'kode' => 'Lab Mikro'],
            ['nama' => 'RUANG 33 LAB TKJ', 'kode' => 'R.33'],
            ['nama' => 'RUANG 34 LAB TKJ', 'kode' => 'R.34'],
            ['nama' => 'RUANG 37 LAB TDPLK', 'kode' => 'R.37'],
            ['nama' => 'RUANG 38 LAB TKJ', 'kode' => 'R.38'],
            ['nama' => 'RUANG 39 LAB TKJ', 'kode' => 'R.39'],
            ['nama' => 'RUANG 40 LAB TKJ', 'kode' => 'R.40'],
            ['nama' => 'RUANG 41 LAB TKJ', 'kode' => 'R.41'],
            ['nama' => 'RUANG 42 LAB RPL', 'kode' => 'R.42'],
            ['nama' => 'RUANG MULTIMEDIA', 'kode' => 'R.MM'],
            ['nama' => 'MASJID BAWAH', 'kode' => 'MASJID B'],
            ['nama' => 'RUANG 44', 'kode' => 'R.44'],
            ['nama' => 'RUANG 45', 'kode' => 'R.45'],
            ['nama' => 'RUANG 46', 'kode' => 'R.46'],
            ['nama' => 'RUANG 47', 'kode' => 'R.47'],
            ['nama' => 'RUANG 48', 'kode' => 'R.48'],
            ['nama' => 'RUANG 51', 'kode' => 'R.51'],
            ['nama' => 'RUANG 52', 'kode' => 'R.52'],
            ['nama' => 'RUANG 53', 'kode' => 'R.53'],
            ['nama' => 'RUANG 54', 'kode' => 'R.54'],
            ['nama' => 'RUANG 55', 'kode' => 'R.55'],
            ['nama' => 'RUANG 56', 'kode' => 'R.56'],
            ['nama' => 'RUANG 57', 'kode' => 'R.57'],
            ['nama' => 'RUANG 58', 'kode' => 'R.58'],
            ['nama' => 'RUANG 60', 'kode' => 'R.60'],
            ['nama' => 'RUANG 61', 'kode' => 'R.61'],
            ['nama' => 'RUANG 62', 'kode' => 'R.62'],
            ['nama' => 'RUANG 63', 'kode' => 'R.63'],
            ['nama' => 'LAPANGAN', 'kode' => 'LAP'],
            ['nama' => 'RUANG 60A', 'kode' => 'R.60A'],
            ['nama' => 'RUANG 60B', 'kode' => 'R.60B'],
            ['nama' => 'MASJID ATAS', 'kode' => 'MASJID A'],
            ['nama' => 'RUANG PODCAST', 'kode' => 'R.POD'],
            ['nama' => 'RUANG SAMSUNG', 'kode' => 'R.SAM1'],
            ['nama' => 'RUANG PERPUS LAMA 1', 'kode' => 'R.PUS1'],
            ['nama' => 'RUANG PERPUS LAMA 2', 'kode' => 'R.PUS2'],
            ['nama' => 'RUANG SAMSUNG 2', 'kode' => 'R.SAM2'],
            ['nama' => 'Lab DPK Kimia', 'kode' => 'R.DPK'],
            ['nama' => 'LAB KUALI', 'kode' => 'R.KUALI'],
            ['nama' => 'LAB AKI XI', 'kode' => 'R.AKI XI'],
            ['nama' => 'Lab AKI XII', 'kode' => 'R.AKI XII'],
            ['nama' => 'Lab BOBA', 'kode' => 'Lab BOBA'],
            ['nama' => 'Perpustakaan', 'kode' => 'Perpus'],
        ];

        foreach ($ruanganList as $r) {
            Ruangan::updateOrCreate(
                ['kode' => $r['kode']],
                ['nama' => $r['nama']]
            );
        }
    }
}

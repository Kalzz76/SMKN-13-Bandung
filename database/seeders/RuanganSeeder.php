<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruanganList = [
            ['nama' => 'Ruang 19', 'kode' => 'R.19'],
            ['nama' => 'Ruang 20', 'kode' => 'R.20'],
            ['nama' => 'Ruang 29', 'kode' => 'R.29'],
            ['nama' => 'Ruang 30', 'kode' => 'R.30'],
            ['nama' => 'Ruang 32', 'kode' => 'R.32'],
            ['nama' => 'Ruang 44', 'kode' => 'R.44'],
            ['nama' => 'Ruang 45', 'kode' => 'R.45'],
            ['nama' => 'Ruang 46', 'kode' => 'R.46'],
            ['nama' => 'Ruang 47', 'kode' => 'R.47'],
            ['nama' => 'Ruang 48', 'kode' => 'R.48'],
            ['nama' => 'Ruang 51', 'kode' => 'R.51'],
            ['nama' => 'Ruang 52', 'kode' => 'R.52'],
            ['nama' => 'Ruang 53', 'kode' => 'R.53'],
            ['nama' => 'Ruang 54', 'kode' => 'R.54'],
            ['nama' => 'Ruang 55', 'kode' => 'R.55'],
            ['nama' => 'Ruang 56', 'kode' => 'R.56'],
            ['nama' => 'Ruang 57', 'kode' => 'R.57'],
            ['nama' => 'Ruang 58', 'kode' => 'R.58'],
            ['nama' => 'Ruang 60', 'kode' => 'R.60'],
            ['nama' => 'Ruang 60A', 'kode' => 'R.60A'],
            ['nama' => 'Ruang 60B', 'kode' => 'R.60B'],
            ['nama' => 'Ruang 61', 'kode' => 'R.61'],
            ['nama' => 'Ruang 62', 'kode' => 'R.62'],
            ['nama' => 'Ruang 63', 'kode' => 'R.63'],
            ['nama' => 'Ruang 33 Lab TKJ', 'kode' => 'R.33'],
            ['nama' => 'Ruang 34 Lab TKJ', 'kode' => 'R.34'],
            ['nama' => 'Ruang 38 Lab TKJ', 'kode' => 'R.38'],
            ['nama' => 'Ruang 39 Lab TKJ', 'kode' => 'R.39'],
            ['nama' => 'Ruang 40 Lab TKJ', 'kode' => 'R.40'],
            ['nama' => 'Ruang 41 Lab TKJ', 'kode' => 'R.41'],
            ['nama' => 'Ruang 42 Lab RPL', 'kode' => 'R.42'],
            ['nama' => 'Ruang Multimedia', 'kode' => 'R.MM'],
            ['nama' => 'Ruang 22 Lab Instrumen Baru', 'kode' => 'R.22'],
            ['nama' => 'Ruang 24 Lab Sekat', 'kode' => 'R.24'],
            ['nama' => 'Ruang 25 Lab ATG', 'kode' => 'R.ATG'],
            ['nama' => 'Ruang 28 Lab Organik', 'kode' => 'R.28'],
            ['nama' => 'Ruang 31 Lab Mikro', 'kode' => 'Lab Mikro'],
            ['nama' => 'Ruang 37 Lab TDPLK', 'kode' => 'R.37'],
            ['nama' => 'Lab DPK Kimia', 'kode' => 'R.DPK'],
            ['nama' => 'Lab Kuali', 'kode' => 'R.KUALI'],
            ['nama' => 'Lab AKI XI', 'kode' => 'R.AKI XI'],
            ['nama' => 'Lab AKI XII', 'kode' => 'R.AKI XII'],
            ['nama' => 'Lab BOBA', 'kode' => 'Lab BOBA'],
            ['nama' => 'Ruang Podcast', 'kode' => 'R.POD'],
            ['nama' => 'Ruang Samsung', 'kode' => 'R.SAM1'],
            ['nama' => 'Ruang Samsung 2', 'kode' => 'R.SAM2'],
            ['nama' => 'Ruang Perpus Lama 1', 'kode' => 'R.PUS1'],
            ['nama' => 'Ruang Perpus Lama 2', 'kode' => 'R.PUS2'],
            ['nama' => 'Perpustakaan', 'kode' => 'Perpus'],
            ['nama' => 'Masjid Atas', 'kode' => 'MASJID A'],
            ['nama' => 'Masjid Bawah', 'kode' => 'MASJID B'],
            ['nama' => 'Lapangan Olahraga/Upacara', 'kode' => 'LAP'],
            ['nama' => 'Ruang Pertemuan / Meeting', 'kode' => 'R.MEETING'],
        ];

        foreach ($ruanganList as $r) {
            Ruangan::updateOrCreate(
                ['kode' => $r['kode']],
                ['nama' => $r['nama']]
            );
        }
    }
}

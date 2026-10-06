<?php

namespace Database\Seeders;

use App\Models\JadwalPembiasaan;
use Illuminate\Database\Seeder;

class JadwalPembiasaanSeeder extends Seeder
{
    public function run(): void
    {
        JadwalPembiasaan::query()->delete();

        $aturan = [
            ['hari' => 'Senin', 'sesi' => 1, 'kelompok' => 'A', 'kegiatan' => 'Perwalian', 'lokasi' => 'Ruang Kelas'],
            ['hari' => 'Senin', 'sesi' => 1, 'kelompok' => 'B', 'kegiatan' => 'Upacara Bendera', 'lokasi' => 'Lapangan'],
            ['hari' => 'Senin', 'sesi' => 2, 'kelompok' => 'A', 'kegiatan' => 'Upacara Bendera', 'lokasi' => 'Lapangan'],
            ['hari' => 'Senin', 'sesi' => 2, 'kelompok' => 'B', 'kegiatan' => 'Perwalian', 'lokasi' => 'Ruang Kelas'],
        ];

        foreach ($aturan as $item) {
            JadwalPembiasaan::create($item);
        }
    }
}

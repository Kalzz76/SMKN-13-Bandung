<?php

namespace Database\Seeders;

use App\Models\JamPelajaran;
use Illuminate\Database\Seeder;

class JamPelajaranSeeder extends Seeder
{
    private const DURASI_JAM_MENIT = 45;

    public static function master(): array
    {
        return [
            'Senin' => [
                'pembiasaan' => [
                    'nama' => 'Carabika',
                    'keterangan' => 'Upacara Bendera / Perwalian (bergantian per sesi)',
                    'mulai' => '06:30',
                    'selesai' => '07:30',
                ],
                'blok' => [
                    ['tipe' => 'pelajaran', 'mulai' => '07:30', 'jumlah' => 3],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 1', 'mulai' => '09:45', 'selesai' => '10:00'],
                    ['tipe' => 'pelajaran', 'mulai' => '10:00', 'jumlah' => 2],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 2 / Dzuhur', 'mulai' => '11:30', 'selesai' => '12:30'],
                    ['tipe' => 'pelajaran', 'mulai' => '12:30', 'jumlah' => 5],
                ],
            ],
            'Selasa' => [
                'pembiasaan' => [
                    'nama' => 'Saji Bajigur',
                    'keterangan' => 'Tadarus & Sarapan Bergizi',
                    'mulai' => '06:30',
                    'selesai' => '06:45',
                ],
                'blok' => [
                    ['tipe' => 'pelajaran', 'mulai' => '06:45', 'jumlah' => 4],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 1', 'mulai' => '09:45', 'selesai' => '10:00'],
                    ['tipe' => 'pelajaran', 'mulai' => '10:00', 'jumlah' => 2],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 2', 'mulai' => '11:30', 'selesai' => '12:30'],
                    ['tipe' => 'pelajaran', 'mulai' => '12:30', 'jumlah' => 5],
                ],
            ],
            'Rabu' => [
                'pembiasaan' => [
                    'nama' => 'Ladu Raos',
                    'keterangan' => 'Sholat Dhuha & Tadarus / PAK',
                    'mulai' => '06:30',
                    'selesai' => '07:30',
                ],
                'blok' => [
                    ['tipe' => 'pelajaran', 'mulai' => '07:30', 'jumlah' => 3],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 1', 'mulai' => '09:45', 'selesai' => '10:00'],
                    ['tipe' => 'pelajaran', 'mulai' => '10:00', 'jumlah' => 2],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 2', 'mulai' => '11:30', 'selesai' => '12:30'],
                    ['tipe' => 'pelajaran', 'mulai' => '12:30', 'jumlah' => 5],
                ],
            ],
            'Kamis' => [
                'pembiasaan' => [
                    'nama' => 'Tarasi Amis',
                    'keterangan' => 'Tadarus & Literasi',
                    'mulai' => '06:30',
                    'selesai' => '06:45',
                ],
                'blok' => [
                    ['tipe' => 'pelajaran', 'mulai' => '06:45', 'jumlah' => 4],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 1', 'mulai' => '09:45', 'selesai' => '10:00'],
                    ['tipe' => 'pelajaran', 'mulai' => '10:00', 'jumlah' => 2],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat 2', 'mulai' => '11:30', 'selesai' => '12:30'],
                    ['tipe' => 'pelajaran', 'mulai' => '12:30', 'jumlah' => 5],
                ],
            ],
            'Jumat' => [
                'pembiasaan' => [
                    'nama' => 'Jalabria',
                    'keterangan' => 'Senam & Jumat Bersih',
                    'mulai' => '06:30',
                    'selesai' => '07:15',
                ],
                'blok' => [
                    ['tipe' => 'pelajaran', 'mulai' => '07:15', 'jumlah' => 6],
                    ['tipe' => 'istirahat', 'nama' => 'Istirahat Jumat & Dzuhur', 'mulai' => '11:45', 'selesai' => '13:00'],
                    ['tipe' => 'pelajaran', 'mulai' => '13:00', 'jumlah' => 4],
                ],
            ],
        ];
    }

    public function run(): void
    {
        JamPelajaran::query()->delete();

        foreach (self::master() as $hari => $definisi) {
            $urutan = 1;
            $jamKe = 0;

            JamPelajaran::create([
                'hari' => $hari,
                'nama' => $definisi['pembiasaan']['nama'],
                'keterangan' => $definisi['pembiasaan']['keterangan'],
                'jenis' => JamPelajaran::JENIS_PEMBIASAAN,
                'jam_ke' => null,
                'jam_mulai' => $definisi['pembiasaan']['mulai'],
                'jam_selesai' => $definisi['pembiasaan']['selesai'],
                'urutan' => $urutan++,
            ]);

            foreach ($definisi['blok'] as $blok) {
                if ($blok['tipe'] === 'istirahat') {
                    JamPelajaran::create([
                        'hari' => $hari,
                        'nama' => $blok['nama'],
                        'keterangan' => null,
                        'jenis' => JamPelajaran::JENIS_ISTIRAHAT,
                        'jam_ke' => null,
                        'jam_mulai' => $blok['mulai'],
                        'jam_selesai' => $blok['selesai'],
                        'urutan' => $urutan++,
                    ]);
                    continue;
                }

                $mulai = $blok['mulai'];
                for ($i = 0; $i < $blok['jumlah']; $i++) {
                    $selesai = $this->tambahMenit($mulai, self::DURASI_JAM_MENIT);
                    $jamKe++;

                    JamPelajaran::create([
                        'hari' => $hari,
                        'nama' => 'Jam ' . $jamKe,
                        'keterangan' => null,
                        'jenis' => JamPelajaran::JENIS_PELAJARAN,
                        'jam_ke' => $jamKe,
                        'jam_mulai' => $mulai,
                        'jam_selesai' => $selesai,
                        'urutan' => $urutan++,
                    ]);

                    $mulai = $selesai;
                }
            }
        }
    }

    private function tambahMenit(string $jam, int $menit): string
    {
        [$j, $m] = array_map('intval', explode(':', $jam));
        $total = $j * 60 + $m + $menit;

        return sprintf('%02d:%02d', intdiv($total, 60), $total % 60);
    }
}

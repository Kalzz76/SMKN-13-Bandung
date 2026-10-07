<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds for XII RPL 1 schedule.
     */
    public function run(): void
    {
        // 1. Dapatkan kelas XII RPL 1
        $kelas = Kelas::where('nama', 'XII RPL 1')->first();
        if (!$kelas) {
            $ruangLab = Ruangan::where('kode', 'R.42')->first();
            $waliKelas = Guru::where('nama', 'like', '%KIKI AIMA%')->first() ?? Guru::first();
            $kelas = Kelas::create([
                'nama' => 'XII RPL 1',
                'id_ruangan' => $ruangLab?->id,
                'id_wali_kelas' => $waliKelas?->id,
            ]);
        }

        // 2. Pastikan mapel PERSIAPAN TKA ada
        Mapel::updateOrCreate(
            ['kode' => 'PERSIAPAN TKA'],
            ['nama' => 'Persiapan TKA', 'jenis' => 'Umum']
        );

        // 3. Pastikan ANNISA memiliki relasi mapel PKK/KIK jika belum terhubung
        $guruAnnisa = Guru::where('nama', 'like', '%ANNISA INTIKARUSDIANSARI%')->first();
        $mapelPkk = Mapel::where('kode', 'PKK/KIK')->first();
        if ($guruAnnisa && $mapelPkk) {
            $guruAnnisa->mapels()->syncWithoutDetaching([$mapelPkk->id]);
            if (!$guruAnnisa->id_mapel) {
                $guruAnnisa->update(['id_mapel' => $mapelPkk->id]);
            }
        }

        // 4. Data jadwal pelajaran kelas XII RPL 1 (Senin s.d. Jumat)
        $daftarJadwal = [
            // ==========================================
            // SENIN
            // Pembiasaan: 06.30 - 07.30 (Upacara / Perwalian)
            // ==========================================
            // 07.30 – 08.15: Mapel = BIND | Ruang = R.51 | Guru = RINA (Jam 1)
            // 08.15 – 09.00: Mapel = BIND | Ruang = R.51 | Guru = RINA (Jam 2)
            [
                'hari' => 'Senin',
                'mapel_kode' => 'BIND',
                'ruang_kode' => 'R.51',
                'guru_keyword' => 'RINA DARYANI',
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 2,
            ],
            // 09.00 - 09.45: Mapel = PABP | Ruang = R.51 | Guru = RUKMANA (Jam 3)
            // (Jeda Istirahat: 09.45 – 10.00)
            [
                'hari' => 'Senin',
                'mapel_kode' => 'PABP',
                'ruang_kode' => 'R.51',
                'guru_keyword' => 'RUKMANA',
                'jam_ke_mulai' => 3,
                'jam_ke_selesai' => 3,
            ],
            // 10.00 – 10.45: Mapel = PABP | Ruang = R.51 | Guru = RUKMANA (Jam 4)
            [
                'hari' => 'Senin',
                'mapel_kode' => 'PABP',
                'ruang_kode' => 'R.51',
                'guru_keyword' => 'RUKMANA',
                'jam_ke_mulai' => 4,
                'jam_ke_selesai' => 4,
            ],
            // 10.45 – 11.30: Mapel = PPB | Ruang = R.41 | Guru = NUR (Jam 5)
            // (Jeda Istirahat: 11.30 – 12.30)
            [
                'hari' => 'Senin',
                'mapel_kode' => 'PPB',
                'ruang_kode' => 'R.41',
                'guru_keyword' => 'NUR FAUZIYAH',
                'jam_ke_mulai' => 5,
                'jam_ke_selesai' => 5,
            ],
            // 12.30 - 13.15: Mapel = PPB | Ruang = R.41 | Guru = NUR (Jam 6)
            // 13.15 - 14.00: Mapel = PPB | Ruang = R.41 | Guru = NUR (Jam 7)
            // 14.00 - 14.45: Mapel = PPB | Ruang = R.41 | Guru = NUR (Jam 8)
            [
                'hari' => 'Senin',
                'mapel_kode' => 'PPB',
                'ruang_kode' => 'R.41',
                'guru_keyword' => 'NUR FAUZIYAH',
                'jam_ke_mulai' => 6,
                'jam_ke_selesai' => 8,
            ],

            // ==========================================
            // SELASA
            // Pembiasaan: 06.30 - 06.45 (Tadarus & Sarapan Bergizi)
            // ==========================================
            // 06.45 – 07.30: Mapel = PKK/KIK | Ruang = R.PUS2 | Guru = ANNISA (Jam 1)
            // 07.30 – 08.15: Mapel = PKK/KIK | Ruang = R.PUS2 | Guru = ANNISA (Jam 2)
            // 08.15 – 09.00: Mapel = PKK/KIK | Ruang = R.PUS2 | Guru = ANNISA (Jam 3)
            // 09.00 – 09.45: Mapel = PKK/KIK | Ruang = R.PUS2 | Guru = ANNISA (Jam 4)
            // (Jeda Istirahat: 09.45 – 10.00)
            [
                'hari' => 'Selasa',
                'mapel_kode' => 'PKK/KIK',
                'ruang_kode' => 'R.PUS2',
                'guru_keyword' => 'ANNISA',
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 4,
            ],
            // 10.00 – 10.45: Mapel = PKK/KIK | Ruang = R.PUS2 | Guru = ANNISA (Jam 5)
            [
                'hari' => 'Selasa',
                'mapel_kode' => 'PKK/KIK',
                'ruang_kode' => 'R.PUS2',
                'guru_keyword' => 'ANNISA',
                'jam_ke_mulai' => 5,
                'jam_ke_selesai' => 5,
            ],
            // 10.45 – 11.30: Mapel = PW | Ruang = R.41 | Guru = ARI (Jam 6)
            // (Jeda Istirahat: 11.30 – 12.30)
            [
                'hari' => 'Selasa',
                'mapel_kode' => 'PW',
                'ruang_kode' => 'R.41',
                'guru_keyword' => 'ARIANTONIUS',
                'jam_ke_mulai' => 6,
                'jam_ke_selesai' => 6,
            ],
            // 12.30 - 13.15: Mapel = PW | Ruang = R.41 | Guru = ARI (Jam 7)
            // 13.15 – 14.00: Mapel = PW | Ruang = R.41 | Guru = ARI (Jam 8)
            // 14.00 – 14.45: Mapel = PW | Ruang = R.41 | Guru = ARI (Jam 9)
            // 14.45 – 15.30: Mapel = PW | Ruang = R.41 | Guru = ARI (Jam 10)
            [
                'hari' => 'Selasa',
                'mapel_kode' => 'PW',
                'ruang_kode' => 'R.41',
                'guru_keyword' => 'ARIANTONIUS',
                'jam_ke_mulai' => 7,
                'jam_ke_selesai' => 10,
            ],

            // ==========================================
            // RABU
            // Pembiasaan: 06.30 - 07.30 (Sholat Dhuha & Tadarus / PAK)
            // ==========================================
            // 07.30 – 08.15: Mapel = BJPG | Ruang = R.60 | Guru = RINI (Jam 1)
            // 08.15 – 09.00: Mapel = BJPG | Ruang = R.60 | Guru = RINI (Jam 2)
            [
                'hari' => 'Rabu',
                'mapel_kode' => 'BJPG',
                'ruang_kode' => 'R.60',
                'guru_keyword' => 'RINI DWI',
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 2,
            ],
            // 09.00 - 09.45: Mapel = PPAN | Ruang = R.60 | Guru = TUBAGUS (Jam 3)
            // (Jeda Istirahat: 09.45 – 10.00)
            [
                'hari' => 'Rabu',
                'mapel_kode' => 'PPAN',
                'ruang_kode' => 'R.60',
                'guru_keyword' => 'TUBAGUS',
                'jam_ke_mulai' => 3,
                'jam_ke_selesai' => 3,
            ],
            // 10.00 – 10.45: Mapel = PPAN | Ruang = R.60 | Guru = TUBAGUS (Jam 4)
            [
                'hari' => 'Rabu',
                'mapel_kode' => 'PPAN',
                'ruang_kode' => 'R.60',
                'guru_keyword' => 'TUBAGUS',
                'jam_ke_mulai' => 4,
                'jam_ke_selesai' => 4,
            ],
            // 10.45 – 11.30: Mapel = BASDAT | Ruang = R.42 | Guru = PRATIWI (Jam 5)
            // (Jeda Istirahat: 11.30 – 12.30)
            [
                'hari' => 'Rabu',
                'mapel_kode' => 'BASDAT',
                'ruang_kode' => 'R.42',
                'guru_keyword' => 'PRATIWI',
                'jam_ke_mulai' => 5,
                'jam_ke_selesai' => 5,
            ],
            // 12.30 - 13.15: Mapel = BASDAT | Ruang = R.42 | Guru = PRATIWI (Jam 6)
            // 13.15 - 14.00: Mapel = BASDAT | Ruang = R.42 | Guru = PRATIWI (Jam 7)
            // 14.00 - 14.45: Mapel = BASDAT | Ruang = R.42 | Guru = PRATIWI (Jam 8)
            [
                'hari' => 'Rabu',
                'mapel_kode' => 'BASDAT',
                'ruang_kode' => 'R.42',
                'guru_keyword' => 'PRATIWI',
                'jam_ke_mulai' => 6,
                'jam_ke_selesai' => 8,
            ],

            // ==========================================
            // KAMIS
            // Pembiasaan: 06.30 - 06.45 (Tadarus & Literasi)
            // ==========================================
            // 06.45 – 07.30: Mapel = MATH | Ruang = R.60A | Guru = SARI (Jam 1)
            // 07.30 – 08.15: Mapel = MATH | Ruang = R.60A | Guru = SARI (Jam 2)
            // 08.15 – 09.00: Mapel = MATH | Ruang = R.60A | Guru = SARI (Jam 3)
            [
                'hari' => 'Kamis',
                'mapel_kode' => 'MATH',
                'ruang_kode' => 'R.60A',
                'guru_keyword' => 'SARINAH',
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 3,
            ],
            // 09.00 – 09.45: Mapel = BPBK | Ruang = R.60A | Guru = HALIDA (Jam 4)
            // (Jeda Istirahat: 09.45 – 10.00)
            [
                'hari' => 'Kamis',
                'mapel_kode' => 'BPBK',
                'ruang_kode' => 'R.60A',
                'guru_keyword' => 'HALIDA',
                'jam_ke_mulai' => 4,
                'jam_ke_selesai' => 4,
            ],
            // 10.00 – 10.45: Mapel = PBTM | Ruang = R.42 | Guru = PURI (Jam 5)
            // 10.45 – 11.30: Mapel = PBTM | Ruang = R.42 | Guru = PURI (Jam 6)
            // (Jeda Istirahat: 11.30 – 12.30)
            [
                'hari' => 'Kamis',
                'mapel_kode' => 'PBTM',
                'ruang_kode' => 'R.42',
                'guru_keyword' => 'MASPURI',
                'jam_ke_mulai' => 5,
                'jam_ke_selesai' => 6,
            ],
            // 12.30 - 13.15: Mapel = PBTM | Ruang = R.42 | Guru = PURI (Jam 7)
            // 13.15 – 14.00: Mapel = PBTM | Ruang = R.42 | Guru = PURI (Jam 8)
            // 14.00 – 14.45: Mapel = PBTM | Ruang = R.42 | Guru = PURI (Jam 9)
            [
                'hari' => 'Kamis',
                'mapel_kode' => 'PBTM',
                'ruang_kode' => 'R.42',
                'guru_keyword' => 'MASPURI',
                'jam_ke_mulai' => 7,
                'jam_ke_selesai' => 9,
            ],

            // ==========================================
            // JUMAT
            // Pembiasaan: 06.30 - 07.15 (Senam & Jumat Bersih)
            // ==========================================
            // 07.15 – 08.00: Mapel = FW/API | Ruang = R.41 | Guru = ARI (Jam 1)
            // 08.00 – 08.45: Mapel = FW/API | Ruang = R.41 | Guru = ARI (Jam 2)
            // 08.45 – 09.30: Mapel = FW/API | Ruang = R.41 | Guru = ARI (Jam 3)
            // 09.30 – 10.15: Mapel = FW/API | Ruang = R.41 | Guru = ARI (Jam 4)
            [
                'hari' => 'Jumat',
                'mapel_kode' => 'FW/API',
                'ruang_kode' => 'R.41',
                'guru_keyword' => 'ARIANTONIUS',
                'jam_ke_mulai' => 1,
                'jam_ke_selesai' => 4,
            ],
            // 10.15 – 11.00: Mapel = BING | Ruang = R.PUS1 | Guru = UJANG (Jam 5)
            // 11.00 – 11.45: Mapel = BING | Ruang = R.PUS1 | Guru = UJANG (Jam 6)
            // (Jeda Istirahat: 11.45 – 13.00)
            [
                'hari' => 'Jumat',
                'mapel_kode' => 'BING',
                'ruang_kode' => 'R.PUS1',
                'guru_keyword' => 'UJANG',
                'jam_ke_mulai' => 5,
                'jam_ke_selesai' => 6,
            ],
            // 13.00 – 13.45: Mapel = PERSIAPAN TKA | Ruang = R.PUS1 (Jam 7)
            // 13.45 – 14.30: Mapel = PERSIAPAN TKA | Ruang = R.PUS1 (Jam 8)
            // 14.30 – 15.15: Mapel = PERSIAPAN TKA | Ruang = R.PUS1 (Jam 9)
            [
                'hari' => 'Jumat',
                'mapel_kode' => 'PERSIAPAN TKA',
                'ruang_kode' => 'R.PUS1',
                'guru_keyword' => null,
                'jam_ke_mulai' => 7,
                'jam_ke_selesai' => 9,
            ],
        ];

        // 5. Bersihkan jadwal sebelumnya khusus kelas XII RPL 1 agar tidak duplikat
        Jadwal::where('id_kelas', $kelas->id)->delete();

        // 6. Masukkan seluruh jadwal XII RPL 1
        foreach ($daftarJadwal as $item) {
            $mapel = Mapel::where('kode', $item['mapel_kode'])->first();
            $ruangan = Ruangan::where('kode', $item['ruang_kode'])->first();

            $guruId = null;
            if (!empty($item['guru_keyword'])) {
                $guru = Guru::where('nama', 'like', '%' . $item['guru_keyword'] . '%')->first();
                $guruId = $guru?->id;
            }

            if (!$mapel || !$ruangan) {
                continue;
            }

            Jadwal::create([
                'id_kelas' => $kelas->id,
                'id_mapel' => $mapel->id,
                'id_guru' => $guruId,
                'id_ruangan' => $ruangan->id,
                'hari' => $item['hari'],
                'jam_ke_mulai' => $item['jam_ke_mulai'],
                'jam_ke_selesai' => $item['jam_ke_selesai'],
            ]);
        }
    }
}

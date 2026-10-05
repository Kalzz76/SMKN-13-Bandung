<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\PengaturanSekolah;
use App\Models\Prestasi;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);

        $sekretaris = User::create([
            'name' => 'Sekretaris Sekolah',
            'username' => 'sekretaris',
            'password' => Hash::make('sekretaris123'),
            'role' => 'sekretaris',
            'status' => 'Aktif',
        ]);

        $userRefky = User::create([
            'name' => 'Refky, M.Kom.',
            'username' => 'refky',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'status' => 'Aktif',
        ]);

        $userUli = User::create([
            'name' => 'Uli, S.Pd.',
            'username' => 'uli',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'status' => 'Aktif',
        ]);

        $userKiki = User::create([
            'name' => 'Kiki, S.T.',
            'username' => 'kiki',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'status' => 'Aktif',
        ]);

        $userNofa = User::create([
            'name' => 'Nofa, S.Pd.',
            'username' => 'nofa',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'status' => 'Aktif',
        ]);

        PengaturanSekolah::create([
            'nama_sekolah' => 'SMK Negeri 13 Bandung',
            'npsn' => '20219145',
            'alamat' => 'Jl. Soekarno-Hatta Km. 10 Gedebage, Kota Bandung, Jawa Barat 40286',
            'email' => 'info@smkn13bandung.sch.id',
            'telepon' => '(022) 7801234 / 7805678',
            'social_media' => '@smkn13bandung',
            'jam_operasional' => 'Senin - Jumat: 07.00 - 16.00 WIB',
            'logo' => null,
            'slogan' => 'Unggul, Berkarakter & Berdaya Saing',
            'deskripsi_singkat' => 'Pusat pendidikan kejuruan unggulan yang mencetak generasi kompeten di bidang teknologi, siap kerja, mandiri, dan berakhlak mulia.',
            'visi' => 'Menjadi pusat pendidikan kejuruan unggulan yang menghasilkan lulusan cerdas, kompetitif, berkarakter Pancasila, dan berwawasan global.',
            'misi' => "Menyelenggarakan pembelajaran berbasis teknologi industri modern.\nMenjalin kemitraan strategis dengan Dunia Usaha dan Dunia Industri (DUDI).\nMembentuk karakter disiplin, berakhlak mulia, dan siap kerja.",
            'sejarah' => 'Berdiri sejak tahun 2004 di Jalan Soekarno-Hatta Kota Bandung, SMK Negeri 13 Bandung konsisten menjadi rujukan pendidikan kejuruan bermutu tinggi. Dengan berfokus pada bidang teknologi informasi, rekayasa perangkat lunak, dan telekomunikasi, sekolah ini telah melahirkan ribuan alumni yang sukses berkarier di industri multinasional maupun wirausaha mandiri.',
            'nama_kepsek' => 'Dr. H. Ahmad Supriyadi, M.Pd.',
            'foto_kepsek' => null,
            'judul_sambutan' => 'Mewujudkan Pendidikan Vokasi Berkualitas Tinggi',
            'sambutan' => 'Assalamualaikum Warahmatullahi Wabarakatuh. Selamat datang di portal resmi SMK Negeri 13 Bandung. Kami berkomitmen meningkatkan mutu pelayanan pendidikan, adaptif terhadap perkembangan teknologi industri terkini, serta menanamkan karakter luhur kepada seluruh peserta didik.',
            'gambar_struktur' => null,
            'lat_sekolah' => -6.94520000,
            'long_sekolah' => 107.67650000,
            'radius_meter' => 100,
            'jam_masuk' => '07:00',
        ]);

        $jamPelajaran = [
            ['nama' => 'Carabika', 'jenis' => 'Carabika', 'jam_ke' => null, 'jam_mulai' => '06:30', 'jam_selesai' => '07:30', 'urutan' => 1],
            ['nama' => 'Jam 1', 'jenis' => 'Pelajaran', 'jam_ke' => 1, 'jam_mulai' => '07:30', 'jam_selesai' => '08:15', 'urutan' => 2],
            ['nama' => 'Jam 2', 'jenis' => 'Pelajaran', 'jam_ke' => 2, 'jam_mulai' => '08:15', 'jam_selesai' => '09:00', 'urutan' => 3],
            ['nama' => 'Jam 3', 'jenis' => 'Pelajaran', 'jam_ke' => 3, 'jam_mulai' => '09:00', 'jam_selesai' => '09:45', 'urutan' => 4],
            ['nama' => 'Istirahat', 'jenis' => 'Istirahat', 'jam_ke' => null, 'jam_mulai' => '09:45', 'jam_selesai' => '10:00', 'urutan' => 5],
            ['nama' => 'Jam 4', 'jenis' => 'Pelajaran', 'jam_ke' => 4, 'jam_mulai' => '10:00', 'jam_selesai' => '10:45', 'urutan' => 6],
            ['nama' => 'Jam 5', 'jenis' => 'Pelajaran', 'jam_ke' => 5, 'jam_mulai' => '10:45', 'jam_selesai' => '11:30', 'urutan' => 7],
            ['nama' => 'Istirahat', 'jenis' => 'Istirahat', 'jam_ke' => null, 'jam_mulai' => '11:30', 'jam_selesai' => '12:30', 'urutan' => 8],
            ['nama' => 'Jam 6', 'jenis' => 'Pelajaran', 'jam_ke' => 6, 'jam_mulai' => '12:30', 'jam_selesai' => '13:15', 'urutan' => 9],
            ['nama' => 'Jam 7', 'jenis' => 'Pelajaran', 'jam_ke' => 7, 'jam_mulai' => '13:15', 'jam_selesai' => '14:00', 'urutan' => 10],
        ];
        foreach ($jamPelajaran as $jp) {
            JamPelajaran::create($jp);
        }

        $ruang52 = Ruangan::create(['kode' => 'R.52', 'nama' => 'Ruang Teori 52', 'kapasitas' => 36]);
        $ruang53 = Ruangan::create(['kode' => 'R.53', 'nama' => 'Ruang Teori 53', 'kapasitas' => 36]);
        $ruangLab = Ruangan::create(['kode' => 'LAB-RPL', 'nama' => 'Laboratorium Komputer RPL', 'kapasitas' => 40]);
        Ruangan::create(['kode' => 'LAP', 'nama' => 'Lapangan Olahraga', 'kapasitas' => 500]);

        $guruRefky = Guru::create([
            'user_id' => $userRefky->id,
            'nama' => 'Refky, M.Kom.',
            'nip' => '198501012010011001',
            'jenis' => 'Guru',
            'jabatan' => 'Kepala Lab RPL',
            'mapel_utama' => 'Produktif RPL',
            'foto' => null,
            'tampil_publik' => true,
            'kode_barcode' => 'BARCODE-REFKY-001',
        ]);

        $guruUli = Guru::create([
            'user_id' => $userUli->id,
            'nama' => 'Uli, S.Pd.',
            'nip' => '198702022012022002',
            'jenis' => 'Guru',
            'jabatan' => 'Guru Pengajar',
            'mapel_utama' => 'DPK / Matematika',
            'foto' => null,
            'tampil_publik' => true,
            'kode_barcode' => 'BARCODE-ULI-002',
        ]);

        $guruKiki = Guru::create([
            'user_id' => $userKiki->id,
            'nama' => 'Kiki, S.T.',
            'nip' => '198903032014031003',
            'jenis' => 'Guru',
            'jabatan' => 'Guru Pengajar',
            'mapel_utama' => 'Jaringan Komputer',
            'foto' => null,
            'tampil_publik' => true,
            'kode_barcode' => 'BARCODE-KIKI-003',
        ]);

        $guruNofa = Guru::create([
            'user_id' => $userNofa->id,
            'nama' => 'Nofa, S.Pd.',
            'nip' => '199004042015042004',
            'jenis' => 'Guru',
            'jabatan' => 'Guru Pengajar',
            'mapel_utama' => 'Matematika',
            'foto' => null,
            'tampil_publik' => true,
            'kode_barcode' => 'BARCODE-NOFA-004',
        ]);

        $kelasX1 = Kelas::create([
            'nama' => 'X KA 1',
            'id_ruangan' => $ruang52->id,
            'id_wali_kelas' => $guruUli->id,
        ]);

        $kelasX2 = Kelas::create([
            'nama' => 'X KA 2',
            'id_ruangan' => $ruang53->id,
            'id_wali_kelas' => $guruNofa->id,
        ]);

        $kelasXII = Kelas::create([
            'nama' => 'XII RPL 1',
            'id_ruangan' => $ruangLab->id,
            'id_wali_kelas' => $guruRefky->id,
        ]);

        Siswa::create([
            'nis' => '100123',
            'nisn' => '0081234001',
            'nama' => 'Rizky Pratama',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasXII->id,
            'tahun_ajaran' => '2026/2027',
        ]);

        Siswa::create([
            'nis' => '100124',
            'nisn' => '0081234002',
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelasXII->id,
            'tahun_ajaran' => '2026/2027',
        ]);

        Siswa::create([
            'nis' => '100125',
            'nisn' => '0081234003',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasX1->id,
            'tahun_ajaran' => '2026/2027',
        ]);

        $mapelDpk = Mapel::create([
            'kode' => 'DPK',
            'nama' => 'Dasar Program Keahlian',
            'kelompok' => 'Produktif',
        ]);

        $mapelMath = Mapel::create([
            'kode' => 'MATH',
            'nama' => 'Matematika',
            'kelompok' => 'Normatif',
        ]);

        Mapel::create([
            'kode' => 'BING',
            'nama' => 'Bahasa Inggris',
            'kelompok' => 'Adaptif',
        ]);

        Jurusan::create([
            'kode' => 'RPL',
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'Pemrograman Web, Mobile Apps, dan Software Engineering.',
            'ikon' => 'fa-code',
        ]);

        Jurusan::create([
            'kode' => 'TKJ',
            'nama' => 'Teknik Komputer & Jaringan',
            'deskripsi' => 'Administrasi Server, Mikrotik, dan Cloud Computing.',
            'ikon' => 'fa-network-wired',
        ]);

        Jurusan::create([
            'kode' => 'TJA',
            'nama' => 'Teknik Jaringan Akses',
            'deskripsi' => 'Infrastruktur Telekomunikasi Modern dan Fiber Optic.',
            'ikon' => 'fa-satellite-dish',
        ]);

        Berita::create([
            'judul' => 'SMKN 13 Bandung Juara 1 LKS Web Technologies',
            'kategori' => 'Prestasi',
            'isi' => 'Tim siswa RPL berhasil menyabet juara pertama dalam ajang LKS Web Technologies tingkat regional dan bersiap menuju jenjang nasional.',
            'gambar' => null,
            'tanggal' => '2026-10-02',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Berita::create([
            'judul' => 'Workshop Industri Bersama Tech Giants',
            'kategori' => 'Kegiatan',
            'isi' => 'Pelatihan kurikulum industri terkini untuk mempersiapkan seluruh siswa kelas XII menghadapi masa praktik kerja lapangan dan industri.',
            'gambar' => null,
            'tanggal' => '2026-09-28',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Berita::create([
            'judul' => 'Penerimaan Tamu Ambalan Pramuka',
            'kategori' => 'Ekstrakurikuler',
            'isi' => 'Kegiatan perkemahan pelantikan dan penerimaan tamu ambalan untuk melatih kedisiplinan, kemandirian, dan solidaritas siswa baru.',
            'gambar' => null,
            'tanggal' => '2026-09-20',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Galeri::create([
            'judul' => 'Laboratorium Komputer RPL',
            'kategori' => 'Fasilitas',
            'foto' => 'galeri/lab-rpl.jpg',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Praktik Jaringan Komputer',
            'kategori' => 'Kegiatan',
            'foto' => 'galeri/praktik-jaringan.jpg',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Perpustakaan Digital',
            'kategori' => 'Fasilitas',
            'foto' => 'galeri/perpustakaan.jpg',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Upacara Bendera Senin',
            'kategori' => 'Kegiatan',
            'foto' => 'galeri/upacara.jpg',
            'tanggal' => '2026-10-01',
        ]);

        Jadwal::create([
            'id_kelas' => $kelasX1->id,
            'id_mapel' => $mapelDpk->id,
            'id_guru' => $guruUli->id,
            'id_ruangan' => $ruang52->id,
            'hari' => 'Senin',
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        Jadwal::create([
            'id_kelas' => $kelasX2->id,
            'id_mapel' => $mapelMath->id,
            'id_guru' => $guruNofa->id,
            'id_ruangan' => $ruang53->id,
            'hari' => 'Senin',
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        Jadwal::create([
            'id_kelas' => $kelasXII->id,
            'id_mapel' => $mapelDpk->id,
            'id_guru' => $guruRefky->id,
            'id_ruangan' => $ruangLab->id,
            'hari' => 'Senin',
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Pramuka',
            'deskripsi' => 'Gerakan Pramuka pangkalan SMKN 13 Bandung aktif membentuk karakter kedisiplinan, kemandirian, dan kepemimpinan.',
            'pembina' => 'Drs. Maman S.',
            'jadwal' => 'Jumat 15.30 WIB',
            'gambar' => null,
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Paskibra',
            'deskripsi' => 'Pasukan Pengibar Bendera dengan prestasi gemilang di tingkat Kota Bandung dan Provinsi Jawa Barat.',
            'pembina' => 'Siti Rahma, S.Pd.',
            'jadwal' => 'Sabtu 08.00 WIB',
            'gambar' => null,
        ]);

        Ekstrakurikuler::create([
            'nama' => 'English Club',
            'deskripsi' => 'Wadah pengembangan kemampuan berbahasa Inggris, debat, dan public speaking siswa.',
            'pembina' => 'Rina Dewi, M.Pd.',
            'jadwal' => 'Rabu 15.30 WIB',
            'gambar' => null,
        ]);

        Prestasi::create([
            'judul' => 'Juara 1 LKS Web Technologies Kota Bandung',
            'tingkat' => 'Kota',
            'tahun' => '2026',
            'nama_peraih' => 'Rizky Pratama',
            'deskripsi' => 'Meraih medali emas pada ajang Lomba Kompetensi Siswa SMK tingkat Kota Bandung bidang Web Technologies.',
            'gambar' => null,
        ]);

        Prestasi::create([
            'judul' => 'Juara 2 Networking Support Jawa Barat',
            'tingkat' => 'Provinsi',
            'tahun' => '2025',
            'nama_peraih' => 'Ahmad Fauzi',
            'deskripsi' => 'Meraih juara 2 dalam kompetisi IT Network System Administration tingkat Jawa Barat.',
            'gambar' => null,
        ]);
    }
}

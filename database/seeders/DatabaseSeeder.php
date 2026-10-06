<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Jadwal;
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
            'alamat' => 'Jl. Soekarno-Hatta Km. 10, Kota Bandung',
            'role' => 'admin',
            'status' => 'Aktif',
            'tema' => 'light',
        ]);

        $sekretaris = User::create([
            'name' => 'RAIKHANIA RIZKY PUTRI HERDIANA',
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
            'npsn' => '20219161',
            'alamat' => 'Jl. Soekarno-Hatta KM. 10, Kelurahan Jatisari, Kecamatan Buahbatu, Kota Bandung, Jawa Barat, Kode Pos 40286',
            'email' => 'smk13bdg@gmail.com / info@smkn13bdg.sch.id',
            'telepon' => '(022) 7318960',
            'social_media' => 'Instagram: @smkn13bdg / @smkn13bandung | YouTube: SMKN 13 Bandung Official',
            'jam_operasional' => 'Senin – Jumat: 07.00 – 16.00 WIB | Sabtu – Minggu & Hari Libur Nasional: Tutup',
            'logo' => 'pengaturan/u1uuRkFr8pwEBbiYShmpK5yMGVU1S7ywCSBifCi0.jpg',
            'slogan' => 'Terdepan dalam Karakter, Unggul dalam Kompetensi, Berdaya Saing Global.',
            'deskripsi_singkat' => 'SMK Negeri 13 Bandung merupakan sekolah kejuruan negeri unggulan di Kota Bandung yang berfokus pada pengembangan vokasi bidang Sains dan Teknologi Informasi. Kami berkomitmen mencetak lulusan berkarakter, kompeten, dan siap bersaing di dunia kerja maupun perguruan tinggi.',
            'visi' => 'Terwujudnya lulusan yang berakhlak mulia, kompeten, dan berdaya suai di tingkat internasional pada tahun 2030.',
            'misi' => "Menyelenggarakan program penguatan pendidikan karakter berlandaskan nilai-nilai luhur dan Profil Pelajar Pancasila / Gapura Panca Waluya.\nMenerapkan kurikulum berbasis kompetensi yang selaras dengan perkembangan Industri 4.0 dan kebutuhan dunia kerja.\nMenyeimbangkan dan meningkatkan sarana prasarana sekolah sesuai Standar Nasional Pendidikan (SNP) serta standar industri.\nMenjalin kemitraan strategis dengan Dunia Usaha, Dunia Industri, dan Institusi Pendidikan (DU/DI/IP) skala nasional dan internasional.\nMenerapkan budaya sekolah ramah lingkungan (Green School) melalui tata kelola sampah, hemat energi, dan pengolahan limbah laboratorium.",
            'sejarah' => "Cikal bakal SMK Negeri 13 Bandung bermula pada 16 September 1938 dengan nama Sekolah Analis Kimia ITB yang dipelopori oleh Prof. C. O. Schaeffer di bawah Departemen Kimia Institut Teknologi Bandung.\n\nPada tahun 1988, pengelolaannya dialihkan ke Departemen Pendidikan dan Kebudayaan dengan nama SMT Kimia Bandung. Selanjutnya, melalui SK Menteri Pendidikan No. 036/O/1997, nama sekolah resmi berganti menjadi SMK Negeri 13 Bandung. Seiring berjalannya waktu, SMKN 13 Bandung bertransformasi tidak hanya unggul di bidang Analisis Kimia, tetapi juga menjadi pusat keunggulan di bidang Teknologi Informasi (RPL dan TKJ/TJKT).",
            'nama_kepsek' => 'Agus Nugroho, S.Pd., M.T.',
            'foto_kepsek' => null,
            'judul_sambutan' => 'Mewujudkan Pendidikan Vokasi Unggul, Berkarakter, dan Berdaya Saing Global',
            'sambutan' => "Assalamu’alaikum Warahmatullahi Wabarakatuh,\n\nSelamat datang di portal resmi SMK Negeri 13 Bandung. Puji dan syukur kita panjatkan ke hadirat Allah SWT atas rahmat dan karunia-Nya sehingga website resmi ini hadir sebagai media informasi, komunikasi, dan transparansi publik sekolah kami.\n\nSebagai sekolah vokasi yang berlandaskan sejarah panjang dan reputasi tinggi, SMKN 13 Bandung terus berinovasi untuk menyelaraskan sistem pembelajaran dengan kebutuhan dunia kerja dan dinamika teknologi global. Kami tidak hanya menempah keterampilan teknis (hard skills) peserta didik, namun juga membangun integritas, kedisiplinan, serta nilai-nilai karakter (soft skills) agar lulusan kami menjadi pribadi yang tangguh, adaptif, dan siap berkontribusi bagi masyarakat.\n\nTerima kasih atas kepercayaan seluruh masyarakat, pihak industri, dan orang tua siswa yang terus berjalan beriringan bersama kami. Semoga platform ini dapat memberikan manfaat yang luas bagi seluruh pemangku kepentingan.\n\nWassalamu’alaikum Warahmatullahi Wabarakatuh.",
            'struktur_organisasi' => PengaturanSekolah::defaultStruktur(),
            'gambar_struktur' => null,
            'lat_sekolah' => -6.94520000,
            'long_sekolah' => 107.67650000,
            'radius_meter' => 100,
            'jam_masuk' => '07:00',
        ]);

        $this->call([
            JamPelajaranSeeder::class,
            JadwalPembiasaanSeeder::class,
        ]);

        $ruang52 = Ruangan::create(['kode' => 'R.52', 'nama' => 'Ruang Teori 52']);
        $ruang53 = Ruangan::create(['kode' => 'R.53', 'nama' => 'Ruang Teori 53']);
        $ruangLab = Ruangan::create(['kode' => 'LAB-RPL', 'nama' => 'Laboratorium Komputer RPL']);
        Ruangan::create(['kode' => 'LAP', 'nama' => 'Lapangan Olahraga']);

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

        $kelasXIRPL = Kelas::create([
            'nama' => 'XI RPL 1',
            'id_ruangan' => $ruangLab->id,
            'id_wali_kelas' => $guruRefky->id,
        ]);

        $kelasXII = Kelas::create([
            'nama' => 'XII RPL 1',
            'id_ruangan' => $ruangLab->id,
            'id_wali_kelas' => $guruRefky->id,
            'struktur' => [
                'km' => 'Rizky Pratama',
                'bendahara_1' => 'Siti Nurhaliza',
            ],
        ]);

        $siswaXIRPL = [
            ['nis' => '102101', 'nama' => 'Aditya Pratama', 'jenis_kelamin' => 'L'],
            ['nis' => '102102', 'nama' => 'Anisa Rahmawati', 'jenis_kelamin' => 'P'],
            ['nis' => '102103', 'nama' => 'Bintang Ramadhan', 'jenis_kelamin' => 'L'],
            ['nis' => '102104', 'nama' => 'Citra Dewi', 'jenis_kelamin' => 'P'],
            ['nis' => '102105', 'nama' => 'Dimas Anggara', 'jenis_kelamin' => 'L'],
            ['nis' => '102106', 'nama' => 'RAIKHANIA RIZKY PUTRI HERDIANA', 'jenis_kelamin' => 'P'],
            ['nis' => '102107', 'nama' => 'Rifki Hidayat', 'jenis_kelamin' => 'L'],
            ['nis' => '102108', 'nama' => 'Zahra Aulia', 'jenis_kelamin' => 'P'],
        ];
        foreach ($siswaXIRPL as $s) {
            Siswa::create([
                'nis' => $s['nis'],
                'nama' => $s['nama'],
                'jenis_kelamin' => $s['jenis_kelamin'],
                'id_kelas' => $kelasXIRPL->id,
                'tahun_ajaran' => '2026/2027',
            ]);
        }

        Siswa::create([
            'nis' => '100123',
            'nisn' => '0081234001',
            'nama' => 'Rizky Pratama',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasXII->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Ketua Murid',
        ]);

        Siswa::create([
            'nis' => '100124',
            'nisn' => '0081234002',
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelasXII->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Bendahara 1',
        ]);

        Siswa::create([
            'nis' => '100125',
            'nisn' => '0081234003',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasX1->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Anggota',
        ]);

        $mapelDpk = Mapel::create([
            'kode' => 'DPK',
            'nama' => 'Dasar Program Keahlian',
            'jenis' => 'Produktif',
        ]);

        $mapelMath = Mapel::create([
            'kode' => 'MATH',
            'nama' => 'Matematika',
            'jenis' => 'Umum',
        ]);

        Mapel::create([
            'kode' => 'BING',
            'nama' => 'Bahasa Inggris',
            'jenis' => 'Umum',
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

        Jadwal::create([
            'id_kelas' => $kelasXIRPL->id,
            'id_mapel' => $mapelDpk->id,
            'id_guru' => $guruRefky->id,
            'id_ruangan' => $ruangLab->id,
            'hari' => 'Senin',
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        Jadwal::create([
            'id_kelas' => $kelasXIRPL->id,
            'id_mapel' => $mapelMath->id,
            'id_guru' => $guruNofa->id,
            'id_ruangan' => $ruangLab->id,
            'hari' => 'Senin',
            'jam_ke_mulai' => 4,
            'jam_ke_selesai' => 5,
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

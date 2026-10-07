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



        PengaturanSekolah::create([
            'nama_sekolah' => 'SMK Negeri 13 Bandung',
            'npsn' => '20219161',
            'alamat' => 'Jl. Soekarno-Hatta KM. 10, Kelurahan Jatisari, Kecamatan Buahbatu, Kota Bandung, Jawa Barat, Kode Pos 40286',
            'email' => 'smk13bdg@gmail.com / info@smkn13bdg.sch.id',
            'telepon' => '(022) 7318960',
            'social_media' => 'Instagram: @smkn13bdg / @smkn13bandung | YouTube: SMKN 13 Bandung Official',
            'jam_operasional' => 'Senin – Jumat: 07.00 – 16.00 WIB | Sabtu – Minggu & Hari Libur Nasional: Tutup',
            'logo' => 'pengaturan/ddmJH0NrFZ6S9DKwBbZFHoNSVyhGzi27kRokgVPj.png',
            'slogan' => 'Terdepan dalam Karakter, Unggul dalam Kompetensi, Berdaya Saing Global.',
            'deskripsi_singkat' => 'SMK Negeri 13 Bandung merupakan sekolah kejuruan negeri unggulan di Kota Bandung yang berfokus pada pengembangan vokasi bidang Sains dan Teknologi Informasi. Kami berkomitmen mencetak lulusan berkarakter, kompeten, dan siap bersaing di dunia kerja maupun perguruan tinggi.',
            'visi' => 'Terwujudnya lulusan yang berakhlak mulia, kompeten, dan berdaya suai di tingkat internasional pada tahun 2030.',
            'misi' => "Penguatan Karakter: Menyelenggarakan program penguatan pendidikan karakter Gapura Panca Waluya dan 8 Dimensi Profil Lulusan.\n" .
                      "Pembelajaran Mendalam: Mengembangkan keterampilan abad ke-21: berpikir kritis, kreatif, komunikatif, dan kolaboratif.\n" .
                      "Profesionalisme GTK: Meningkatkan profesionalisme Guru dan Tenaga Kependidikan secara berkelanjutan.\n" .
                      "Sarana Prasarana: Meningkatkan sarana prasarana mengacu pada Standar Nasional Pendidikan dan Dunia Industri.\n" .
                      "Digitalisasi Sekolah: Pengelolaan pendidikan berbasis Teknologi Informasi dan Komunikasi (TIK).\n" .
                      "Kemitraan Luas: Kemitraan strategis dengan Dunia Industri dan Institusi Pendidikan di dalam maupun luar negeri.\n" .
                      "Asesmen Berkualitas: Melaksanakan asesmen yang berkelanjutan dan otentik.\n" .
                      "Budaya Lingkungan: Budaya ramah lingkungan melalui pengolahan limbah, pengelolaan sampah dan hemat energi.",
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
            RuanganSeeder::class,
            GuruSeeder::class,
            MapelSeeder::class,
        ]);

        $ruang52 = Ruangan::where('kode', 'R.52')->first();
        $ruang53 = Ruangan::where('kode', 'R.53')->first();
        $ruangLab = Ruangan::where('kode', 'R.42')->first();

        $guruUli = Guru::where('nama', 'like', '%ULI SOLIHAT%')->first();
        $guruKiki = Guru::where('nama', 'like', '%KIKI AIMA%')->first();
        $guruNofa = Guru::where('nama', 'like', '%NOFA NIRAWATI%')->first();

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
            'id_wali_kelas' => $guruKiki->id,
        ]);

        $kelasXII = Kelas::create([
            'nama' => 'XII RPL 1',
            'id_ruangan' => $ruangLab->id,
            'id_wali_kelas' => $guruKiki->id,
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
            ]);
        }

        Siswa::create([
            'nis' => '100123',
            'nisn' => '0081234001',
            'nama' => 'Rizky Pratama',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasXII->id,
            'jabatan' => 'Ketua Murid',
        ]);

        Siswa::create([
            'nis' => '100124',
            'nisn' => '0081234002',
            'nama' => 'Siti Nurhaliza',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelasXII->id,
            'jabatan' => 'Bendahara 1',
        ]);

        Siswa::create([
            'nis' => '100125',
            'nisn' => '0081234003',
            'nama' => 'Ahmad Fauzi',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelasX1->id,
            'jabatan' => 'Anggota',
        ]);

        $mapelDpk = Mapel::where('kode', 'DPK')->first();
        $mapelMath = Mapel::where('kode', 'MATH')->first();

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
            'judul' => 'Siswa TKJ Raih Medali Emas LKS',
            'kategori' => 'Prestasi',
            'isi' => "Delegasi SMKN 13 Bandung konsentrasi keahlian TKJ berhasil mengungguli puluhan perwakilan sekolah lain dalam ajang kompetensi siswa tingkat Provinsi Jawa Barat.\n\nKeberhasilan ini merupakan buah dari pembinaan intensif selama berbulan-bulan, mulai dari latihan konfigurasi jaringan, simulasi troubleshooting, hingga pendampingan langsung oleh guru produktif dan mitra industri.\n\nKepala sekolah menyampaikan apresiasi dan berharap prestasi ini memotivasi seluruh siswa untuk terus mengasah kompetensi serta berani bersaing di tingkat nasional.",
            'gambar' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-02',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Berita::create([
            'judul' => 'Workshop Sinkronisasi Kurikulum',
            'kategori' => 'Agenda',
            'isi' => "Langkah strategis penyesuaian kurikulum APL, TKJ, dan RPL dengan kompetensi nyata yang dibutuhkan ekosistem startup dan manufaktur saat ini.\n\nKegiatan diikuti oleh para kepala program, guru produktif, serta perwakilan dunia usaha dan dunia industri yang memberikan masukan mengenai kompetensi terbaru yang dibutuhkan lulusan.\n\nHasil workshop akan dituangkan dalam perangkat ajar yang diterapkan mulai semester berikutnya.",
            'gambar' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-09-28',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Berita::create([
            'judul' => 'Penerimaan Tamu Ambalan Pramuka',
            'kategori' => 'Ekskul',
            'isi' => "Membangun karakter siswa yang disiplin, mandiri, tangguh, serta peka terhadap pelestarian lingkungan lewat kegiatan perkemahan.\n\nRangkaian acara meliputi upacara penerimaan, penjelajahan, permainan kelompok, dan api unggun yang diikuti seluruh calon anggota baru.\n\nPembina berharap kegiatan ini menumbuhkan jiwa kepemimpinan dan kebersamaan antarsiswa.",
            'gambar' => 'https://images.unsplash.com/photo-1533630654593-b222d5d44449?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-09-20',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Galeri::create([
            'judul' => 'Uji Kompetensi APL',
            'kategori' => 'Akademik',
            'foto' => 'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Hackathon Siswa RPL',
            'kategori' => 'Lomba',
            'foto' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Gelar Karya P5 (Pancasila)',
            'kategori' => 'Kegiatan',
            'foto' => 'https://images.unsplash.com/photo-1511632765486-a01c80cf59af?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);

        Galeri::create([
            'judul' => 'Fasilitas & Lab Jaringan',
            'kategori' => 'Fasilitas',
            'foto' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80',
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

        $this->call([
            JadwalSeeder::class,
        ]);

        Jadwal::create([
            'id_kelas' => $kelasXIRPL->id,
            'id_mapel' => $mapelDpk->id,
            'id_guru' => $guruKiki->id,
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

        $this->call(RuanganSeeder::class);
    }
}

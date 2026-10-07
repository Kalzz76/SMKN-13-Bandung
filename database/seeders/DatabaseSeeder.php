<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\GaleriFoto;
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
            MapelSeeder::class,
            GuruSeeder::class,
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

        $siswaXIIRPL = [
            ['nis' => '102419349', 'nisn' => '0089761823', 'nama' => 'ADELYA FAUZI ALFIAN', 'jenis_kelamin' => 'P'],
            ['nis' => '102419350', 'nisn' => '0083620508', 'nama' => 'ADLY SYAKIEB HAFIDZ GUSTIRA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419351', 'nisn' => '0097003151', 'nama' => 'ALFIZAR SAFIY HAMIZAN', 'jenis_kelamin' => 'L'],
            ['nis' => '102419352', 'nisn' => '0092137145', 'nama' => 'ALIF YUSUF ANWAR', 'jenis_kelamin' => 'L'],
            ['nis' => '102419353', 'nisn' => '0087916106', 'nama' => 'ALYA ALMIRA PUTRI', 'jenis_kelamin' => 'P'],
            ['nis' => '102419354', 'nisn' => '0095085901', 'nama' => 'ANNISA AGHNIYA FAZA', 'jenis_kelamin' => 'P'],
            ['nis' => '102419355', 'nisn' => '0095336041', 'nama' => 'ARBIANSYAH AKBAR', 'jenis_kelamin' => 'L'],
            ['nis' => '102419356', 'nisn' => '0089341771', 'nama' => 'AYYAS HUSAYN', 'jenis_kelamin' => 'L'],
            ['nis' => '102419357', 'nisn' => '0097296846', 'nama' => 'BINTANG PUTRA RASYA DIKA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419358', 'nisn' => '0094995529', 'nama' => 'DAFFA DERYAN RASHIF', 'jenis_kelamin' => 'L'],
            ['nis' => '102419359', 'nisn' => '0096900894', 'nama' => 'DANIS FERDIANSYAH', 'jenis_kelamin' => 'L'],
            ['nis' => '102419360', 'nisn' => '3094589164', 'nama' => 'DEAN SULTAN SADYA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419361', 'nisn' => '0098787501', 'nama' => 'DZIKRY FARERA LENGGANA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419362', 'nisn' => '0083912690', 'nama' => 'FARREL MUTTAQIN', 'jenis_kelamin' => 'L'],
            ['nis' => '102419363', 'nisn' => '0086517268', 'nama' => 'FREGA TEGUH DWIGUNA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419364', 'nisn' => '0099930276', 'nama' => 'HANIF YANWAR WAHIDAN', 'jenis_kelamin' => 'L'],
            ['nis' => '102419365', 'nisn' => '0095573252', 'nama' => 'IMEITA NATASHA', 'jenis_kelamin' => 'P'],
            ['nis' => '102419366', 'nisn' => '0087467723', 'nama' => 'IRHAM HADI AHSANU', 'jenis_kelamin' => 'L'],
            ['nis' => '102419367', 'nisn' => '3097144451', 'nama' => 'KAISA VIDYA AMATULLAH', 'jenis_kelamin' => 'P'],
            ['nis' => '102419368', 'nisn' => '0081744762', 'nama' => 'KAKA ANDREA YAHYA', 'jenis_kelamin' => 'P'],
            ['nis' => '102419369', 'nisn' => '0088190060', 'nama' => 'MARVA AULIA AHMAD', 'jenis_kelamin' => 'P'],
            ['nis' => '102419370', 'nisn' => '0084058748', 'nama' => 'MUHAMMAD RAFFA HARVANI', 'jenis_kelamin' => 'L'],
            ['nis' => '102419371', 'nisn' => '0095147822', 'nama' => 'MUHAMMAD WILDAN TAUFIK', 'jenis_kelamin' => 'L'],
            ['nis' => '102419372', 'nisn' => '0087215222', 'nama' => 'NABIL AKBAR FADHILLAH', 'jenis_kelamin' => 'L'],
            ['nis' => '102419373', 'nisn' => '0084249496', 'nama' => 'NAILAH FAKHIRAH HANAF', 'jenis_kelamin' => 'P'],
            ['nis' => '102419374', 'nisn' => '3081221325', 'nama' => 'NAUFAL RAUSYAN FIKRI', 'jenis_kelamin' => 'L'],
            ['nis' => '102419375', 'nisn' => '0082483612', 'nama' => 'NESYA MEGA PUTRI', 'jenis_kelamin' => 'P'],
            ['nis' => '102419376', 'nisn' => '0084245542', 'nama' => 'PUTRI JASMINE AZZAHRA RAMADHANI', 'jenis_kelamin' => 'P'],
            ['nis' => '102419377', 'nisn' => '0082947154', 'nama' => 'RAFADITYA SYAHPUTRA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419378', 'nisn' => '0094557984', 'nama' => 'RAIKHANIA RIZKY PUTRI HERDIANA', 'jenis_kelamin' => 'P', 'jabatan' => 'Sekretaris', 'user_id' => $sekretaris->id],
            ['nis' => '102419379', 'nisn' => '0082273407', 'nama' => 'REFKY FAVIAN MAHARDIKA', 'jenis_kelamin' => 'L'],
            ['nis' => '102419380', 'nisn' => '0093347067', 'nama' => 'RIZKY FAUZI RAHMAN', 'jenis_kelamin' => 'L'],
            ['nis' => '102419381', 'nisn' => '0091176198', 'nama' => 'SASHYKIRANA ANANDITA SAHRONI', 'jenis_kelamin' => 'P'],
            ['nis' => '102419382', 'nisn' => '0089999286', 'nama' => 'SITUMORANG, GABRIELDO', 'jenis_kelamin' => 'L'],
            ['nis' => '102419383', 'nisn' => '0097955381', 'nama' => 'TIFAYATUL HUSNA AZAHRA', 'jenis_kelamin' => 'P'],
            ['nis' => '102419384', 'nisn' => '0091042703', 'nama' => 'YUGA PUTRA NUGRAHA', 'jenis_kelamin' => 'L'],
        ];
        foreach ($siswaXIIRPL as $s) {
            Siswa::create([
                'nis' => $s['nis'],
                'nisn' => $s['nisn'],
                'nama' => $s['nama'],
                'jenis_kelamin' => $s['jenis_kelamin'],
                'id_kelas' => $kelasXII->id,
                'jabatan' => $s['jabatan'] ?? 'Anggota',
                'user_id' => $s['user_id'] ?? null,
            ]);
        }

        $mapelDpk = Mapel::where('kode', 'DPK')->first();
        $mapelMath = Mapel::where('kode', 'MATH')->first();

        Jurusan::create([
            'kode' => 'KA',
            'nama' => 'Kimia Analisis',
            'deskripsi' => 'Mencetak pakar analisis kimia konvensional dan instrumen untuk standar kontrol mutu industri dan laboratorium modern.',
            'ikon' => 'fa-vial',
        ]);

        Jurusan::create([
            'kode' => 'TKJ',
            'nama' => 'Teknik Komputer & Jaringan',
            'deskripsi' => 'Keahlian merancang, membangun, mengonfigurasi dan mengelola infrastruktur jaringan enterprise hingga keamanan jaringan komputer.',
            'ikon' => 'fa-network-wired',
        ]);

        Jurusan::create([
            'kode' => 'RPL',
            'nama' => 'Rekayasa Perangkat Lunak',
            'deskripsi' => 'Pemrograman basis data, rekayasa perangkat lunak mobile, desktop dan integrasi sistem web terdistribusi.',
            'ikon' => 'fa-code',
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

        Berita::create([
            'judul' => 'Kunjungan Industri Siswa Kimia Analisis ke Bio Farma',
            'kategori' => 'Kegiatan',
            'isi' => "Siswa konsentrasi keahlian Kimia Analisis SMKN 13 Bandung melakukan kunjungan industri ke fasilitas riset dan kontrol mutu PT Bio Farma.\n\nKunjungan ini memberikan gambaran langsung penerapan instrumen pengujian modern dan standar Good Manufacturing Practice (GMP) di industri farmasi terkemuka.\n\nPara siswa mendapatkan pemaparan materi dari praktisi laboratorium serta berkesempatan mengamati langsung alur kerja pengujian mikrobiologi dan kimiawi.",
            'gambar' => 'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-09-15',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        Berita::create([
            'judul' => 'Pengumuman Asesmen Sumatif Akhir Semester Genap',
            'kategori' => 'Pengumuman',
            'isi' => "Diberitahukan kepada seluruh peserta didik kelas X, XI, dan XII bahwa pelaksanaan Asesmen Sumatif Akhir Semester (ASAS) Genap akan dilaksanakan secara digital melalui platform sekolah.\n\nPeserta didik diharapkan mempersiapkan perangkat gawai yang kompatibel dan memastikan kehadiran tepat waktu pada setiap sesi ujian sesuai jadwal yang telah ditentukan.\n\nKartu peserta ujian dapat diunduh melalui akun masing-masing peserta didik mulai tanggal 25 Mei 2026.",
            'gambar' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-09-10',
            'status' => 'Publish',
            'id_user' => $admin->id,
        ]);

        $gal1 = Galeri::create([
            'judul' => 'Uji Kompetensi APL',
            'kategori' => 'Akademik',
            'foto' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);
        GaleriFoto::create(['galeri_id' => $gal1->id, 'foto' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80', 'urutan' => 0]);
        GaleriFoto::create(['galeri_id' => $gal1->id, 'foto' => 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=600&q=80', 'urutan' => 1]);
        GaleriFoto::create(['galeri_id' => $gal1->id, 'foto' => 'https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&w=600&q=80', 'urutan' => 2]);
        GaleriFoto::create(['galeri_id' => $gal1->id, 'foto' => 'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=600&q=80', 'urutan' => 3]);
        GaleriFoto::create(['galeri_id' => $gal1->id, 'foto' => 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=600&q=80', 'urutan' => 4]);

        $gal2 = Galeri::create([
            'judul' => 'Hackathon Siswa RPL',
            'kategori' => 'Lomba',
            'foto' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);
        GaleriFoto::create(['galeri_id' => $gal2->id, 'foto' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80', 'urutan' => 0]);
        GaleriFoto::create(['galeri_id' => $gal2->id, 'foto' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80', 'urutan' => 1]);
        GaleriFoto::create(['galeri_id' => $gal2->id, 'foto' => 'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=600&q=80', 'urutan' => 2]);
        GaleriFoto::create(['galeri_id' => $gal2->id, 'foto' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80', 'urutan' => 3]);
        GaleriFoto::create(['galeri_id' => $gal2->id, 'foto' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80', 'urutan' => 4]);

        $gal3 = Galeri::create([
            'judul' => 'Gelar Karya P5 (Pancasila)',
            'kategori' => 'Kegiatan',
            'foto' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);
        GaleriFoto::create(['galeri_id' => $gal3->id, 'foto' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80', 'urutan' => 0]);
        GaleriFoto::create(['galeri_id' => $gal3->id, 'foto' => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=600&q=80', 'urutan' => 1]);
        GaleriFoto::create(['galeri_id' => $gal3->id, 'foto' => 'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?auto=format&fit=crop&w=600&q=80', 'urutan' => 2]);
        GaleriFoto::create(['galeri_id' => $gal3->id, 'foto' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80', 'urutan' => 3]);
        GaleriFoto::create(['galeri_id' => $gal3->id, 'foto' => 'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=600&q=80', 'urutan' => 4]);

        $gal4 = Galeri::create([
            'judul' => 'Fasilitas & Lab Jaringan',
            'kategori' => 'Fasilitas',
            'foto' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80',
            'tanggal' => '2026-10-01',
        ]);
        GaleriFoto::create(['galeri_id' => $gal4->id, 'foto' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80', 'urutan' => 0]);
        GaleriFoto::create(['galeri_id' => $gal4->id, 'foto' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80', 'urutan' => 1]);
        GaleriFoto::create(['galeri_id' => $gal4->id, 'foto' => 'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?auto=format&fit=crop&w=600&q=80', 'urutan' => 2]);
        GaleriFoto::create(['galeri_id' => $gal4->id, 'foto' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=600&q=80', 'urutan' => 3]);
        GaleriFoto::create(['galeri_id' => $gal4->id, 'foto' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80', 'urutan' => 4]);

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
            'nama' => 'PMR (Palang Merah Remaja)',
            'deskripsi' => 'Pelatihan pertolongan pertama, kesehatan remaja, dan kesiapsiagaan bencana.',
            'pembina' => 'Dra. Hj. Euis Rohaeti',
            'jadwal' => 'Rabu & Jumat 15.30 WIB',
            'gambar' => 'ekskul/pmr.jpg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'IRMA Al-Hikmah',
            'deskripsi' => 'Wadah pembinaan keislaman dan akhlak mulia melalui kegiatan keagamaan di lingkungan sekolah.',
            'pembina' => 'Drs. H. Maman S.',
            'jadwal' => 'Kamis & Jumat 15.30 WIB',
            'gambar' => 'ekskul/irma.jpg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Sastrala',
            'deskripsi' => 'Mengembangkan minat dan bakat siswa di bidang sastra, menulis, dan berkarya kreatif.',
            'pembina' => 'Nurul Diningsih, S.Hum.',
            'jadwal' => 'Selasa 15.30 WIB',
            'gambar' => 'ekskul/sastrala.jpeg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Padus (Paduan Suara)',
            'deskripsi' => 'Melatih olah vokal, harmoni, dan penampilan paduan suara untuk berbagai acara sekolah.',
            'pembina' => 'Rina Dewi, M.Pd.',
            'jadwal' => 'Senin & Rabu 15.30 WIB',
            'gambar' => 'ekskul/padus.jpeg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Banzai',
            'deskripsi' => 'Wadah kreativitas dan pengembangan minat siswa SMKN 13 Bandung di bidang bahasa dan budaya Jepang.',
            'pembina' => 'Tessa Eka Yuniar, S.Pd.',
            'jadwal' => 'Rabu 15.30 WIB',
            'gambar' => 'ekskul/banzai.jpg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Karawitan',
            'deskripsi' => 'Melestarikan seni musik tradisional Sunda melalui latihan dan pementasan karawitan.',
            'pembina' => 'Odang Supriatna, S.E.',
            'jadwal' => 'Kamis 15.30 WIB',
            'gambar' => 'ekskul/karawitan.jpg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Pramuka',
            'deskripsi' => 'Gerakan pramuka berlandaskan Dasa Darma untuk mencetak siswa berkarakter dan berjiwa kepemimpinan.',
            'pembina' => 'Drs. Maman S.',
            'jadwal' => 'Jumat 15.30 WIB',
            'gambar' => 'ekskul/pramuka.jpeg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'Paskibra',
            'deskripsi' => 'Melatih kedisiplinan, baris-berbaris, dan nasionalisme untuk petugas upacara bendera.',
            'pembina' => 'Siti Rahma, S.Pd.',
            'jadwal' => 'Sabtu 08.00 WIB',
            'gambar' => 'ekskul/paskibra.jpeg',
        ]);

        Ekstrakurikuler::create([
            'nama' => 'English Club',
            'deskripsi' => 'Melatih kemampuan berbahasa Inggris lewat percakapan, debat, dan berbagai kegiatan seru.',
            'pembina' => 'Rina Dewi, M.Pd.',
            'jadwal' => 'Rabu 15.30 WIB',
            'gambar' => 'ekskul/english_club.jpg',
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

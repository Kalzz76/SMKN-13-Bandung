<?php

namespace Tests\Feature;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\Jurusan;
use App\Models\PengaturanSekolah;
use App\Models\Prestasi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPengaturanDanKontenTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_halaman_pengaturan_dan_konten_bisa_diakses_admin(): void
    {
        $urls = [
            '/admin/pengaturan',
            '/admin/profil-sambutan',
            '/admin/berita',
            '/admin/galeri',
            '/admin/jurusan',
            '/admin/ekskul',
            '/admin/prestasi',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($this->admin)->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_tamu_tidak_bisa_mengakses_halaman_admin_konten(): void
    {
        $response = $this->get('/admin/pengaturan');
        $response->assertRedirect('/');
    }

    public function test_admin_bisa_memperbarui_pengaturan_sekolah(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('logo.png');

        $response = $this->actingAs($this->admin)->put('/admin/pengaturan', [
            'nama_sekolah' => 'SMKN 13 Bandung Update',
            'npsn' => '20219154',
            'alamat' => 'Jl. Soekarno-Hatta Km. 10',
            'email' => 'admin@smkn13bandung.sch.id',
            'telepon' => '0227801234',
            'social_media' => '@smkn13bdg',
            'jam_operasional' => '07.00 - 16.00 WIB',
            'lat_sekolah' => -6.94580000,
            'long_sekolah' => 107.67650000,
            'radius_meter' => 150,
            'jam_masuk' => '07:15',
            'logo' => $file,
        ]);

        $response->assertRedirect('/admin/pengaturan');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengaturan_sekolah', [
            'nama_sekolah' => 'SMKN 13 Bandung Update',
            'radius_meter' => 150,
        ]);
    }

    public function test_admin_bisa_memperbarui_profil_dan_sambutan(): void
    {
        Storage::fake('public');
        $fotoKepsek = UploadedFile::fake()->image('kepsek.jpg');

        $response = $this->actingAs($this->admin)->put('/admin/profil-sambutan', [
            'slogan' => 'Cerdas & Berkarakter',
            'deskripsi_singkat' => 'Pusat kejuruan teknologi.',
            'visi' => 'Menjadi SMK Unggul Nasional.',
            'misi' => "Mendidik siswa berkarakter\nMenjalin kerja sama industri",
            'sejarah' => 'Berdiri sejak tahun 1963...',
            'nama_kepsek' => 'Dr. H. Ahmad Supriyadi, M.Pd.',
            'judul_sambutan' => 'Menyongsong Era Digital',
            'sambutan' => 'Selamat datang di website resmi...',
            'foto_kepsek' => $fotoKepsek,
        ]);

        $response->assertRedirect('/admin/profil-sambutan');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengaturan_sekolah', [
            'slogan' => 'Cerdas & Berkarakter',
            'nama_kepsek' => 'Dr. H. Ahmad Supriyadi, M.Pd.',
        ]);
    }

    public function test_crud_berita_berhasil(): void
    {
        Storage::fake('public');
        $gambar = UploadedFile::fake()->image('berita.jpg');

        $responseTambah = $this->actingAs($this->admin)->post('/admin/berita', [
            'judul' => 'Uji Coba Berita Baru',
            'kategori' => 'Prestasi',
            'isi' => 'Konten pengujian berita sekolah.',
            'status' => 'Publish',
            'tanggal' => '2026-10-05',
            'gambar' => $gambar,
        ]);

        $responseTambah->assertRedirect('/admin/berita');
        $this->assertDatabaseHas('berita', [
            'judul' => 'Uji Coba Berita Baru',
            'kategori' => 'Prestasi',
        ]);

        $berita = Berita::where('judul', 'Uji Coba Berita Baru')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/berita/' . $berita->id, [
            'judul' => 'Uji Coba Berita Diedit',
            'kategori' => 'Kegiatan',
            'isi' => 'Konten pengujian berita sekolah diperbarui.',
            'status' => 'Draft',
            'tanggal' => '2026-10-06',
        ]);

        $responseEdit->assertRedirect('/admin/berita');
        $this->assertDatabaseHas('berita', [
            'id' => $berita->id,
            'judul' => 'Uji Coba Berita Diedit',
            'status' => 'Draft',
        ]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/berita/' . $berita->id);
        $responseHapus->assertRedirect('/admin/berita');
        $this->assertDatabaseMissing('berita', [
            'id' => $berita->id,
        ]);
    }

    public function test_crud_galeri_berhasil(): void
    {
        Storage::fake('public');
        $foto = UploadedFile::fake()->image('galeri.jpg');

        $responseTambah = $this->actingAs($this->admin)->post('/admin/galeri', [
            'judul' => 'Ruang Server Baru',
            'kategori' => 'Fasilitas',
            'tanggal' => '2026-10-05',
            'foto' => $foto,
        ]);

        $responseTambah->assertRedirect('/admin/galeri');
        $this->assertDatabaseHas('galeri', [
            'judul' => 'Ruang Server Baru',
            'kategori' => 'Fasilitas',
        ]);

        $galeri = Galeri::where('judul', 'Ruang Server Baru')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/galeri/' . $galeri->id, [
            'judul' => 'Ruang Server & Cloud',
            'kategori' => 'Fasilitas',
            'tanggal' => '2026-10-06',
        ]);

        $responseEdit->assertRedirect('/admin/galeri');
        $this->assertDatabaseHas('galeri', [
            'id' => $galeri->id,
            'judul' => 'Ruang Server & Cloud',
        ]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/galeri/' . $galeri->id);
        $responseHapus->assertRedirect('/admin/galeri');
        $this->assertDatabaseMissing('galeri', [
            'id' => $galeri->id,
        ]);
    }

    public function test_upload_multiple_foto_galeri_dan_kelola_album(): void
    {
        Storage::fake('public');
        $foto1 = UploadedFile::fake()->image('foto1.jpg');
        $foto2 = UploadedFile::fake()->image('foto2.jpg');
        $foto3 = UploadedFile::fake()->image('foto3.jpg');

        $responseTambah = $this->actingAs($this->admin)->post('/admin/galeri', [
            'judul' => 'Lab Multimedia Anyar',
            'kategori' => 'Fasilitas',
            'tanggal' => '2026-10-06',
            'foto' => [$foto1, $foto2, $foto3],
        ]);

        $responseTambah->assertRedirect('/admin/galeri');
        $this->assertDatabaseHas('galeri', [
            'judul' => 'Lab Multimedia Anyar',
        ]);

        $galeri = Galeri::where('judul', 'Lab Multimedia Anyar')->first();
        $this->assertCount(3, $galeri->fotos);

        $fotoBaru = UploadedFile::fake()->image('foto4.jpg');
        $responseTambahFoto = $this->actingAs($this->admin)->post('/admin/galeri/' . $galeri->id . '/tambah-foto', [
            'foto' => [$fotoBaru],
        ]);
        $responseTambahFoto->assertRedirect('/admin/galeri');
        $galeri->refresh();
        $this->assertCount(4, $galeri->fotos);

        $fotoItem = $galeri->fotos->last();
        $responseHapusFoto = $this->actingAs($this->admin)->delete('/admin/galeri/foto/' . $fotoItem->id);
        $responseHapusFoto->assertStatus(302);
        $this->assertDatabaseMissing('galeri_foto', ['id' => $fotoItem->id]);
    }

    public function test_crud_jurusan_berhasil(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/jurusan', [
            'kode' => 'SIJA',
            'nama' => 'Sistem Informasi Jaringan dan Aplikasi',
            'deskripsi' => 'Pengembangan komputasi awan dan IoT.',
            'ikon' => 'fa-cloud',
        ]);

        $responseTambah->assertRedirect('/admin/jurusan');
        $this->assertDatabaseHas('jurusan', [
            'kode' => 'SIJA',
            'nama' => 'Sistem Informasi Jaringan dan Aplikasi',
        ]);

        $jurusan = Jurusan::where('kode', 'SIJA')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/jurusan/' . $jurusan->id, [
            'kode' => 'SIJA',
            'nama' => 'Sistem Informasi Jaringan & Aplikasi',
            'deskripsi' => 'Deskripsi update.',
            'ikon' => 'fa-server',
        ]);

        $responseEdit->assertRedirect('/admin/jurusan');
        $this->assertDatabaseHas('jurusan', [
            'id' => $jurusan->id,
            'nama' => 'Sistem Informasi Jaringan & Aplikasi',
            'ikon' => 'fa-server',
        ]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/jurusan/' . $jurusan->id);
        $responseHapus->assertRedirect('/admin/jurusan');
        $this->assertDatabaseMissing('jurusan', [
            'id' => $jurusan->id,
        ]);
    }

    public function test_crud_ekskul_berhasil(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/ekskul', [
            'nama' => 'PMR Wira',
            'pembina' => 'Dr. Ratna',
            'jadwal' => 'Kamis 15.30 WIB',
            'deskripsi' => 'Palang Merah Remaja unit sekolah.',
        ]);

        $responseTambah->assertRedirect('/admin/ekskul');
        $this->assertDatabaseHas('ekstrakurikuler', [
            'nama' => 'PMR Wira',
        ]);

        $ekskul = Ekstrakurikuler::where('nama', 'PMR Wira')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/ekskul/' . $ekskul->id, [
            'nama' => 'Palang Merah Remaja',
            'pembina' => 'Dr. Ratna Dewi',
            'jadwal' => 'Kamis 15.30 WIB',
            'deskripsi' => 'Kegiatan pertolongan pertama.',
        ]);

        $responseEdit->assertRedirect('/admin/ekskul');
        $this->assertDatabaseHas('ekstrakurikuler', [
            'id' => $ekskul->id,
            'nama' => 'Palang Merah Remaja',
        ]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/ekskul/' . $ekskul->id);
        $responseHapus->assertRedirect('/admin/ekskul');
        $this->assertDatabaseMissing('ekstrakurikuler', [
            'id' => $ekskul->id,
        ]);
    }

    public function test_crud_prestasi_berhasil(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/prestasi', [
            'judul' => 'Juara 1 Cyber Security',
            'tingkat' => 'Nasional',
            'tahun' => '2026',
            'nama_peraih' => 'Tim Whitehat SMKN 13',
            'deskripsi' => 'Kompetisi keamanan siber tingkat nasional.',
        ]);

        $responseTambah->assertRedirect('/admin/prestasi');
        $this->assertDatabaseHas('prestasi', [
            'judul' => 'Juara 1 Cyber Security',
            'tingkat' => 'Nasional',
        ]);

        $prestasi = Prestasi::where('judul', 'Juara 1 Cyber Security')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/prestasi/' . $prestasi->id, [
            'judul' => 'Juara 1 Cyber Security Nasional',
            'tingkat' => 'Nasional',
            'tahun' => '2026',
            'nama_peraih' => 'Tim Whitehat 13',
            'deskripsi' => 'Deskripsi update.',
        ]);

        $responseEdit->assertRedirect('/admin/prestasi');
        $this->assertDatabaseHas('prestasi', [
            'id' => $prestasi->id,
            'judul' => 'Juara 1 Cyber Security Nasional',
        ]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/prestasi/' . $prestasi->id);
        $responseHapus->assertRedirect('/admin/prestasi');
        $this->assertDatabaseMissing('prestasi', [
            'id' => $prestasi->id,
        ]);
    }
}

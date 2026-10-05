<?php

namespace Tests\Feature;

use App\Models\Berita;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HalamanPublikTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_beranda_menampilkan_data_sekolah(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SMK Negeri 13 Bandung');
        $response->assertSee('Sambutan Pimpinan');
    }

    public function test_halaman_profil_menampilkan_visi_misi_dan_guru(): void
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);
        $response->assertSee('Sejarah Singkat SMKN 13 Bandung');
        $response->assertSee('Visi & Misi', false);
        $response->assertSee('Tenaga Pendidik & Staff Pengajar', false);
    }

    public function test_halaman_jurusan_menampilkan_program_keahlian(): void
    {
        $response = $this->get('/jurusan');
        $response->assertStatus(200);
        $response->assertSee('Rekayasa Perangkat Lunak');
        $response->assertSee('TKJ');
    }

    public function test_halaman_berita_menampilkan_daftar_berita(): void
    {
        $response = $this->get('/berita');
        $response->assertStatus(200);
        $response->assertSee('Berita, Kegiatan & Prestasi Sekolah', false);
    }

    public function test_halaman_detail_berita_menampilkan_isi_lengkap(): void
    {
        $berita = Berita::where('status', 'Publish')->first();
        $response = $this->get('/berita/' . $berita->id);
        $response->assertStatus(200);
        $response->assertSee($berita->judul);
        $response->assertSee('Kembali ke Berita & Informasi', false);
    }

    public function test_halaman_galeri_dan_filter_berfungsi(): void
    {
        $responseAll = $this->get('/galeri');
        $responseAll->assertStatus(200);
        $responseAll->assertSee('Galeri Kegiatan & Fasilitas Sekolah', false);

        $responseFasilitas = $this->get('/galeri?kategori=Fasilitas');
        $responseFasilitas->assertStatus(200);
    }

    public function test_halaman_kontak_menampilkan_identitas_sekolah(): void
    {
        $response = $this->get('/kontak');
        $response->assertStatus(200);
        $response->assertSee('Alamat Kampus');
        $response->assertSee('Jam Operasional Layanan');
    }

    public function test_halaman_ekstrakurikuler_menampilkan_daftar_ekskul(): void
    {
        $response = $this->get('/ekstrakurikuler');
        $response->assertStatus(200);
        $response->assertSee('Ekstrakurikuler SMKN 13 Bandung');
        $response->assertSee('Pramuka');
    }

    public function test_halaman_prestasi_menampilkan_daftar_prestasi(): void
    {
        $response = $this->get('/prestasi');
        $response->assertStatus(200);
        $response->assertSee('Torehan Prestasi SMKN 13 Bandung');
        $response->assertSee('Juara 1 LKS Web Technologies Kota Bandung');
    }
}

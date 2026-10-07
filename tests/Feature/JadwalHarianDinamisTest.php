<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\JadwalPembiasaan;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalHarianDinamisTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    private function payload(string $hari, int $mulai, int $selesai, array $override = []): array
    {
        $kelasUji = Kelas::firstOrCreate(
            ['nama' => 'X RPL 99'],
            [
                'id_ruangan' => Ruangan::where('kode', 'R.20')->first()->id,
                'id_wali_kelas' => Guru::where('nama', 'like', '%KIKI AIMA%')->first()->id,
            ]
        );

        return array_merge([
            'hari' => $hari,
            'id_kelas' => $kelasUji->id,
            'id_mapel' => Mapel::first()->id,
            'id_guru' => Guru::where('nama', 'like', '%KIKI AIMA%')->first()->id,
            'id_ruangan' => Ruangan::where('kode', 'R.20')->first()->id,
            'jam_ke_mulai' => $mulai,
            'jam_ke_selesai' => $selesai,
        ], $override);
    }

    public function test_master_jam_sesuai_dokumen_per_hari(): void
    {
        $harapan = ['Senin' => 10, 'Selasa' => 11, 'Rabu' => 10, 'Kamis' => 11, 'Jumat' => 10];

        foreach ($harapan as $hari => $maks) {
            $ringkasan = JamPelajaran::ringkasanHari($hari);
            $this->assertSame($maks, $ringkasan['maks_jam'], "Jumlah jam {$hari}");
            $this->assertCount($maks, $ringkasan['jam']);
        }

        $senin = JamPelajaran::ringkasanHari('Senin');
        $this->assertSame('Carabika', $senin['pembiasaan']['nama']);
        $this->assertSame([3, 5], array_column($senin['istirahat'], 'setelah_jam'));
        $this->assertSame('12:30', $senin['jam'][5]['jam_mulai']);
        $this->assertSame('16:15', $senin['jam'][9]['jam_selesai']);

        $selasa = JamPelajaran::ringkasanHari('Selasa');
        $this->assertSame('Saji Bajigur', $selasa['pembiasaan']['nama']);
        $this->assertSame('06:45', $selasa['jam'][0]['jam_mulai']);
        $this->assertSame([4, 6], array_column($selasa['istirahat'], 'setelah_jam'));
        $this->assertSame('16:15', $selasa['jam'][10]['jam_selesai']);

        $this->assertSame('Ladu Raos', JamPelajaran::ringkasanHari('Rabu')['pembiasaan']['nama']);
        $this->assertSame('Tarasi Amis', JamPelajaran::ringkasanHari('Kamis')['pembiasaan']['nama']);

        $jumat = JamPelajaran::ringkasanHari('Jumat');
        $this->assertSame('Jalabria', $jumat['pembiasaan']['nama']);
        $this->assertSame('07:15', $jumat['jam'][0]['jam_mulai']);
        $this->assertSame([6], array_column($jumat['istirahat'], 'setelah_jam'));
        $this->assertSame('13:00', $jumat['jam'][6]['jam_mulai']);
        $this->assertSame('16:00', $jumat['jam'][9]['jam_selesai']);
    }

    public function test_endpoint_jam_per_hari_mengembalikan_struktur_hari(): void
    {
        $response = $this->actingAs($this->admin)->getJson('/admin/jadwal/jam/Selasa');
        $response->assertOk()
            ->assertJsonPath('hari', 'Selasa')
            ->assertJsonPath('maks_jam', 11)
            ->assertJsonPath('pembiasaan.nama', 'Saji Bajigur')
            ->assertJsonCount(11, 'jam')
            ->assertJsonPath('bagian.0.label', 'Pagi')
            ->assertJsonPath('bagian.2.label', 'Sore');

        $this->actingAs($this->admin)->getJson('/admin/jadwal/jam/Jumat')
            ->assertOk()
            ->assertJsonPath('maks_jam', 10)
            ->assertJsonCount(2, 'bagian');

        $this->actingAs($this->admin)->getJson('/admin/jadwal/jam/Sabtu')->assertNotFound();
    }

    public function test_jam_ke_11_hanya_valid_pada_selasa_dan_kamis(): void
    {
        $senin = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Senin', 11, 11));
        $senin->assertSessionHas('error');

        $jumat = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Jumat', 10, 11));
        $jumat->assertSessionHas('error');

        $selasa = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Selasa', 10, 11));
        $selasa->assertSessionMissing('error');
        $this->assertDatabaseHas('jadwal', ['hari' => 'Selasa', 'jam_ke_mulai' => 10, 'jam_ke_selesai' => 11]);

        $kamis = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Kamis', 7, 11));
        $kamis->assertSessionMissing('error');
        $this->assertDatabaseHas('jadwal', ['hari' => 'Kamis', 'jam_ke_mulai' => 7, 'jam_ke_selesai' => 11]);
    }

    public function test_istirahat_mengikuti_struktur_masing_masing_hari(): void
    {
        $selasaTanpaIstirahat = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Selasa', 1, 4));
        $selasaTanpaIstirahat->assertSessionMissing('error');

        $guruBebas = Guru::create(['nama' => 'Guru Split Test', 'jenis' => 'Guru']);
        $ruangBebas = Ruangan::where('kode', 'R.55')->first();

        $senin = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Senin', 3, 4, [
            'id_guru' => $guruBebas->id,
            'id_ruangan' => $ruangBebas->id,
        ]));
        $senin->assertSessionMissing('error');
        $this->assertDatabaseHas('jadwal', ['hari' => 'Senin', 'jam_ke_mulai' => 3, 'jam_ke_selesai' => 3]);
        $this->assertDatabaseHas('jadwal', ['hari' => 'Senin', 'jam_ke_mulai' => 4, 'jam_ke_selesai' => 4]);

        $jumatMelewati = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Jumat', 5, 7, [
            'id_guru' => $guruBebas->id,
            'id_ruangan' => $ruangBebas->id,
        ]));
        $jumatMelewati->assertSessionMissing('error');
        $this->assertDatabaseHas('jadwal', ['hari' => 'Jumat', 'jam_ke_mulai' => 5, 'jam_ke_selesai' => 6]);
        $this->assertDatabaseHas('jadwal', ['hari' => 'Jumat', 'jam_ke_mulai' => 7, 'jam_ke_selesai' => 7]);

        $guruBebas2 = Guru::create(['nama' => 'Guru Split Test 2', 'jenis' => 'Guru']);
        $kelasUji2 = Kelas::create([
            'nama' => 'X RPL 98',
            'id_ruangan' => Ruangan::where('kode', 'R.56')->first()->id,
            'id_wali_kelas' => $guruBebas2->id,
        ]);
        $jumatPanjang = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Jumat', 1, 6, [
            'id_kelas' => $kelasUji2->id,
            'id_guru' => $guruBebas2->id,
            'id_ruangan' => Ruangan::where('kode', 'R.56')->first()->id,
        ]));
        $jumatPanjang->assertSessionMissing('error');
        $this->assertDatabaseHas('jadwal', ['hari' => 'Jumat', 'id_kelas' => $kelasUji2->id, 'jam_ke_mulai' => 1, 'jam_ke_selesai' => 6]);
    }

    public function test_bentrok_dicek_per_hari_dan_rentang_jam(): void
    {
        $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Rabu', 1, 3))
            ->assertSessionMissing('error');

        $bentrok = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Rabu', 3, 3));
        $bentrok->assertSessionHas('error');

        $hariLain = $this->actingAs($this->admin)->post('/admin/jadwal', $this->payload('Kamis', 1, 3));
        $hariLain->assertSessionMissing('error');
    }

    public function test_halaman_jadwal_menampilkan_pembiasaan_dan_jam_sesuai_hari(): void
    {
        $selasa = $this->actingAs($this->admin)->get('/admin/jadwal?hari=Selasa');
        $selasa->assertOk()
            ->assertSee('SAJI BAJIGUR', false)
            ->assertSee('JAM 11', false)
            ->assertSee('06.45 - 07.30', false)
            ->assertDontSee('PERWALIAN', false);

        $jumat = $this->actingAs($this->admin)->get('/admin/jadwal?hari=Jumat');
        $jumat->assertOk()
            ->assertSee('JALABRIA', false)
            ->assertSee('JAM 10', false)
            ->assertDontSee('JAM 11', false);

        $senin = $this->actingAs($this->admin)->get('/admin/jadwal?hari=Senin');
        $senin->assertOk()
            ->assertSee('CARABIKA', false)
            ->assertDontSee('JAM 11', false);
    }

    public function test_rotasi_carabika_senin_bergantian_per_sesi(): void
    {
        $sesi1 = $this->actingAs($this->admin)->get('/admin/jadwal?hari=Senin&sesi=1');
        $sesi1->assertOk()
            ->assertSee('UPACARA BENDERA', false)
            ->assertSee('PERWALIAN', false);

        $kelasXII = Kelas::where('nama', 'XII RPL 1')->first();
        $kelasX = Kelas::where('nama', 'X KA 1')->first();
        $this->assertSame('A', $kelasXII->kelompok_pembiasaan);
        $this->assertSame('B', $kelasX->kelompok_pembiasaan);

        $labelSesi1 = JadwalPembiasaan::where('hari', 'Senin')->where('sesi', 1);
        $this->assertSame('Perwalian', (clone $labelSesi1)->where('kelompok', 'A')->first()->kegiatan);
        $this->assertSame('Upacara Bendera', (clone $labelSesi1)->where('kelompok', 'B')->first()->kegiatan);

        $labelSesi2 = JadwalPembiasaan::where('hari', 'Senin')->where('sesi', 2);
        $this->assertSame('Upacara Bendera', (clone $labelSesi2)->where('kelompok', 'A')->first()->kegiatan);
        $this->assertSame('Perwalian', (clone $labelSesi2)->where('kelompok', 'B')->first()->kegiatan);

        $this->actingAs($this->admin)->get('/admin/jadwal?hari=Senin&sesi=2')
            ->assertOk()
            ->assertSee('Sesi 2 (Minggu Genap)', false);
    }

    public function test_tingkat_kelas_dikenali_dari_nama(): void
    {
        $this->assertSame('X', (new Kelas(['nama' => 'X KA 1']))->tingkat);
        $this->assertSame('XI', (new Kelas(['nama' => 'XI RPL 2']))->tingkat);
        $this->assertSame('XII', (new Kelas(['nama' => 'XII TKJ 1']))->tingkat);
        $this->assertSame('XIII', (new Kelas(['nama' => 'XIII KA 1']))->tingkat);
        $this->assertNull((new Kelas(['nama' => 'Lainnya']))->tingkat);
    }

    public function test_halaman_jadwal_memuat_relasi_mapel_guru_untuk_filter_otomatis(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/jadwal');
        $response->assertOk()
            ->assertSee('guruMengampuMapel', false)
            ->assertSee('onTambahMapelChange', false)
            ->assertSee('onEditMapelChange', false);

        $daftarGuru = $response->viewData('daftarGuru');
        $this->assertNotEmpty($daftarGuru);
        $this->assertTrue($daftarGuru->first()->relationLoaded('mapels'));
    }
}

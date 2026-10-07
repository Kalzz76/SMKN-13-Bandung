<?php

namespace Tests\Feature;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JurnalKelas;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\PengaturanSekolah;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BarcodeDanAbsensiGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $userGuru;
    protected $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();

        $this->userGuru = User::where('role', 'guru')->first();
        if (!$this->userGuru) {
            $this->userGuru = User::create([
                'name' => 'Guru Pengajar Test',
                'username' => 'guru.test',
                'email' => 'guru.test@smkn13bdg.sch.id',
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);
        }

        $this->guru = Guru::where('user_id', $this->userGuru->id)->first();
        if (!$this->guru) {
            $this->guru = Guru::create([
                'user_id' => $this->userGuru->id,
                'nip' => '198501012010011005',
                'nama' => $this->userGuru->name,
                'jenis' => 'Guru',
                'kode_barcode' => 'BARCODE-TEST-123456789012345678',
            ]);
        } elseif (empty($this->guru->kode_barcode)) {
            $this->guru->kode_barcode = 'BARCODE-TEST-123456789012345678';
            $this->guru->save();
        }
    }

    public function test_admin_dapat_mengakses_halaman_barcode(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/barcode');
        $response->assertStatus(200);
        $response->assertSee('Barcode');
    }

    public function test_admin_dapat_generate_barcode_guru(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/barcode/generate', [
            'id_guru' => $this->guru->id,
        ]);

        $response->assertRedirect('/admin/barcode?guru_id=' . $this->guru->id);
        $this->guru->refresh();
        $this->assertNotEmpty($this->guru->kode_barcode);
        $this->assertEquals(32, strlen($this->guru->kode_barcode));
    }

    public function test_admin_dapat_generate_semua_barcode_guru(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/barcode/generate-semua');
        $response->assertRedirect('/admin/barcode');

        $semuaGuru = Guru::where('jenis', 'Guru')->get();
        foreach ($semuaGuru as $g) {
            $this->assertNotEmpty($g->kode_barcode);
        }
    }

    public function test_admin_dapat_melihat_halaman_cetak_barcode(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/barcode/cetak');
        $response->assertStatus(200);
        $response->assertSee($this->guru->nama);
    }

    public function test_guru_dapat_mengakses_dashboard_dan_tab_tabnya(): void
    {
        $tabs = ['validasi', 'jadwal', 'rekap'];
        foreach ($tabs as $tab) {
            $response = $this->actingAs($this->userGuru)->get('/guru?tab=' . $tab);
            $response->assertStatus(200);
        }
    }

    public function test_guru_berhasil_scan_absensi_jika_lokasi_dalam_radius_dan_kode_cocok(): void
    {
        $pengaturan = PengaturanSekolah::first();
        $lat = $pengaturan->lat_sekolah ?? -6.94580000;
        $long = $pengaturan->long_sekolah ?? 107.67650000;

        $response = $this->actingAs($this->userGuru)->post('/guru/absensi-scan', [
            'kode_barcode' => $this->guru->kode_barcode,
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        $response->assertRedirect('/guru?tab=absensi-saya');
        $response->assertSessionHas('sukses');

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
        ]);
    }

    public function test_guru_gagal_scan_jika_lokasi_di_luar_radius_sekolah(): void
    {
        $response = $this->actingAs($this->userGuru)->post('/guru/absensi-scan', [
            'kode_barcode' => $this->guru->kode_barcode,
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $response->assertRedirect('/guru?tab=absensi-saya');
        $response->assertSessionHas('error');

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->assertDatabaseMissing('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
        ]);
    }

    public function test_guru_gagal_scan_jika_kode_barcode_salah(): void
    {
        $pengaturan = PengaturanSekolah::first();
        $lat = $pengaturan->lat_sekolah ?? -6.94580000;
        $long = $pengaturan->long_sekolah ?? 107.67650000;

        $response = $this->actingAs($this->userGuru)->post('/guru/absensi-scan', [
            'kode_barcode' => 'KODE-BARCODE-YANG-SALAH-DAN-TIDAK-COCOK',
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        $response->assertRedirect('/guru?tab=absensi-saya');
        $response->assertSessionHas('error');
    }

    public function test_guru_tidak_bisa_absen_ganda_pada_hari_yang_sama(): void
    {
        $pengaturan = PengaturanSekolah::first();
        $lat = $pengaturan->lat_sekolah ?? -6.94580000;
        $long = $pengaturan->long_sekolah ?? 107.67650000;

        $this->actingAs($this->userGuru)->post('/guru/absensi-scan', [
            'kode_barcode' => $this->guru->kode_barcode,
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        $responseKedua = $this->actingAs($this->userGuru)->post('/guru/absensi-scan', [
            'kode_barcode' => $this->guru->kode_barcode,
            'latitude' => $lat,
            'longitude' => $long,
        ]);

        $responseKedua->assertRedirect('/guru?tab=absensi-saya');
        $responseKedua->assertSessionHas('error');
    }

    public function test_guru_dapat_mengakses_halaman_absensi_siswa_untuk_jadwal_miliknya(): void
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';

        $kelas = Kelas::first();
        $mapel = Mapel::first();
        $ruangan = Ruangan::first();

        $jadwal = Jadwal::create([
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $this->guru->id,
            'id_ruangan' => $ruangan->id,
            'hari' => $hariIni,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        $jamSlot = \App\Models\JamPelajaran::where('hari', $hariIni)->where('jam_ke', 1)->first();
        $waktuTes = $jamSlot ? $jamSlot->jam_mulai : '07:30';
        Carbon::setTestNow(Carbon::parse("today {$waktuTes}", 'Asia/Jakarta'));

        $response = $this->actingAs($this->userGuru)->get('/guru/absensi-siswa/' . $jadwal->id);
        Carbon::setTestNow();
        $response->assertStatus(200);
        $response->assertSee($kelas->nama);
        $response->assertSee($mapel->nama);
    }

    public function test_guru_dilarang_mengakses_absensi_siswa_jadwal_guru_lain(): void
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';

        $guruLain = Guru::where('id', '!=', $this->guru->id)->first();
        $kelas = Kelas::first();
        $mapel = Mapel::first();
        $ruangan = Ruangan::first();

        $jadwalLain = Jadwal::create([
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $guruLain->id,
            'id_ruangan' => $ruangan->id,
            'hari' => $hariIni,
            'jam_ke_mulai' => 4,
            'jam_ke_selesai' => 5,
        ]);

        $response = $this->actingAs($this->userGuru)->get('/guru/absensi-siswa/' . $jadwalLain->id);
        $response->assertStatus(403);
    }

    public function test_guru_berhasil_menyimpan_absensi_siswa_dan_jurnal_kelas(): void
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';

        $kelas = Kelas::first();
        $mapel = Mapel::first();
        $ruangan = Ruangan::first();

        $jadwal = Jadwal::create([
            'id_kelas' => $kelas->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $this->guru->id,
            'id_ruangan' => $ruangan->id,
            'hari' => $hariIni,
            'jam_ke_mulai' => 6,
            'jam_ke_selesai' => 7,
        ]);

        $siswa = Siswa::where('id_kelas', $kelas->id)->first();
        if (!$siswa) {
            $siswa = Siswa::create([
                'nis' => '99887766',
                'nisn' => '0011223344',
                'nama' => 'Siswa Test Kehadiran',
                'id_kelas' => $kelas->id,
                'jenis_kelamin' => 'L',
            ]);
        }

        $jamSlot = \App\Models\JamPelajaran::where('hari', $hariIni)->where('jam_ke', 6)->first();
        $waktuTes = $jamSlot ? $jamSlot->jam_mulai : '11:00';
        Carbon::setTestNow(Carbon::parse("today {$waktuTes}", 'Asia/Jakarta'));

        $response = $this->actingAs($this->userGuru)->post('/guru/absensi-siswa/' . $jadwal->id, [
            'status' => [
                $siswa->id => 'Hadir',
            ],
            'keterangan' => [
                $siswa->id => 'Tepat waktu dan aktif',
            ],
            'materi' => 'Pengenalan Basis Data Relasional dan Perancangan Skema',
        ]);

        Carbon::setTestNow();
        $response->assertRedirect('/guru?tab=jadwal');
        $response->assertSessionHas('sukses');

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->assertDatabaseHas('absensi_siswa', [
            'id_jadwal' => $jadwal->id,
            'id_siswa' => $siswa->id,
            'status' => 'Hadir',
            'keterangan' => 'Tepat waktu dan aktif',
            'tanggal' => $tanggalHariIni,
        ]);

        $this->assertDatabaseHas('jurnal_kelas', [
            'id_jadwal' => $jadwal->id,
            'materi' => 'Pengenalan Basis Data Relasional dan Perancangan Skema',
            'tanggal' => $tanggalHariIni,
        ]);
    }
}

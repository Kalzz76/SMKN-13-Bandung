<?php

namespace Tests\Feature;

use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SekretarisDanLaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $sekretaris;
    protected $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->sekretaris = User::where('role', 'sekretaris')->first();
        $this->guru = Guru::where('jenis', 'Guru')->first();
    }

    public function test_admin_dapat_mengakses_dashboard_dan_melihat_statistik(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Panel Utama');
        $response->assertSee('Total Guru');
        $response->assertSee('Total Siswa');
    }

    public function test_admin_dapat_mengakses_laporan_absensi_guru_dan_siswa(): void
    {
        $responseGuru = $this->actingAs($this->admin)->get('/admin/laporan?tab=guru');
        $responseGuru->assertStatus(200);
        $responseGuru->assertSee('Absensi Guru');

        $kelas = Kelas::first();
        $responseSiswa = $this->actingAs($this->admin)->get('/admin/laporan?tab=siswa&kelas_id=' . $kelas->id);
        $responseSiswa->assertStatus(200);
        $responseSiswa->assertSee('Absensi Siswa');
    }

    public function test_admin_dapat_ekspor_csv_dan_cetak_laporan_guru(): void
    {
        $responseCsv = $this->actingAs($this->admin)->get('/admin/laporan/guru/csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $responseCetak = $this->actingAs($this->admin)->get('/admin/laporan/guru/cetak');
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('Laporan Rekapitulasi Presensi Kehadiran Guru Pengajar');
    }

    public function test_admin_dapat_ekspor_csv_dan_cetak_laporan_siswa(): void
    {
        $kelas = Kelas::first();

        $responseCsv = $this->actingAs($this->admin)->get('/admin/laporan/siswa/csv?kelas_id=' . $kelas->id);
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $responseCetak = $this->actingAs($this->admin)->get('/admin/laporan/siswa/cetak?kelas_id=' . $kelas->id);
        $responseCetak->assertStatus(200);
        $responseCetak->assertSee('Laporan Rekapitulasi Presensi Kehadiran Siswa');
    }

    public function test_admin_dapat_melihat_halaman_log_aktivitas(): void
    {
        LogAktivitas::catat('Pengujian Log', 'Mencatat aktivitas pengujian');

        $response = $this->actingAs($this->admin)->get('/admin/log');
        $response->assertStatus(200);
        $response->assertSee('P. Log Aktivitas Sistem');
        $response->assertSee('Pengujian Log');

        $responseCari = $this->actingAs($this->admin)->get('/admin/log?cari=Pengujian');
        $responseCari->assertStatus(200);
        $responseCari->assertSee('Pengujian Log');
    }

    public function test_sekretaris_dapat_mengakses_dashboard_dan_tab_tabnya(): void
    {
        $tabs = ['pengganti', 'jadwal', 'laporan'];
        foreach ($tabs as $tab) {
            $response = $this->actingAs($this->sekretaris)->get('/sekretaris?tab=' . $tab);
            $response->assertStatus(200);
        }
    }

    public function test_sekretaris_berhasil_mencatat_absensi_pengganti_guru(): void
    {
        $response = $this->actingAs($this->sekretaris)->post('/sekretaris/absensi-pengganti', [
            'id_guru' => $this->guru->id,
            'status' => 'Sakit',
            'alasan' => 'Sakit demam, istirahat dokter',
        ]);

        $response->assertRedirect('/sekretaris?tab=pengganti');
        $response->assertSessionHas('sukses');

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'Sakit',
            'metode' => 'Sekretaris',
            'alasan' => 'Sakit demam, istirahat dokter',
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'aksi' => 'Absensi Pengganti Sekretaris',
        ]);
    }

    public function test_sekretaris_tidak_bisa_mengganti_jika_guru_sudah_scan_mandiri(): void
    {
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        AbsensiGuru::create([
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
            'jam_masuk' => '06:55:00',
            'status' => 'Hadir',
            'latitude' => -6.9458,
            'longitude' => 107.6765,
            'jarak_meter' => 15,
            'metode' => 'Scan',
        ]);

        $response = $this->actingAs($this->sekretaris)->post('/sekretaris/absensi-pengganti', [
            'id_guru' => $this->guru->id,
            'status' => 'Izin',
            'alasan' => 'Keperluan keluarga mendadak',
        ]);

        $response->assertRedirect('/sekretaris?tab=pengganti');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'Hadir',
            'metode' => 'Scan',
        ]);
    }

    public function test_sekretaris_dapat_memperbarui_absensi_pengganti_sebelumnya(): void
    {
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        AbsensiGuru::create([
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'Izin',
            'metode' => 'Sekretaris',
            'alasan' => 'Izin mengurus berkas dinas',
            'id_user_input' => $this->sekretaris->id,
        ]);

        $response = $this->actingAs($this->sekretaris)->post('/sekretaris/absensi-pengganti', [
            'id_guru' => $this->guru->id,
            'status' => 'Sakit',
            'alasan' => 'Kondisi kesehatan memburuk',
        ]);

        $response->assertRedirect('/sekretaris?tab=pengganti');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggalHariIni,
            'status' => 'Sakit',
            'alasan' => 'Kondisi kesehatan memburuk',
        ]);
    }
}

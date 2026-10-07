<?php

namespace Tests\Feature;

use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\PengajuanIzinGuru;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanIzinDanValidasiAbsensiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $userGuru;
    protected Guru $guru;
    protected User $userSekretaris;
    protected Kelas $kelas;
    protected Siswa $siswa;
    protected Mapel $mapel;
    protected Ruangan $ruangan;
    protected string $hariIni;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();

        $this->userGuru = User::where('role', 'guru')->first();
        $this->guru = Guru::where('user_id', $this->userGuru->id)->first() ?? Guru::first();

        $this->userSekretaris = User::where('role', 'sekretaris')->first();
        if (!$this->userSekretaris) {
            $this->userSekretaris = User::create([
                'name' => 'Sekretaris Test Izin',
                'username' => 'sekre.test.izin',
                'password' => bcrypt('password'),
                'role' => 'sekretaris',
            ]);
        }

        $this->kelas = Kelas::first() ?? Kelas::create(['nama' => 'XII RPL 1', 'tingkat' => 12]);
        $this->mapel = Mapel::first() ?? Mapel::create(['nama' => 'Pemrograman Web', 'kode' => 'PW']);
        $this->ruangan = Ruangan::first() ?? Ruangan::create(['nama' => 'Lab RPL 1']);

        $this->siswa = Siswa::firstOrCreate(
            ['nis' => '11223344'],
            [
                'nisn' => '0011223399',
                'nama' => 'Siswa Test Sekretaris',
                'id_kelas' => $this->kelas->id,
                'jenis_kelamin' => 'L',
            ]
        );

        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $this->hariIni = $hariMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';
    }

    public function test_guru_ditolak_jika_mengisi_absensi_di_luar_jam_kbm(): void
    {
        $jadwal = Jadwal::create([
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_guru' => $this->guru->id,
            'id_ruangan' => $this->ruangan->id,
            'hari' => $this->hariIni,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 2,
        ]);

        // Simulasikan jam 05:00 subuh (sebelum jam mulai 07:00)
        Carbon::setTestNow(Carbon::parse('today 05:00:00', 'Asia/Jakarta'));

        $response = $this->actingAs($this->userGuru)->get('/guru/absensi-siswa/' . $jadwal->id);
        $response->assertRedirect('/guru?tab=jadwal');
        $response->assertSessionHas('error');

        Carbon::setTestNow();
    }

    public function test_guru_dapat_mengajukan_izin_terlambat_dengan_jam_estimasi(): void
    {
        $response = $this->actingAs($this->userGuru)->post('/guru/izin', [
            'jenis_izin' => 'Terlambat',
            'tanggal_mulai' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            'jam_estimasi' => '08:30',
            'alasan' => 'Kendala ban bocor di perjalanan ke sekolah',
        ]);

        $response->assertRedirect('/guru/izin');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengajuan_izin_guru', [
            'id_guru' => $this->guru->id,
            'jenis_izin' => 'Terlambat',
            'jam_estimasi' => '08:30',
            'status' => 'Menunggu',
        ]);
    }

    public function test_admin_dapat_menyetujui_izin_dan_otomatis_tercatat_ke_absensi_guru(): void
    {
        $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $pengajuan = PengajuanIzinGuru::create([
            'id_guru' => $this->guru->id,
            'jenis_izin' => 'Sakit',
            'tanggal_mulai' => $tanggal,
            'tanggal_selesai' => $tanggal,
            'alasan' => 'Demam dan flu berat',
            'status' => 'Menunggu',
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/izin/{$pengajuan->id}/setujui");
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengajuan_izin_guru', [
            'id' => $pengajuan->id,
            'status' => 'Disetujui',
        ]);

        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggal,
            'status' => 'Sakit',
            'metode' => 'Pengajuan Izin',
        ]);
    }

    public function test_admin_dapat_mencatat_kehadiran_guru_manual_sebagai_alpa(): void
    {
        $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $response = $this->actingAs($this->admin)->post('/admin/izin/catat-manual', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggal,
            'status' => 'Alpa',
            'alasan' => 'Tidak hadir tanpa keterangan',
        ]);

        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('absensi_guru', [
            'id_guru' => $this->guru->id,
            'tanggal' => $tanggal,
            'status' => 'Alpa',
            'metode' => 'Admin Manual',
        ]);
    }

    public function test_sekretaris_mengisi_absensi_berstatus_menunggu_validasi_lalu_disetujui_guru(): void
    {
        $jadwal = Jadwal::create([
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_guru' => $this->guru->id,
            'id_ruangan' => $this->ruangan->id,
            'hari' => $this->hariIni,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Sekretaris mengisi absensi siswa
        $responseSekre = $this->actingAs($this->userSekretaris)->post('/sekretaris/absensi-siswa/' . $jadwal->id, [
            'status' => [
                $this->siswa->id => 'Hadir',
            ],
            'keterangan' => [
                $this->siswa->id => 'Hadir tepat waktu',
            ],
        ]);

        $responseSekre->assertSessionHas('sukses');

        $this->assertDatabaseHas('absensi_siswa', [
            'id_jadwal' => $jadwal->id,
            'id_siswa' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status_validasi' => 'menunggu_validasi',
            'diisi_oleh' => 'sekretaris',
        ]);

        // Guru menyetujui validasi absensi
        $responseGuru = $this->actingAs($this->userGuru)->post('/guru/validasi/setujui', [
            'id_jadwal' => $jadwal->id,
            'tanggal' => $tanggal,
        ]);

        $responseGuru->assertSessionHas('sukses');

        $this->assertDatabaseHas('absensi_siswa', [
            'id_jadwal' => $jadwal->id,
            'id_siswa' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status_validasi' => 'disetujui',
        ]);
    }

    public function test_sekretaris_dilarang_mengisi_ulang_jika_absensi_sudah_dicatat_guru(): void
    {
        $jadwal = Jadwal::create([
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_guru' => $this->guru->id,
            'id_ruangan' => $this->ruangan->id,
            'hari' => $this->hariIni,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        $tanggal = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Absensi sudah dicatat oleh guru
        AbsensiSiswa::create([
            'id_jadwal' => $jadwal->id,
            'id_siswa' => $this->siswa->id,
            'tanggal' => $tanggal,
            'status' => 'Hadir',
            'id_guru_pengisi' => $this->guru->id,
            'status_validasi' => 'disetujui',
            'diisi_oleh' => 'guru',
        ]);

        // Sekretaris mencoba mengakses form absensi
        $response = $this->actingAs($this->userSekretaris)->get('/sekretaris/absensi-siswa/' . $jadwal->id);
        $response->assertRedirect('/sekretaris');
        $response->assertSessionHas('error');
    }
}

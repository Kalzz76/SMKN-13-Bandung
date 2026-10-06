<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DataMasterDanJadwalTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_halaman_data_master_dan_jadwal_bisa_diakses_admin(): void
    {
        $urls = [
            '/admin/ruangan',
            '/admin/mapel',
            '/admin/guru',
            '/admin/kelas',
            '/admin/siswa',
            '/admin/user',
            '/admin/jadwal',
        ];

        foreach ($urls as $url) {
            $response = $this->actingAs($this->admin)->get($url);
            $response->assertStatus(200);
        }
    }

    public function test_crud_ruangan_dan_proteksi_hapus(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/ruangan', [
            'kode' => 'LAB-IOT',
            'nama' => 'Laboratorium IoT dan Robotika',
        ]);
        $responseTambah->assertRedirect('/admin/ruangan');
        $this->assertDatabaseHas('ruangan', [
            'kode' => 'LAB-IOT',
        ]);

        $ruangan = Ruangan::where('kode', 'LAB-IOT')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/ruangan/' . $ruangan->id, [
            'kode' => 'LAB-IOT-1',
            'nama' => 'Laboratorium IoT dan Robotika Modern',
        ]);
        $responseEdit->assertRedirect('/admin/ruangan');
        $this->assertDatabaseHas('ruangan', [
            'id' => $ruangan->id,
            'kode' => 'LAB-IOT-1',
        ]);

        $ruanganTerpakai = Ruangan::where('kode', 'LAB-RPL')->first();
        $responseHapusTerpakai = $this->actingAs($this->admin)->delete('/admin/ruangan/' . $ruanganTerpakai->id);
        $responseHapusTerpakai->assertRedirect('/admin/ruangan');
        $responseHapusTerpakai->assertSessionHas('error');
        $this->assertDatabaseHas('ruangan', ['id' => $ruanganTerpakai->id]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/ruangan/' . $ruangan->id);
        $responseHapus->assertRedirect('/admin/ruangan');
        $this->assertDatabaseMissing('ruangan', ['id' => $ruangan->id]);
    }

    public function test_crud_mapel_dan_proteksi_hapus(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/mapel', [
            'kode' => 'PWPB',
            'nama' => 'Pemrograman Web dan Perangkat Bergerak',
            'jenis' => 'Produktif',
        ]);
        $responseTambah->assertRedirect('/admin/mapel');
        $this->assertDatabaseHas('mapel', [
            'kode' => 'PWPB',
        ]);

        $mapel = Mapel::where('kode', 'PWPB')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/mapel/' . $mapel->id, [
            'kode' => 'PWPB-1',
            'nama' => 'Pemrograman Web & Mobile Apps',
            'jenis' => 'Produktif',
        ]);
        $responseEdit->assertRedirect('/admin/mapel');
        $this->assertDatabaseHas('mapel', [
            'id' => $mapel->id,
            'kode' => 'PWPB-1',
        ]);

        $mapelTerpakai = Mapel::where('kode', 'DPK')->first();
        $responseHapusTerpakai = $this->actingAs($this->admin)->delete('/admin/mapel/' . $mapelTerpakai->id);
        $responseHapusTerpakai->assertRedirect('/admin/mapel');
        $responseHapusTerpakai->assertSessionHas('error');
        $this->assertDatabaseHas('mapel', ['id' => $mapelTerpakai->id]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/mapel/' . $mapel->id);
        $responseHapus->assertRedirect('/admin/mapel');
        $this->assertDatabaseMissing('mapel', ['id' => $mapel->id]);
    }

    public function test_crud_guru_dan_proteksi_hapus(): void
    {
        Storage::fake('public');
        $foto = UploadedFile::fake()->image('guru.jpg');

        $responseNipHuruf = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama' => 'Guru Penguji Gagal',
            'nip' => 'NIP12345ABC',
            'jenis' => 'Guru',
        ]);
        $responseNipHuruf->assertSessionHasErrors('nip');

        $responseTambah = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama' => 'Guru Penguji, S.T.',
            'nip' => '199505052020051005',
            'jenis' => 'Guru',
            'jabatan' => 'Guru Pengajar',
            'mapel_utama' => 'Basis Data',
            'foto' => $foto,
            'tampil_publik' => 1,
        ]);
        $responseTambah->assertRedirect('/admin/guru');
        $this->assertDatabaseHas('guru', [
            'nama' => 'Guru Penguji, S.T.',
        ]);

        $guru = Guru::where('nama', 'Guru Penguji, S.T.')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/guru/' . $guru->id, [
            'nama' => 'Guru Penguji Utama, M.T.',
            'nip' => '199505052020051005',
            'jenis' => 'Guru',
            'jabatan' => 'Koordinator Praktik',
            'mapel_utama' => 'Basis Data & SQL',
            'tampil_publik' => 1,
        ]);
        $responseEdit->assertRedirect('/admin/guru');
        $this->assertDatabaseHas('guru', [
            'id' => $guru->id,
            'nama' => 'Guru Penguji Utama, M.T.',
        ]);

        $guruTerpakai = Guru::where('nama', 'Refky, M.Kom.')->first();
        $responseHapusTerpakai = $this->actingAs($this->admin)->delete('/admin/guru/' . $guruTerpakai->id);
        $responseHapusTerpakai->assertRedirect('/admin/guru');
        $responseHapusTerpakai->assertSessionHas('error');
        $this->assertDatabaseHas('guru', ['id' => $guruTerpakai->id]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/guru/' . $guru->id);
        $responseHapus->assertRedirect('/admin/guru');
        $this->assertDatabaseMissing('guru', ['id' => $guru->id]);
    }

    public function test_crud_kelas_dan_proteksi_hapus(): void
    {
        $ruang = Ruangan::first();
        $guru = Guru::first();

        $responseTambah = $this->actingAs($this->admin)->post('/admin/kelas', [
            'nama' => 'XI RPL 2',
            'id_ruangan' => $ruang->id,
            'id_wali_kelas' => $guru->id,
        ]);
        $responseTambah->assertRedirect('/admin/kelas');
        $this->assertDatabaseHas('kelas', ['nama' => 'XI RPL 2']);

        $kelas = Kelas::where('nama', 'XI RPL 2')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/kelas/' . $kelas->id, [
            'nama' => 'XI RPL 2 Unggulan',
            'id_ruangan' => $ruang->id,
            'id_wali_kelas' => $guru->id,
        ]);
        $responseEdit->assertRedirect('/admin/kelas');
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id, 'nama' => 'XI RPL 2 Unggulan']);

        $responseShow = $this->actingAs($this->admin)->get('/admin/kelas/' . $kelas->id);
        $responseShow->assertStatus(200);
        $responseShow->assertSee('XI RPL 2 Unggulan');

        $responseStruktur = $this->actingAs($this->admin)->put('/admin/kelas/' . $kelas->id . '/struktur', [
            'km' => 'Siswa Ketua',
            'wakil_km' => 'Siswa Wakil',
            'bendahara_1' => 'Siswa Bendahara 1',
        ]);
        $responseStruktur->assertRedirect('/admin/kelas/' . $kelas->id);
        $this->assertDatabaseHas('kelas', ['id' => $kelas->id]);

        $kelasTerpakai = Kelas::where('nama', 'XII RPL 1')->first();
        $responseHapusTerpakai = $this->actingAs($this->admin)->delete('/admin/kelas/' . $kelasTerpakai->id);
        $responseHapusTerpakai->assertRedirect('/admin/kelas');
        $responseHapusTerpakai->assertSessionHas('error');
        $this->assertDatabaseHas('kelas', ['id' => $kelasTerpakai->id]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/kelas/' . $kelas->id);
        $responseHapus->assertRedirect('/admin/kelas');
        $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
    }

    public function test_crud_siswa(): void
    {
        $kelas = Kelas::first();

        $responseNisHuruf = $this->actingAs($this->admin)->post('/admin/siswa', [
            'nis' => 'NIS100ABC',
            'nisn' => '0081234567',
            'nama' => 'Siswa Gagal',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
        ]);
        $responseNisHuruf->assertSessionHasErrors('nis');

        $responseNisnHuruf = $this->actingAs($this->admin)->post('/admin/siswa', [
            'nis' => '100998',
            'nisn' => 'NISN123ABC',
            'nama' => 'Siswa Gagal NISN',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
        ]);
        $responseNisnHuruf->assertSessionHasErrors('nisn');

        $responseTambah = $this->actingAs($this->admin)->post('/admin/siswa', [
            'nis' => '100999',
            'nisn' => '0081234567',
            'nama' => 'Siswa Percobaan',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
        ]);
        $responseTambah->assertRedirect('/admin/siswa');
        $this->assertDatabaseHas('siswa', ['nis' => '100999', 'nisn' => '0081234567']);

        $siswa = Siswa::where('nis', '100999')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/siswa/' . $siswa->id, [
            'nis' => '100999',
            'nisn' => '0089876543',
            'nama' => 'Siswa Percobaan Update',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
        ]);
        $responseEdit->assertRedirect('/admin/siswa');
        $this->assertDatabaseHas('siswa', ['id' => $siswa->id, 'nama' => 'Siswa Percobaan Update', 'nisn' => '0089876543', 'jenis_kelamin' => 'P']);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/siswa/' . $siswa->id);
        $responseHapus->assertRedirect('/admin/siswa');
        $this->assertDatabaseMissing('siswa', ['id' => $siswa->id]);
    }

    public function test_crud_user_dan_proteksi_hapus_akun_sendiri(): void
    {
        $responseTambah = $this->actingAs($this->admin)->post('/admin/user', [
            'name' => 'Akun Penguji',
            'username' => 'penguji',
            'role' => 'guru',
            'status' => 'Aktif',
            'password' => 'secret123',
        ]);
        $responseTambah->assertRedirect('/admin/user');
        $this->assertDatabaseHas('users', ['username' => 'penguji']);

        $user = User::where('username', 'penguji')->first();

        $responseEdit = $this->actingAs($this->admin)->put('/admin/user/' . $user->id, [
            'name' => 'Akun Penguji Edit',
            'username' => 'pengujiedit',
            'role' => 'guru',
            'status' => 'Nonaktif',
        ]);
        $responseEdit->assertRedirect('/admin/user');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'username' => 'pengujiedit', 'status' => 'Nonaktif']);

        $responseHapusSendiri = $this->actingAs($this->admin)->delete('/admin/user/' . $this->admin->id);
        $responseHapusSendiri->assertRedirect('/admin/user');
        $responseHapusSendiri->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        $responseHapus = $this->actingAs($this->admin)->delete('/admin/user/' . $user->id);
        $responseHapus->assertRedirect('/admin/user');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_dapat_reset_password_user_ke_default(): void
    {
        $guruUser = User::create([
            'name' => 'Guru Testing Reset',
            'username' => 'gurutestreset',
            'role' => 'guru',
            'status' => 'Aktif',
            'password' => bcrypt('passwordlama123'),
        ]);

        $sekreUser = User::create([
            'name' => 'Sekre Testing Reset',
            'username' => 'sekretestreset',
            'role' => 'sekretaris',
            'status' => 'Aktif',
            'password' => bcrypt('passwordlama123'),
        ]);

        $responseResetGuru = $this->actingAs($this->admin)->post("/admin/user/{$guruUser->id}/reset-password");
        $responseResetGuru->assertRedirect('/admin/user');
        $responseResetGuru->assertSessionHas('sukses');

        $guruUser->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('guru123', $guruUser->password));

        $responseResetSekre = $this->actingAs($this->admin)->post("/admin/user/{$sekreUser->id}/reset-password");
        $responseResetSekre->assertRedirect('/admin/user');
        $responseResetSekre->assertSessionHas('sukses');

        $sekreUser->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('sekretaris123', $sekreUser->password));
    }

    public function test_crud_jadwal_dan_validasi_bentrok(): void
    {
        $kelas1 = Kelas::where('nama', 'XII RPL 1')->first();
        $kelas2 = Kelas::where('nama', 'X KA 1')->first();
        $mapel = Mapel::first();
        $guru = Guru::where('nama', 'Refky, M.Kom.')->first();
        $ruang = Ruangan::where('kode', 'LAB-RPL')->first();

        $responseSelesaiKecil = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas1->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $guru->id,
            'id_ruangan' => $ruang->id,
            'jam_ke_mulai' => 3,
            'jam_ke_selesai' => 1,
        ]);
        $responseSelesaiKecil->assertSessionHas('error');

        $responseLewatIstirahat = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas1->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $guru->id,
            'id_ruangan' => $ruang->id,
            'jam_ke_mulai' => 3,
            'jam_ke_selesai' => 5,
        ]);
        $responseLewatIstirahat->assertSessionHas('error');

        $responseValid = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas1->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $guru->id,
            'id_ruangan' => $ruang->id,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);
        $responseValid->assertRedirect('/admin/jadwal?hari=Selasa');
        $this->assertDatabaseHas('jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas1->id,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 3,
        ]);

        $responseBentrokGuru = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas2->id,
            'id_mapel' => $mapel->id,
            'id_guru' => $guru->id,
            'id_ruangan' => Ruangan::where('kode', 'R.52')->first()->id,
            'jam_ke_mulai' => 2,
            'jam_ke_selesai' => 3,
        ]);
        $responseBentrokGuru->assertSessionHas('error');

        $responseBentrokKelas = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Selasa',
            'id_kelas' => $kelas1->id,
            'id_mapel' => Mapel::where('id', '!=', $mapel->id)->first()->id,
            'id_guru' => Guru::where('id', '!=', $guru->id)->first()->id,
            'id_ruangan' => Ruangan::where('kode', 'R.52')->first()->id,
            'jam_ke_mulai' => 2,
            'jam_ke_selesai' => 3,
        ]);
        $responseBentrokKelas->assertSessionHas('error');

        $jadwalBaru = Jadwal::where('hari', 'Selasa')->where('id_kelas', $kelas1->id)->first();
        $responseHapus = $this->actingAs($this->admin)->delete('/admin/jadwal/' . $jadwalBaru->id);
        $responseHapus->assertRedirect('/admin/jadwal?hari=Selasa');
        $this->assertDatabaseMissing('jadwal', ['id' => $jadwalBaru->id]);
    }
}

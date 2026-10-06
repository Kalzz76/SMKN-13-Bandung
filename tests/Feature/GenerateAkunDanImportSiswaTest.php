<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GenerateAkunDanImportSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_generate_akun_guru_berhasil(): void
    {
        $guru = Guru::create([
            'nip' => '198501152010011005',
            'nama' => 'Drs. Hendra Gunawan, M.Pd.',
            'jenis_kelamin' => 'L',
            'mata_pelajaran' => 'Matematika',
            'status' => 'Aktif',
            'user_id' => null,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/user/generate-guru');
        $response->assertRedirect('/admin/user');
        $response->assertSessionHas('sukses');

        $guru->refresh();
        $this->assertNotNull($guru->user_id);

        $user = User::find($guru->user_id);
        $this->assertNotNull($user);
        $this->assertEquals('hendra1985', $user->username);
        $this->assertEquals('guru', $user->role);
        $this->assertEquals('Aktif', $user->status);
        $this->assertTrue(Hash::check('guru123', $user->password));

        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'username' => 'hendra1985',
            'password' => 'guru123',
        ]);
        $loginResponse->assertRedirect('/guru');
        $this->assertAuthenticatedAs($user);
    }

    public function test_generate_akun_siswa_sekretaris_berhasil(): void
    {
        $kelas = Kelas::first();

        $sekretaris = Siswa::create([
            'nis' => '242510999',
            'nisn' => '0087654321',
            'nama' => 'Syifa Nurhaliza',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Sekretaris',
            'user_id' => null,
        ]);

        $anggota = Siswa::create([
            'nis' => '242510998',
            'nisn' => '0087654322',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Anggota',
            'user_id' => null,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/user/generate-siswa');
        $response->assertRedirect('/admin/user');
        $response->assertSessionHas('sukses');

        $sekretaris->refresh();
        $anggota->refresh();

        $this->assertNotNull($sekretaris->user_id);
        $this->assertNull($anggota->user_id);

        $userSekre = User::find($sekretaris->user_id);
        $this->assertNotNull($userSekre);
        $this->assertEquals('syifa21', $userSekre->username);
        $this->assertEquals('sekretaris', $userSekre->role);
        $this->assertEquals('Aktif', $userSekre->status);
        $this->assertTrue(Hash::check('sekretaris123', $userSekre->password));

        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'username' => 'syifa21',
            'password' => 'sekretaris123',
        ]);
        $loginResponse->assertRedirect('/sekretaris');
        $this->assertAuthenticatedAs($userSekre);
    }

    public function test_download_template_siswa_berhasil(): void
    {
        $responseXlsx = $this->actingAs($this->admin)->get('/admin/siswa/template');
        $responseXlsx->assertStatus(200);
        $responseXlsx->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $responseCsv = $this->actingAs($this->admin)->get('/admin/siswa/template?format=csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_import_data_siswa_csv_berhasil(): void
    {
        $kelas = Kelas::first();
        $namaKelas = $kelas->nama;

        $csvContent = "NIS,NISN,Nama Lengkap,Jenis Kelamin,Kelas,Tahun Ajaran\n"
                    . "990111,0099901111,Siswa Baru Satu,L,{$namaKelas},2026/2027\n"
                    . "990112,0099901112,Siswa Baru Dua,P,{$namaKelas},2026/2027\n";

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csvContent);

        $response = $this->actingAs($this->admin)->post('/admin/siswa/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/admin/siswa');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('siswa', [
            'nis' => '990111',
            'nama' => 'Siswa Baru Satu',
            'jabatan' => 'Anggota',
        ]);

        $this->assertDatabaseHas('siswa', [
            'nis' => '990112',
            'nama' => 'Siswa Baru Dua',
            'jabatan' => 'Anggota',
        ]);
    }

    public function test_import_data_siswa_format_simple_xlsx_berhasil(): void
    {
        $realPath = base_path('Data_Siswa_X_RPL_1_Simple.xlsx');
        if (!file_exists($realPath)) {
            $this->markTestSkipped('File Data_Siswa_X_RPL_1_Simple.xlsx tidak ditemukan');
        }

        $file = new UploadedFile(
            $realPath,
            'Data_Siswa_X_RPL_1_Simple.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs($this->admin)->post('/admin/siswa/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/admin/siswa');
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('kelas', [
            'nama' => 'X RPL 1',
        ]);

        $this->assertDatabaseHas('siswa', [
            'nis' => '102419349',
            'nisn' => '0089761823',
            'nama' => 'ADELYA FAUZI ALFIAN',
            'jenis_kelamin' => 'P',
        ]);

        $this->assertDatabaseHas('siswa', [
            'nis' => '102419384',
            'nisn' => '0091042703',
            'nama' => 'YUGA PUTRA NUGRAHA',
            'jenis_kelamin' => 'L',
        ]);

        $xRpl1 = Kelas::where('nama', 'X RPL 1')->first();
        $this->assertNotNull($xRpl1);
        $this->assertEquals(36, Siswa::where('id_kelas', $xRpl1->id)->count());
    }

    public function test_bulk_delete_per_kelas_berhasil(): void
    {
        $kelas = Kelas::first();
        Siswa::create([
            'nis' => '881001',
            'nisn' => '0088100101',
            'nama' => 'Test Siswa Delete 1',
            'jenis_kelamin' => 'L',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Anggota',
        ]);
        Siswa::create([
            'nis' => '881002',
            'nisn' => '0088100102',
            'nama' => 'Test Siswa Delete 2',
            'jenis_kelamin' => 'P',
            'id_kelas' => $kelas->id,
            'tahun_ajaran' => '2026/2027',
            'jabatan' => 'Anggota',
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/siswa/bulk-delete', [
            'scope' => 'per_kelas',
            'id_kelas' => $kelas->id,
            'konfirmasi' => '1',
            'hapus_absensi' => '1',
        ]);

        $response->assertRedirect('/admin/siswa');
        $response->assertSessionHas('sukses');
        $this->assertEquals(0, Siswa::where('id_kelas', $kelas->id)->count());
    }

    public function test_bulk_delete_semua_kelas_berhasil(): void
    {
        $response = $this->actingAs($this->admin)->delete('/admin/siswa/bulk-delete', [
            'scope' => 'semua',
            'konfirmasi' => '1',
            'hapus_absensi' => '1',
        ]);

        $response->assertRedirect('/admin/siswa');
        $response->assertSessionHas('sukses');
        $this->assertEquals(0, Siswa::count());
    }
}

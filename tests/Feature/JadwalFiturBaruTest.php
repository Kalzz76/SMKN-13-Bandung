<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class JadwalFiturBaruTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Kelas $kelas;
    private Mapel $mapel;
    private Ruangan $ruangan;
    private Guru $guru;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('role', 'admin')->first();

        $this->kelas = Kelas::first() ?? Kelas::create(['nama' => 'X RPL 1', 'tingkat' => 'X', 'kelompok_pembiasaan' => 'B']);
        $this->mapel = Mapel::first() ?? Mapel::create(['nama' => 'Matematika', 'kode' => 'MAT']);
        $this->ruangan = Ruangan::first() ?? Ruangan::create(['nama' => 'Ruang 01', 'kode' => 'R.01']);
        $this->guru = Guru::first() ?? Guru::create(['nama' => 'Budi Santoso', 'nip' => '198001012010011001', 'jenis' => 'Guru']);
    }

    public function test_download_template_jadwal_berhasil(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/jadwal/template');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_tambah_jadwal_kegiatan_berhasil(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/jadwal', [
            'hari' => 'Senin',
            'id_kelas' => $this->kelas->id,
            'is_kegiatan' => '1',
            'nama_kegiatan' => 'Literasi Pagi',
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 1,
        ]);

        $response->assertRedirect('/admin/jadwal?hari=Senin');
        $this->assertDatabaseHas('jadwal', [
            'id_kelas' => $this->kelas->id,
            'hari' => 'Senin',
            'is_kegiatan' => 1,
            'nama_kegiatan' => 'Literasi Pagi',
        ]);
    }

    public function test_import_jadwal_excel_berhasil(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hari');
        $sheet->setCellValue('B1', 'Slot');
        $sheet->setCellValue('C1', 'Kelas');
        $sheet->setCellValue('D1', 'Mapel');
        $sheet->setCellValue('E1', 'Ruangan');
        $sheet->setCellValue('F1', 'Guru');

        $sheet->setCellValue('A2', 'Selasa');
        $sheet->setCellValue('B2', 'Jam 1');
        $sheet->setCellValue('C2', $this->kelas->nama);
        $sheet->setCellValue('D2', $this->mapel->nama);
        $sheet->setCellValue('E2', $this->ruangan->nama);
        $sheet->setCellValue('F2', $this->guru->nama);

        $tempFile = tempnam(sys_get_temp_dir(), 'jadwal_test_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $uploadedFile = new UploadedFile($tempFile, 'jadwal.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($this->admin)->post('/admin/jadwal/import', [
            'file' => $uploadedFile,
        ]);

        $response->assertRedirect('/admin/jadwal');
        $this->assertDatabaseHas('jadwal', [
            'id_kelas' => $this->kelas->id,
            'hari' => 'Selasa',
            'id_mapel' => $this->mapel->id,
            'jam_ke_mulai' => 1,
        ]);

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

    public function test_fix_teacher_ids_berhasil(): void
    {
        $this->guru->mapels()->syncWithoutDetaching([$this->mapel->id]);

        $jadwalTanpaGuru = Jadwal::create([
            'hari' => 'Rabu',
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_ruangan' => $this->ruangan->id,
            'id_guru' => null,
            'jam_ke_mulai' => 1,
            'jam_ke_selesai' => 2,
            'is_kegiatan' => false,
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/jadwal/fix-teacher-ids');
        $response->assertRedirect('/admin/jadwal');

        $this->assertDatabaseHas('jadwal', [
            'id' => $jadwalTanpaGuru->id,
            'id_guru' => $this->guru->id,
        ]);
    }

    public function test_tabel_rincian_menggabungkan_jadwal_sama_dan_berurutan(): void
    {
        $j1 = Jadwal::create([
            'hari' => 'Rabu',
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_ruangan' => $this->ruangan->id,
            'id_guru' => $this->guru->id,
            'jam_ke_mulai' => 3,
            'jam_ke_selesai' => 3,
            'is_kegiatan' => false,
        ]);

        $j2 = Jadwal::create([
            'hari' => 'Rabu',
            'id_kelas' => $this->kelas->id,
            'id_mapel' => $this->mapel->id,
            'id_ruangan' => $this->ruangan->id,
            'id_guru' => $this->guru->id,
            'jam_ke_mulai' => 4,
            'jam_ke_selesai' => 5,
            'is_kegiatan' => false,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/jadwal?hari=Rabu');
        $response->assertOk();
        $response->assertSee('Jam 3 - 5');

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/jadwal/{$j1->id},{$j2->id}");
        $deleteResponse->assertRedirect(route('admin.jadwal.index', ['hari' => 'Rabu']));

        $this->assertDatabaseMissing('jadwal', ['id' => $j1->id]);
        $this->assertDatabaseMissing('jadwal', ['id' => $j2->id]);
    }
}


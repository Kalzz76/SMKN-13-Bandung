<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruMultipleMapelTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_guru_bisa_memiliki_multiple_mapel_saat_dibuat(): void
    {
        $mapel1 = Mapel::create(['kode' => 'MP-1', 'nama' => 'Mapel Pengujian Satu', 'jenis' => 'Umum']);
        $mapel2 = Mapel::create(['kode' => 'MP-2', 'nama' => 'Mapel Pengujian Dua', 'jenis' => 'Kejuruan']);

        $response = $this->actingAs($this->admin)->post('/admin/guru', [
            'nama' => 'Guru Multi Mapel, S.Pd.',
            'nip' => '198501012010011001',
            'jenis' => 'Guru',
            'mapel_ids' => [$mapel1->id, $mapel2->id],
            'tampil_publik' => 1,
        ]);

        $response->assertRedirect('/admin/guru');
        $this->assertDatabaseHas('guru', [
            'nama' => 'Guru Multi Mapel, S.Pd.',
            'nip' => '198501012010011001',
        ]);

        $guru = Guru::where('nip', '198501012010011001')->first();
        $this->assertNotNull($guru);
        $this->assertCount(2, $guru->mapels);
        $this->assertTrue($guru->mapels->contains($mapel1));
        $this->assertTrue($guru->mapels->contains($mapel2));
        $this->assertDatabaseHas('guru_mapel', ['guru_id' => $guru->id, 'mapel_id' => $mapel1->id]);
        $this->assertDatabaseHas('guru_mapel', ['guru_id' => $guru->id, 'mapel_id' => $mapel2->id]);
    }

    public function test_guru_bisa_diupdate_daftar_mapelnya(): void
    {
        $mapel1 = Mapel::create(['kode' => 'MP-A', 'nama' => 'Mapel A', 'jenis' => 'Umum']);
        $mapel2 = Mapel::create(['kode' => 'MP-B', 'nama' => 'Mapel B', 'jenis' => 'Umum']);
        $mapel3 = Mapel::create(['kode' => 'MP-C', 'nama' => 'Mapel C', 'jenis' => 'Kejuruan']);

        $guru = Guru::create([
            'nama' => 'Guru Update Mapel, M.Pd.',
            'nip' => '198702022012021002',
            'jenis' => 'Guru',
            'tampil_publik' => 1,
        ]);
        $guru->mapels()->sync([$mapel1->id]);

        $this->assertCount(1, $guru->fresh()->mapels);

        // Update jadi mapel2 dan mapel3
        $response = $this->actingAs($this->admin)->put('/admin/guru/' . $guru->id, [
            'nama' => 'Guru Update Mapel, M.Pd.',
            'nip' => '198702022012021002',
            'jenis' => 'Guru',
            'mapel_ids' => [$mapel2->id, $mapel3->id],
            'tampil_publik' => 1,
        ]);

        $response->assertRedirect('/admin/guru');
        $guruFresh = $guru->fresh();
        $this->assertCount(2, $guruFresh->mapels);
        $this->assertFalse($guruFresh->mapels->contains($mapel1));
        $this->assertTrue($guruFresh->mapels->contains($mapel2));
        $this->assertTrue($guruFresh->mapels->contains($mapel3));
    }

    public function test_pencarian_guru_mengembalikan_live_data_container(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/guru?cari=OMAN', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertSee('id="liveDataContainer"', false);
        $response->assertSee('OMAN SOMANA');
    }
}

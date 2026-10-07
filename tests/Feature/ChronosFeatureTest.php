<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\ChronosService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChronosFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guru;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->guru = User::where('role', 'guru')->first();
    }

    protected function tearDown(): void
    {
        ChronosService::disable();
        parent::tearDown();
    }

    public function test_admin_dapat_mengakses_halaman_chronos(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/chronos');
        $response->assertStatus(200);
        $response->assertSee('Chronos');
        $response->assertSee('Pengatur waktu virtual');
    }

    public function test_non_admin_tidak_dapat_mengakses_chronos(): void
    {
        $response = $this->actingAs($this->guru)->get('/admin/chronos');
        $response->assertStatus(403);
    }

    public function test_admin_dapat_mengaktifkan_dan_menonaktifkan_chronos(): void
    {
        $responseEnable = $this->actingAs($this->admin)->post('/admin/chronos/toggle', [
            'enabled' => '1',
        ]);
        $responseEnable->assertRedirect('/admin/chronos');
        $responseEnable->assertSessionHas('sukses');

        $this->assertTrue(ChronosService::status()['enabled']);

        $responseDisable = $this->actingAs($this->admin)->post('/admin/chronos/toggle', [
            'enabled' => '0',
        ]);
        $responseDisable->assertRedirect('/admin/chronos');
        $this->assertFalse(ChronosService::status()['enabled']);
    }

    public function test_admin_dapat_mengatur_waktu_virtual_chronos(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/chronos/update', [
            'date' => '2026-10-12',
            'hour' => 8,
            'minute' => 30,
            'second' => 0,
        ]);

        $response->assertRedirect('/admin/chronos');
        $status = ChronosService::status();
        $this->assertTrue($status['enabled']);
        $this->assertEquals('2026-10-12', $status['date']);
        $this->assertEquals(8, $status['hour']);
        $this->assertEquals(30, $status['minute']);
        $this->assertEquals('Senin', $status['day_name']);

        $testResponse = $this->actingAs($this->admin)->get('/admin');
        $testResponse->assertStatus(200);
        $this->assertEquals('2026-10-12', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        $this->assertEquals('08:30', Carbon::now('Asia/Jakarta')->format('H:i'));
    }

    public function test_admin_dapat_menerapkan_preset_cepat(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/chronos/preset', [
            'day' => 1,
            'hour' => 6,
            'minute' => 30,
            'label' => 'Senin Jam 1',
        ]);

        $response->assertRedirect('/admin/chronos');
        $status = ChronosService::status();
        $this->assertTrue($status['enabled']);
        $this->assertEquals(6, $status['hour']);
        $this->assertEquals(30, $status['minute']);
        $this->assertEquals('Senin', $status['day_name']);
    }

    public function test_admin_dapat_mereset_chronos_ke_waktu_nyata(): void
    {
        ChronosService::setTime('2026-10-12', 8, 0, 0);
        $this->assertTrue(ChronosService::status()['enabled']);

        $response = $this->actingAs($this->admin)->post('/admin/chronos/reset');
        $response->assertRedirect('/admin/chronos');

        $status = ChronosService::status();
        $this->assertFalse($status['enabled']);
        $this->assertFalse(Carbon::hasTestNow());
    }
}

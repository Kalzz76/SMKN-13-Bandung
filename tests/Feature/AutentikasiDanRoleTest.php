<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutentikasiDanRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_beranda_publik_dapat_diakses(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('SMK Negeri 13 Bandung');
    }

    public function test_tamu_tidak_dapat_mengakses_dashboard_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_login_admin_berhasil_dan_diarahkan_ke_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();

        $adminResponse = $this->get('/admin');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Dashboard Administrator');
    }

    public function test_login_guru_berhasil_dan_diarahkan_ke_portal_guru(): void
    {
        $response = $this->post('/login', [
            'username' => 'refky',
            'password' => 'guru123',
        ]);

        $response->assertRedirect('/guru');
        $this->assertAuthenticated();

        $guruResponse = $this->get('/guru');
        $guruResponse->assertStatus(200);
        $guruResponse->assertSee('Dashboard Guru');
    }

    public function test_login_sekretaris_berhasil_dan_diarahkan_ke_portal_sekretaris(): void
    {
        $response = $this->post('/login', [
            'username' => 'sekretaris',
            'password' => 'sekretaris123',
        ]);

        $response->assertRedirect('/sekretaris');
        $this->assertAuthenticated();

        $sekreResponse = $this->get('/sekretaris');
        $sekreResponse->assertStatus(200);
        $sekreResponse->assertSee('Dashboard Sekretaris');
    }

    public function test_guru_mengakses_admin_ditolak_403(): void
    {
        $guru = User::where('role', 'guru')->first();
        $response = $this->actingAs($guru)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_mengakses_guru_ditolak_403(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/guru');
        $response->assertStatus(403);
    }

    public function test_akun_nonaktif_ditolak_login(): void
    {
        $user = User::where('username', 'refky')->first();
        $user->update(['status' => 'Nonaktif']);

        $response = $this->post('/login', [
            'username' => 'refky',
            'password' => 'guru123',
        ]);

        $this->assertGuest();
        $response->assertSessionHas('error');
    }

    public function test_logout_berhasil_dan_kembali_ke_portal(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
        $response->assertSessionHas('sukses');
    }
}

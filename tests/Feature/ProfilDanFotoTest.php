<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfilDanFotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_dapat_mengakses_halaman_profil()
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get(route('admin.profil'));

        $response->assertStatus(200);
        $response->assertSee('Account Settings');
    }

    public function test_unggah_foto_profil_via_ajax_berhasil()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg', 200, 200);

        $response = $this->actingAs($user)->postJson(route('admin.profil.foto.update'), [
            'foto' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $user->refresh();
        $this->assertNotNull($user->foto);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_unggah_foto_profil_user_biasa_via_ajax_berhasil()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'guru',
        ]);

        $file = UploadedFile::fake()->image('guru_avatar.png', 150, 150);

        $response = $this->actingAs($user)->postJson(route('profil.foto.update'), [
            'foto' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $user->refresh();
        $this->assertNotNull($user->foto);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_update_profil_dengan_foto_via_form_berhasil()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'admin',
            'name' => 'Nama Lama',
        ]);

        $file = UploadedFile::fake()->image('profile_form.jpg', 300, 300);

        $response = $this->actingAs($user)->put(route('admin.profil.update'), [
            'name' => 'Nama Baru',
            'username' => $user->username,
            'alamat' => 'Bandung',
            'foto' => $file,
        ]);

        $response->assertRedirect(route('admin.profil'));

        $user->refresh();
        $this->assertEquals('Nama Baru', $user->name);
        $this->assertNotNull($user->foto);
        Storage::disk('public')->assertExists($user->foto);
    }

    public function test_validasi_foto_menolak_file_non_gambar()
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $file = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->postJson(route('admin.profil.foto.update'), [
            'foto' => $file,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['foto']);
    }
}

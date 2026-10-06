<?php

use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EkskulController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\ProfilSekolahController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\AbsensiGuruController;
use App\Http\Controllers\Guru\AbsensiSiswaController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfilController as UserProfilController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\Sekretaris\DashboardController as SekretarisDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublikController::class, 'beranda'])->name('beranda');
Route::get('/profil', [PublikController::class, 'profil'])->name('publik.profil');
Route::get('/jurusan', [PublikController::class, 'jurusan'])->name('publik.jurusan');
Route::get('/berita', [PublikController::class, 'berita'])->name('publik.berita');
Route::get('/berita/{id}', [PublikController::class, 'detailBerita'])->name('publik.detail-berita');
Route::get('/galeri', [PublikController::class, 'galeri'])->name('publik.galeri');
Route::get('/kontak', [PublikController::class, 'kontak'])->name('publik.kontak');
Route::get('/ekstrakurikuler', [PublikController::class, 'ekstrakurikuler'])->name('publik.ekstrakurikuler');
Route::get('/prestasi', [PublikController::class, 'prestasi'])->name('publik.prestasi');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::get('/api/check-username', [LoginController::class, 'checkUsername'])->name('api.check-username');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/akun/profil', [UserProfilController::class, 'index'])->name('profil.index');
    Route::put('/akun/profil', [UserProfilController::class, 'update'])->name('profil.update');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
    Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');

    Route::get('/mapel', [MapelController::class, 'index'])->name('mapel.index');
    Route::post('/mapel', [MapelController::class, 'store'])->name('mapel.store');
    Route::put('/mapel/{id}', [MapelController::class, 'update'])->name('mapel.update');
    Route::delete('/mapel/{id}', [MapelController::class, 'destroy'])->name('mapel.destroy');

    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
    Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
    Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');

    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
    Route::get('/kelas/{id}', [KelasController::class, 'show'])->name('kelas.show');
    Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
    Route::put('/kelas/{id}/struktur', [KelasController::class, 'updateStruktur'])->name('kelas.update-struktur');
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/template', [SiswaController::class, 'template'])->name('siswa.template');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::delete('/siswa/bulk-delete', [SiswaController::class, 'bulkDelete'])->name('siswa.bulk-delete');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/generate-guru', [UserController::class, 'generateGuru'])->name('user.generate-guru');
    Route::post('/user/generate-siswa', [UserController::class, 'generateSiswa'])->name('user.generate-siswa');
    Route::post('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/jadwal/jam/{hari}', [JadwalController::class, 'jamHari'])->name('jadwal.jam');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/profil-sambutan', [ProfilSekolahController::class, 'index'])->name('profil-sambutan.index');
    Route::put('/profil-sambutan', [ProfilSekolahController::class, 'update'])->name('profil-sambutan.update');

    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::post('/berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::put('/galeri/{id}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
    Route::post('/galeri/{id}/tambah-foto', [GaleriController::class, 'tambahFoto'])->name('galeri.tambah-foto');
    Route::delete('/galeri/foto/{id}', [GaleriController::class, 'destroyFoto'])->name('galeri.destroy-foto');

    Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan.index');
    Route::post('/jurusan', [JurusanController::class, 'store'])->name('jurusan.store');
    Route::put('/jurusan/{id}', [JurusanController::class, 'update'])->name('jurusan.update');
    Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy'])->name('jurusan.destroy');

    Route::get('/ekskul', [EkskulController::class, 'index'])->name('ekskul.index');
    Route::post('/ekskul', [EkskulController::class, 'store'])->name('ekskul.store');
    Route::put('/ekskul/{id}', [EkskulController::class, 'update'])->name('ekskul.update');
    Route::delete('/ekskul/{id}', [EkskulController::class, 'destroy'])->name('ekskul.destroy');

    Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');
    Route::post('/prestasi', [PrestasiController::class, 'store'])->name('prestasi.store');
    Route::put('/prestasi/{id}', [PrestasiController::class, 'update'])->name('prestasi.update');
    Route::delete('/prestasi/{id}', [PrestasiController::class, 'destroy'])->name('prestasi.destroy');

    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::post('/barcode/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
    Route::post('/barcode/generate-semua', [BarcodeController::class, 'generateSemua'])->name('barcode.generate-semua');
    Route::get('/barcode/cetak', [BarcodeController::class, 'cetak'])->name('barcode.cetak');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/guru/csv', [LaporanController::class, 'exportGuruCsv'])->name('laporan.guru.csv');
    Route::get('/laporan/guru/cetak', [LaporanController::class, 'cetakGuru'])->name('laporan.guru.cetak');
    Route::get('/laporan/siswa/csv', [LaporanController::class, 'exportSiswaCsv'])->name('laporan.siswa.csv');
    Route::get('/laporan/siswa/cetak', [LaporanController::class, 'cetakSiswa'])->name('laporan.siswa.cetak');

    Route::get('/log', [LogAktivitasController::class, 'index'])->name('log.index');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', [GuruDashboardController::class, 'index'])->name('dashboard');
    Route::get('/jadwal', [GuruDashboardController::class, 'jadwal'])->name('jadwal');
    Route::get('/validasi', [GuruDashboardController::class, 'validasi'])->name('validasi');
    Route::get('/rekap', [GuruDashboardController::class, 'rekap'])->name('rekap');
    Route::get('/absensi-saya', [GuruDashboardController::class, 'absensiSaya'])->name('absensi-saya');
    Route::post('/absensi-scan', [AbsensiGuruController::class, 'simpan'])->name('absensi-scan');
    Route::get('/absensi-siswa/{id}', [AbsensiSiswaController::class, 'index'])->name('absensi-siswa');
    Route::post('/absensi-siswa/{id}', [AbsensiSiswaController::class, 'simpan'])->name('absensi-siswa.simpan');
});

Route::middleware(['auth', 'role:sekretaris'])->prefix('sekretaris')->name('sekretaris.')->group(function () {
    Route::get('/', [SekretarisDashboardController::class, 'index'])->name('dashboard');
    Route::get('/absensi-siswa/{id}', [SekretarisDashboardController::class, 'absensiSiswa'])->name('absensi-siswa');
    Route::post('/absensi-siswa/{id}', [SekretarisDashboardController::class, 'simpanAbsensiSiswa'])->name('absensi-siswa.simpan');
});

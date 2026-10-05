<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\PengaturanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSekolah::first();
        if (!$pengaturan) {
            $pengaturan = PengaturanSekolah::create([
                'nama_sekolah' => 'SMK Negeri 13 Bandung',
                'lat_sekolah' => -6.94580000,
                'long_sekolah' => 107.67650000,
                'radius_meter' => 100,
                'jam_masuk' => '07:00',
            ]);
        }

        $struktur = $pengaturan->struktur;

        return view('admin.profil-sambutan', compact('pengaturan', 'struktur'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'slogan' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'nama_kepsek' => 'nullable|string|max:255',
            'judul_sambutan' => 'nullable|string|max:255',
            'sambutan' => 'nullable|string',
            'foto_kepsek' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gambar_struktur' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'struktur_organisasi' => 'nullable|array',
            'foto_struktur.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $pengaturan = PengaturanSekolah::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanSekolah();
        }

        $pengaturan->slogan = $request->slogan;
        $pengaturan->deskripsi_singkat = $request->deskripsi_singkat;
        $pengaturan->visi = $request->visi;
        $pengaturan->misi = $request->misi;
        $pengaturan->sejarah = $request->sejarah;
        $pengaturan->nama_kepsek = $request->nama_kepsek;
        $pengaturan->judul_sambutan = $request->judul_sambutan;
        $pengaturan->sambutan = $request->sambutan;

        $strukturData = $request->input('struktur_organisasi', []);
        $existingStruktur = $pengaturan->struktur_organisasi ?? [];
        if (!is_array($existingStruktur)) {
            $existingStruktur = [];
        }

        $photoKeys = [
            'kepala_sekolah',
            'komite_sekolah',
            'pendamping_sekolah',
            'wakasek_kurikulum',
            'wakasek_kesiswaan',
            'wakasek_sarpras',
            'wakasek_hubinmas',
            'koor_tu',
            'wakil_mutu',
            'kaprog_kimia',
            'kaprog_tjkt',
            'kaprog_pplg',
        ];

        foreach ($photoKeys as $key) {
            $fotoKey = 'foto_' . $key;
            if ($request->hasFile("foto_struktur.{$key}")) {
                if (!empty($existingStruktur[$fotoKey]) && Storage::disk('public')->exists($existingStruktur[$fotoKey])) {
                    Storage::disk('public')->delete($existingStruktur[$fotoKey]);
                }
                $strukturData[$fotoKey] = $request->file("foto_struktur.{$key}")->store('profil/struktur', 'public');
            } elseif (isset($existingStruktur[$fotoKey])) {
                $strukturData[$fotoKey] = $existingStruktur[$fotoKey];
            }
        }

        if ($request->has('struktur_organisasi') || $request->hasFile('foto_struktur')) {
            $pengaturan->struktur_organisasi = $strukturData;
        }

        if ($request->hasFile('foto_kepsek')) {
            if ($pengaturan->foto_kepsek && Storage::disk('public')->exists($pengaturan->foto_kepsek)) {
                Storage::disk('public')->delete($pengaturan->foto_kepsek);
            }
            $pengaturan->foto_kepsek = $request->file('foto_kepsek')->store('profil', 'public');
        }

        if ($request->hasFile('gambar_struktur')) {
            if ($pengaturan->gambar_struktur && Storage::disk('public')->exists($pengaturan->gambar_struktur)) {
                Storage::disk('public')->delete($pengaturan->gambar_struktur);
            }
            $pengaturan->gambar_struktur = $request->file('gambar_struktur')->store('profil', 'public');
        }

        $pengaturan->save();

        LogAktivitas::catat('Ubah Profil Sekolah', 'Memperbarui profil sekolah, struktur organisasi, dan sambutan pimpinan');

        return redirect()->route('admin.profil-sambutan.index')->with('sukses', 'Profil, sambutan kepala sekolah, dan struktur organisasi berhasil diperbarui.');
    }
}

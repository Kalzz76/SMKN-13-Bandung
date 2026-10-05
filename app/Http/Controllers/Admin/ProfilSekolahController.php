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

        return view('admin.profil-sambutan', compact('pengaturan'));
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

        LogAktivitas::catat('Ubah Profil Sekolah', 'Memperbarui profil sekolah, visi misi, dan sambutan pimpinan');

        return redirect()->route('admin.profil-sambutan.index')->with('sukses', 'Profil dan sambutan kepala sekolah berhasil diperbarui.');
    }
}

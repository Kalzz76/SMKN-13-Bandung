<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::query()->latest('tanggal');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('kategori', 'like', "%{$cari}%")
                  ->orWhere('isi', 'like', "%{$cari}%");
            });
        }

        $daftarBerita = $query->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('daftarBerita'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
            'status' => 'required|in:Draft,Publish',
            'tanggal' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('berita', 'public');
        }

        $berita = Berita::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
            'gambar' => $gambarPath,
            'id_user' => auth()->id(),
        ]);

        LogAktivitas::catat('Tambah Berita', "Menambahkan berita '{$berita->judul}'");

        return redirect()->route('admin.berita.index')->with('sukses', 'Berita berhasil diterbitkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'isi' => 'required|string',
            'status' => 'required|in:Draft,Publish',
            'tanggal' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $berita = Berita::findOrFail($id);

        $berita->judul = $request->judul;
        $berita->kategori = $request->kategori;
        $berita->isi = $request->isi;
        $berita->status = $request->status;
        $berita->tanggal = $request->tanggal;

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
                Storage::disk('public')->delete($berita->gambar);
            }
            $berita->gambar = $request->file('gambar')->store('berita', 'public');
        }

        $berita->save();

        LogAktivitas::catat('Ubah Berita', "Memperbarui berita '{$berita->judul}'");

        return redirect()->route('admin.berita.index')->with('sukses', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        if ($berita->gambar && Storage::disk('public')->exists($berita->gambar)) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $judul = $berita->judul;
        $berita->delete();

        LogAktivitas::catat('Hapus Berita', "Menghapus berita '{$judul}'");

        return redirect()->route('admin.berita.index')->with('sukses', 'Berita berhasil dihapus.');
    }
}

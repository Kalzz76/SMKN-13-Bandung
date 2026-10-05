<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::query()->latest('tanggal');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('judul', 'like', "%{$cari}%");
        }

        $daftarGaleri = $query->paginate(12)->withQueryString();

        return view('admin.galeri.index', compact('daftarGaleri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Fasilitas,Kegiatan',
            'tanggal' => 'required|date',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = $request->file('foto')->store('galeri', 'public');

        $galeri = Galeri::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'foto' => $fotoPath,
        ]);

        LogAktivitas::catat('Tambah Galeri', "Menambahkan foto galeri '{$galeri->judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', 'Foto galeri berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Fasilitas,Kegiatan',
            'tanggal' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $galeri = Galeri::findOrFail($id);

        $galeri->judul = $request->judul;
        $galeri->kategori = $request->kategori;
        $galeri->tanggal = $request->tanggal;

        if ($request->hasFile('foto')) {
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $galeri->foto = $request->file('foto')->store('galeri', 'public');
        }

        $galeri->save();

        LogAktivitas::catat('Ubah Galeri', "Memperbarui foto galeri '{$galeri->judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $judul = $galeri->judul;
        $galeri->delete();

        LogAktivitas::catat('Hapus Galeri', "Menghapus foto galeri '{$judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', 'Foto galeri berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prestasi::query()->latest('tahun');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('nama_peraih', 'like', "%{$cari}%")
                  ->orWhere('tingkat', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        $daftarPrestasi = $query->paginate(10)->withQueryString();

        return view('admin.prestasi.index', compact('daftarPrestasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tingkat' => 'required|in:Sekolah,Kota,Provinsi,Nasional,Internasional',
            'tahun' => 'required|string|max:10',
            'nama_peraih' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('prestasi', 'public');
        }

        $prestasi = Prestasi::create([
            'judul' => $request->judul,
            'tingkat' => $request->tingkat,
            'tahun' => $request->tahun,
            'nama_peraih' => $request->nama_peraih,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
        ]);

        LogAktivitas::catat('Tambah Prestasi', "Menambahkan prestasi '{$prestasi->judul}' ({$prestasi->nama_peraih})");

        return redirect()->route('admin.prestasi.index')->with('sukses', 'Prestasi siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tingkat' => 'required|in:Sekolah,Kota,Provinsi,Nasional,Internasional',
            'tahun' => 'required|string|max:10',
            'nama_peraih' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $prestasi = Prestasi::findOrFail($id);

        $prestasi->judul = $request->judul;
        $prestasi->tingkat = $request->tingkat;
        $prestasi->tahun = $request->tahun;
        $prestasi->nama_peraih = $request->nama_peraih;
        $prestasi->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            if ($prestasi->gambar && Storage::disk('public')->exists($prestasi->gambar)) {
                Storage::disk('public')->delete($prestasi->gambar);
            }
            $prestasi->gambar = $request->file('gambar')->store('prestasi', 'public');
        }

        $prestasi->save();

        LogAktivitas::catat('Ubah Prestasi', "Memperbarui prestasi '{$prestasi->judul}'");

        return redirect()->route('admin.prestasi.index')->with('sukses', 'Prestasi siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prestasi = Prestasi::findOrFail($id);

        if ($prestasi->gambar && Storage::disk('public')->exists($prestasi->gambar)) {
            Storage::disk('public')->delete($prestasi->gambar);
        }

        $judul = $prestasi->judul;
        $prestasi->delete();

        LogAktivitas::catat('Hapus Prestasi', "Menghapus prestasi '{$judul}'");

        return redirect()->route('admin.prestasi.index')->with('sukses', 'Prestasi siswa berhasil dihapus.');
    }
}

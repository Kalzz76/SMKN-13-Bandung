<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EkskulController extends Controller
{
    public function index(Request $request)
    {
        $query = Ekstrakurikuler::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('pembina', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        $daftarEkskul = $query->paginate(10)->withQueryString();

        return view('admin.ekskul.index', compact('daftarEkskul'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pembina' => 'nullable|string|max:255',
            'jadwal' => 'nullable|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('ekskul', 'public');
        }

        $ekskul = Ekstrakurikuler::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'pembina' => $request->pembina,
            'jadwal' => $request->jadwal,
            'gambar' => $gambarPath,
        ]);

        LogAktivitas::catat('Tambah Ekskul', "Menambahkan ekstrakurikuler '{$ekskul->nama}'");

        return redirect()->route('admin.ekskul.index')->with('sukses', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pembina' => 'nullable|string|max:255',
            'jadwal' => 'nullable|string|max:100',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $ekskul = Ekstrakurikuler::findOrFail($id);

        $ekskul->nama = $request->nama;
        $ekskul->deskripsi = $request->deskripsi;
        $ekskul->pembina = $request->pembina;
        $ekskul->jadwal = $request->jadwal;

        if ($request->hasFile('gambar')) {
            if ($ekskul->gambar && Storage::disk('public')->exists($ekskul->gambar)) {
                Storage::disk('public')->delete($ekskul->gambar);
            }
            $ekskul->gambar = $request->file('gambar')->store('ekskul', 'public');
        }

        $ekskul->save();

        LogAktivitas::catat('Ubah Ekskul', "Memperbarui ekstrakurikuler '{$ekskul->nama}'");

        return redirect()->route('admin.ekskul.index')->with('sukses', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ekskul = Ekstrakurikuler::findOrFail($id);

        if ($ekskul->gambar && Storage::disk('public')->exists($ekskul->gambar)) {
            Storage::disk('public')->delete($ekskul->gambar);
        }

        $nama = $ekskul->nama;
        $ekskul->delete();

        LogAktivitas::catat('Hapus Ekskul', "Menghapus ekstrakurikuler '{$nama}'");

        return redirect()->route('admin.ekskul.index')->with('sukses', 'Ekstrakurikuler berhasil dihapus.');
    }
}

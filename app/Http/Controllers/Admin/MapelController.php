<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode', 'like', "%{$cari}%")
                  ->orWhere('nama', 'like', "%{$cari}%")
                  ->orWhere('jenis', 'like', "%{$cari}%");
            });
        }

        $daftarMapel = $query->paginate(10)->withQueryString();

        return view('admin.mapel.index', compact('daftarMapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:mapel,kode',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:Umum,Produktif',
        ]);

        $mapel = Mapel::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'jenis' => $request->jenis,
        ]);

        LogAktivitas::catat('Tambah Mapel', "Menambahkan mata pelajaran '{$mapel->nama}' ({$mapel->kode})");

        return redirect()->route('admin.mapel.index')->with('sukses', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:mapel,kode,' . $id,
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:Umum,Produktif',
        ]);

        $mapel->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'jenis' => $request->jenis,
        ]);

        LogAktivitas::catat('Ubah Mapel', "Memperbarui mata pelajaran '{$mapel->nama}' ({$mapel->kode})");

        return redirect()->route('admin.mapel.index')->with('sukses', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);

        if ($mapel->jadwal()->count() > 0) {
            return redirect()->route('admin.mapel.index')->with('error', 'Mata pelajaran tidak dapat dihapus karena masih digunakan dalam jadwal pelajaran.');
        }

        $nama = $mapel->nama;
        $mapel->delete();

        LogAktivitas::catat('Hapus Mapel', "Menghapus mata pelajaran '{$nama}'");

        return redirect()->route('admin.mapel.index')->with('sukses', 'Mata pelajaran berhasil dihapus.');
    }
}

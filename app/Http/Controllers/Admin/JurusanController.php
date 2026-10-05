<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurusan::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode', 'like', "%{$cari}%")
                  ->orWhere('nama', 'like', "%{$cari}%")
                  ->orWhere('deskripsi', 'like', "%{$cari}%");
            });
        }

        $daftarJurusan = $query->paginate(10)->withQueryString();

        return view('admin.jurusan.index', compact('daftarJurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:jurusan,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'required|string|max:50',
        ]);

        $jurusan = Jurusan::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ikon' => $request->ikon,
        ]);

        LogAktivitas::catat('Tambah Jurusan', "Menambahkan jurusan '{$jurusan->nama}' ({$jurusan->kode})");

        return redirect()->route('admin.jurusan.index')->with('sukses', 'Program keahlian jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:20|unique:jurusan,kode,' . $id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'required|string|max:50',
        ]);

        $jurusan->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'ikon' => $request->ikon,
        ]);

        LogAktivitas::catat('Ubah Jurusan', "Memperbarui jurusan '{$jurusan->nama}' ({$jurusan->kode})");

        return redirect()->route('admin.jurusan.index')->with('sukses', 'Program keahlian jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $nama = $jurusan->nama;
        $jurusan->delete();

        LogAktivitas::catat('Hapus Jurusan', "Menghapus jurusan '{$nama}'");

        return redirect()->route('admin.jurusan.index')->with('sukses', 'Jurusan berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::query();

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('kode', 'like', "%{$cari}%")
                  ->orWhere('nama', 'like', "%{$cari}%");
            });
        }

        $daftarRuangan = $query->paginate(10)->withQueryString();

        return view('admin.ruangan.index', compact('daftarRuangan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:ruangan,kode',
            'nama' => 'required|string|max:255',
        ]);

        $ruangan = Ruangan::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
        ]);

        LogAktivitas::catat('Tambah Ruangan', "Menambahkan ruangan '{$ruangan->nama}' ({$ruangan->kode})");

        return redirect()->route('admin.ruangan.index')->with('sukses', 'Data ruangan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:50|unique:ruangan,kode,' . $id,
            'nama' => 'required|string|max:255',
        ]);

        $ruangan->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
        ]);

        LogAktivitas::catat('Ubah Ruangan', "Memperbarui ruangan '{$ruangan->nama}' ({$ruangan->kode})");

        return redirect()->route('admin.ruangan.index')->with('sukses', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);

        if ($ruangan->kelas()->count() > 0 || $ruangan->jadwal()->count() > 0) {
            return redirect()->route('admin.ruangan.index')->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan oleh data kelas atau jadwal pelajaran.');
        }

        $nama = $ruangan->nama;
        $ruangan->delete();

        LogAktivitas::catat('Hapus Ruangan', "Menghapus ruangan '{$nama}'");

        return redirect()->route('admin.ruangan.index')->with('sukses', 'Data ruangan berhasil dihapus.');
    }
}

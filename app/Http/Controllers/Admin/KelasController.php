<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::query()->with(['ruangan', 'waliKelas', 'siswa']);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhereHas('ruangan', function ($rq) use ($cari) {
                      $rq->where('nama', 'like', "%{$cari}%")
                         ->orWhere('kode', 'like', "%{$cari}%");
                  })
                  ->orWhereHas('waliKelas', function ($gq) use ($cari) {
                      $gq->where('nama', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarKelas = $query->paginate(10)->withQueryString();
        $daftarRuangan = Ruangan::orderBy('kode')->get();
        $daftarGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();

        return view('admin.kelas.index', compact('daftarKelas', 'daftarRuangan', 'daftarGuru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama',
            'id_ruangan' => 'nullable|exists:ruangan,id',
            'id_wali_kelas' => 'nullable|exists:guru,id',
        ]);

        $kelas = Kelas::create([
            'nama' => $request->nama,
            'id_ruangan' => $request->id_ruangan,
            'id_wali_kelas' => $request->id_wali_kelas,
        ]);

        LogAktivitas::catat('Tambah Kelas', "Menambahkan kelas '{$kelas->nama}'");

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama,' . $id,
            'id_ruangan' => 'nullable|exists:ruangan,id',
            'id_wali_kelas' => 'nullable|exists:guru,id',
        ]);

        $kelas->update([
            'nama' => $request->nama,
            'id_ruangan' => $request->id_ruangan,
            'id_wali_kelas' => $request->id_wali_kelas,
        ]);

        LogAktivitas::catat('Ubah Kelas', "Memperbarui kelas '{$kelas->nama}'");

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0 || $kelas->jadwal()->count() > 0) {
            return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa atau jadwal pelajaran.');
        }

        $nama = $kelas->nama;
        $kelas->delete();

        LogAktivitas::catat('Hapus Kelas', "Menghapus kelas '{$nama}'");

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil dihapus.');
    }
}

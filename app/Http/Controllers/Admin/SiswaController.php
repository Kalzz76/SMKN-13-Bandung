<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query()->with('kelas');

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nis', 'like', "%{$cari}%")
                  ->orWhere('nisn', 'like', "%{$cari}%")
                  ->orWhere('nama', 'like', "%{$cari}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$cari}%")
                  ->orWhereHas('kelas', function ($kq) use ($cari) {
                      $kq->where('nama', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarSiswa = $query->paginate(10)->withQueryString();
        $daftarKelas = Kelas::orderBy('nama')->get();

        return view('admin.siswa.index', compact('daftarSiswa', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nis'],
            'nisn' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nisn'],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ], [
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
        ]);

        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        LogAktivitas::catat('Tambah Siswa', "Menambahkan siswa '{$siswa->nama}' (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nis,' . $id],
            'nisn' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nisn,' . $id],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ], [
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        LogAktivitas::catat('Ubah Siswa', "Memperbarui data siswa '{$siswa->nama}' (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->absensi()->count() > 0) {
            return redirect()->route('admin.siswa.index')->with('error', 'Data siswa tidak dapat dihapus karena memiliki riwayat absensi.');
        }

        $nama = $siswa->nama;
        $siswa->delete();

        LogAktivitas::catat('Hapus Siswa', "Menghapus siswa '{$nama}'");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil dihapus.');
    }
}

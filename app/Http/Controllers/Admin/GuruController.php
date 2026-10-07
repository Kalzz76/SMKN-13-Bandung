<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query()->with(['user', 'mapel', 'mapels']);

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nip', 'like', "%{$cari}%")
                  ->orWhere('jabatan', 'like', "%{$cari}%")
                  ->orWhere('mapel_utama', 'like', "%{$cari}%")
                  ->orWhereHas('mapel', function ($mq) use ($cari) {
                      $mq->where('nama', 'like', "%{$cari}%")
                         ->orWhere('kode', 'like', "%{$cari}%");
                  })
                  ->orWhereHas('mapels', function ($mq) use ($cari) {
                      $mq->where('nama', 'like', "%{$cari}%")
                         ->orWhere('kode', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarGuru = $query->paginate(12)->withQueryString();
        $daftarMapel = Mapel::orderBy('nama')->get();

        return view('admin.guru.index', compact('daftarGuru', 'daftarMapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => ['required', 'regex:/^[0-9]+$/', 'max:50'],
            'jenis' => 'required|in:Guru,Staff',
            'jabatan' => 'nullable|string|max:255',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mapel,id',
            'id_mapel' => 'nullable|exists:mapel,id',
            'mapel_utama' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tampil_publik' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id|unique:guru,user_id',
        ], [
            'nip.regex' => 'NIP hanya boleh berisi angka.',
        ]);

        $mapelIds = $request->input('mapel_ids', []);
        if (empty($mapelIds) && $request->filled('id_mapel')) {
            $mapelIds = [(int) $request->id_mapel];
        }

        $namaMapels = Mapel::whereIn('id', $mapelIds)->pluck('nama')->toArray();
        $mapelUtama = !empty($namaMapels) ? implode(', ', $namaMapels) : $request->mapel_utama;
        $primaryMapelId = !empty($mapelIds) ? $mapelIds[0] : $request->id_mapel;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('guru', 'public');
        }

        $guru = Guru::create([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jenis' => $request->jenis,
            'jabatan' => $request->jabatan ?? ($request->jenis === 'Guru' ? 'Guru Pengajar' : 'Staff Tata Usaha'),
            'id_mapel' => $primaryMapelId,
            'mapel_utama' => $mapelUtama,
            'foto' => $fotoPath,
            'tampil_publik' => $request->has('tampil_publik') ? 1 : 0,
            'user_id' => $request->user_id,
        ]);

        if (!empty($mapelIds)) {
            $guru->mapels()->sync($mapelIds);
        }

        LogAktivitas::catat('Tambah Guru', "Menambahkan tenaga pendidik/staff '{$guru->nama}'");

        return redirect()->route('admin.guru.index')->with('sukses', 'Data guru/staff berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => ['required', 'regex:/^[0-9]+$/', 'max:50'],
            'jenis' => 'required|in:Guru,Staff',
            'jabatan' => 'nullable|string|max:255',
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'exists:mapel,id',
            'id_mapel' => 'nullable|exists:mapel,id',
            'mapel_utama' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tampil_publik' => 'nullable|boolean',
            'user_id' => 'nullable|exists:users,id|unique:guru,user_id,' . $id,
        ], [
            'nip.regex' => 'NIP hanya boleh berisi angka.',
        ]);

        $guru->nama = $request->nama;
        $guru->nip = $request->nip;
        $guru->jenis = $request->jenis;
        if ($request->filled('jabatan')) {
            $guru->jabatan = $request->jabatan;
        }

        $mapelIds = $request->input('mapel_ids', null);
        if ($mapelIds === null && $request->has('id_mapel')) {
            $mapelIds = $request->filled('id_mapel') ? [(int) $request->id_mapel] : [];
        }

        if ($mapelIds !== null) {
            $guru->mapels()->sync($mapelIds);
            $namaMapels = Mapel::whereIn('id', $mapelIds)->pluck('nama')->toArray();
            $guru->mapel_utama = !empty($namaMapels) ? implode(', ', $namaMapels) : ($request->mapel_utama ?? $guru->mapel_utama);
            $guru->id_mapel = !empty($mapelIds) ? $mapelIds[0] : null;
        } elseif ($request->filled('mapel_utama')) {
            $guru->mapel_utama = $request->mapel_utama;
        }

        $guru->tampil_publik = $request->has('tampil_publik') ? 1 : 0;
        if ($request->has('user_id')) {
            $guru->user_id = $request->user_id;
        }

        if ($request->hasFile('foto')) {
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }
            $guru->foto = $request->file('foto')->store('guru', 'public');
        }

        $guru->save();

        LogAktivitas::catat('Ubah Guru', "Memperbarui data tenaga pendidik/staff '{$guru->nama}'");

        return redirect()->route('admin.guru.index')->with('sukses', 'Data guru/staff berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->kelasWali()->count() > 0 || $guru->jadwal()->count() > 0 || $guru->absensi()->count() > 0) {
            return redirect()->route('admin.guru.index')->with('error', 'Guru/Staff tidak dapat dihapus karena masih terdaftar sebagai wali kelas, memiliki jadwal mengajar, atau data absensi.');
        }

        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $nama = $guru->nama;
        $guru->delete();

        LogAktivitas::catat('Hapus Guru', "Menghapus data tenaga pendidik/staff '{$nama}'");

        return redirect()->route('admin.guru.index')->with('sukses', 'Data guru/staff berhasil dihapus.');
    }
}

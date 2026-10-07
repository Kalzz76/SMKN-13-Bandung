<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Ruangan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $semuaKelas = Kelas::with(['ruangan', 'waliKelas'])->get();

        return view('admin.kelas.index', compact('daftarKelas', 'daftarRuangan', 'daftarGuru', 'semuaKelas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100|unique:kelas,nama',
            'id_ruangan' => 'nullable|exists:ruangan,id|unique:kelas,id_ruangan',
            'id_wali_kelas' => 'nullable|exists:guru,id|unique:kelas,id_wali_kelas',
        ], [
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah digunakan.',
            'id_ruangan.unique' => 'Ruangan ini sudah digunakan oleh kelas lain.',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid.',
            'id_wali_kelas.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
            'id_wali_kelas.exists' => 'Guru yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $kelas = Kelas::create([
            'nama' => $request->nama,
            'id_ruangan' => $request->id_ruangan ?: null,
            'id_wali_kelas' => $request->id_wali_kelas ?: null,
        ]);

        LogAktivitas::catat('Tambah Kelas', "Menambahkan kelas '{$kelas->nama}'");

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100|unique:kelas,nama,' . $id,
            'id_ruangan' => 'nullable|exists:ruangan,id|unique:kelas,id_ruangan,' . $id,
            'id_wali_kelas' => 'nullable|exists:guru,id|unique:kelas,id_wali_kelas,' . $id,
        ], [
            'nama.required' => 'Nama kelas wajib diisi.',
            'nama.unique' => 'Nama kelas sudah digunakan.',
            'id_ruangan.unique' => 'Ruangan ini sudah digunakan oleh kelas lain.',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid.',
            'id_wali_kelas.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
            'id_wali_kelas.exists' => 'Guru yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        $kelas->update([
            'nama' => $request->nama,
            'id_ruangan' => $request->id_ruangan ?: null,
            'id_wali_kelas' => $request->id_wali_kelas ?: null,
        ]);

        LogAktivitas::catat('Ubah Kelas', "Memperbarui kelas '{$kelas->nama}'");

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil diperbarui.');
    }

    public function show(Request $request, $id)
    {
        $kelas = Kelas::with(['ruangan', 'waliKelas', 'siswa' => function ($q) {
            $q->orderBy('nama');
        }])->findOrFail($id);

        $daftarRuangan = Ruangan::orderBy('kode')->get();
        $daftarGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $daftarKelasLain = Kelas::where('id', '!=', $id)->get();

        return view('admin.kelas.show', compact('kelas', 'daftarRuangan', 'daftarGuru', 'daftarKelasLain'));
    }

    public function updateStruktur(Request $request, $id)
    {
        $kelas = Kelas::with('siswa')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'id_wali_kelas' => 'nullable|exists:guru,id|unique:kelas,id_wali_kelas,' . $id,
            'km' => 'nullable|string|max:255',
            'wakil_km' => 'nullable|string|max:255',
            'bendahara_1' => 'nullable|string|max:255',
            'bendahara_2' => 'nullable|string|max:255',
            'sekretaris_1' => 'nullable|string|max:255',
            'sekretaris_2' => 'nullable|string|max:255',
            'pj_keagamaan' => 'nullable|string|max:255',
            'pj_keamanan' => 'nullable|string|max:255',
        ], [
            'id_wali_kelas.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.',
            'id_wali_kelas.exists' => 'Guru yang dipilih tidak valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        if ($request->has('id_wali_kelas')) {
            $kelas->id_wali_kelas = $request->id_wali_kelas ?: null;
        }

        $validated = $validator->validated();
        $struktur = collect($validated)->except(['id_wali_kelas'])->all();
        $kelas->struktur = $struktur;
        $kelas->save();

        Siswa::where('id_kelas', $kelas->id)->update(['jabatan' => 'Anggota']);

        $mapping = [
            'Ketua Murid' => $validated['km'] ?? null,
            'Wakil Ketua Murid' => $validated['wakil_km'] ?? null,
            'Bendahara 1' => $validated['bendahara_1'] ?? null,
            'Bendahara 2' => $validated['bendahara_2'] ?? null,
            'Sekretaris 1' => $validated['sekretaris_1'] ?? null,
            'Sekretaris 2' => $validated['sekretaris_2'] ?? null,
            'PJ Keagamaan' => $validated['pj_keagamaan'] ?? null,
            'PJ Keamanan' => $validated['pj_keamanan'] ?? null,
        ];

        foreach ($mapping as $jabatan => $nama) {
            if (!empty($nama)) {
                Siswa::where('id_kelas', $kelas->id)
                    ->where(function ($q) use ($nama) {
                        $q->where('nama', $nama)
                          ->orWhere('id', $nama);
                    })
                    ->update(['jabatan' => $jabatan]);
            }
        }

        LogAktivitas::catat('Kelola Struktur Kelas', "Memperbarui struktur organisasi kelas '{$kelas->nama}'");

        return redirect()->route('admin.kelas.show', $kelas->id)->with('sukses', 'Struktur organisasi kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0) {
            return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki data siswa. Harap hapus atau pindahkan siswa terlebih dahulu.');
        }

        DB::transaction(function () use ($kelas) {
            $kelas->jadwal()->delete();
            $nama = $kelas->nama;
            $kelas->delete();
            LogAktivitas::catat('Hapus Kelas', "Menghapus kelas '{$nama}'");
        });

        return redirect()->route('admin.kelas.index')->with('sukses', 'Data kelas berhasil dihapus.');
    }
}

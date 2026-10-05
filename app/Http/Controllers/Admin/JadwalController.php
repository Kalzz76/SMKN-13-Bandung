<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $hariIniInggris = Carbon::now('Asia/Jakarta')->format('l');
        $petaHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
        ];

        $hariDefault = $petaHari[$hariIniInggris] ?? 'Senin';
        $hariTerpilih = $request->query('hari', $hariDefault);

        if (!in_array($hariTerpilih, $daftarHari)) {
            $hariTerpilih = 'Senin';
        }

        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();
        $daftarJadwal = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])
            ->where('hari', $hariTerpilih)
            ->get();
        $semuaJadwal = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])->get();

        $daftarJamPelajaran = JamPelajaran::orderBy('urutan')->get();
        $daftarMapel = Mapel::orderBy('nama')->get();
        $daftarGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $daftarRuangan = Ruangan::orderBy('kode')->get();

        return view('admin.jadwal.index', compact(
            'daftarHari',
            'hariTerpilih',
            'daftarKelas',
            'daftarJadwal',
            'semuaJadwal',
            'daftarJamPelajaran',
            'daftarMapel',
            'daftarGuru',
            'daftarRuangan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mapel,id',
            'id_guru' => 'required|exists:guru,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'jam_ke_mulai' => 'required|integer|min:1|max:7',
            'jam_ke_selesai' => 'required|integer|min:1|max:7',
        ]);

        if ($request->jam_ke_selesai < $request->jam_ke_mulai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai tidak boleh lebih awal dari jam mulai.');
        }

        if (($request->jam_ke_mulai <= 3 && $request->jam_ke_selesai >= 4) ||
            ($request->jam_ke_mulai <= 5 && $request->jam_ke_selesai >= 6)) {
            return redirect()->back()->withInput()->with('error', 'Jadwal tidak boleh melewati jam istirahat. Harap pecah menjadi dua jadwal terpisah sebelum dan sesudah istirahat.');
        }

        $bentrokGuru = Jadwal::where('hari', $request->hari)
            ->where('id_guru', $request->id_guru)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokGuru) {
            $guru = Guru::find($request->id_guru);
            $kelas = Kelas::find($bentrokGuru->id_kelas);
            return redirect()->back()->withInput()->with('error', "Guru {$guru->nama} sudah memiliki jadwal mengajar di kelas {$kelas->nama} pada jam tersebut.");
        }

        $bentrokKelas = Jadwal::where('hari', $request->hari)
            ->where('id_kelas', $request->id_kelas)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokKelas) {
            $kelas = Kelas::find($request->id_kelas);
            return redirect()->back()->withInput()->with('error', "Kelas {$kelas->nama} sudah memiliki jadwal pelajaran lain pada jam tersebut.");
        }

        $bentrokRuangan = Jadwal::where('hari', $request->hari)
            ->where('id_ruangan', $request->id_ruangan)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokRuangan) {
            $ruangan = Ruangan::find($request->id_ruangan);
            $kelas = Kelas::find($bentrokRuangan->id_kelas);
            return redirect()->back()->withInput()->with('error', "Ruangan {$ruangan->nama} sudah digunakan oleh kelas {$kelas->nama} pada jam tersebut.");
        }

        $jadwal = Jadwal::create([
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'id_ruangan' => $request->id_ruangan,
            'jam_ke_mulai' => $request->jam_ke_mulai,
            'jam_ke_selesai' => $request->jam_ke_selesai,
        ]);

        $kelas = Kelas::find($request->id_kelas);
        $mapel = Mapel::find($request->id_mapel);
        LogAktivitas::catat('Tambah Jadwal', "Menambahkan jadwal pelajaran {$kelas->nama} - {$mapel->nama} ({$jadwal->hari})");

        return redirect()->route('admin.jadwal.index', ['hari' => $request->hari])->with('sukses', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mapel,id',
            'id_guru' => 'required|exists:guru,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'jam_ke_mulai' => 'required|integer|min:1|max:7',
            'jam_ke_selesai' => 'required|integer|min:1|max:7',
        ]);

        if ($request->jam_ke_selesai < $request->jam_ke_mulai) {
            return redirect()->back()->withInput()->with('error', 'Jam selesai tidak boleh lebih awal dari jam mulai.');
        }

        if (($request->jam_ke_mulai <= 3 && $request->jam_ke_selesai >= 4) ||
            ($request->jam_ke_mulai <= 5 && $request->jam_ke_selesai >= 6)) {
            return redirect()->back()->withInput()->with('error', 'Jadwal tidak boleh melewati jam istirahat. Harap pecah menjadi dua jadwal terpisah sebelum dan sesudah istirahat.');
        }

        $bentrokGuru = Jadwal::where('hari', $request->hari)
            ->where('id_guru', $request->id_guru)
            ->where('id', '!=', $id)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokGuru) {
            $guru = Guru::find($request->id_guru);
            $kelas = Kelas::find($bentrokGuru->id_kelas);
            return redirect()->back()->withInput()->with('error', "Guru {$guru->nama} sudah memiliki jadwal mengajar di kelas {$kelas->nama} pada jam tersebut.");
        }

        $bentrokKelas = Jadwal::where('hari', $request->hari)
            ->where('id_kelas', $request->id_kelas)
            ->where('id', '!=', $id)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokKelas) {
            $kelas = Kelas::find($request->id_kelas);
            return redirect()->back()->withInput()->with('error', "Kelas {$kelas->nama} sudah memiliki jadwal pelajaran lain pada jam tersebut.");
        }

        $bentrokRuangan = Jadwal::where('hari', $request->hari)
            ->where('id_ruangan', $request->id_ruangan)
            ->where('id', '!=', $id)
            ->where(function ($q) use ($request) {
                $q->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
                  ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai);
            })
            ->first();

        if ($bentrokRuangan) {
            $ruangan = Ruangan::find($request->id_ruangan);
            $kelas = Kelas::find($bentrokRuangan->id_kelas);
            return redirect()->back()->withInput()->with('error', "Ruangan {$ruangan->nama} sudah digunakan oleh kelas {$kelas->nama} pada jam tersebut.");
        }

        $jadwal->update([
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru,
            'id_ruangan' => $request->id_ruangan,
            'jam_ke_mulai' => $request->jam_ke_mulai,
            'jam_ke_selesai' => $request->jam_ke_selesai,
        ]);

        $kelas = Kelas::find($request->id_kelas);
        $mapel = Mapel::find($request->id_mapel);
        LogAktivitas::catat('Ubah Jadwal', "Memperbarui jadwal pelajaran {$kelas->nama} - {$mapel->nama} ({$jadwal->hari})");

        return redirect()->route('admin.jadwal.index', ['hari' => $request->hari])->with('sukses', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($jadwal->absensiSiswa()->count() > 0 || $jadwal->jurnalKelas()->count() > 0) {
            return redirect()->back()->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki riwayat absensi siswa atau jurnal kelas.');
        }

        $hari = $jadwal->hari;
        $kelas = $jadwal->kelas ? $jadwal->kelas->nama : 'Kelas';
        $mapel = $jadwal->mapel ? $jadwal->mapel->nama : 'Mapel';

        $jadwal->delete();

        LogAktivitas::catat('Hapus Jadwal', "Menghapus jadwal pelajaran {$kelas} - {$mapel} ({$hari})");

        return redirect()->route('admin.jadwal.index', ['hari' => $hari])->with('sukses', 'Jadwal pelajaran berhasil dihapus.');
    }
}

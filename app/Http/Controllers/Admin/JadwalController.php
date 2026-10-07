<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalPembiasaan;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    private const DAFTAR_HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function index(Request $request)
    {
        $daftarHari = self::DAFTAR_HARI;

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

        $slotHari = JamPelajaran::slotHari($hariTerpilih);
        $ringkasanHari = JamPelajaran::ringkasan($slotHari, $hariTerpilih);
        $jamKeMap = $slotHari->where('jenis', JamPelajaran::JENIS_PELAJARAN)->keyBy('jam_ke');

        $sesiMingguIni = JadwalPembiasaan::sesiMingguIni();
        $sesiTerpilih = (int) $request->query('sesi', $sesiMingguIni);
        if (!in_array($sesiTerpilih, [1, 2], true)) {
            $sesiTerpilih = $sesiMingguIni;
        }
        $parameterSesi = $request->has('sesi') ? ['sesi' => $sesiTerpilih] : [];

        $aturanRotasi = JadwalPembiasaan::aturanHari($hariTerpilih);
        $adaRotasi = $aturanRotasi->isNotEmpty();
        $namaPembiasaanHari = $ringkasanHari['pembiasaan']['nama'] ?? 'Pembiasaan';

        $labelPembiasaan = [];
        foreach ($daftarKelas as $k) {
            $aturan = $aturanRotasi->first(function ($a) use ($sesiTerpilih, $k) {
                return $a->sesi === $sesiTerpilih && $a->kelompok === $k->kelompok_pembiasaan;
            });

            $labelPembiasaan[$k->id] = $aturan
                ? ['kegiatan' => $aturan->kegiatan, 'lokasi' => $aturan->lokasi, 'rotasi' => true]
                : ['kegiatan' => $namaPembiasaanHari, 'lokasi' => null, 'rotasi' => false];
        }

        $daftarMapel = Mapel::orderBy('nama')->get();
        $daftarGuru = Guru::where('jenis', 'Guru')->with('mapels:id,nama,kode')->orderBy('nama')->get();
        $daftarRuangan = Ruangan::orderBy('kode')->get();

        return view('admin.jadwal.index', compact(
            'daftarHari',
            'hariTerpilih',
            'daftarKelas',
            'daftarJadwal',
            'semuaJadwal',
            'slotHari',
            'ringkasanHari',
            'jamKeMap',
            'sesiTerpilih',
            'sesiMingguIni',
            'parameterSesi',
            'aturanRotasi',
            'adaRotasi',
            'labelPembiasaan',
            'daftarMapel',
            'daftarGuru',
            'daftarRuangan'
        ));
    }

    public function jamHari(string $hari)
    {
        abort_unless(in_array($hari, self::DAFTAR_HARI, true), 404);

        return response()->json(JamPelajaran::ringkasanHari($hari));
    }

    public function store(Request $request)
    {
        $request->validate($this->aturanValidasi());

        if ($kesalahan = $this->periksaStrukturJam($request)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        if ($kesalahan = $this->periksaBentrok($request)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        $jadwal = Jadwal::create($this->dataJadwal($request));

        $kelas = Kelas::find($request->id_kelas);
        $mapel = Mapel::find($request->id_mapel);
        LogAktivitas::catat('Tambah Jadwal', "Menambahkan jadwal pelajaran {$kelas->nama} - {$mapel->nama} ({$jadwal->hari})");

        return redirect()->route('admin.jadwal.index', ['hari' => $request->hari])->with('sukses', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate($this->aturanValidasi());

        if ($kesalahan = $this->periksaStrukturJam($request)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        if ($kesalahan = $this->periksaBentrok($request, (int) $id)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        $jadwal->update($this->dataJadwal($request));

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

    private function aturanValidasi(): array
    {
        return [
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mapel,id',
            'id_guru' => 'nullable|exists:guru,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'jam_ke_mulai' => 'required|integer|min:1',
            'jam_ke_selesai' => 'required|integer|min:1',
        ];
    }

    private function dataJadwal(Request $request): array
    {
        return [
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru ?: null,
            'id_ruangan' => $request->id_ruangan,
            'jam_ke_mulai' => $request->jam_ke_mulai,
            'jam_ke_selesai' => $request->jam_ke_selesai,
        ];
    }

    private function periksaStrukturJam(Request $request): ?string
    {
        $mulai = (int) $request->jam_ke_mulai;
        $selesai = (int) $request->jam_ke_selesai;

        if ($selesai < $mulai) {
            return 'Jam selesai tidak boleh lebih awal dari jam mulai.';
        }

        $ringkasan = JamPelajaran::ringkasanHari($request->hari);
        $maksimal = $ringkasan['maks_jam'];

        if ($mulai > $maksimal || $selesai > $maksimal) {
            return "Hari {$request->hari} hanya memiliki Jam 1 sampai Jam {$maksimal}. Jam yang dipilih tidak tersedia.";
        }

        return null;
    }

    private function periksaBentrok(Request $request, ?int $kecualiId = null): ?string
    {
        if ($request->id_guru) {
            $bentrokGuru = $this->cariBentrok($request, 'id_guru', $kecualiId);
            if ($bentrokGuru) {
                $guru = Guru::find($request->id_guru);
                $kelas = Kelas::find($bentrokGuru->id_kelas);
                return "Guru {$guru->nama} sudah memiliki jadwal mengajar di kelas {$kelas->nama} pada jam tersebut.";
            }
        }

        $bentrokKelas = $this->cariBentrok($request, 'id_kelas', $kecualiId);
        if ($bentrokKelas) {
            $kelas = Kelas::find($request->id_kelas);
            return "Kelas {$kelas->nama} sudah memiliki jadwal pelajaran lain pada jam tersebut.";
        }

        $bentrokRuangan = $this->cariBentrok($request, 'id_ruangan', $kecualiId);
        if ($bentrokRuangan) {
            $ruangan = Ruangan::find($request->id_ruangan);
            $kelas = Kelas::find($bentrokRuangan->id_kelas);
            return "Ruangan {$ruangan->nama} sudah digunakan oleh kelas {$kelas->nama} pada jam tersebut.";
        }

        return null;
    }

    private function cariBentrok(Request $request, string $kolom, ?int $kecualiId = null): ?Jadwal
    {
        $nilai = $request->input($kolom);
        if (!$nilai) {
            return null;
        }

        return Jadwal::where('hari', $request->hari)
            ->where($kolom, $nilai)
            ->when($kecualiId, fn ($q) => $q->where('id', '!=', $kecualiId))
            ->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
            ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai)
            ->first();
    }
}

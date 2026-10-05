<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JurnalKelas;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiSiswaController extends Controller
{
    public function index($id)
    {
        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Akun Anda tidak memiliki profil guru.');
        }

        $jadwal = Jadwal::with(['kelas.siswa', 'mapel', 'ruangan'])->findOrFail($id);

        if ($jadwal->id_guru !== $guru->id) {
            abort(403, 'Anda bukan pengajar untuk jadwal kelas ini.');
        }

        $hariIniMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $hariIniMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';

        if ($jadwal->hari !== $hariIni) {
            return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
                ->with('error', "Jadwal ini berlangsung pada hari {$jadwal->hari}. Absensi hanya dapat diisi pada hari jadwal mengajar berlangsung.");
        }

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $daftarSiswa = $jadwal->kelas->siswa()->orderBy('nama')->get();

        $absensiTersimpan = AbsensiSiswa::where('id_jadwal', $jadwal->id)
            ->where('tanggal', $tanggalHariIni)
            ->get()
            ->keyBy('id_siswa');

        $jurnalTersimpan = JurnalKelas::where('id_jadwal', $jadwal->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        return view('guru.absensi-siswa', compact(
            'jadwal',
            'daftarSiswa',
            'absensiTersimpan',
            'jurnalTersimpan',
            'tanggalHariIni'
        ));
    }

    public function simpan(Request $request, $id)
    {
        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Akun Anda tidak memiliki profil guru.');
        }

        $jadwal = Jadwal::with(['kelas.siswa', 'mapel'])->findOrFail($id);

        if ($jadwal->id_guru !== $guru->id) {
            abort(403, 'Anda bukan pengajar untuk jadwal kelas ini.');
        }

        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:Hadir,Sakit,Izin,Alpa',
            'keterangan' => 'nullable|array',
            'materi' => 'nullable|string',
        ]);

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        foreach ($request->status as $siswaId => $statusKehadiran) {
            $ket = $request->keterangan[$siswaId] ?? null;

            AbsensiSiswa::updateOrCreate(
                [
                    'id_jadwal' => $jadwal->id,
                    'id_siswa' => $siswaId,
                    'tanggal' => $tanggalHariIni,
                ],
                [
                    'status' => $statusKehadiran,
                    'keterangan' => $ket,
                    'id_guru_pengisi' => $guru->id,
                ]
            );
        }

        if ($request->filled('materi')) {
            JurnalKelas::updateOrCreate(
                [
                    'id_jadwal' => $jadwal->id,
                    'tanggal' => $tanggalHariIni,
                ],
                [
                    'materi' => $request->materi,
                    'id_guru' => $guru->id,
                ]
            );
        }

        LogAktivitas::catat('Absensi Siswa & Jurnal', "Mengisi absensi kelas {$jadwal->kelas->nama} ({$jadwal->mapel->nama})");

        return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
            ->with('sukses', "Absensi siswa kelas {$jadwal->kelas->nama} dan jurnal materi berhasil disimpan.");
    }
}

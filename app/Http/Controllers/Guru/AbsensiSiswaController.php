<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
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
            return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
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

        $absensiGuruHariIni = AbsensiGuru::where('id_guru', $guru->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        $daftarSlotJam = range($jadwal->jam_ke_mulai, $jadwal->jam_ke_selesai);

        return view('guru.absensi-siswa', compact(
            'jadwal',
            'daftarSiswa',
            'absensiTersimpan',
            'jurnalTersimpan',
            'tanggalHariIni',
            'absensiGuruHariIni',
            'daftarSlotJam'
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

        if ($request->filled('kehadiran_guru')) {
            $statusGuru = $request->kehadiran_guru === 'Hadir' ? 'Hadir' : 'Tidak Hadir';
            $alasan = $request->alasan_guru;
            if ($request->filled('keterangan_guru')) {
                $alasan = ($alasan ? "{$alasan} - " : "") . $request->keterangan_guru;
            }
            AbsensiGuru::updateOrCreate(
                [
                    'id_guru' => $guru->id,
                    'tanggal' => $tanggalHariIni,
                ],
                [
                    'status' => $statusGuru,
                    'metode' => 'Jadwal Kelas',
                    'jam_masuk' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'alasan' => $alasan,
                    'id_user_input' => auth()->id(),
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

        return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
            ->with('sukses', "Absensi siswa kelas {$jadwal->kelas->nama} dan jurnal materi berhasil disimpan.");
    }
}

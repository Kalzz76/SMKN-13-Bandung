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

        // Validasi batasan jam KBM (Hanya bisa absen jika jam sekarang berada di antara jam mulai dan jam selesai)
        $jamKeMapPerHari = \App\Models\JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));
        $jamKeMap = $jamKeMapPerHari->get($jadwal->hari, collect());
        $mulai = $jamKeMap[$jadwal->jam_ke_mulai]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($jadwal->jam_ke_mulai - 1)));
        $selesai = $jamKeMap[$jadwal->jam_ke_selesai]->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($jadwal->jam_ke_selesai - 1)));
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        if ($nowTime < $mulai) {
            return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
                ->with('error', "Sesi KBM belum dimulai. Absensi hanya dapat diisi mulai pukul {$mulai} WIB sesuai jadwal pelajaran.");
        }

        if ($nowTime > $selesai) {
            return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
                ->with('error', "Batas waktu absensi untuk sesi ini telah berakhir ({$selesai} WIB).");
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

        $daftarSlotJam = range($jadwal->jam_ke_mulai, $jadwal->jam_ke_selesai);

        return view('guru.absensi-siswa', compact(
            'jadwal',
            'daftarSiswa',
            'absensiTersimpan',
            'jurnalTersimpan',
            'tanggalHariIni',
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

        // Validasi batasan jam KBM di backend
        $jamKeMapPerHari = \App\Models\JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));
        $jamKeMap = $jamKeMapPerHari->get($jadwal->hari, collect());
        $mulai = $jamKeMap[$jadwal->jam_ke_mulai]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($jadwal->jam_ke_mulai - 1)));
        $selesai = $jamKeMap[$jadwal->jam_ke_selesai]->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($jadwal->jam_ke_selesai - 1)));
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        if ($nowTime < $mulai) {
            return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
                ->with('error', "Sesi KBM belum dimulai. Absensi hanya dapat diisi mulai pukul {$mulai} WIB.");
        }

        if ($nowTime > $selesai) {
            return redirect()->route('guru.dashboard', ['tab' => 'jadwal'])
                ->with('error', "Batas waktu absensi untuk sesi ini telah berakhir ({$selesai} WIB).");
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
                    'status_validasi' => 'disetujui',
                    'diisi_oleh' => 'guru',
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

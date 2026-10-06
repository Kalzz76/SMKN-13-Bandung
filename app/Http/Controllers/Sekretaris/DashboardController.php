<?php

namespace App\Http\Controllers\Sekretaris;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tabAktif = $request->get('tab', 'jadwal');

        $hariIniMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $namaHariIni = $hariIniMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $daftarGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();

        $user = auth()->user();
        $siswaUser = Siswa::with('kelas')->where('nama', $user->name)->first();
        $kelasAktif = $siswaUser ? $siswaUser->kelas : ($daftarKelas->where('nama', 'XI RPL 1')->first() ?? $daftarKelas->first());
        $kelasId = $kelasAktif->id ?? null;

        $daftarSiswaKelas = $kelasAktif ? Siswa::where('id_kelas', $kelasAktif->id)->orderBy('nama')->get() : collect();

        $jadwalKelasHariIni = $kelasAktif ? Jadwal::with(['mapel', 'guru', 'ruangan'])
            ->where('id_kelas', $kelasAktif->id)
            ->where('hari', $namaHariIni)
            ->orderBy('jam_ke_mulai')
            ->get() : collect();

        $jadwalKelasMingguan = $kelasAktif ? Jadwal::with(['mapel', 'guru', 'ruangan'])
            ->where('id_kelas', $kelasAktif->id)
            ->orderBy('jam_ke_mulai')
            ->get()
            ->groupBy('hari') : collect();

        $statusAbsensiSiswa = [];
        $rekapAbsensiHariIni = [];
        $detailAbsensiTersimpan = [];
        foreach ($jadwalKelasHariIni as $j) {
            $records = AbsensiSiswa::where('id_jadwal', $j->id)
                ->where('tanggal', $tanggalHariIni)
                ->get();
            $statusAbsensiSiswa[$j->id] = $records->isNotEmpty();
            if ($records->isNotEmpty()) {
                $rekapAbsensiHariIni[$j->id] = [
                    'hadir' => $records->where('status', 'Hadir')->count(),
                    'sakit' => $records->where('status', 'Sakit')->count(),
                    'izin' => $records->where('status', 'Izin')->count(),
                    'alpa' => $records->where('status', 'Alpa')->count(),
                    'total' => $records->count(),
                ];
                $detailAbsensiTersimpan[$j->id] = $records->keyBy('id_siswa');
            }
        }

        $absensiHariIniList = AbsensiGuru::where('tanggal', $tanggalHariIni)->get()->keyBy('id_guru');
        $guruBelumAbsen = $daftarGuru->filter(function ($g) use ($absensiHariIniList) {
            return !isset($absensiHariIniList[$g->id]);
        });

        $jadwalHariIniSemua = Jadwal::with(['kelas', 'mapel', 'ruangan'])
            ->where('hari', $namaHariIni)
            ->orderBy('jam_ke_mulai')
            ->get()
            ->groupBy('id_guru');

        $hariMatriks = $request->get('hari', in_array($namaHariIni, ['Sabtu', 'Minggu']) ? 'Senin' : $namaHariIni);
        $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $slotJam = JamPelajaran::slotHari($hariMatriks);
        $jadwalMatriks = Jadwal::with(['mapel', 'guru', 'ruangan', 'kelas'])
            ->where('hari', $hariMatriks)
            ->get();

        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 4);

        $riwayatGuru = AbsensiGuru::with(['guru', 'userInput'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->paginate(15, ['*'], 'halaman_guru')
            ->withQueryString();

        $rekapGuru = [];
        foreach ($daftarGuru as $g) {
            $absList = AbsensiGuru::where('id_guru', $g->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absList->where('status', 'Hadir')->count();
            $terlambat = $absList->where('status', 'Terlambat')->count();
            $sakit = $absList->where('status', 'Sakit')->count();
            $izin = $absList->where('status', 'Izin')->count();
            $alpa = $absList->where('status', 'Alpa')->count();
            $total = $hadir + $terlambat + $sakit + $izin + $alpa;

            $rekapGuru[] = [
                'guru' => $g,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'total' => $total,
            ];
        }

        $daftarMapel = Mapel::orderBy('nama')->get();
        $mapelId = $request->get('mapel_id');
        $kelasTerpilih = $kelasAktif;
        $rekapSiswa = [];

        if ($kelasTerpilih) {
            $siswaList = Siswa::where('id_kelas', $kelasTerpilih->id)->orderBy('nama')->get();
            foreach ($siswaList as $s) {
                $absensiSiswaQuery = AbsensiSiswa::where('id_siswa', $s->id)
                    ->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);

                if ($mapelId) {
                    $absensiSiswaQuery->whereHas('jadwal', function ($q) use ($mapelId) {
                        $q->where('id_mapel', $mapelId);
                    });
                }

                $records = $absensiSiswaQuery->get();
                $hadir = $records->where('status', 'Hadir')->count();
                $sakit = $records->where('status', 'Sakit')->count();
                $izin = $records->where('status', 'Izin')->count();
                $alpa = $records->where('status', 'Alpa')->count();
                $totalPertemuan = $hadir + $sakit + $izin + $alpa;
                $persentase = $totalPertemuan > 0 ? round(($hadir / $totalPertemuan) * 100) : 0;

                $rekapSiswa[] = [
                    'siswa' => $s,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alpa' => $alpa,
                    'total' => $totalPertemuan,
                    'persentase' => $persentase,
                ];
            }
        }

        return view('sekretaris.dashboard', compact(
            'tabAktif',
            'namaHariIni',
            'tanggalHariIni',
            'kelasAktif',
            'daftarSiswaKelas',
            'jadwalKelasHariIni',
            'jadwalKelasMingguan',
            'statusAbsensiSiswa',
            'rekapAbsensiHariIni',
            'detailAbsensiTersimpan',
            'daftarGuru',
            'absensiHariIniList',
            'guruBelumAbsen',
            'jadwalHariIniSemua',
            'hariMatriks',
            'daftarHari',
            'slotJam',
            'daftarKelas',
            'jadwalMatriks',
            'bulan',
            'tahun',
            'daftarBulan',
            'daftarTahun',
            'riwayatGuru',
            'rekapGuru',
            'daftarMapel',
            'kelasId',
            'mapelId',
            'kelasTerpilih',
            'rekapSiswa'
        ));
    }

    public function absensiSiswa($id)
    {
        $jadwal = Jadwal::with(['kelas.siswa', 'mapel', 'ruangan', 'guru'])->findOrFail($id);

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
            return redirect()->route('sekretaris.dashboard', ['tab' => 'jadwal'])
                ->with('error', "Jadwal ini berlangsung pada hari {$jadwal->hari}. Absensi hanya dapat diisi pada hari jadwal berlangsung.");
        }

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $daftarSiswa = $jadwal->kelas ? $jadwal->kelas->siswa()->orderBy('nama')->get() : collect();

        $absensiTersimpan = AbsensiSiswa::where('id_jadwal', $jadwal->id)
            ->where('tanggal', $tanggalHariIni)
            ->get()
            ->keyBy('id_siswa');

        $absensiGuruHariIni = $jadwal->id_guru ? AbsensiGuru::where('id_guru', $jadwal->id_guru)
            ->where('tanggal', $tanggalHariIni)
            ->first() : null;

        $daftarSlotJam = range($jadwal->jam_ke_mulai, $jadwal->jam_ke_selesai);

        return view('sekretaris.absensi-siswa', compact(
            'jadwal',
            'daftarSiswa',
            'absensiTersimpan',
            'tanggalHariIni',
            'absensiGuruHariIni',
            'daftarSlotJam'
        ));
    }

    public function simpanAbsensiSiswa(Request $request, $id)
    {
        $jadwal = Jadwal::with(['kelas.siswa', 'mapel', 'guru'])->findOrFail($id);

        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:Hadir,Sakit,Izin,Alpa',
            'keterangan' => 'nullable|array',
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
                    'id_guru_pengisi' => $jadwal->id_guru,
                ]
            );
        }

        if ($request->filled('kehadiran_guru') && $jadwal->id_guru) {
            $statusGuru = $request->kehadiran_guru === 'Hadir' ? 'Hadir' : 'Tidak Hadir';
            $alasan = $request->alasan_guru;
            if ($request->filled('keterangan_guru')) {
                $alasan = ($alasan ? "{$alasan} - " : "") . $request->keterangan_guru;
            }
            AbsensiGuru::updateOrCreate(
                [
                    'id_guru' => $jadwal->id_guru,
                    'tanggal' => $tanggalHariIni,
                ],
                [
                    'status' => $statusGuru,
                    'metode' => 'Sekretaris',
                    'jam_masuk' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
                    'alasan' => $alasan,
                    'id_user_input' => auth()->id(),
                ]
            );
        }

        LogAktivitas::catat('Absensi Siswa oleh Sekretaris', "Mencatat absensi siswa kelas {$jadwal->kelas->nama} mapel {$jadwal->mapel->nama}");

        return redirect()->route('sekretaris.dashboard', [
            'tab' => 'jadwal',
            'kelas_id' => $jadwal->id_kelas,
        ])->with('sukses', "Presensi siswa kelas {$jadwal->kelas->nama} untuk mata pelajaran {$jadwal->mapel->nama} berhasil disimpan!");
    }
}

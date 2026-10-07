<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JamPelajaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function dapatkanDataDasar(Request $request)
    {
        $guru = Guru::where('user_id', auth()->id())->first();

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
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        $daftarHariUrutan = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5];

        $jamKeMapPerHari = JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));

        $formatRentangWaktu = function ($hari, $mulaiKe, $selesaiKe) use ($jamKeMapPerHari) {
            $jamKeMap = $jamKeMapPerHari->get($hari, collect());
            $mulai = $jamKeMap[$mulaiKe]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($mulaiKe - 1)));
            $selesai = $jamKeMap[$selesaiKe]->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($selesaiKe - 1)));
            return [$mulai, $selesai, "{$mulai} - {$selesai}"];
        };

        $semuaJadwal = collect();
        $jadwalHariIni = collect();
        $statusAbsensiSiswa = [];
        $absensiHariIni = null;
        $rekapAbsensi = null;
        $jadwalBerikutnya = null;
        $jumlahSesiHariIni = 0;
        $jumlahBelumDiisi = 0;
        $jadwalMingguanPerHari = collect();

        if ($guru) {
            $semuaJadwal = Jadwal::where('id_guru', $guru->id)
                ->with(['kelas', 'mapel', 'ruangan'])
                ->get()
                ->sortBy(function ($item) use ($daftarHariUrutan) {
                    return ($daftarHariUrutan[$item->hari] ?? 99) * 100 + $item->jam_ke_mulai;
                });

            foreach ($semuaJadwal as $j) {
                [$mulai, $selesai, $rentang] = $formatRentangWaktu($j->hari, $j->jam_ke_mulai, $j->jam_ke_selesai);
                $j->jam_mulai_formatted = $mulai;
                $j->jam_selesai_formatted = $selesai;
                $j->rentang_waktu = $rentang;
            }

            $jadwalHariIni = Jadwal::where('id_guru', $guru->id)
                ->where('hari', $hariIni)
                ->with(['kelas', 'mapel', 'ruangan'])
                ->orderBy('jam_ke_mulai')
                ->get();

            foreach ($jadwalHariIni as $j) {
                [$mulai, $selesai, $rentang] = $formatRentangWaktu($j->hari, $j->jam_ke_mulai, $j->jam_ke_selesai);
                $j->jam_mulai_formatted = $mulai;
                $j->jam_selesai_formatted = $selesai;
                $j->rentang_waktu = $rentang;

                if ($nowTime >= $mulai && $nowTime <= $selesai) {
                    $j->status_waktu = 'berlangsung';
                } elseif ($nowTime < $mulai) {
                    $j->status_waktu = 'segera';
                } else {
                    $j->status_waktu = 'selesai';
                }

                $sudah = AbsensiSiswa::where('id_jadwal', $j->id)
                    ->where('tanggal', $tanggalHariIni)
                    ->exists();

                $statusAbsensiSiswa[$j->id] = $sudah;
                $j->sudah_diisi = $sudah;
            }

            $absensiHariIni = AbsensiGuru::where('id_guru', $guru->id)
                ->where('tanggal', $tanggalHariIni)
                ->first();

            $queryRekap = AbsensiGuru::where('id_guru', $guru->id)->latest('tanggal');
            if ($request->filled('bulan')) {
                $queryRekap->whereMonth('tanggal', $request->bulan);
            }
            if ($request->filled('tahun')) {
                $queryRekap->whereYear('tanggal', $request->tahun);
            }
            $rekapAbsensi = $queryRekap->paginate(15)->withQueryString();

            $jadwalBerikutnya = $jadwalHariIni->firstWhere('status_waktu', 'berlangsung')
                ?? $jadwalHariIni->firstWhere('status_waktu', 'segera');

            $jumlahSesiHariIni = $jadwalHariIni->count();
            $jumlahBelumDiisi = $jadwalHariIni->where('sudah_diisi', false)->count();

            $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            foreach ($daftarHari as $dHari) {
                $jadwalMingguanPerHari[$dHari] = $semuaJadwal->where('hari', $dHari)->values();
            }
        }

        $daftarKelas = $semuaJadwal->pluck('kelas')->filter()->unique('id');
        $daftarMapel = $semuaJadwal->pluck('mapel')->filter()->unique('id');

        return compact(
            'guru',
            'hariIni',
            'tanggalHariIni',
            'semuaJadwal',
            'jadwalHariIni',
            'statusAbsensiSiswa',
            'absensiHariIni',
            'rekapAbsensi',
            'daftarKelas',
            'daftarMapel',
            'jadwalBerikutnya',
            'jumlahSesiHariIni',
            'jumlahBelumDiisi',
            'jadwalMingguanPerHari'
        );
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab');
        if ($tab === 'validasi') {
            return $this->validasi($request);
        }
        if ($tab === 'rekap') {
            return $this->rekap($request);
        }
        if ($tab === 'absensi-saya') {
            return $this->absensiSaya($request);
        }

        return $this->jadwal($request);
    }

    public function jadwal(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        return view('guru.jadwal.index', $data);
    }

    public function validasi(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        $guru = $data['guru'];

        $daftarValidasi = collect();
        if ($guru) {
            $semuaJadwalGuruIds = Jadwal::where('id_guru', $guru->id)->pluck('id');

            // Ambil data absensi siswa yang berstatus menunggu_validasi
            $absensiMenunggu = AbsensiSiswa::whereIn('id_jadwal', $semuaJadwalGuruIds)
                ->where('status_validasi', 'menunggu_validasi')
                ->with(['jadwal.kelas', 'jadwal.mapel', 'siswa'])
                ->get()
                ->groupBy(function ($item) {
                    return $item->id_jadwal . '_' . $item->tanggal;
                });

            foreach ($absensiMenunggu as $key => $items) {
                $first = $items->first();
                $j = $first->jadwal;
                $daftarValidasi->push([
                    'id_jadwal' => $j->id,
                    'tanggal' => $first->tanggal,
                    'tanggal_formatted' => Carbon::parse($first->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y'),
                    'kelas' => $j->kelas->nama ?? 'Kelas',
                    'mapel' => $j->mapel->nama ?? 'Mapel',
                    'jam' => "Jam ke {$j->jam_ke_mulai} - {$j->jam_ke_selesai}",
                    'total_siswa' => $items->count(),
                    'hadir' => $items->where('status', 'Hadir')->count(),
                    'sakit' => $items->where('status', 'Sakit')->count(),
                    'izin' => $items->where('status', 'Izin')->count(),
                    'alpa' => $items->where('status', 'Alpa')->count(),
                    'students' => $items->map(function ($ab) {
                        return [
                            'nis' => $ab->siswa->nis ?? '-',
                            'name' => $ab->siswa->nama ?? 'Siswa',
                            'status' => $ab->status,
                            'keterangan' => $ab->keterangan ?? '-',
                        ];
                    })->values()->toArray(),
                ]);
            }
        }

        $data['daftarValidasi'] = $daftarValidasi;
        return view('guru.validasi.index', $data);
    }

    public function setujuiValidasi(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
        ]);

        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $jadwal = Jadwal::with(['kelas', 'mapel'])->where('id', $request->id_jadwal)->where('id_guru', $guru->id)->firstOrFail();

        AbsensiSiswa::where('id_jadwal', $jadwal->id)
            ->where('tanggal', $request->tanggal)
            ->where('status_validasi', 'menunggu_validasi')
            ->update([
                'status_validasi' => 'disetujui',
                'id_guru_pengisi' => $guru->id,
            ]);

        \App\Models\LogAktivitas::catat('Validasi Absensi Siswa', "Guru {$guru->nama} menyetujui absensi kelas {$jadwal->kelas->nama} ({$jadwal->mapel->nama}) untuk tanggal {$request->tanggal}");

        return redirect()->route('guru.validasi')
            ->with('sukses', "Absensi kelas {$jadwal->kelas->nama} ({$jadwal->mapel->nama}) berhasil disetujui dan disahkan.");
    }

    public function tolakValidasi(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
        ]);

        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $jadwal = Jadwal::with(['kelas', 'mapel'])->where('id', $request->id_jadwal)->where('id_guru', $guru->id)->firstOrFail();

        AbsensiSiswa::where('id_jadwal', $jadwal->id)
            ->where('tanggal', $request->tanggal)
            ->where('status_validasi', 'menunggu_validasi')
            ->delete();

        \App\Models\LogAktivitas::catat('Tolak Absensi Siswa', "Guru {$guru->nama} menolak laporan absensi kelas {$jadwal->kelas->nama} ({$jadwal->mapel->nama}) tanggal {$request->tanggal}");

        return redirect()->route('guru.validasi')
            ->with('sukses', "Laporan absensi kelas {$jadwal->kelas->nama} telah ditolak dan dihapus.");
    }

    public function rekap(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        $guru = $data['guru'];

        $daftarKelas = $data['daftarKelas'];
        $daftarMapel = $data['daftarMapel'];

        $rentang = $request->get('rentang', '1 Bulan');
        $idKelas = $request->get('id_kelas');
        $idMapel = $request->get('id_mapel');

        $tanggalMulai = match ($rentang) {
            '1 Semester' => Carbon::now('Asia/Jakarta')->subMonths(6)->startOfDay(),
            '1 Tahun' => Carbon::now('Asia/Jakarta')->subYear()->startOfDay(),
            default => Carbon::now('Asia/Jakarta')->subMonth()->startOfDay(),
        };

        $totalAbsensiSiswa = 0;
        $totalHadirSiswa = 0;
        $rasioKehadiranSiswa = 0;
        $daftarRekapSiswa = collect();

        if ($guru) {
            $jadwalIds = Jadwal::where('id_guru', $guru->id)
                ->when($idKelas, fn($q) => $q->where('id_kelas', $idKelas))
                ->when($idMapel, fn($q) => $q->where('id_mapel', $idMapel))
                ->pluck('id');

            $absensiQuery = AbsensiSiswa::whereIn('id_jadwal', $jadwalIds)
                ->where('tanggal', '>=', $tanggalMulai->format('Y-m-d'))
                ->where(function ($q) {
                    $q->where('status_validasi', 'disetujui')
                      ->orWhereNull('status_validasi');
                });

            $allAbsensi = (clone $absensiQuery)->with(['siswa', 'jadwal.kelas', 'jadwal.mapel'])->get();

            $totalAbsensiSiswa = $allAbsensi->count();
            $totalHadirSiswa = $allAbsensi->where('status', 'Hadir')->count();
            $rasioKehadiranSiswa = $totalAbsensiSiswa > 0 ? round(($totalHadirSiswa / $totalAbsensiSiswa) * 100) : 0;

            $daftarRekapSiswa = $allAbsensi->groupBy(function ($item) {
                return $item->id_jadwal . '_' . $item->tanggal;
            })->map(function ($items) {
                $first = $items->first();
                return [
                    'tanggal' => $first->tanggal,
                    'kelas' => $first->jadwal->kelas->nama ?? '-',
                    'mapel' => $first->jadwal->mapel->nama ?? '-',
                    'total' => $items->count(),
                    'hadir' => $items->where('status', 'Hadir')->count(),
                    'sakit' => $items->where('status', 'Sakit')->count(),
                    'izin' => $items->where('status', 'Izin')->count(),
                    'alpa' => $items->where('status', 'Alpa')->count(),
                ];
            })->values();
        }

        $data['rentang'] = $rentang;
        $data['idKelasPilihan'] = $idKelas;
        $data['idMapelPilihan'] = $idMapel;
        $data['totalAbsensiSiswa'] = $totalAbsensiSiswa;
        $data['totalHadirSiswa'] = $totalHadirSiswa;
        $data['rasioKehadiranSiswa'] = $rasioKehadiranSiswa;
        $data['daftarRekapSiswa'] = $daftarRekapSiswa;

        return view('guru.rekap.index', $data);
    }

    public function absensiSaya(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        return view('guru.absensi-saya.index', $data);
    }
}

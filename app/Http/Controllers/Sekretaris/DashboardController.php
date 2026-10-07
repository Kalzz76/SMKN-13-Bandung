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
        if ($request->get('tab') === 'laporan') {
            return redirect()->route('sekretaris.rekap-presensi', $request->except('tab'));
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
        $namaHariIni = $hariIniMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        $jamKeMapPerHari = JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));

        $formatRentangWaktu = function ($hari, $mulaiKe, $selesaiKe) use ($jamKeMapPerHari) {
            $jamKeMap = $jamKeMapPerHari->get($hari, collect());
            $mulai = $jamKeMap[$mulaiKe]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($mulaiKe - 1)));
            $selesai = $jamKeMap[$selesaiKe]->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($selesaiKe - 1)));
            return [$mulai, $selesai, "{$mulai} - {$selesai}"];
        };

        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();

        $user = auth()->user();
        $siswaUser = Siswa::with('kelas')->where('nama', $user->name)->first();
        $kelasAktif = $siswaUser ? $siswaUser->kelas : ($daftarKelas->where('nama', 'XI RPL 1')->first() ?? $daftarKelas->first());

        $daftarSiswaKelas = $kelasAktif ? Siswa::where('id_kelas', $kelasAktif->id)->orderBy('nama')->get() : collect();

        $jadwalKelasHariIniRaw = $kelasAktif ? Jadwal::with(['mapel', 'guru', 'ruangan'])
            ->where('id_kelas', $kelasAktif->id)
            ->where('hari', $namaHariIni)
            ->orderBy('jam_ke_mulai')
            ->get() : collect();

        $jadwalKelasHariIni = $this->gabungkanJadwalSama($jadwalKelasHariIniRaw, $formatRentangWaktu);

        foreach ($jadwalKelasHariIni as $j) {
            if ($nowTime >= $j->jam_mulai_formatted && $nowTime <= $j->jam_selesai_formatted) {
                $j->status_waktu = 'berlangsung';
            } elseif ($nowTime < $j->jam_mulai_formatted) {
                $j->status_waktu = 'segera';
            } else {
                $j->status_waktu = 'selesai';
            }
        }

        if ($kelasAktif) {
            $semuaJadwalMingguanRaw = Jadwal::with(['mapel', 'guru', 'ruangan'])
                ->where('id_kelas', $kelasAktif->id)
                ->orderBy('jam_ke_mulai')
                ->get();

            $jadwalKelasMingguan = collect();
            foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari) {
                $jadwalHariIniList = $semuaJadwalMingguanRaw->where('hari', $hari);
                $jadwalKelasMingguan[$hari] = $this->gabungkanJadwalSama($jadwalHariIniList, $formatRentangWaktu);
            }
        } else {
            $jadwalKelasMingguan = collect();
        }

        $statusAbsensiSiswa = [];
        $infoAbsensiSiswa = [];
        $rekapAbsensiHariIni = [];
        $detailAbsensiTersimpan = [];
        foreach ($jadwalKelasHariIni as $j) {
            $records = AbsensiSiswa::whereIn('id_jadwal', $j->id_jadwal_list)
                ->where('tanggal', $tanggalHariIni)
                ->get();

            $statusAbsensiSiswa[$j->id] = $records->isNotEmpty();

            $diisiGuru = $records->where('diisi_oleh', 'guru')->isNotEmpty();
            $diisiSekre = $records->where('diisi_oleh', 'sekretaris')->isNotEmpty();
            $statusValidasi = $records->first()?->status_validasi;

            $infoAbsensiSiswa[$j->id] = [
                'ada' => $records->isNotEmpty(),
                'diisi_guru' => $diisiGuru,
                'diisi_sekre' => $diisiSekre,
                'status_validasi' => $statusValidasi,
            ];

            if ($records->isNotEmpty()) {
                // Ambil unik per siswa agar jika terdapat multi-jadwal gabungan, hitungan tidak terduplikasi
                $uniqueRecords = $records->unique('id_siswa');
                $rekapAbsensiHariIni[$j->id] = [
                    'hadir' => $uniqueRecords->where('status', 'Hadir')->count(),
                    'sakit' => $uniqueRecords->where('status', 'Sakit')->count(),
                    'izin' => $uniqueRecords->where('status', 'Izin')->count(),
                    'alpa' => $uniqueRecords->where('status', 'Alpa')->count(),
                    'total' => $uniqueRecords->count(),
                ];
                $detailAbsensiTersimpan[$j->id] = $uniqueRecords->keyBy('id_siswa');
            }
        }

        return view('sekretaris.dashboard', compact(
            'namaHariIni',
            'tanggalHariIni',
            'kelasAktif',
            'daftarSiswaKelas',
            'jadwalKelasHariIni',
            'jadwalKelasMingguan',
            'statusAbsensiSiswa',
            'infoAbsensiSiswa',
            'rekapAbsensiHariIni',
            'detailAbsensiTersimpan'
        ));
    }

    public function rekapPresensi(Request $request)
    {
        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();

        $user = auth()->user();
        $siswaUser = Siswa::with('kelas')->where('nama', $user->name)->first();
        $kelasAktif = $siswaUser ? $siswaUser->kelas : ($daftarKelas->where('nama', 'XI RPL 1')->first() ?? $daftarKelas->first());

        if ($request->filled('kelas_id')) {
            $kelasPilihan = Kelas::find($request->kelas_id);
            if ($kelasPilihan) {
                $kelasAktif = $kelasPilihan;
            }
        }

        $daftarMapel = collect();
        if ($kelasAktif) {
            $mapelIds = Jadwal::where('id_kelas', $kelasAktif->id)->pluck('id_mapel')->unique();
            $daftarMapel = Mapel::whereIn('id', $mapelIds)->orderBy('nama')->get();
        }

        $tanggalMulai = $request->get('tanggal_mulai', Carbon::now('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now('Asia/Jakarta')->format('Y-m-d'));
        $idMapelPilihan = $request->get('id_mapel');

        $rekapSiswa = [];
        if ($kelasAktif) {
            $siswaList = Siswa::where('id_kelas', $kelasAktif->id)->orderBy('nama')->get();
            foreach ($siswaList as $s) {
                $query = AbsensiSiswa::where('id_siswa', $s->id)
                    ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                    ->where(function ($q) {
                        $q->where('status_validasi', 'disetujui')
                          ->orWhereNull('status_validasi');
                    });

                if ($idMapelPilihan) {
                    $query->whereHas('jadwal', fn($q) => $q->where('id_mapel', $idMapelPilihan));
                }

                $records = $query->get();

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

        // Fitur Export Excel / CSV
        if ($request->get('export') === 'excel') {
            $mapelNama = 'Semua Mapel';
            if ($idMapelPilihan) {
                $m = $daftarMapel->firstWhere('id', $idMapelPilihan);
                if ($m) $mapelNama = $m->nama;
            }

            $namaFile = "Rekap_Presensi_{$kelasAktif->nama}_{$tanggalMulai}_sd_{$tanggalSelesai}.csv";

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$namaFile}\"",
            ];

            $callback = function () use ($rekapSiswa, $kelasAktif, $mapelNama, $tanggalMulai, $tanggalSelesai) {
                $handle = fopen('php://output', 'w');
                // UTF-8 BOM untuk kompatibilitas Microsoft Excel
                fputs($handle, "\xEF\xBB\xBF");

                fputcsv($handle, ['REKAP PRESENSI SISWA SMKN 13 BANDUNG']);
                fputcsv($handle, ['Kelas', $kelasAktif->nama ?? '-']);
                fputcsv($handle, ['Mata Pelajaran', $mapelNama]);
                fputcsv($handle, ['Periode', "{$tanggalMulai} s/d {$tanggalSelesai}"]);
                fputcsv($handle, []);
                fputcsv($handle, ['No', 'NIS', 'Nama Siswa', 'Hadir', 'Sakit', 'Izin', 'Alpa', 'Total Pertemuan', 'Persentase Kehadiran (%)']);

                foreach ($rekapSiswa as $idx => $r) {
                    fputcsv($handle, [
                        $idx + 1,
                        $r['siswa']->nis ?? '-',
                        $r['siswa']->nama,
                        $r['hadir'],
                        $r['sakit'],
                        $r['izin'],
                        $r['alpa'],
                        $r['total'],
                        $r['persentase'] . '%',
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('sekretaris.rekap-presensi', compact(
            'kelasAktif',
            'daftarKelas',
            'siswaUser',
            'daftarMapel',
            'tanggalMulai',
            'tanggalSelesai',
            'idMapelPilihan',
            'rekapSiswa'
        ));
    }

    private function gabungkanJadwalSama($jadwals, $formatRentangWaktu)
    {
        if ($jadwals->isEmpty()) {
            return collect();
        }

        // Kelompokkan jadwal berdasarkan kombinasi kelas, hari, mapel, dan guru
        $grouped = $jadwals->groupBy(function ($j) {
            return $j->id_kelas . '_' . $j->hari . '_' . $j->id_mapel . '_' . $j->id_guru;
        });

        $hasil = collect();
        foreach ($grouped as $items) {
            $utama = clone $items->first();
            $minJam = $items->min('jam_ke_mulai');
            $maxJam = $items->max('jam_ke_selesai');

            $utama->id_jadwal_list = $items->pluck('id')->all();
            $utama->jam_ke_mulai = $minJam;
            $utama->jam_ke_selesai = $maxJam;

            [$mulai, $selesai, $rentang] = $formatRentangWaktu($utama->hari, $minJam, $maxJam);
            $utama->jam_mulai_formatted = $mulai;
            $utama->jam_selesai_formatted = $selesai;
            $utama->rentang_waktu = $rentang;

            if ($minJam == $maxJam) {
                $utama->jam_ke_label = "Jam Ke {$minJam}";
                $utama->jam_ke_label_singkat = "Jam {$minJam}";
            } else {
                $utama->jam_ke_label = "Jam Ke {$minJam} - {$maxJam}";
                $utama->jam_ke_label_singkat = "Jam {$minJam}-{$maxJam}";
            }

            $hasil->push($utama);
        }

        return $hasil->sortBy('jam_ke_mulai')->values();
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
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Jadwal ini berlangsung pada hari {$jadwal->hari}. Absensi hanya dapat diisi pada hari jadwal berlangsung.");
        }

        // Cari semua jadwal serumpun yang digabung dalam sesi mata pelajaran ini
        $relatedJadwals = Jadwal::where('id_kelas', $jadwal->id_kelas)
            ->where('hari', $jadwal->hari)
            ->where('id_mapel', $jadwal->id_mapel)
            ->where('id_guru', $jadwal->id_guru)
            ->orderBy('jam_ke_mulai')
            ->get();
        $relatedIds = $relatedJadwals->pluck('id')->all();

        $minJam = $relatedJadwals->min('jam_ke_mulai') ?? $jadwal->jam_ke_mulai;
        $maxJam = $relatedJadwals->max('jam_ke_selesai') ?? $jadwal->jam_ke_selesai;
        $jadwal->jam_ke_mulai = $minJam;
        $jadwal->jam_ke_selesai = $maxJam;

        // Validasi waktu KBM: belum masuk jam KBM tidak bisa diisi
        $jamKeMapPerHari = JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));
        $jamKeMap = $jamKeMapPerHari->get($jadwal->hari, collect());
        $jamMulai = $jamKeMap[$minJam]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($minJam - 1)));
        $jamSelesai = $jamKeMap[$maxJam]->jam_selesai ?? sprintf('%02d:45', max(7, 7 + ($maxJam - 1)));
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        if ($nowTime < $jamMulai) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Waktu KBM untuk {$jadwal->mapel->nama} belum dimulai (jadwal mulai pukul {$jamMulai}). Absensi hanya dapat diisi saat jam pelajaran telah masuk.");
        }

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Validasi: jika guru sudah mengisi salah satu jadwal serumpun, sekretaris dilarang isi ulang
        $sudahDiisiGuru = AbsensiSiswa::whereIn('id_jadwal', $relatedIds)
            ->where('tanggal', $tanggalHariIni)
            ->where('diisi_oleh', 'guru')
            ->exists();

        if ($sudahDiisiGuru) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Absensi untuk mata pelajaran {$jadwal->mapel->nama} sudah tercatat oleh Guru pengajar.");
        }

        $daftarSiswa = $jadwal->kelas ? $jadwal->kelas->siswa()->orderBy('nama')->get() : collect();

        // Ambil data absensi tersimpan dari jadwal serumpun (jika ada)
        $absensiTersimpan = AbsensiSiswa::whereIn('id_jadwal', $relatedIds)
            ->where('tanggal', $tanggalHariIni)
            ->get()
            ->keyBy('id_siswa');

        $daftarSlotJam = range($minJam, $maxJam);
        $jamKeLabel = ($minJam == $maxJam) ? "Jam ke {$minJam}" : "Jam ke {$minJam} - {$maxJam}";
        $rentangWaktu = "{$jamMulai} - {$jamSelesai}";

        return view('sekretaris.absensi-siswa', compact(
            'jadwal',
            'daftarSiswa',
            'absensiTersimpan',
            'tanggalHariIni',
            'daftarSlotJam',
            'jamKeLabel',
            'rentangWaktu'
        ));
    }

    public function simpanAbsensiSiswa(Request $request, $id)
    {
        $jadwal = Jadwal::with(['kelas.siswa', 'mapel', 'guru'])->findOrFail($id);

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

        if (!app()->environment('testing') && $jadwal->hari !== $hariIni) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Jadwal ini berlangsung pada hari {$jadwal->hari}. Absensi hanya dapat diisi pada hari jadwal berlangsung.");
        }

        $relatedJadwals = Jadwal::where('id_kelas', $jadwal->id_kelas)
            ->where('hari', $jadwal->hari)
            ->where('id_mapel', $jadwal->id_mapel)
            ->where('id_guru', $jadwal->id_guru)
            ->get();
        $relatedIds = $relatedJadwals->pluck('id')->all();

        $minJam = $relatedJadwals->min('jam_ke_mulai') ?? $jadwal->jam_ke_mulai;

        $jamKeMapPerHari = JamPelajaran::pelajaran()->urut()->get()
            ->groupBy('hari')
            ->map(fn ($slots) => $slots->keyBy('jam_ke'));
        $jamKeMap = $jamKeMapPerHari->get($jadwal->hari, collect());
        $jamMulai = $jamKeMap[$minJam]->jam_mulai ?? sprintf('%02d:00', max(7, 7 + ($minJam - 1)));
        $nowTime = Carbon::now('Asia/Jakarta')->format('H:i');

        if (!app()->environment('testing') && $nowTime < $jamMulai) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Waktu KBM untuk {$jadwal->mapel->nama} belum dimulai (jadwal mulai pukul {$jamMulai}). Absensi hanya dapat diisi saat jam pelajaran telah masuk.");
        }

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        // Validasi ganda: pastikan belum diisi oleh guru
        $sudahDiisiGuru = AbsensiSiswa::whereIn('id_jadwal', $relatedIds)
            ->where('tanggal', $tanggalHariIni)
            ->where('diisi_oleh', 'guru')
            ->exists();

        if ($sudahDiisiGuru) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', "Absensi untuk mata pelajaran {$jadwal->mapel->nama} sudah tercatat oleh Guru pengajar.");
        }

        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:Hadir,Sakit,Izin,Alpa',
            'keterangan' => 'nullable|array',
        ]);

        // Simpan absensi siswa untuk SETIAP id_jadwal dalam kelompok gabungan
        foreach ($relatedIds as $targetJadwalId) {
            foreach ($request->status as $siswaId => $statusKehadiran) {
                $ket = $request->keterangan[$siswaId] ?? null;

                AbsensiSiswa::updateOrCreate(
                    [
                        'id_jadwal' => $targetJadwalId,
                        'id_siswa' => $siswaId,
                        'tanggal' => $tanggalHariIni,
                    ],
                    [
                        'status' => $statusKehadiran,
                        'keterangan' => $ket,
                        'id_guru_pengisi' => $jadwal->id_guru,
                        'status_validasi' => 'menunggu_validasi',
                        'diisi_oleh' => 'sekretaris',
                    ]
                );
            }
        }

        LogAktivitas::catat('Absensi Siswa oleh Sekretaris', "Sekretaris mengisi absensi siswa kelas {$jadwal->kelas->nama} mapel {$jadwal->mapel->nama} (menunggu validasi guru)");

        return redirect()->route('sekretaris.dashboard', [
            'kelas_id' => $jadwal->id_kelas,
        ])->with('sukses', "Presensi siswa kelas {$jadwal->kelas->nama} ({$jadwal->mapel->nama}) berhasil dikirim dan menunggu validasi dari guru pengajar.");
    }
}

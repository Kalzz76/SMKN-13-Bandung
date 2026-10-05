<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'guru');
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $daftarTahun = range(Carbon::now()->year, Carbon::now()->year - 4);

        $queryGuru = AbsensiGuru::with(['guru', 'userInput'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc');

        if ($request->filled('guru_id')) {
            $queryGuru->where('id_guru', $request->guru_id);
        }

        $riwayatGuru = $queryGuru->paginate(15, ['*'], 'halaman_guru')->withQueryString();

        $semuaGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $rekapGuru = [];
        foreach ($semuaGuru as $g) {
            $absensiList = AbsensiGuru::where('id_guru', $g->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absensiList->where('status', 'Hadir')->count();
            $terlambat = $absensiList->where('status', 'Terlambat')->count();
            $sakit = $absensiList->where('status', 'Sakit')->count();
            $izin = $absensiList->where('status', 'Izin')->count();
            $alpa = $absensiList->where('status', 'Alpa')->count();
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

        $daftarKelas = Kelas::orderBy('nama')->get();
        $daftarMapel = Mapel::orderBy('nama')->get();

        $kelasId = $request->get('kelas_id', $daftarKelas->first()->id ?? null);
        $mapelId = $request->get('mapel_id');

        $kelasTerpilih = $kelasId ? Kelas::find($kelasId) : null;
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

        return view('admin.laporan.index', compact(
            'tab',
            'bulan',
            'tahun',
            'daftarBulan',
            'daftarTahun',
            'semuaGuru',
            'riwayatGuru',
            'rekapGuru',
            'daftarKelas',
            'daftarMapel',
            'kelasId',
            'mapelId',
            'kelasTerpilih',
            'rekapSiswa'
        ));
    }

    public function exportGuruCsv(Request $request)
    {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);

        $daftarAbsensi = AbsensiGuru::with(['guru', 'userInput'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->get();

        $filename = "laporan_absensi_guru_{$tahun}_{$bulan}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($daftarAbsensi) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Tanggal', 'Jam Masuk', 'Nama Guru', 'NIP', 'Status', 'Metode', 'Keterangan/Lokasi', 'Pencatat']);

            foreach ($daftarAbsensi as $index => $row) {
                $lokasiKet = '';
                if ($row->metode === 'Scan') {
                    $jarak = $row->jarak_meter ? "{$row->jarak_meter} m" : '-';
                    $lokasiKet = "Lat: {$row->latitude}, Long: {$row->longitude} ({$jarak})";
                } else {
                    $lokasiKet = $row->alasan ?? '-';
                }

                $pencatat = $row->metode === 'Scan' ? 'Scan Barcode Mandiri' : ($row->userInput ? $row->userInput->name : 'Sekretaris');

                fputcsv($file, [
                    $index + 1,
                    $row->tanggal,
                    $row->jam_masuk ?? '-',
                    $row->guru ? $row->guru->nama : '-',
                    $row->guru ? $row->guru->nip : '-',
                    $row->status,
                    $row->metode,
                    $lokasiKet,
                    $pencatat
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetakGuru(Request $request)
    {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $daftarAbsensi = AbsensiGuru::with(['guru', 'userInput'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_masuk', 'asc')
            ->get();

        $semuaGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $rekapGuru = [];
        foreach ($semuaGuru as $g) {
            $absensiList = AbsensiGuru::where('id_guru', $g->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absensiList->where('status', 'Hadir')->count();
            $terlambat = $absensiList->where('status', 'Terlambat')->count();
            $sakit = $absensiList->where('status', 'Sakit')->count();
            $izin = $absensiList->where('status', 'Izin')->count();
            $alpa = $absensiList->where('status', 'Alpa')->count();
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

        return view('admin.laporan.cetak-guru', compact('daftarAbsensi', 'rekapGuru', 'bulan', 'tahun', 'daftarBulan'));
    }

    public function exportSiswaCsv(Request $request)
    {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $kelasId = $request->get('kelas_id');
        $mapelId = $request->get('mapel_id');

        $kelas = Kelas::findOrFail($kelasId);
        $mapel = $mapelId ? Mapel::find($mapelId) : null;

        $siswaList = Siswa::where('id_kelas', $kelas->id)->orderBy('nama')->get();

        $filename = "laporan_absensi_siswa_{$kelas->nama}_{$tahun}_{$bulan}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($siswaList, $bulan, $tahun, $mapelId, $kelas, $mapel) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['REKAPITULASI ABSENSI SISWA']);
            fputcsv($file, ['Kelas', $kelas->nama]);
            fputcsv($file, ['Periode', "Bulan {$bulan} / Tahun {$tahun}"]);
            if ($mapel) {
                fputcsv($file, ['Mata Pelajaran', $mapel->nama]);
            }
            fputcsv($file, []);
            fputcsv($file, ['No', 'NIS', 'Nama Siswa', 'L/P', 'Hadir', 'Sakit', 'Izin', 'Alpa', 'Total Pertemuan', 'Persentase Kehadiran']);

            foreach ($siswaList as $index => $s) {
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

                fputcsv($file, [
                    $index + 1,
                    $s->nis,
                    $s->nama,
                    $s->jenis_kelamin ?? '-',
                    $hadir,
                    $sakit,
                    $izin,
                    $alpa,
                    $totalPertemuan,
                    "{$persentase}%"
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function cetakSiswa(Request $request)
    {
        $bulan = (int) $request->get('bulan', Carbon::now()->month);
        $tahun = (int) $request->get('tahun', Carbon::now()->year);
        $kelasId = $request->get('kelas_id');
        $mapelId = $request->get('mapel_id');

        $daftarBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $kelas = Kelas::findOrFail($kelasId);
        $mapel = $mapelId ? Mapel::find($mapelId) : null;
        $siswaList = Siswa::where('id_kelas', $kelas->id)->orderBy('nama')->get();

        $rekapSiswa = [];
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

        return view('admin.laporan.cetak-siswa', compact(
            'kelas',
            'mapel',
            'bulan',
            'tahun',
            'daftarBulan',
            'rekapSiswa'
        ));
    }
}

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
        $tabAktif = $request->get('tab', 'pengganti');

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
        $slotJam = JamPelajaran::orderBy('urutan')->get();
        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();
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

        return view('sekretaris.dashboard', compact(
            'tabAktif',
            'namaHariIni',
            'tanggalHariIni',
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

    public function simpanAbsensiPengganti(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id',
            'status' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'alasan' => 'required|string',
        ]);

        $guru = Guru::findOrFail($request->id_guru);
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $jamSekarang = Carbon::now('Asia/Jakarta')->format('H:i:s');

        $absensiLama = AbsensiGuru::where('id_guru', $guru->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        if ($absensiLama && $absensiLama->metode === 'Scan') {
            return redirect()->route('sekretaris.dashboard', ['tab' => 'pengganti'])
                ->with('error', "Guru {$guru->nama} sudah melakukan absensi mandiri melalui scan barcode hari ini.");
        }

        AbsensiGuru::updateOrCreate(
            [
                'id_guru' => $guru->id,
                'tanggal' => $tanggalHariIni,
            ],
            [
                'jam_masuk' => $request->status === 'Hadir' ? $jamSekarang : null,
                'status' => $request->status,
                'metode' => 'Sekretaris',
                'alasan' => $request->alasan,
                'id_user_input' => auth()->id(),
                'latitude' => null,
                'longitude' => null,
                'jarak_meter' => null,
            ]
        );

        LogAktivitas::catat('Absensi Pengganti Sekretaris', "Mencatat absensi guru {$guru->nama} sebagai {$request->status} (Alasan: {$request->alasan})");

        return redirect()->route('sekretaris.dashboard', ['tab' => 'pengganti'])
            ->with('sukses', "Absensi pengganti untuk {$guru->nama} berhasil dicatat sebagai {$request->status}.");
    }
}

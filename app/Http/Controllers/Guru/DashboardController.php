<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\AbsensiSiswa;
use App\Models\Guru;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
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

        $daftarHariUrutan = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5];

        $semuaJadwal = collect();
        $jadwalHariIni = collect();
        $statusAbsensiSiswa = [];
        $absensiHariIni = null;
        $rekapAbsensi = null;

        if ($guru) {
            $semuaJadwal = Jadwal::where('id_guru', $guru->id)
                ->with(['kelas', 'mapel', 'ruangan'])
                ->get()
                ->sortBy(function ($item) use ($daftarHariUrutan) {
                    return ($daftarHariUrutan[$item->hari] ?? 99) * 100 + $item->jam_ke_mulai;
                });

            $jadwalHariIni = Jadwal::where('id_guru', $guru->id)
                ->where('hari', $hariIni)
                ->with(['kelas', 'mapel', 'ruangan'])
                ->orderBy('jam_ke_mulai')
                ->get();

            foreach ($jadwalHariIni as $j) {
                $statusAbsensiSiswa[$j->id] = AbsensiSiswa::where('id_jadwal', $j->id)
                    ->where('tanggal', $tanggalHariIni)
                    ->exists();
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
        }

        $tabAktif = $request->query('tab', 'validasi');

        return view('guru.dashboard', compact(
            'guru',
            'hariIni',
            'tanggalHariIni',
            'semuaJadwal',
            'jadwalHariIni',
            'statusAbsensiSiswa',
            'absensiHariIni',
            'rekapAbsensi',
            'tabAktif'
        ));
    }
}

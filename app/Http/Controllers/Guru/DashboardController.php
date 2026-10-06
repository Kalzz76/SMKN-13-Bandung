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
        return view('guru.validasi.index', $data);
    }

    public function rekap(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        return view('guru.rekap.index', $data);
    }

    public function absensiSaya(Request $request)
    {
        $data = $this->dapatkanDataDasar($request);
        return view('guru.absensi-saya.index', $data);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Berita;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $totalGuru = Guru::count();
        $totalSiswa = Siswa::count();
        $totalKelas = Kelas::count();
        $totalBerita = Berita::count();

        $absensiHariIni = AbsensiGuru::where('tanggal', $tanggalHariIni)->get();
        $guruHadirHariIni = $absensiHariIni->where('status', 'Hadir')->count();
        $guruTerlambatHariIni = $absensiHariIni->where('status', 'Terlambat')->count();
        $guruIzinSakitHariIni = $absensiHariIni->whereIn('status', ['Izin', 'Sakit', 'Alpa'])->count();

        $daftarGuruPengajar = Guru::where('jenis', 'Guru')->orderBy('nama')->get();
        $idGuruSudahAbsen = $absensiHariIni->pluck('id_guru')->toArray();
        $guruBelumAbsen = $daftarGuruPengajar->filter(function ($g) use ($idGuruSudahAbsen) {
            return !in_array($g->id, $idGuruSudahAbsen);
        });

        $beritaTerbaru = Berita::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa',
            'totalKelas',
            'totalBerita',
            'tanggalHariIni',
            'guruHadirHariIni',
            'guruTerlambatHariIni',
            'guruIzinSakitHariIni',
            'guruBelumAbsen',
            'beritaTerbaru'
        ));
    }
}

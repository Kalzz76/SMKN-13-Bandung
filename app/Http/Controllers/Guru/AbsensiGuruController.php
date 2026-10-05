<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\PengaturanSekolah;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiGuruController extends Controller
{
    public function hitungJarak($lat1, $long1, $lat2, $long2)
    {
        $radiusBumi = 6371000;
        $selisihLat = deg2rad($lat2 - $lat1);
        $selisihLong = deg2rad($long2 - $long1);
        $a = sin($selisihLat / 2) * sin($selisihLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($selisihLong / 2) * sin($selisihLong / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radiusBumi * $c;
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'kode_barcode' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
                ->with('error', 'Akun login Anda belum terhubung dengan data guru.');
        }

        if ($guru->kode_barcode !== $request->kode_barcode) {
            return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
                ->with('error', 'Kode barcode tidak valid atau bukan milik Anda.');
        }

        $tanggalHariIni = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $jamSekarang = Carbon::now('Asia/Jakarta')->format('H:i:s');

        $sudahAbsen = AbsensiGuru::where('id_guru', $guru->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        if ($sudahAbsen) {
            return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
                ->with('error', 'Anda sudah melakukan absensi kehadiran untuk hari ini.');
        }

        $pengaturan = PengaturanSekolah::first();
        $latSekolah = $pengaturan->lat_sekolah ?? -6.94580000;
        $longSekolah = $pengaturan->long_sekolah ?? 107.67650000;
        $radiusMeter = $pengaturan->radius_meter ?? 100;
        $jamMasuk = $pengaturan->jam_masuk ?? '07:00:00';

        $jarak = $this->hitungJarak($request->latitude, $request->longitude, $latSekolah, $longSekolah);

        if ($jarak > $radiusMeter) {
            $jarakBulat = round($jarak);
            return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
                ->with('error', "Lokasi Anda berada di luar radius sekolah ({$jarakBulat} meter dari kampus, batas toleransi {$radiusMeter} meter).");
        }

        $status = 'Hadir';
        if (strtotime($jamSekarang) > strtotime($jamMasuk)) {
            $status = 'Terlambat';
        }

        AbsensiGuru::create([
            'id_guru' => $guru->id,
            'tanggal' => $tanggalHariIni,
            'jam_masuk' => $jamSekarang,
            'status' => $status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'jarak_meter' => round($jarak),
            'metode' => 'Scan',
            'alasan' => null,
            'id_user_input' => auth()->id(),
        ]);

        LogAktivitas::catat('Absensi Mandiri', "Guru {$guru->nama} melakukan scan absensi ({$status}, jarak " . round($jarak) . "m)");

        return redirect()->route('guru.dashboard', ['tab' => 'validasi'])
            ->with('sukses', "Scan absensi berhasil dicatat! Status kehadiran: {$status}.");
    }
}

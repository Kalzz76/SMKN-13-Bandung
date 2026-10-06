<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\PengaturanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        $daftarGuru = Guru::with('mapel')->where('jenis', 'Guru')->orderBy('nama')->get();

        $guruTerpilih = null;
        if ($request->filled('guru_id')) {
            $guruTerpilih = Guru::with('mapel')->find($request->guru_id);
        } else {
            $guruTerpilih = $daftarGuru->firstWhere('kode_barcode', '!=', null) ?? $daftarGuru->first();
        }

        $pengaturan = PengaturanSekolah::first();

        return view('admin.barcode.index', compact('daftarGuru', 'guruTerpilih', 'pengaturan'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id',
        ]);

        $guru = Guru::findOrFail($request->id_guru);
        $guru->kode_barcode = Str::random(32);
        $guru->save();

        LogAktivitas::catat('Generate Barcode', "Membuat kode barcode baru untuk guru {$guru->nama}");

        return redirect()->route('admin.barcode.index', ['guru_id' => $guru->id])
            ->with('sukses', "Barcode absensi untuk {$guru->nama} berhasil dibuat.");
    }

    public function generateSemua()
    {
        $daftarGuru = Guru::where('jenis', 'Guru')->get();

        foreach ($daftarGuru as $guru) {
            $guru->kode_barcode = Str::random(32);
            $guru->save();
        }

        LogAktivitas::catat('Generate Semua Barcode', 'Membuat ulang barcode untuk seluruh guru pengajar');

        return redirect()->route('admin.barcode.index')
            ->with('sukses', 'Barcode absensi untuk seluruh guru pengajar berhasil digenerate ulang.');
    }

    public function cetak(Request $request)
    {
        $pengaturan = PengaturanSekolah::first();

        if ($request->filled('id')) {
            $guru = Guru::with('mapel')->where('jenis', 'Guru')->findOrFail($request->id);
            if (empty($guru->kode_barcode)) {
                $guru->kode_barcode = Str::random(32);
                $guru->save();
                LogAktivitas::catat('Generate Barcode', "Membuat kode barcode otomatis saat cetak untuk guru {$guru->nama}");
            }
            $daftarGuru = collect([$guru]);
        } elseif ($request->get('filter') === 'belum') {
            $daftarGuru = Guru::with('mapel')->where('jenis', 'Guru')->where(function ($query) {
                $query->whereNull('kode_barcode')->orWhere('kode_barcode', '');
            })->orderBy('nama')->get();

            foreach ($daftarGuru as $guru) {
                if (empty($guru->kode_barcode)) {
                    $guru->kode_barcode = Str::random(32);
                    $guru->save();
                }
            }
            LogAktivitas::catat('Generate Barcode Massal', 'Membuat barcode otomatis untuk guru yang belum memiliki barcode saat cetak');
        } else {
            $daftarGuru = Guru::with('mapel')->where('jenis', 'Guru')->orderBy('nama')->get();

            foreach ($daftarGuru as $guru) {
                if (empty($guru->kode_barcode)) {
                    $guru->kode_barcode = Str::random(32);
                    $guru->save();
                }
            }
        }

        return view('admin.barcode.cetak', compact('daftarGuru', 'pengaturan'));
    }
}


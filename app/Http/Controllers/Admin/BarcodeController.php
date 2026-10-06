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
        $pengaturan = PengaturanSekolah::first();
        $daftarGuru = Guru::where('jenis', 'Guru')->orderBy('nama')->get();

        $guruTerpilih = null;
        if ($request->filled('guru_id')) {
            $guruTerpilih = Guru::find($request->guru_id);
        } else {
            $guruTerpilih = $daftarGuru->firstWhere('kode_barcode', '!=', null) ?? $daftarGuru->first();
        }

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
            $guru = Guru::where('jenis', 'Guru')->findOrFail($request->id);
            $daftarGuru = collect([$guru]);
        } else {
            $daftarGuru = Guru::where('jenis', 'Guru')->whereNotNull('kode_barcode')->orderBy('nama')->get();
        }

        return view('admin.barcode.cetak', compact('daftarGuru', 'pengaturan'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\PengaturanSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSekolah::first();
        if (!$pengaturan) {
            $pengaturan = PengaturanSekolah::create([
                'nama_sekolah' => 'SMK Negeri 13 Bandung',
                'lat_sekolah' => -6.94580000,
                'long_sekolah' => 107.67650000,
                'radius_meter' => 100,
                'jam_masuk' => '07:00',
            ]);
        }

        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:50',
            'social_media' => 'nullable|string|max:100',
            'jam_operasional' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'lat_sekolah' => 'required|numeric',
            'long_sekolah' => 'required|numeric',
            'radius_meter' => 'required|integer|min:10',
            'jam_masuk' => 'required',
        ]);

        $pengaturan = PengaturanSekolah::first();
        if (!$pengaturan) {
            $pengaturan = new PengaturanSekolah();
        }

        $pengaturan->nama_sekolah = $request->nama_sekolah;
        $pengaturan->npsn = $request->npsn;
        $pengaturan->alamat = $request->alamat;
        $pengaturan->email = $request->email;
        $pengaturan->telepon = $request->telepon;
        $pengaturan->social_media = $request->social_media;
        $pengaturan->jam_operasional = $request->jam_operasional;
        $pengaturan->lat_sekolah = $request->lat_sekolah;
        $pengaturan->long_sekolah = $request->long_sekolah;
        $pengaturan->radius_meter = $request->radius_meter;
        $pengaturan->jam_masuk = $request->jam_masuk;

        if ($request->hasFile('logo')) {
            if ($pengaturan->logo && Storage::disk('public')->exists($pengaturan->logo)) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $pengaturan->logo = $request->file('logo')->store('pengaturan', 'public');
        }

        $pengaturan->save();

        LogAktivitas::catat('Ubah Pengaturan', 'Memperbarui identitas sekolah dan parameter absensi');

        return redirect()->route('admin.pengaturan.index')->with('sukses', 'Pengaturan identitas sekolah berhasil diperbarui.');
    }
}

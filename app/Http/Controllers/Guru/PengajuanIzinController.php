<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\PengajuanIzinGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengajuanIzinController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Akun Anda belum terhubung dengan data profil guru.');
        }

        $daftarPengajuan = PengajuanIzinGuru::where('id_guru', $guru->id)
            ->latest()
            ->paginate(10);

        return view('guru.izin.index', compact('guru', 'daftarPengajuan'));
    }

    public function store(Request $request)
    {
        $guru = Guru::where('user_id', auth()->id())->first();
        if (!$guru) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $request->validate([
            'jenis_izin' => 'required|in:Sakit,Izin,Terlambat,Cuti,Tugas Luar',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jam_estimasi' => 'nullable|string',
            'alasan' => 'required|string|max:1000',
            'bukti' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ], [
            'jenis_izin.required' => 'Pilih jenis izin yang diajukan.',
            'tanggal_mulai.required' => 'Tentukan tanggal mulai izin.',
            'alasan.required' => 'Mohon sertakan alasan pengajuan izin.',
            'bukti.max' => 'Ukuran file bukti maksimal 3 MB.',
            'bukti.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
        ]);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalSelesai = $request->jenis_izin === 'Terlambat' 
            ? $tanggalMulai 
            : ($request->tanggal_selesai ?: $tanggalMulai);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('pengajuan_izin', 'public');
        }

        PengajuanIzinGuru::create([
            'id_guru' => $guru->id,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'jam_estimasi' => $request->jenis_izin === 'Terlambat' ? $request->jam_estimasi : null,
            'alasan' => $request->alasan,
            'bukti' => $buktiPath,
            'status' => 'Menunggu',
        ]);

        LogAktivitas::catat('Pengajuan Izin Guru', "Guru {$guru->nama} mengajukan izin ({$request->jenis_izin}) periode {$tanggalMulai} s/d {$tanggalSelesai}");

        return redirect()->route('guru.izin.index')
            ->with('sukses', "Pengajuan izin {$request->jenis_izin} berhasil dikirim dan sedang menunggu persetujuan dari admin.");
    }
}

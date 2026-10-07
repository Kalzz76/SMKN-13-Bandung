<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use App\Models\Guru;
use App\Models\LogAktivitas;
use App\Models\PengajuanIzinGuru;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PengajuanIzinController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->get('status');
        $search = $request->get('search');

        $query = PengajuanIzinGuru::with(['guru', 'userPenyetuju'])->latest();

        if ($statusFilter && in_array($statusFilter, ['Menunggu', 'Disetujui', 'Ditolak'])) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($sq) use ($search) {
                $sq->whereHas('guru', function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nip', 'like', "%{$search}%");
                })->orWhere('jenis_izin', 'like', "%{$search}%");
            });
        }

        $daftarPengajuan = $query->paginate(15)->withQueryString();

        $totalPengajuan = PengajuanIzinGuru::count();
        $totalMenunggu = PengajuanIzinGuru::where('status', 'Menunggu')->count();
        $totalDisetujui = PengajuanIzinGuru::where('status', 'Disetujui')->count();
        $totalDitolak = PengajuanIzinGuru::where('status', 'Ditolak')->count();

        $semuaGuru = Guru::orderBy('nama')->get();

        return view('admin.izin.index', compact(
            'daftarPengajuan',
            'statusFilter',
            'search',
            'totalPengajuan',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'semuaGuru'
        ));
    }

    public function setujui(Request $request, $id)
    {
        $pengajuan = PengajuanIzinGuru::with('guru')->findOrFail($id);

        if ($pengajuan->status === 'Disetujui') {
            return back()->with('info', 'Pengajuan ini sudah disetujui sebelumnya.');
        }

        $pengajuan->update([
            'status' => 'Disetujui',
            'catatan_admin' => $request->catatan_admin,
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => Carbon::now('Asia/Jakarta'),
        ]);

        // Catat otomatis ke tabel absensi_guru untuk rentang tanggal izin
        $start = Carbon::parse($pengajuan->tanggal_mulai);
        $end = Carbon::parse($pengajuan->tanggal_selesai);

        while ($start->lte($end)) {
            // Abaikan hari Minggu (libur sekolah)
            if ($start->dayOfWeek !== Carbon::SUNDAY) {
                AbsensiGuru::updateOrCreate(
                    [
                        'id_guru' => $pengajuan->id_guru,
                        'tanggal' => $start->format('Y-m-d'),
                    ],
                    [
                        'status' => $pengajuan->jenis_izin,
                        'metode' => 'Pengajuan Izin',
                        'jam_masuk' => $pengajuan->jam_estimasi,
                        'alasan' => $pengajuan->alasan,
                        'id_user_input' => auth()->id(),
                    ]
                );
            }
            $start->addDay();
        }

        LogAktivitas::catat('Setujui Izin Guru', "Admin menyetujui permohonan izin {$pengajuan->jenis_izin} guru {$pengajuan->guru->nama}");

        return back()->with('sukses', "Pengajuan izin {$pengajuan->jenis_izin} untuk {$pengajuan->guru->nama} berhasil disetujui dan telah dicatat ke rekap absensi.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanIzinGuru::with('guru')->findOrFail($id);

        $pengajuan->update([
            'status' => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
            'disetujui_oleh' => auth()->id(),
            'disetujui_pada' => Carbon::now('Asia/Jakarta'),
        ]);

        LogAktivitas::catat('Tolak Izin Guru', "Admin menolak permohonan izin {$pengajuan->jenis_izin} guru {$pengajuan->guru->nama}");

        return back()->with('sukses', "Pengajuan izin {$pengajuan->jenis_izin} untuk {$pengajuan->guru->nama} telah ditolak.");
    }

    /**
     * Catat / Tandai Kehadiran Manual oleh Admin (termasuk status Alpa)
     */
    public function catatManual(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|exists:guru,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Terlambat,Sakit,Izin,Cuti,Tugas Luar,Alpa',
            'alasan' => 'nullable|string|max:255',
        ]);

        $guru = Guru::findOrFail($request->id_guru);

        AbsensiGuru::updateOrCreate(
            [
                'id_guru' => $guru->id,
                'tanggal' => $request->tanggal,
            ],
            [
                'status' => $request->status,
                'metode' => 'Admin Manual',
                'jam_masuk' => $request->status === 'Hadir' ? '07:00:00' : null,
                'alasan' => $request->alasan ?: ($request->status === 'Alpa' ? 'Tidak hadir tanpa keterangan (Alpa)' : null),
                'id_user_input' => auth()->id(),
            ]
        );

        LogAktivitas::catat('Catat Kehadiran Guru Manual', "Admin menandai kehadiran {$guru->nama} pada tanggal {$request->tanggal} sebagai {$request->status}");

        return back()->with('sukses', "Status kehadiran {$guru->nama} pada tanggal {$request->tanggal} berhasil dicatat sebagai {$request->status}.");
    }
}

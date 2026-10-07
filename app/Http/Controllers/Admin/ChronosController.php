<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Services\ChronosService;
use Illuminate\Http\Request;

class ChronosController extends Controller
{
    public function index()
    {
        $status = ChronosService::status();
        $activeJadwal = ChronosService::getActiveJadwal();

        return view('admin.chronos.index', compact('status', 'activeJadwal'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
            'second' => 'nullable|integer|min:0|max:59',
        ]);

        $second = (int) ($request->second ?? 0);
        ChronosService::setTime($request->date, (int) $request->hour, (int) $request->minute, $second);

        LogAktivitas::catat('Ubah Chronos', "Mengatur waktu virtual ke {$request->date} " . sprintf('%02d:%02d', $request->hour, $request->minute));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'sukses' => true,
                'message' => 'Waktu virtual Chronos berhasil diperbarui.',
                'status' => ChronosService::status(),
            ]);
        }

        return redirect()->route('admin.chronos.index')->with('sukses', 'Waktu virtual Chronos berhasil diperbarui.');
    }

    public function toggle(Request $request)
    {
        $enabled = filter_var($request->input('enabled', true), FILTER_VALIDATE_BOOLEAN);

        if ($enabled) {
            ChronosService::enable();
            LogAktivitas::catat('Aktifkan Chronos', 'Waktu virtual sistem telah diaktifkan');
            $msg = 'Chronos berhasil diaktifkan. Sistem sekarang menggunakan waktu virtual.';
        } else {
            ChronosService::disable();
            LogAktivitas::catat('Nonaktifkan Chronos', 'Waktu virtual sistem dinonaktifkan (kembali ke waktu nyata)');
            $msg = 'Chronos berhasil dinonaktifkan. Sistem kembali membaca waktu nyata.';
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'sukses' => true,
                'message' => $msg,
                'status' => ChronosService::status(),
            ]);
        }

        return redirect()->route('admin.chronos.index')->with('sukses', $msg);
    }

    public function preset(Request $request)
    {
        $request->validate([
            'day' => 'required|integer|min:1|max:7',
            'hour' => 'required|integer|min:0|max:23',
            'minute' => 'required|integer|min:0|max:59',
            'label' => 'nullable|string',
        ]);

        ChronosService::applyPreset((int) $request->day, (int) $request->hour, (int) $request->minute);

        $label = $request->label ?? 'Preset Cepat';
        LogAktivitas::catat('Preset Chronos', "Menerapkan preset {$label}");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'sukses' => true,
                'message' => "Preset {$label} berhasil diterapkan.",
                'status' => ChronosService::status(),
            ]);
        }

        return redirect()->route('admin.chronos.index')->with('sukses', "Preset {$label} berhasil diterapkan.");
    }

    public function reset(Request $request)
    {
        ChronosService::disable();
        LogAktivitas::catat('Reset Chronos', 'Mengembalikan waktu sistem ke waktu nyata');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'sukses' => true,
                'message' => 'Waktu sistem telah dikembalikan ke waktu nyata.',
                'status' => ChronosService::status(),
            ]);
        }

        return redirect()->route('admin.chronos.index')->with('sukses', 'Waktu sistem telah dikembalikan ke waktu nyata.');
    }
}

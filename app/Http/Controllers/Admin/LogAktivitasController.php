<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('aksi', 'like', "%{$cari}%")
                  ->orWhere('keterangan', 'like', "%{$cari}%")
                  ->orWhereHas('user', function ($sub) use ($cari) {
                      $sub->where('name', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarLog = $query->paginate(15)->withQueryString();

        return view('admin.log.index', compact('daftarLog'));
    }
}

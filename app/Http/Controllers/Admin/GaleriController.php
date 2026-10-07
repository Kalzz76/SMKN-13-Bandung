<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use App\Models\GaleriFoto;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Galeri::query()->with('fotos')->latest('tanggal');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('judul', 'like', "%{$cari}%");
        }

        $daftarGaleri = $query->paginate(12)->withQueryString();

        return view('admin.galeri.index', compact('daftarGaleri'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'foto' => 'required',
        ]);

        $fotoInput = $request->file('foto');
        $files = is_array($fotoInput) ? $fotoInput : ($fotoInput ? [$fotoInput] : []);

        if (empty($files)) {
            return back()->withErrors(['foto' => 'Wajib mengunggah minimal satu foto.']);
        }

        foreach ($files as $f) {
            if (!$f->isValid() || !in_array(strtolower($f->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                return back()->withErrors(['foto' => 'Format file foto harus berupa gambar JPG, JPEG, PNG, atau WEBP.']);
            }
            if ($f->getSize() > 3 * 1024 * 1024) {
                return back()->withErrors(['foto' => 'Ukuran setiap file gambar maksimal 3MB.']);
            }
        }

        $galeri = Galeri::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'foto' => '',
        ]);

        $coverFoto = null;
        foreach ($files as $index => $file) {
            $path = $file->store('galeri', 'public');
            if ($index === 0) {
                $coverFoto = $path;
            }
            GaleriFoto::create([
                'galeri_id' => $galeri->id,
                'foto' => $path,
                'urutan' => $index,
            ]);
        }

        $galeri->update(['foto' => $coverFoto]);

        $jumlahFoto = count($files);
        LogAktivitas::catat('Tambah Galeri', "Menambahkan album galeri '{$galeri->judul}' ({$jumlahFoto} foto)");

        return redirect()->route('admin.galeri.index')->with('sukses', "Album galeri berhasil ditambahkan dengan {$jumlahFoto} foto.");
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'tanggal' => 'required|date',
        ]);

        $galeri = Galeri::findOrFail($id);
        $galeri->judul = $request->judul;
        $galeri->kategori = $request->kategori;
        $galeri->tanggal = $request->tanggal;

        if ($request->hasFile('foto')) {
            $fotoInput = $request->file('foto');
            $files = is_array($fotoInput) ? $fotoInput : [$fotoInput];
            $currentCount = $galeri->fotos()->count();

            foreach ($files as $idx => $file) {
                if ($file->isValid() && in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                    $path = $file->store('galeri', 'public');
                    GaleriFoto::create([
                        'galeri_id' => $galeri->id,
                        'foto' => $path,
                        'urutan' => $currentCount + $idx,
                    ]);
                    if (empty($galeri->foto)) {
                        $galeri->foto = $path;
                    }
                }
            }
        }

        $galeri->save();

        LogAktivitas::catat('Ubah Galeri', "Memperbarui album galeri '{$galeri->judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', 'Album galeri berhasil diperbarui.');
    }

    public function tambahFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required',
        ]);

        $galeri = Galeri::findOrFail($id);
        $fotoInput = $request->file('foto');
        $files = is_array($fotoInput) ? $fotoInput : ($fotoInput ? [$fotoInput] : []);

        if (empty($files)) {
            return back()->withErrors(['foto' => 'Wajib memilih minimal satu foto baru.']);
        }

        $currentCount = $galeri->fotos()->count();
        $berhasil = 0;

        foreach ($files as $idx => $file) {
            if ($file->isValid() && in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'webp'])) {
                $path = $file->store('galeri', 'public');
                GaleriFoto::create([
                    'galeri_id' => $galeri->id,
                    'foto' => $path,
                    'urutan' => $currentCount + $idx,
                ]);

                if (empty($galeri->foto)) {
                    $galeri->update(['foto' => $path]);
                }
                $berhasil++;
            }
        }

        LogAktivitas::catat('Tambah Foto Galeri', "Menambahkan {$berhasil} foto ke album '{$galeri->judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', "Berhasil menambahkan {$berhasil} foto baru ke album.");
    }

    public function destroyFoto($id)
    {
        $fotoItem = GaleriFoto::findOrFail($id);
        $galeri = $fotoItem->galeri;

        if ($galeri && $galeri->fotos()->count() <= 1) {
            return back()->with('error', 'Album harus memiliki minimal 1 foto dokumentasi.');
        }

        if ($fotoItem->foto && Storage::disk('public')->exists($fotoItem->foto)) {
            Storage::disk('public')->delete($fotoItem->foto);
        }

        $isCover = $galeri && ($galeri->foto === $fotoItem->foto);
        $fotoItem->delete();

        if ($galeri && $isCover) {
            $nextFoto = $galeri->fotos()->first();
            $galeri->update(['foto' => $nextFoto ? $nextFoto->foto : '']);
        }

        LogAktivitas::catat('Hapus Foto Galeri', "Menghapus foto dari album '{$galeri->judul}'");

        return back()->with('sukses', 'Foto berhasil dihapus dari album.');
    }

    public function destroy($id)
    {
        $galeri = Galeri::with('fotos')->findOrFail($id);

        foreach ($galeri->fotos as $f) {
            if ($f->foto && Storage::disk('public')->exists($f->foto)) {
                Storage::disk('public')->delete($f->foto);
            }
        }

        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $judul = $galeri->judul;
        $galeri->delete();

        LogAktivitas::catat('Hapus Galeri', "Menghapus album galeri '{$judul}'");

        return redirect()->route('admin.galeri.index')->with('sukses', 'Album galeri berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\PengaturanSekolah;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PublikController extends Controller
{
    public function beranda()
    {
        $pengaturan = PengaturanSekolah::first();
        $beritaTerbaru = Berita::where('status', 'Publish')->orderBy('tanggal', 'desc')->take(3)->get();

        return view('publik.beranda', compact('pengaturan', 'beritaTerbaru'));
    }

    public function profil()
    {
        $pengaturan = PengaturanSekolah::first();
        $daftarGuru = Guru::where('tampil_publik', true)->get();
        $struktur = $pengaturan ? $pengaturan->struktur : PengaturanSekolah::defaultStruktur();

        $daftarMisi = [];
        if ($pengaturan && !empty($pengaturan->misi)) {
            $daftarMisi = array_filter(array_map('trim', explode("\n", $pengaturan->misi)));
        }

        return view('publik.profil', compact('pengaturan', 'daftarGuru', 'daftarMisi', 'struktur'));
    }

    public function jurusan()
    {
        $daftarJurusan = Jurusan::all();

        return view('publik.jurusan', compact('daftarJurusan'));
    }

    public function berita()
    {
        $daftarBerita = Berita::where('status', 'Publish')->orderBy('tanggal', 'desc')->paginate(9);

        return view('publik.berita', compact('daftarBerita'));
    }

    public function detailBerita($id)
    {
        $berita = Berita::where('status', 'Publish')->with('user')->findOrFail($id);
        $beritaTerkait = Berita::where('status', 'Publish')->where('id', '!=', $id)->orderBy('tanggal', 'desc')->take(3)->get();

        return view('publik.detail-berita', compact('berita', 'beritaTerkait'));
    }

    public function galeri(Request $request)
    {
        $kategori = $request->query('kategori', 'Semua');
        $query = Galeri::query()->with('fotos');

        if ($kategori !== 'Semua' && in_array($kategori, ['Fasilitas', 'Kegiatan'])) {
            $query->where('kategori', $kategori);
        }

        $daftarGaleri = $query->orderBy('tanggal', 'desc')->get();

        return view('publik.galeri', compact('daftarGaleri', 'kategori'));
    }

    public function kontak()
    {
        $pengaturan = PengaturanSekolah::first();

        return view('publik.kontak', compact('pengaturan'));
    }

    public function ekstrakurikuler()
    {
        $daftarEkskul = Ekstrakurikuler::all();

        return view('publik.ekstrakurikuler', compact('daftarEkskul'));
    }
}

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
        $daftarBerita = Berita::where('status', 'Publish')->orderBy('tanggal', 'desc')->take(3)->get();
        $daftarPrestasi = Prestasi::latest('id')->take(3)->get();

        $items = collect();
        foreach ($daftarPrestasi as $p) {
            $items->push((object) [
                'id' => 'p_' . $p->id,
                'judul' => $p->judul,
                'kategori' => 'Prestasi',
                'tingkat' => $p->tingkat,
                'tahun' => $p->tahun,
                'nama_peraih' => $p->nama_peraih,
                'tanggal' => $p->created_at ? $p->created_at->format('Y-m-d') : ($p->tahun . '-01-01'),
                'isi' => ($p->nama_peraih ? "Diraih oleh {$p->nama_peraih} (Tingkat {$p->tingkat} - Tahun {$p->tahun}). " : '') . ($p->deskripsi ?? ''),
                'gambar_url' => $p->gambar_url,
                'is_prestasi' => true,
                'url' => route('publik.detail-berita', 'p_' . $p->id),
            ]);
        }
        foreach ($daftarBerita as $b) {
            $items->push((object) [
                'id' => (string)$b->id,
                'judul' => $b->judul,
                'kategori' => $b->kategori,
                'tingkat' => null,
                'tahun' => \Carbon\Carbon::parse($b->tanggal)->format('Y'),
                'nama_peraih' => null,
                'tanggal' => $b->tanggal,
                'isi' => $b->isi,
                'gambar_url' => $b->gambar_url,
                'is_prestasi' => ($b->kategori === 'Prestasi'),
                'url' => route('publik.detail-berita', $b->id),
            ]);
        }

        $beritaTerbaru = $items->sortByDesc('tanggal')->take(3)->values();

        return view('publik.beranda', compact('pengaturan', 'beritaTerbaru'));
    }

    public function profil()
    {
        $pengaturan = PengaturanSekolah::first();
        $daftarGuru = Guru::where('tampil_publik', true)->with('mapels', 'mapel')->get();
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

    public function berita(Request $request)
    {
        $daftarBeritaRaw = Berita::where('status', 'Publish')->with('user')->orderBy('tanggal', 'desc')->get();
        $daftarPrestasiRaw = Prestasi::latest('id')->get();

        $items = collect();

        // 1. Integrasikan data prestasi resmi dari Admin
        foreach ($daftarPrestasiRaw as $p) {
            $items->push((object) [
                'id' => 'p_' . $p->id,
                'raw_id' => $p->id,
                'judul' => $p->judul,
                'kategori' => 'Prestasi',
                'tingkat' => $p->tingkat,
                'tahun' => $p->tahun,
                'nama_peraih' => $p->nama_peraih,
                'tanggal' => $p->created_at ? $p->created_at->format('Y-m-d') : ($p->tahun . '-01-01'),
                'isi' => ($p->nama_peraih ? "Peraih: {$p->nama_peraih} ({$p->tingkat} - {$p->tahun}). " : '') . ($p->deskripsi ?? ''),
                'gambar_url' => $p->gambar_url,
                'is_prestasi' => true,
                'user' => (object) ['name' => $p->nama_peraih ?: 'Prestasi Siswa'],
                'url' => route('publik.detail-berita', 'p_' . $p->id),
            ]);
        }

        // 2. Integrasikan data warta berita lainnya
        foreach ($daftarBeritaRaw as $b) {
            $items->push((object) [
                'id' => (string)$b->id,
                'raw_id' => $b->id,
                'judul' => $b->judul,
                'kategori' => $b->kategori,
                'tingkat' => null,
                'tahun' => \Carbon\Carbon::parse($b->tanggal)->format('Y'),
                'nama_peraih' => null,
                'tanggal' => $b->tanggal,
                'isi' => $b->isi,
                'gambar_url' => $b->gambar_url,
                'is_prestasi' => ($b->kategori === 'Prestasi'),
                'user' => $b->user,
                'url' => route('publik.detail-berita', $b->id),
            ]);
        }

        $items = $items->sortByDesc('tanggal')->values();

        $page = (int) $request->get('page', 1);
        $perPage = 18;
        $total = $items->count();
        $daftarBerita = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('publik.berita', compact('daftarBerita'));
    }

    public function detailBerita($id)
    {
        if (str_starts_with((string)$id, 'p_')) {
            $prestasiId = (int) substr($id, 2);
            $p = Prestasi::findOrFail($prestasiId);
            $berita = (object) [
                'id' => $id,
                'judul' => $p->judul,
                'kategori' => 'Prestasi',
                'tingkat' => $p->tingkat,
                'tahun' => $p->tahun,
                'nama_peraih' => $p->nama_peraih,
                'is_prestasi' => true,
                'tanggal' => $p->created_at ? $p->created_at->format('Y-m-d') : ($p->tahun . '-01-01'),
                'gambar_url' => $p->gambar_url,
                'isi' => $p->deskripsi ?? 'Tidak ada deskripsi tambahan.',
                'user' => (object) ['name' => $p->nama_peraih ?: 'Prestasi Siswa'],
            ];
        } else {
            $b = Berita::where('status', 'Publish')->with('user')->find($id);
            if ($b) {
                $berita = (object) [
                    'id' => (string)$b->id,
                    'judul' => $b->judul,
                    'kategori' => $b->kategori,
                    'tingkat' => null,
                    'tahun' => \Carbon\Carbon::parse($b->tanggal)->format('Y'),
                    'nama_peraih' => null,
                    'is_prestasi' => ($b->kategori === 'Prestasi'),
                    'tanggal' => $b->tanggal,
                    'gambar_url' => $b->gambar_url,
                    'isi' => $b->isi,
                    'user' => $b->user,
                ];
            } else {
                $p = Prestasi::findOrFail($id);
                $berita = (object) [
                    'id' => 'p_' . $p->id,
                    'judul' => $p->judul,
                    'kategori' => 'Prestasi',
                    'tingkat' => $p->tingkat,
                    'tahun' => $p->tahun,
                    'nama_peraih' => $p->nama_peraih,
                    'is_prestasi' => true,
                    'tanggal' => $p->created_at ? $p->created_at->format('Y-m-d') : ($p->tahun . '-01-01'),
                    'gambar_url' => $p->gambar_url,
                    'isi' => $p->deskripsi ?? 'Tidak ada deskripsi tambahan.',
                    'user' => (object) ['name' => $p->nama_peraih ?: 'Prestasi Siswa'],
                ];
            }
        }

        $itemsTerkait = collect();
        $daftarPrestasiLain = Prestasi::latest('id')->get();
        foreach ($daftarPrestasiLain as $p) {
            if ('p_' . $p->id === (string)$id || (string)$p->id === (string)$id) continue;
            $itemsTerkait->push((object) [
                'id' => 'p_' . $p->id,
                'judul' => $p->judul,
                'kategori' => 'Prestasi',
                'is_prestasi' => true,
                'tanggal' => $p->created_at ? $p->created_at->format('Y-m-d') : ($p->tahun . '-01-01'),
                'gambar_url' => $p->gambar_url,
                'isi' => ($p->nama_peraih ? "Peraih: {$p->nama_peraih}. " : '') . ($p->deskripsi ?? ''),
            ]);
        }
        $daftarBeritaLain = Berita::where('status', 'Publish')->where('id', '!=', $id)->orderBy('tanggal', 'desc')->get();
        foreach ($daftarBeritaLain as $b) {
            $itemsTerkait->push((object) [
                'id' => (string)$b->id,
                'judul' => $b->judul,
                'kategori' => $b->kategori,
                'is_prestasi' => ($b->kategori === 'Prestasi'),
                'tanggal' => $b->tanggal,
                'gambar_url' => $b->gambar_url,
                'isi' => $b->isi,
            ]);
        }
        $beritaTerkait = $itemsTerkait->sortByDesc('tanggal')->take(3)->values();

        return view('publik.detail-berita', compact('berita', 'beritaTerkait'));
    }

    public function galeri(Request $request)
    {
        $query = Galeri::query();

        if (\Illuminate\Support\Facades\Schema::hasTable('galeri_foto')) {
            $query->with('fotos');
        }

        $daftarGaleri = $query->orderBy('tanggal', 'desc')->get();

        return view('publik.galeri', compact('daftarGaleri'));
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

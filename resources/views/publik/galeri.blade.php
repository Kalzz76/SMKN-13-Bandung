@extends('layouts.publik', ['title' => 'Galeri & Dokumentasi - SMKN 13 Bandung'])

@section('content')
@php
    $albumItems = [];
    foreach($daftarGaleri as $gal) {
        $mainPhoto = null;
        if (!empty($gal->foto_utama)) {
            if (\Illuminate\Support\Str::startsWith($gal->foto_utama, ['http://', 'https://'])) {
                $mainPhoto = $gal->foto_utama;
            } elseif (file_exists(public_path('storage/' . $gal->foto_utama))) {
                $mainPhoto = asset('storage/' . $gal->foto_utama);
            } elseif (file_exists(public_path('Assets/' . basename($gal->foto_utama)))) {
                $mainPhoto = asset('Assets/' . basename($gal->foto_utama));
            }
        }
        if (!$mainPhoto && !empty($gal->foto)) {
            if (\Illuminate\Support\Str::startsWith($gal->foto, ['http://', 'https://'])) {
                $mainPhoto = $gal->foto;
            } elseif (file_exists(public_path('storage/' . $gal->foto))) {
                $mainPhoto = asset('storage/' . $gal->foto);
            } elseif (file_exists(public_path('Assets/' . basename($gal->foto)))) {
                $mainPhoto = asset('Assets/' . basename($gal->foto));
            }
        }

        $subPhotos = [];
        if ($gal->fotos && $gal->fotos->isNotEmpty()) {
            foreach ($gal->fotos as $f) {
                if (!empty($f->foto)) {
                    if (\Illuminate\Support\Str::startsWith($f->foto, ['http://', 'https://'])) {
                        $subPhotos[] = $f->foto;
                    } elseif (file_exists(public_path('storage/' . $f->foto))) {
                        $subPhotos[] = asset('storage/' . $f->foto);
                    }
                }
            }
        }

        if ($mainPhoto) {
            $albumItems[] = [
                'id' => 'gal' . $gal->id,
                'judul' => $gal->judul,
                'kategori' => $gal->kategori ?? 'Kegiatan',
                'mainPhoto' => $mainPhoto,
                'subPhotos' => $subPhotos
            ];
        }
    }

    if (empty($albumItems)) {
        $albumItems = [
            [
                'id' => 'gal1',
                'judul' => 'Uji Kompetensi APL',
                'kategori' => 'Akademik',
                'mainPhoto' => 'https://images.unsplash.com/photo-1581093458791-9f3c3900df4b?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=300&q=80',
                    'https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&w=300&q=80'
                ]
            ],
            [
                'id' => 'gal2',
                'judul' => 'Hackathon Siswa RPL',
                'kategori' => 'Lomba',
                'mainPhoto' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=300&q=80',
                    'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=300&q=80'
                ]
            ],
            [
                'id' => 'gal3',
                'judul' => 'Gelar Karya P5 (Pancasila)',
                'kategori' => 'Kegiatan',
                'mainPhoto' => 'https://images.unsplash.com/photo-1511632765486-a01c80cf59af?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=300&q=80',
                    'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?auto=format&fit=crop&w=300&q=80'
                ]
            ],
            [
                'id' => 'gal4',
                'judul' => 'Fasilitas & Lab Jaringan',
                'kategori' => 'Fasilitas',
                'mainPhoto' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=300&q=80',
                    'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?auto=format&fit=crop&w=300&q=80'
                ]
            ]
        ];
    }
@endphp

<div class="py-16 px-4 max-w-7xl mx-auto w-full flex-grow">
    <!-- Header Galeri -->
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-[.25em] text-teal-primary">Galeri</span>
        <h1 class="text-4xl sm:text-5xl font-black text-navy-dark mt-3 mb-4">Galeri & Dokumentasi</h1>
        <p class="text-lg text-text-muted">Koleksi album foto kegiatan dan fasilitas sekolah SMKN 13 Bandung.</p>
    </div>

    <!-- Filter Pills Kategori -->
    <div id="galFilters" class="flex flex-wrap justify-center gap-3 mt-10"></div>

    <!-- 3D Coverflow Slider Track -->
    <div class="relative overflow-hidden mt-10 -mx-4 py-4" onmouseenter="galPaused=true" onmouseleave="galPaused=false">
        <div id="galTrack" class="relative h-[470px]"></div>
    </div>

    <!-- Navigasi Prev / Next -->
    <div class="flex justify-center gap-3 mt-6">
        <button onclick="galPrev()" aria-label="Sebelumnya"
            class="w-11 h-11 rounded-full border border-navy-dark/60 text-navy-dark hover:bg-navy-dark hover:text-white transition flex items-center justify-center shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <button onclick="galNext()" aria-label="Berikutnya"
            class="w-11 h-11 rounded-full border border-navy-dark/60 text-navy-dark hover:bg-navy-dark hover:text-white transition flex items-center justify-center shadow-sm">
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </div>
</div>

<script>
    const galeriAlbums = @json($albumItems);
    let galFilter = 'Semua';
    let galIdx = 0;
    let galTimer = null;
    let galPaused = false;

    function galItems() {
        const o = [];
        galeriAlbums.forEach(a => {
            [a.mainPhoto, ...(a.subPhotos || [])].forEach(u => {
                if (u) {
                    o.push({ src: u, judul: a.judul, kat: a.kategori });
                }
            });
        });
        return galFilter === 'Semua' ? o : o.filter(x => x.kat === galFilter);
    }

    function renderGaleriPage() {
        const kats = ['Semua', ...new Set(galeriAlbums.map(a => a.kategori))];
        const filterBox = document.getElementById('galFilters');
        if (filterBox) {
            filterBox.innerHTML = kats.map(k => `
                <button type="button" onclick="setGalFilter('${k}')"
                    class="px-5 py-2 rounded-full text-sm font-semibold border transition ${galFilter === k ? 'bg-navy-dark text-white border-navy-dark shadow-sm' : 'border-navy-dark/40 text-navy-dark hover:bg-teal-tint'}">
                    ${k}
                </button>
            `).join('');
        }

        const it = galItems();
        const track = document.getElementById('galTrack');
        if (!track) return;

        track.innerHTML = it.map((x, i) => `
            <div class="gal-card" onclick="galGo(${i})">
                <img src="${x.src}" alt="${x.judul}">
                <div class="gal-shade"></div>
                <div class="gal-info">
                    <span class="text-xs font-black uppercase tracking-widest text-teal-accent">${x.kat}</span>
                    <h5 class="text-xl font-bold mt-1 text-white">${x.judul}</h5>
                </div>
            </div>
        `).join('');

        galIdx = Math.min(galIdx, Math.max(it.length - 1, 0));
        positionGal();
        startGalAuto();
    }

    function positionGal() {
        const track = document.getElementById('galTrack');
        if (!track) return;
        const cards = [...track.querySelectorAll('.gal-card')];
        const N = cards.length;
        if (!N) return;

        const W = track.clientWidth || 900;
        const cw = Math.min(380, W * 0.62);
        const step = cw * 0.78;

        cards.forEach((c, i) => {
            let d = ((i - galIdx) % N + N) % N;
            if (d > N / 2) d -= N;
            const a = Math.abs(d);
            const x = d * step * (a > 1 ? 1.02 : 1);

            c.style.width = cw + 'px';
            c.style.transform = `translateX(calc(-50% + ${x}px)) scale(${a === 0 ? 1 : a === 1 ? 0.84 : 0.7})`;
            c.style.zIndex = 10 - a;
            c.style.opacity = a > 2 ? 0 : 1;
            c.style.pointerEvents = a > 2 ? 'none' : 'auto';
            c.classList.toggle('is-active', a === 0);
        });
    }

    function galGo(i) {
        galIdx = i;
        positionGal();
        startGalAuto();
    }

    function galNext() {
        const N = galItems().length;
        if (!N) return;
        galIdx = (galIdx + 1) % N;
        positionGal();
        startGalAuto();
    }

    function galPrev() {
        const N = galItems().length;
        if (!N) return;
        galIdx = (galIdx - 1 + N) % N;
        positionGal();
        startGalAuto();
    }

    function setGalFilter(k) {
        galFilter = k;
        galIdx = 0;
        renderGaleriPage();
    }

    function startGalAuto() {
        clearInterval(galTimer);
        galTimer = setInterval(() => {
            if (galPaused) return;
            const N = galItems().length;
            if (N > 1) {
                galIdx = (galIdx + 1) % N;
                positionGal();
            }
        }, 3200);
    }

    window.addEventListener('resize', positionGal);
    document.addEventListener('DOMContentLoaded', renderGaleriPage);
</script>
@endsection

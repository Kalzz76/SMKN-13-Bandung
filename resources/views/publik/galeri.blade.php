@extends('layouts.publik', ['title' => 'Galeri & Dokumentasi - SMKN 13 Bandung'])

@section('content')
@php
    $albumItems = [];
    foreach($daftarGaleri as $gal) {
        $fotos = [];
        if ($gal->fotos && $gal->fotos->isNotEmpty()) {
            foreach ($gal->fotos as $f) {
                if ($f->foto_url) {
                    $fotos[] = $f->foto_url;
                }
            }
        }
        if (empty($fotos) && $gal->foto_utama_url) {
            $fotos[] = $gal->foto_utama_url;
        }

        // Deduplikasi agar foto_utama tidak double dengan foto pertama di tabel fotos
        $fotos = array_values(array_unique(array_filter($fotos)));

        if (!empty($fotos)) {
            $albumItems[] = [
                'id' => 'gal' . $gal->id,
                'judul' => $gal->judul,
                'kategori' => $gal->kategori ?? 'Kegiatan',
                'mainPhoto' => $fotos[0],
                'subPhotos' => array_slice($fotos, 1)
            ];
        }
    }

    if (empty($albumItems)) {
        $albumItems = [
            [
                'id' => 'gal1',
                'judul' => 'Uji Kompetensi APL',
                'kategori' => 'Akademik',
                'mainPhoto' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1576086213369-97a306d36557?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1579154204601-01588f351e67?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=600&q=80'
                ]
            ],
            [
                'id' => 'gal2',
                'judul' => 'Hackathon Siswa RPL',
                'kategori' => 'Lomba',
                'mainPhoto' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=600&q=80'
                ]
            ],
            [
                'id' => 'gal3',
                'judul' => 'Gelar Karya P5 (Pancasila)',
                'kategori' => 'Kegiatan',
                'mainPhoto' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1491438590914-bc09fcaaf77a?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=600&q=80'
                ]
            ],
            [
                'id' => 'gal4',
                'judul' => 'Fasilitas & Lab Jaringan',
                'kategori' => 'Fasilitas',
                'mainPhoto' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=600&q=80',
                'subPhotos' => [
                    'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=600&q=80',
                    'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'
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

    function galRawItems() {
        const o = [];
        galeriAlbums.forEach(a => {
            const list = [a.mainPhoto, ...(a.subPhotos || [])].filter(Boolean);
            const unique = [...new Set(list)];
            unique.forEach(u => {
                o.push({ src: u, judul: a.judul, kat: a.kategori });
            });
        });
        return galFilter === 'Semua' ? o : o.filter(x => x.kat === galFilter);
    }

    function galItems() {
        const raw = galRawItems();
        if (raw.length === 0) return [];
        // Pastikan susunan coverflow memiliki minimal 5 kartu agar simetris sempurna persis seperti filter 'Semua'
        if (raw.length >= 5) return raw;
        let expanded = [...raw];
        while (expanded.length < 5) {
            expanded = expanded.concat(raw);
        }
        return expanded;
    }

    function renderGaleriPage(immediate = true) {
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
                <img src="${x.src}" alt="${x.judul}" onerror="this.onerror=null;this.src='{{ asset('Assets/LOGOS.jpg') }}';" loading="lazy">
                <div class="gal-shade"></div>
                <div class="gal-info">
                    <span class="text-xs font-black uppercase tracking-widest text-teal-accent">${x.kat}</span>
                    <h5 class="text-xl font-bold mt-1 text-white">${x.judul}</h5>
                </div>
            </div>
        `).join('');

        galIdx = Math.min(galIdx, Math.max(it.length - 1, 0));
        positionGal(immediate);
        startGalAuto();
    }

    function positionGal(immediate = false) {
        const track = document.getElementById('galTrack');
        if (!track) return;
        const cards = [...track.querySelectorAll('.gal-card')];
        const N = cards.length;
        if (!N) return;

        const W = track.clientWidth || 900;
        const cw = Math.min(380, W * 0.62);
        const step = cw * 0.78;

        cards.forEach((c, i) => {
            if (immediate) {
                c.style.transition = 'none';
            } else {
                c.style.transition = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.7s ease';
            }

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

        if (immediate) {
            track.offsetHeight;
            setTimeout(() => {
                cards.forEach(c => {
                    c.style.transition = 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.7s ease';
                });
            }, 50);
        }
    }

    function galGo(i) {
        galIdx = i;
        positionGal(false);
        startGalAuto();
    }

    function galNext() {
        const N = galItems().length;
        if (!N) return;
        galIdx = (galIdx + 1) % N;
        positionGal(false);
        startGalAuto();
    }

    function galPrev() {
        const N = galItems().length;
        if (!N) return;
        galIdx = (galIdx - 1 + N) % N;
        positionGal(false);
        startGalAuto();
    }

    function setGalFilter(k) {
        if (galFilter === k) return;
        galFilter = k;
        galIdx = 0;
        renderGaleriPage(true);
    }

    function startGalAuto() {
        clearInterval(galTimer);
        galTimer = setInterval(() => {
            if (galPaused) return;
            const N = galItems().length;
            if (N > 1) {
                galIdx = (galIdx + 1) % N;
                positionGal(false);
            }
        }, 3200);
    }

    window.addEventListener('resize', () => positionGal(false));
    document.addEventListener('DOMContentLoaded', () => renderGaleriPage(true));
</script>
@endsection

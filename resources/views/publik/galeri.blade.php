@extends('layouts.publik', ['title' => 'Galeri & Fasilitas - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 w-full flex-grow">
    <!-- Header Halaman -->
    <div class="text-center max-w-3xl mx-auto">
        <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Dokumentasi Visual</span>
        <h1 class="text-4xl sm:text-5xl font-black text-navy-dark mt-1 mb-3">Galeri Kegiatan & Fasilitas Sekolah</h1>
        <p class="text-base sm:text-lg text-text-muted">Koleksi album potret sarana prasarana modern, laboratorium berstandar industri, dan dinamika kegiatan siswa SMKN 13 Bandung.</p>
    </div>

    <!-- Filter Kategori -->
    <div class="flex justify-center space-x-3 flex-wrap gap-y-2">
        <a href="{{ route('publik.galeri', ['kategori' => 'Semua']) }}"
            class="px-6 py-2.5 rounded-full text-xs font-bold transition shadow-xs {{ ($kategori ?? 'Semua') === 'Semua' ? 'bg-navy-dark text-white shadow-md' : 'bg-white text-navy-dark border border-teal-tint hover:bg-teal-tint' }}">
            Semua Album
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Fasilitas']) }}"
            class="px-6 py-2.5 rounded-full text-xs font-bold transition shadow-xs {{ ($kategori ?? '') === 'Fasilitas' ? 'bg-navy-dark text-white shadow-md' : 'bg-white text-navy-dark border border-teal-tint hover:bg-teal-tint' }}">
            Fasilitas Sekolah
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Kegiatan']) }}"
            class="px-6 py-2.5 rounded-full text-xs font-bold transition shadow-xs {{ ($kategori ?? '') === 'Kegiatan' ? 'bg-navy-dark text-white shadow-md' : 'bg-white text-navy-dark border border-teal-tint hover:bg-teal-tint' }}">
            Kegiatan Siswa
        </a>
    </div>

    <!-- 3D Coverflow Slider Section -->
    <div class="relative overflow-hidden -mx-4 py-4" onmouseenter="galPaused=true" onmouseleave="galPaused=false">
        <div id="galTrack" class="relative h-[460px] max-w-5xl mx-auto">
            <!-- Cards will be positioned by JavaScript -->
        </div>

        <!-- Tombol Kontrol Slider -->
        <div class="flex justify-center gap-4 mt-8">
            <button onclick="galPrev()" aria-label="Sebelumnya"
                class="w-12 h-12 rounded-full border-2 border-navy-dark text-navy-dark hover:bg-navy-dark hover:text-white transition flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button onclick="galNext()" aria-label="Berikutnya"
                class="w-12 h-12 rounded-full border-2 border-navy-dark text-navy-dark hover:bg-navy-dark hover:text-white transition flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- Grid Album Foto Lengkap -->
    <div class="pt-8 border-t border-teal-tint">
        <div class="flex justify-between items-center mb-8">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Daftar Album</span>
                <h3 class="text-2xl font-black text-navy-dark mt-0.5">Semua Album Foto</h3>
            </div>
            <span class="text-xs text-text-muted font-bold bg-teal-tint px-3 py-1.5 rounded-full">
                {{ $daftarGaleri->count() }} Album
            </span>
        </div>

        @if($daftarGaleri->isEmpty())
            <div class="bg-bg-card rounded-3xl p-16 text-center text-text-muted border border-teal-tint max-w-lg mx-auto">
                <i class="fa-solid fa-images text-4xl text-teal-primary/40 mb-3"></i>
                <p class="font-bold text-navy-dark">Belum ada foto dalam kategori ini</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($daftarGaleri as $ga)
                    @php
                        $coverImg = $ga->foto_utama;
                        $fotoCount = $ga->fotos->count() ?: ($ga->foto ? 1 : 0);
                        $fotosPayload = $ga->fotos->map(function($f) {
                            return asset('storage/' . $f->foto);
                        });
                        if ($fotosPayload->isEmpty() && $ga->foto) {
                            $fotosPayload = collect([asset('storage/' . $ga->foto)]);
                        }
                    @endphp
                    <div class="group relative rounded-3xl overflow-hidden shadow-sm bg-navy-dark min-h-[260px] aspect-[4/3] cursor-pointer transform hover:-translate-y-1.5 transition duration-300 border border-teal-tint"
                        onclick="openLightbox({{ json_encode($ga->judul) }}, {{ json_encode($ga->kategori) }}, {{ json_encode($fotosPayload) }})">
                        @if(!empty($coverImg) && file_exists(public_path('storage/' . $coverImg)))
                            <img src="{{ asset('storage/' . $coverImg) }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $ga->judul }}">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-navy-mid to-navy-dark flex flex-col items-center justify-center p-4 text-center">
                                <i class="fa-solid {{ $ga->kategori === 'Fasilitas' ? 'fa-building-columns' : 'fa-users' }} text-4xl text-teal-accent/70 mb-2 group-hover:scale-110 transition duration-300"></i>
                                <span class="text-xs text-teal-tint/80">{{ $ga->kategori }}</span>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 z-10">
                            <span class="bg-navy-dark/80 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full flex items-center gap-1.5 shadow">
                                <i class="fa-solid fa-images text-teal-accent"></i>
                                <span>{{ $fotoCount }} Foto</span>
                            </span>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark/95 via-navy-dark/40 to-transparent flex flex-col justify-end p-5 transition">
                            <span class="text-teal-accent text-[11px] font-bold uppercase tracking-wider mb-1">
                                {{ $ga->kategori }} &bull; {{ \Carbon\Carbon::parse($ga->tanggal)->isoFormat('D MMM Y') }}
                            </span>
                            <h4 class="text-white font-bold text-base leading-snug line-clamp-2 group-hover:text-teal-accent transition">
                                {{ $ga->judul }}
                            </h4>

                            <div class="flex items-center text-xs text-teal-tint font-bold mt-2 pt-2 border-t border-white/10 opacity-90 group-hover:opacity-100 transition">
                                <span>Buka Album</span>
                                <i class="fa-solid fa-arrow-right text-[10px] ml-1.5 group-hover:translate-x-1 transition"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- LIGHTBOX MODAL -->
<div id="lightboxModal" class="fixed inset-0 bg-navy-dark/95 backdrop-blur-md z-50 flex flex-col items-center justify-between p-4 sm:p-6 hidden select-none">
    <div class="w-full max-w-5xl flex justify-between items-center text-white pb-3 border-b border-white/10">
        <div>
            <h3 id="lightboxTitle" class="text-base sm:text-lg font-bold">Judul Album</h3>
            <div class="flex items-center gap-2 text-xs text-teal-tint mt-0.5">
                <span id="lightboxCategory" class="text-teal-accent font-semibold"></span>
                <span>&bull;</span>
                <span id="lightboxCounter" class="text-teal-tint"></span>
            </div>
        </div>
        <button type="button" onclick="closeLightbox()" class="text-white/80 hover:text-white bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center transition text-lg" title="Tutup (Esc)">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="relative w-full max-w-5xl flex-grow flex items-center justify-center my-4 overflow-hidden">
        <button type="button" onclick="prevPhoto()" class="absolute left-2 sm:left-4 z-20 text-white/80 hover:text-white bg-navy-dark/70 hover:bg-navy-dark w-12 h-12 rounded-full flex items-center justify-center transition text-lg shadow-lg border border-teal-tint/30" title="Sebelumnya">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="w-full h-full flex items-center justify-center">
            <img id="lightboxImg" src="" alt="Galeri SMKN 13 Bandung" class="max-h-[65vh] max-w-full object-contain rounded-2xl shadow-2xl transition duration-300">
        </div>

        <button type="button" onclick="nextPhoto()" class="absolute right-2 sm:right-4 z-20 text-white/80 hover:text-white bg-navy-dark/70 hover:bg-navy-dark w-12 h-12 rounded-full flex items-center justify-center transition text-lg shadow-lg border border-teal-tint/30" title="Berikutnya">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div id="lightboxThumbs" class="w-full max-w-3xl flex items-center justify-center gap-2 overflow-x-auto py-2"></div>
</div>

<script>
    // DATA UNTUK 3D COVERFLOW SLIDER
    @php
        $coverflowItems = [];
        foreach($daftarGaleri as $g) {
            $src = !empty($g->foto_utama) && file_exists(public_path('storage/' . $g->foto_utama))
                ? asset('storage/' . $g->foto_utama)
                : asset('Assets/LOGOS.jpg');
            $coverflowItems[] = [
                'src' => $src,
                'judul' => $g->judul,
                'kat' => $g->kategori
            ];
        }
        // Fallback jika kosong
        if (empty($coverflowItems)) {
            $coverflowItems = [
                ['src' => asset('Assets/sejarah1.jpg.jpeg'), 'judul' => 'Uji Kompetensi Kimia Analisis', 'kat' => 'Fasilitas'],
                ['src' => asset('Assets/sejarah2.jpg.jpeg'), 'judul' => 'Laboratorium Cisco & Jaringan', 'kat' => 'Fasilitas'],
                ['src' => asset('Assets/sejarah3.jpg.jpeg'), 'judul' => 'Gelar Karya Pembelajaran', 'kat' => 'Kegiatan'],
                ['src' => asset('Assets/sejarah4.jpg.jpeg'), 'judul' => 'Fasilitas Ruang Praktek Siswa', 'kat' => 'Fasilitas']
            ];
        }
    @endphp

    const galData = @json($coverflowItems);
    let galIdx = 0;
    let galPaused = false;

    function renderGalTrack() {
        const track = document.getElementById('galTrack');
        if (!track || !galData.length) return;

        track.innerHTML = galData.map((x, i) => `
            <div class="gal-card" onclick="galGo(${i})">
                <img src="${x.src}" alt="${x.judul}">
                <div class="gal-shade"></div>
                <div class="gal-info">
                    <span class="text-xs font-black uppercase tracking-widest text-teal-accent">${x.kat}</span>
                    <h5 class="text-xl font-bold mt-1 text-white">${x.judul}</h5>
                </div>
            </div>
        `).join('');

        positionGal();
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
    }

    function galNext() {
        if (!galData.length) return;
        galIdx = (galIdx + 1) % galData.length;
        positionGal();
    }

    function galPrev() {
        if (!galData.length) return;
        galIdx = (galIdx - 1 + galData.length) % galData.length;
        positionGal();
    }

    // Auto rotate coverflow
    setInterval(() => {
        if (!galPaused && galData.length > 1) {
            galNext();
        }
    }, 4500);

    window.addEventListener('resize', positionGal);
    document.addEventListener('DOMContentLoaded', renderGalTrack);

    // LIGHTBOX LOGIC
    let currentPhotos = [];
    let currentIdx = 0;

    function openLightbox(title, category, photos) {
        currentPhotos = photos && photos.length ? photos : [];
        if (!currentPhotos.length) return;
        currentIdx = 0;

        document.getElementById('lightboxTitle').innerText = title;
        document.getElementById('lightboxCategory').innerText = category;
        updateLightboxView();
        document.getElementById('lightboxModal').classList.remove('hidden');
    }

    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
    }

    function updateLightboxView() {
        if (!currentPhotos.length) return;
        document.getElementById('lightboxImg').src = currentPhotos[currentIdx];
        document.getElementById('lightboxCounter').innerText = (currentIdx + 1) + ' / ' + currentPhotos.length;

        const thumbsContainer = document.getElementById('lightboxThumbs');
        thumbsContainer.innerHTML = '';
        currentPhotos.forEach((src, idx) => {
            const thumb = document.createElement('img');
            thumb.src = src;
            thumb.className = `w-12 h-12 object-cover rounded-xl cursor-pointer border-2 transition ${idx === currentIdx ? 'border-teal-accent scale-105' : 'border-transparent opacity-60 hover:opacity-100'}`;
            thumb.onclick = () => { currentIdx = idx; updateLightboxView(); };
            thumbsContainer.appendChild(thumb);
        });
    }

    function nextPhoto() {
        if (currentPhotos.length) {
            currentIdx = (currentIdx + 1) % currentPhotos.length;
            updateLightboxView();
        }
    }

    function prevPhoto() {
        if (currentPhotos.length) {
            currentIdx = (currentIdx - 1 + currentPhotos.length) % currentPhotos.length;
            updateLightboxView();
        }
    }

    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('lightboxModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextPhoto();
            if (e.key === 'ArrowLeft') prevPhoto();
        }
    });
</script>
@endsection

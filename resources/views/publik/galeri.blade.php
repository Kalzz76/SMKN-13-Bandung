@extends('layouts.publik', ['title' => 'Galeri & Fasilitas - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Dokumentasi Visual</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Galeri Kegiatan & Fasilitas Sekolah</h1>
        <p class="text-slate-500 text-sm mt-2">Potret sarana prasarana modern dan dinamika kegiatan siswa SMKN 13 Bandung.</p>
    </div>

    <div class="flex justify-center space-x-2">
        <a href="{{ route('publik.galeri', ['kategori' => 'Semua']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? 'Semua') === 'Semua' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Semua Foto
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Fasilitas']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? '') === 'Fasilitas' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Fasilitas Sekolah
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Kegiatan']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? '') === 'Kegiatan' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Kegiatan Siswa
        </a>
    </div>

    @if($daftarGaleri->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-images text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada foto dalam kategori ini.</p>
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
                <div class="group relative rounded-2xl overflow-hidden shadow bg-slate-900 min-h-[240px] aspect-[4/3] cursor-pointer transform hover:-translate-y-1 transition duration-300"
                    onclick="openLightbox({{ json_encode($ga->judul) }}, {{ json_encode($ga->kategori) }}, {{ json_encode($fotosPayload) }})">
                    @if(!empty($coverImg) && file_exists(public_path('storage/' . $coverImg)))
                        <img src="{{ asset('storage/' . $coverImg) }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $ga->judul }}">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-slate-800 to-slate-900 flex flex-col items-center justify-center p-4 text-center">
                            <i class="fa-solid {{ $ga->kategori === 'Fasilitas' ? 'fa-building' : 'fa-users' }} text-4xl text-emerald-500/60 mb-2 group-hover:scale-110 transition duration-300"></i>
                            <span class="text-xs text-slate-400">{{ $ga->kategori }}</span>
                        </div>
                    @endif

                    <div class="absolute top-3 right-3 z-10">
                        <span class="bg-black/60 backdrop-blur-md text-white text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow">
                            <i class="fa-solid fa-images text-emerald-400"></i>
                            <span>{{ $fotoCount }} Foto</span>
                        </span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4 transition">
                        <span class="text-emerald-400 text-[11px] font-bold uppercase tracking-wider mb-1">
                            {{ $ga->kategori }} &bull; {{ \Carbon\Carbon::parse($ga->tanggal)->isoFormat('D MMM Y') }}
                        </span>
                        <h3 class="text-white font-bold text-sm leading-snug line-clamp-2 group-hover:text-emerald-200 transition">
                            {{ $ga->judul }}
                        </h3>

                        <div class="flex items-center text-xs text-white/80 font-medium mt-2 pt-2 border-t border-white/10 opacity-90 group-hover:opacity-100 transition">
                            <span>Buka Album</span>
                            <i class="fa-solid fa-arrow-right text-[10px] ml-1.5 group-hover:translate-x-1 transition"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div id="lightboxModal" class="fixed inset-0 bg-slate-950/90 backdrop-blur-md z-50 flex flex-col items-center justify-between p-4 sm:p-6 hidden select-none">
    <div class="w-full max-w-5xl flex justify-between items-center text-white pb-3 border-b border-white/10">
        <div>
            <h3 id="lightboxTitle" class="text-base sm:text-lg font-bold">Judul Album</h3>
            <div class="flex items-center gap-2 text-xs text-slate-300 mt-0.5">
                <span id="lightboxCategory" class="text-emerald-400 font-semibold"></span>
                <span>&bull;</span>
                <span id="lightboxCounter" class="text-slate-300"></span>
            </div>
        </div>
        <button type="button" onclick="closeLightbox()" class="text-white/70 hover:text-white bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center transition text-lg" title="Tutup (Esc)">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <div class="relative w-full max-w-5xl flex-grow flex items-center justify-center my-4 overflow-hidden">
        <button type="button" onclick="prevPhoto()" class="absolute left-2 sm:left-4 z-20 text-white/80 hover:text-white bg-black/50 hover:bg-black/80 w-11 h-11 rounded-full flex items-center justify-center transition text-lg shadow-lg" title="Sebelumnya (Panah Kiri)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="w-full h-full flex items-center justify-center">
            <img id="lightboxImg" src="" alt="Galeri SMKN 13 Bandung" class="max-h-[65vh] max-w-full object-contain rounded-2xl shadow-2xl transition duration-300">
        </div>

        <button type="button" onclick="nextPhoto()" class="absolute right-2 sm:right-4 z-20 text-white/80 hover:text-white bg-black/50 hover:bg-black/80 w-11 h-11 rounded-full flex items-center justify-center transition text-lg shadow-lg" title="Berikutnya (Panah Kanan)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

    <div class="w-full max-w-5xl pt-3 border-t border-white/10">
        <div id="lightboxThumbnails" class="flex items-center justify-center gap-2 overflow-x-auto py-1"></div>
    </div>
</div>

<script>
let currentPhotos = [];
let currentIndex = 0;

function openLightbox(title, category, photos) {
    if (!photos || photos.length === 0) return;
    currentPhotos = photos;
    currentIndex = 0;

    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxCategory').textContent = category;

    updateLightboxView();
    renderThumbnails();

    document.getElementById('lightboxModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightboxModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function updateLightboxView() {
    const img = document.getElementById('lightboxImg');
    img.src = currentPhotos[currentIndex];
    document.getElementById('lightboxCounter').textContent = `Foto ${currentIndex + 1} dari ${currentPhotos.length}`;
    highlightActiveThumbnail();
}

function renderThumbnails() {
    const container = document.getElementById('lightboxThumbnails');
    container.innerHTML = '';

    if (currentPhotos.length <= 1) {
        container.parentElement.classList.add('hidden');
        return;
    }
    container.parentElement.classList.remove('hidden');

    currentPhotos.forEach((photoUrl, idx) => {
        const thumb = document.createElement('button');
        thumb.type = 'button';
        thumb.className = `w-14 h-10 rounded-lg overflow-hidden shrink-0 border-2 transition ${idx === currentIndex ? 'border-emerald-400 scale-105' : 'border-transparent opacity-60 hover:opacity-100'}`;
        thumb.onclick = () => {
            currentIndex = idx;
            updateLightboxView();
        };
        thumb.innerHTML = `<img src="${photoUrl}" class="w-full h-full object-cover">`;
        container.appendChild(thumb);
    });
}

function highlightActiveThumbnail() {
    const container = document.getElementById('lightboxThumbnails');
    const buttons = container.querySelectorAll('button');
    buttons.forEach((btn, idx) => {
        if (idx === currentIndex) {
            btn.className = 'w-14 h-10 rounded-lg overflow-hidden shrink-0 border-2 border-emerald-400 scale-105 transition';
        } else {
            btn.className = 'w-14 h-10 rounded-lg overflow-hidden shrink-0 border-2 border-transparent opacity-60 hover:opacity-100 transition';
        }
    });
}

function prevPhoto() {
    if (currentPhotos.length <= 1) return;
    currentIndex = (currentIndex - 1 + currentPhotos.length) % currentPhotos.length;
    updateLightboxView();
}

function nextPhoto() {
    if (currentPhotos.length <= 1) return;
    currentIndex = (currentIndex + 1) % currentPhotos.length;
    updateLightboxView();
}

document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightboxModal');
    if (modal && !modal.classList.contains('hidden')) {
        if (e.key === 'Escape') closeLightbox();
        else if (e.key === 'ArrowLeft') prevPhoto();
        else if (e.key === 'ArrowRight') nextPhoto();
    }
});
</script>
@endsection

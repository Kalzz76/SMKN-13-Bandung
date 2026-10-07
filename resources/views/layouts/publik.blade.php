@php
    $sitePengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SMKN 13 Bandung - Official Portal & CMS Sekolah' }}</title>
    <script>document.documentElement.classList.add("js")</script>

    @if(!empty($sitePengaturan?->logo_url))
        <link rel="icon" type="image/png" href="{{ $sitePengaturan->logo_url }}">
        <link rel="apple-touch-icon" href="{{ $sitePengaturan->logo_url }}">
    @endif

    <!-- Tailwind CSS with Custom Theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'navy-dark': '#0F2A47',
                            'navy-mid': '#17476B',
                            'teal-primary': '#1D6F6A',
                            'teal-light': '#3A9189',
                            'teal-tint': '#E1ECEE',
                            'teal-accent': '#9ED6CF',
                            'brick-red': '#B03A2E',
                            'brick-tint': '#F5E1DE',
                            'brick-dark': '#8A2B22',
                            'bg-page': '#F3F5F8',
                            'bg-card': '#FDFDFE',
                            'text-main': '#17212B',
                            'text-muted': '#5B6773'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .btn-animate {
            transition: all 0.2s;
        }
        button.btn-animate:hover, a.btn-animate:hover {
            transform: translateY(-2px);
        }
        .btn-animate:active {
            transform: scale(0.95);
        }

        /* ===== ANIMASI ===== */
        .js .rv {
            opacity: 0;
        }
        .rv.in {
            animation: rvUp 0.85s cubic-bezier(0.2, 0.7, 0.2, 1) both;
            animation-delay: var(--d, 0ms);
        }
        .rv[data-rv=left].in { animation-name: rvLeft; }
        .rv[data-rv=right].in { animation-name: rvRight; }
        .rv[data-rv=zoom].in { animation-name: rvZoom; }

        @keyframes rvUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: none; }
        }
        @keyframes rvLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: none; }
        }
        @keyframes rvRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: none; }
        }
        @keyframes rvZoom {
            from { opacity: 0; transform: scale(0.95) translateY(16px); }
            to { opacity: 1; transform: none; }
        }
        @keyframes heroIn {
            from { opacity: 0; transform: translateY(26px); filter: blur(6px); }
            to { opacity: 1; transform: none; filter: none; }
        }
        .hero-anim > * {
            opacity: 0;
            animation: heroIn 0.9s cubic-bezier(0.2, 0.7, 0.2, 1) forwards;
        }
        .hero-anim > :nth-child(1) { animation-delay: 0.15s; }
        .hero-anim > :nth-child(2) { animation-delay: 0.3s; }
        .hero-anim > :nth-child(3) { animation-delay: 0.45s; }
        .hero-anim > :nth-child(4) { animation-delay: 0.6s; }
        .hero-anim > :nth-child(5) { animation-delay: 0.75s; }

        @keyframes accIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: none; }
        }
        .accordion-content.expanded > * {
            animation: accIn 0.6s cubic-bezier(0.2, 0.7, 0.2, 1) both;
        }
        .accordion-content.expanded > :nth-child(2) { animation-delay: 0.1s; }
        .accordion-content.expanded > :nth-child(3) { animation-delay: 0.2s; }

        #mainNav.scrolled {
            background: rgba(15, 42, 71, 0.92);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.45);
        }
        .nav-item {
            position: relative;
        }
        .nav-item::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            height: 2px;
            width: 0;
            background: #9ED6CF;
            transition: width 0.3s;
        }
        .nav-item:hover::after, .nav-item.text-teal-accent::after {
            width: 100%;
        }
        .feat-ico {
            transition: all 0.35s cubic-bezier(0.2, 0.7, 0.2, 1);
        }
        .btn-animate:hover .feat-ico {
            background: #1D6F6A;
            color: #fff;
            transform: translateY(-6px) rotate(-4deg) scale(1.06);
            box-shadow: 0 14px 28px -10px rgba(29, 111, 106, 0.55);
        }
        .bcard-img, .ek-img {
            transition: transform 0.9s cubic-bezier(0.2, 0.7, 0.2, 1);
        }
        .bcard:hover .bcard-img, .ekc:hover .ek-img {
            transform: scale(1.06);
        }
        .orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(60px);
            opacity: 0.35;
            animation: orbDrift 16s ease-in-out infinite alternate;
            pointer-events: none;
        }
        .orb-a {
            width: 18rem;
            height: 18rem;
            background: #3A9189;
            top: -5rem;
            right: -4rem;
        }
        .orb-b {
            width: 16rem;
            height: 16rem;
            background: #17476B;
            bottom: -6rem;
            left: -3rem;
            animation-delay: -8s;
        }
        @keyframes orbDrift {
            to { transform: translate(-30px, 24px) scale(1.15); }
        }
        @media (prefers-reduced-motion: reduce) {
            .js .rv { opacity: 1; }
            .rv.in, .hero-anim > *, .accordion-content.expanded > *, .orb { animation: none !important; }
            .hero-anim > * { opacity: 1; }
        }

        .accordion-content {
            transition: max-height 0.35s ease-out, padding 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }
        .accordion-content.expanded {
            max-height: 5000px;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .accordion-icon {
            transition: transform 0.3s;
        }
        .accordion-btn.expanded .accordion-icon {
            transform: rotate(180deg);
        }

        .timeline-line::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 1.25rem;
            width: 4px;
            background: #E1ECEE;
        }

        /* Marquee Guru */
        .marquee {
            overflow: hidden;
            padding: 1rem 0;
            -webkit-mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
            mask-image: linear-gradient(90deg, transparent, #000 6%, #000 94%, transparent);
        }
        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 180s linear infinite;
        }
        .marquee:hover .marquee-track {
            animation-play-state: paused;
        }
        .marquee-group {
            display: flex;
            gap: 1.5rem;
            padding-right: 1.5rem;
            flex-shrink: 0;
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-color: #0F2A47;
            opacity: 0;
            transform: scale(1.1);
            transition: opacity 1.3s ease, transform 7s ease-out;
        }
        .hero-slide.active {
            opacity: 1;
            transform: scale(1);
        }
        .slider-dot {
            width: 0.6rem;
            height: 0.6rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.45);
            transition: all 0.3s;
        }
        .slider-dot.active {
            width: 1.8rem;
            background: #9ED6CF;
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-slide {
                transition: opacity 0.5s;
                transform: none !important;
            }
        }

        .gal-card {
            position: absolute;
            top: 10px;
            left: 50%;
            height: 440px;
            border-radius: 1.5rem;
            overflow: hidden;
            cursor: pointer;
            background: #0f2a47;
            box-shadow: 0 25px 50px -12px rgba(15, 42, 71, 0.35);
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.7s ease;
        }
        .gal-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .gal-shade {
            position: absolute;
            inset: 0;
            background: rgba(15, 42, 71, 0.45);
            transition: opacity 0.7s;
        }
        .gal-card.is-active .gal-shade {
            opacity: 0;
        }
        .gal-info {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 1.5rem;
            color: #fff;
            background: linear-gradient(to top, rgba(15, 42, 71, 0.95), transparent);
            opacity: 0;
            transition: opacity 0.7s;
        }
        .gal-card.is-active .gal-info {
            opacity: 1;
        }

        .sej-thumb {
            opacity: 0.6;
            transition: opacity 0.3s, border-color 0.3s, box-shadow 0.3s;
        }
        .sej-thumb:hover {
            opacity: 0.9;
        }
        .sej-thumb.active {
            opacity: 1;
            border-color: #1D6F6A;
            box-shadow: 0 0 0 3px rgba(29, 111, 106, 0.25);
        }
    </style>
</head>

<body class="bg-bg-page text-text-main antialiased min-h-screen flex flex-col overflow-x-hidden">
    <div id="scrollBar" class="fixed top-0 left-0 h-1 w-0 bg-gradient-to-r from-teal-accent to-teal-primary z-[90] pointer-events-none"></div>

    <nav id="mainNav" class="bg-navy-dark text-white shadow-md sticky top-0 z-50 border-b border-navy-mid transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('beranda') }}" class="flex items-center space-x-3 btn-animate">
                    @if(!empty($sitePengaturan?->logo_url))
                        <img src="{{ $sitePengaturan->logo_url }}" alt="Logo SMKN 13 Bandung"
                            class="h-12 w-12 object-contain drop-shadow">
                    @else
                        <img src="{{ asset('Assets/LOGOS.png') }}" alt="Logo SMKN 13 Bandung"
                            class="h-12 w-12 object-contain drop-shadow">
                    @endif
                    <div>
                        <span class="text-base sm:text-lg font-bold block leading-tight tracking-tight">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 BANDUNG' }}</span>
                        <span class="text-xs text-teal-accent tracking-wider uppercase font-semibold">Official Portal</span>
                    </div>
                </a>

                <div class="hidden lg:flex items-center space-x-7 text-sm font-bold uppercase tracking-wider">
                    <a href="{{ route('beranda') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('beranda') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Beranda
                    </a>

                    <div class="relative group">
                        <a href="{{ route('publik.profil') }}"
                            class="nav-item flex items-center space-x-1.5 py-2 transition {{ request()->routeIs('publik.profil') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                            <span>Profil</span>
                            <i class="fa-solid fa-chevron-down text-[10px] group-hover:rotate-180 transition-transform"></i>
                        </a>
                        <div class="absolute left-0 w-60 bg-white rounded-2xl shadow-xl py-2 hidden group-hover:block z-50 border border-teal-tint">
                            <a href="{{ route('publik.profil') }}#visi-misi"
                                class="block px-5 py-3 text-sm text-navy-dark hover:bg-teal-tint font-bold transition">Visi & Misi</a>
                            <a href="{{ route('publik.profil') }}#sejarah"
                                class="block px-5 py-3 text-sm text-navy-dark hover:bg-teal-tint font-bold transition">Sejarah Sekolah</a>
                            <a href="{{ route('publik.profil') }}#struktur"
                                class="block px-5 py-3 text-sm text-navy-dark hover:bg-teal-tint font-bold border-t border-teal-tint transition">Struktur Organisasi</a>
                            <a href="{{ route('publik.profil') }}#pengajar"
                                class="block px-5 py-3 text-sm text-navy-dark hover:bg-teal-tint font-bold border-t border-teal-tint transition">Tenaga Pengajar</a>
                        </div>
                    </div>

                    <a href="{{ route('publik.jurusan') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('publik.jurusan') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Jurusan
                    </a>
                    <a href="{{ route('publik.berita') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('publik.berita*') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Berita
                    </a>
                    <a href="{{ route('publik.galeri') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('publik.galeri') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Galeri
                    </a>
                    <a href="{{ route('publik.ekstrakurikuler') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('publik.ekstrakurikuler') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Ekskul
                    </a>
                    <a href="{{ route('publik.kontak') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('publik.kontak') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Kontak
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="btn-animate bg-teal-primary hover:bg-teal-light text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-1.5">
                                <i class="fa-solid fa-gauge"></i>
                                <span class="hidden sm:inline">Admin</span>
                            </a>
                        @elseif(auth()->user()->role === 'guru')
                            <a href="{{ route('guru.dashboard') }}"
                                class="btn-animate bg-teal-primary hover:bg-teal-light text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-1.5">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <span class="hidden sm:inline">Portal Guru</span>
                            </a>
                        @elseif(auth()->user()->role === 'sekretaris')
                            <a href="{{ route('sekretaris.dashboard') }}"
                                class="btn-animate bg-amber-600 hover:bg-amber-500 text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-1.5">
                                <i class="fa-solid fa-user-pen"></i>
                                <span class="hidden sm:inline">Portal Sekretaris</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="btn-animate bg-teal-primary hover:bg-teal-light px-4 sm:px-6 py-2 sm:py-2.5 rounded-full text-sm font-bold shadow-md text-white inline-flex items-center">
                            <i class="fa-solid fa-lock sm:mr-2"></i><span class="hidden sm:inline">Login</span>
                        </a>
                    @endauth
                    <button id="menuBtn" onclick="toggleMobileMenu()" aria-label="Menu"
                        class="lg:hidden w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                        <i id="menuIcon" class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenu"
            class="hidden lg:hidden border-t border-navy-mid bg-navy-dark px-4 pb-4 max-h-[75vh] overflow-y-auto">
            <a href="{{ route('beranda') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider border-b border-navy-mid {{ request()->routeIs('beranda') ? 'text-teal-accent' : 'text-white' }}">Beranda</a>
            <div class="py-3 border-b border-navy-mid">
                <span class="block font-bold uppercase text-sm tracking-wider text-teal-accent mb-2">Profil</span>
                <a href="{{ route('publik.profil') }}#visi-misi" class="block w-full text-left py-2 pl-4 text-sm font-semibold text-slate-200 hover:text-teal-accent">Visi & Misi</a>
                <a href="{{ route('publik.profil') }}#sejarah" class="block w-full text-left py-2 pl-4 text-sm font-semibold text-slate-200 hover:text-teal-accent">Sejarah Sekolah</a>
                <a href="{{ route('publik.profil') }}#struktur" class="block w-full text-left py-2 pl-4 text-sm font-semibold text-slate-200 hover:text-teal-accent">Struktur Organisasi</a>
                <a href="{{ route('publik.profil') }}#pengajar" class="block w-full text-left py-2 pl-4 text-sm font-semibold text-slate-200 hover:text-teal-accent">Tenaga Pengajar</a>
            </div>
            <a href="{{ route('publik.jurusan') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider border-b border-navy-mid {{ request()->routeIs('publik.jurusan') ? 'text-teal-accent' : 'text-white' }}">Jurusan</a>
            <a href="{{ route('publik.berita') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider border-b border-navy-mid {{ request()->routeIs('publik.berita*') ? 'text-teal-accent' : 'text-white' }}">Berita</a>
            <a href="{{ route('publik.galeri') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider border-b border-navy-mid {{ request()->routeIs('publik.galeri') ? 'text-teal-accent' : 'text-white' }}">Galeri</a>
            <a href="{{ route('publik.ekstrakurikuler') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider border-b border-navy-mid {{ request()->routeIs('publik.ekstrakurikuler') ? 'text-teal-accent' : 'text-white' }}">Ekskul</a>
            <a href="{{ route('publik.kontak') }}"
                class="block w-full text-left py-3 font-bold uppercase text-sm tracking-wider {{ request()->routeIs('publik.kontak') ? 'text-teal-accent' : 'text-white' }}">Kontak</a>
        </div>
    </nav>

    <!-- CONTENT WRAPPER -->
    <main class="flex-grow flex flex-col bg-bg-page">
        @yield('content')
    </main>

    <!-- FOOTER RESMI (NAVY DARK 4-KOLOM) -->
    <footer class="bg-navy-dark text-white py-14 border-t-4 border-teal-primary mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10">
            <!-- Kolom 1: Profil Brand -->
            <div>
                <div class="flex items-center space-x-3 mb-6">
                    @if(!empty($sitePengaturan?->logo_url))
                        <img src="{{ $sitePengaturan->logo_url }}" alt="Logo SMKN 13"
                            class="h-10 w-10 object-contain drop-shadow">
                    @else
                        <img src="{{ asset('Assets/LOGOS.png') }}" alt="Logo SMKN 13"
                            class="h-10 w-10 object-contain drop-shadow">
                    @endif
                    <span class="font-black text-xl tracking-tight">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</span>
                </div>
                <p class="text-sm text-teal-tint/80 leading-relaxed">
                    {{ $sitePengaturan->deskripsi_singkat ?? 'Pusat pendidikan vokasi berbasis Teknologi dan Sains di Kota Bandung, mencetak lulusan berakhlak mulia dan berdaya saing global.' }}
                </p>
                @if(!empty($sitePengaturan->npsn))
                    <span class="inline-block mt-3 text-xs font-bold text-teal-accent bg-navy-mid px-3 py-1 rounded-full">
                        NPSN: {{ $sitePengaturan->npsn }}
                    </span>
                @endif
            </div>

            <!-- Kolom 2: Tautan Cepat -->
            <div>
                <h4 class="text-teal-accent font-bold mb-5 text-lg">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('publik.profil') }}" class="hover:text-teal-accent transition">Profil & Visi Misi</a></li>
                    <li><a href="{{ route('publik.jurusan') }}" class="hover:text-teal-accent transition">Konsentrasi Keahlian</a></li>
                    <li><a href="{{ route('publik.berita') }}" class="hover:text-teal-accent transition">Warta & Berita Terkini</a></li>
                    <li><a href="{{ route('publik.galeri') }}" class="hover:text-teal-accent transition">Galeri & Dokumentasi</a></li>
                    <li><a href="{{ route('publik.ekstrakurikuler') }}" class="hover:text-teal-accent transition">Ekstrakurikuler Pilihan</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Media Sosial -->
            <div>
                <h4 class="text-teal-accent font-bold mb-5 text-lg">Media Sosial</h4>
                <p class="text-xs text-teal-tint/70 mb-4">Ikuti kegiatan dan kabar terbaru kami melalui media sosial resmi:</p>
                <div class="flex space-x-3">
                    <a href="https://www.instagram.com/smkn13bandung?stkn=NnhydHlmaTV2NHMy" target="_blank" rel="noopener noreferrer"
                        aria-label="Instagram SMKN 13 Bandung"
                        class="w-10 h-10 rounded-full bg-teal-primary hover:bg-teal-light text-white flex items-center justify-center transition shadow">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/@smkn13bandungofficial" target="_blank" rel="noopener noreferrer"
                        aria-label="YouTube SMKN 13 Bandung"
                        class="w-10 h-10 rounded-full bg-teal-primary hover:bg-teal-light text-white flex items-center justify-center transition shadow">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                    <a href="https://www.facebook.com/smkn13bandung" target="_blank" rel="noopener noreferrer"
                        aria-label="Facebook SMKN 13 Bandung"
                        class="w-10 h-10 rounded-full bg-teal-primary hover:bg-teal-light text-white flex items-center justify-center transition shadow">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                </div>
            </div>

            <!-- Kolom 4: Kontak Resmi -->
            <div>
                <h4 class="text-teal-accent font-bold mb-5 text-lg">Kontak Resmi</h4>
                <div class="space-y-2 text-sm text-teal-tint/80">
                    <p class="flex items-start space-x-2">
                        <i class="fa-solid fa-location-dot w-5 text-teal-accent mt-1"></i>
                        <span>{{ $sitePengaturan->alamat ?? 'Jl. Soekarno-Hatta Km. 10, Kota Bandung, Jawa Barat 40294' }}</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <i class="fa-solid fa-envelope w-5 text-teal-accent"></i>
                        <span>{{ $sitePengaturan->email ?? 'smkn13bandung@gmail.com' }}</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <i class="fa-solid fa-phone w-5 text-teal-accent"></i>
                        <span>{{ $sitePengaturan->telepon ?? '(022) 7830182' }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-navy-mid text-center text-sm text-teal-tint/60">
            &copy; {{ date('Y') }} {{ $sitePengaturan->nama_sekolah ?? 'SMK Negeri 13 Bandung' }}. All rights reserved.
        </div>
    </footer>

    <div id="galLightbox" class="fixed inset-0 z-[80] hidden items-center justify-center bg-navy-dark/95 backdrop-blur-md p-4 sm:p-10" onclick="if(event.target===this)galClose()">
        <button onclick="galClose()" aria-label="Tutup" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center z-10">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <button onclick="galLbStep(-1)" aria-label="Sebelumnya" class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center z-10">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button onclick="galLbStep(1)" aria-label="Berikutnya" class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center z-10">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
        <figure class="max-w-6xl w-full m-0 flex flex-col items-center">
            <img id="galLbImg" src="" alt="" class="max-h-[80vh] max-w-full object-contain rounded-2xl shadow-2xl">
            <figcaption class="mt-4 text-center text-white">
                <span id="galLbKat" class="text-xs font-black uppercase tracking-widest text-teal-accent"></span>
                <h5 id="galLbJudul" class="text-xl font-bold mt-1"></h5>
                <span id="galLbCount" class="text-sm text-teal-tint/70"></span>
            </figcaption>
        </figure>
    </div>

    @include('partials.modal-pesan')

    <script>
        function toggleMobileMenu(force) {
            const m = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            if (!m) return;
            const open = force === undefined ? m.classList.contains('hidden') : force;
            m.classList.toggle('hidden', !open);
            if (icon) {
                icon.className = 'fa-solid ' + (open ? 'fa-xmark' : 'fa-bars') + ' text-lg';
            }
        }
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) toggleMobileMenu(false);
        });

        /* ===== SLIDER BACKGROUND ===== */
        const ASSET_BASE = "{{ asset('') }}".replace(/\/$/, '');
        const SLIDER_SETS = {
            home: ['home1.webp', 'home2.webp', 'home3.jpg', 'home4.jpg', 'home5.jpg', 'home6.jpeg', 'home7.jpg', 'home8.jpg', 'home9.jpg', 'home10.jpg', 'home11.jpg', 'home12.jpeg'],
            jurusan: ['jurusan1.webp', 'jurusan2.jpg', 'jurusan3.jpg', 'jurusan4.jpeg', 'jurusan5.jpeg', 'jurusan6.jpeg']
        };
        const EXT_TRY = ['jpg', 'jpeg', 'webp', 'png', 'jfif', 'avif'];

        function resolveImg(file, cb) {
            const m = file.match(/^(.*)\.([a-z0-9]+)$/i) || [null, file, ''];
            const base = m[1], ext = (m[2] || '').toLowerCase(), c = [];
            const dirs = ['Assets', 'assets'];
            const exts = ext ? [ext, ...EXT_TRY.filter(x => x !== ext)] : EXT_TRY;

            exts.forEach(x => dirs.forEach(d => {
                c.push(ASSET_BASE + '/' + d + '/' + encodeURIComponent(base + '.' + x));
                c.push('/' + d + '/' + encodeURIComponent(base + '.' + x));
            }));
            dirs.forEach(d => {
                c.push(ASSET_BASE + '/' + d + '/' + encodeURIComponent(base));
                c.push('/' + d + '/' + encodeURIComponent(base));
            });
            c.push('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1600&q=80');

            let i = 0;
            const t = () => {
                if (i >= c.length) return;
                const im = new Image(), u = c[i];
                im.onload = () => cb(u);
                im.onerror = () => { i++; t(); };
                im.src = u;
            };
            t();
        }

        function initSliders() {
            document.querySelectorAll('[data-slider]').forEach(box => {
                const sliderKey = box.dataset.slider || 'home';
                const set = SLIDER_SETS[sliderKey] || SLIDER_SETS.home;
                const off = (+box.dataset.offset || 0) % set.length;
                const files = set.slice(off).concat(set.slice(0, off));

                box.innerHTML = files.map((f, i) => `<div class="hero-slide ${i ? '' : 'active'}"></div>`).join('') +
                    (box.dataset.dots ? `<div class="absolute bottom-5 inset-x-0 flex justify-center gap-1.5 sm:gap-2 z-20 px-4 flex-wrap">${files.map((_, i) => `<button type="button" aria-label="Slide ${i+1}" class="slider-dot ${i ? '' : 'active'}"></button>`).join('')}</div>` : '');

                const slides = [...box.querySelectorAll('.hero-slide')];
                const dots = [...box.querySelectorAll('.slider-dot')];

                const load = i => {
                    i = (i + slides.length) % slides.length;
                    const sl = slides[i];
                    if (!sl || sl.dataset.loaded) return;
                    sl.dataset.loaded = '1';
                    resolveImg(files[i], u => { sl.style.backgroundImage = `url('${u}')`; });
                };

                let cur = 0, timer;
                load(0);
                load(1);

                const go = n => {
                    if (!slides[cur]) return;
                    slides[cur].classList.remove('active');
                    if (dots[cur]) dots[cur].classList.remove('active');
                    cur = (n + slides.length) % slides.length;
                    load(cur);
                    load(cur + 1);
                    if (slides[cur]) slides[cur].classList.add('active');
                    if (dots[cur]) dots[cur].classList.add('active');
                };

                const start = () => {
                    clearInterval(timer);
                    timer = setInterval(() => go(cur + 1), 5500);
                };

                dots.forEach((d, i) => d.addEventListener('click', () => { go(i); start(); }));
                start();
            });
        }
        document.addEventListener('DOMContentLoaded', initSliders);

        let galItemsData = [];
        let galIdx = 0;

        function setGalItemsData(items) {
            galItemsData = items || [];
        }

        function galOpen(i) {
            if (!galItemsData.length) return;
            galIdx = i;
            galLbShow();
            const lb = document.getElementById('galLightbox');
            if (lb) {
                lb.classList.remove('hidden');
                lb.classList.add('flex');
            }
            document.body.style.overflow = 'hidden';
        }

        function galLbShow() {
            const x = galItemsData[galIdx];
            if (!x) return;
            const img = document.getElementById('galLbImg');
            const kat = document.getElementById('galLbKat');
            const judul = document.getElementById('galLbJudul');
            const count = document.getElementById('galLbCount');
            if (img) {
                img.src = x.src ? x.src.replace(/w=\d+/, 'w=1600') : '';
                img.alt = x.judul || '';
            }
            if (kat) kat.innerText = x.kat || '';
            if (judul) judul.innerText = x.judul || '';
            if (count) count.innerText = (galIdx + 1) + ' / ' + galItemsData.length;
        }

        function galLbStep(d) {
            const N = galItemsData.length;
            if (!N) return;
            galIdx = (galIdx + d + N) % N;
            galLbShow();
        }

        function galClose() {
            const lb = document.getElementById('galLightbox');
            if (lb) {
                lb.classList.add('hidden');
                lb.classList.remove('flex');
            }
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => {
            const lb = document.getElementById('galLightbox');
            if (!lb || lb.classList.contains('hidden')) return;
            if (e.key === 'Escape') galClose();
            else if (e.key === 'ArrowLeft') galLbStep(-1);
            else if (e.key === 'ArrowRight') galLbStep(1);
        });

        /* ===== ANIMASI: reveal on scroll, progress bar, navbar ===== */
        let rvIO;
        function initReveal() {
            const els = [...document.querySelectorAll('.rv:not([data-rvq])')].filter(e => e.getClientRects().length);
            if (!('IntersectionObserver' in window) || matchMedia('(prefers-reduced-motion:reduce)').matches) {
                els.forEach(e => e.classList.remove('rv'));
                return;
            }
            rvIO = rvIO || new IntersectionObserver(es => es.forEach(en => {
                if (!en.isIntersecting) return;
                const el = en.target;
                rvIO.unobserve(el);
                el.classList.add('in');
                el.addEventListener('animationend', ev => {
                    if (ev.target !== el) return;
                    el.classList.remove('rv', 'in');
                    el.style.removeProperty('--d');
                });
            }), { threshold: 0.1, rootMargin: '0px 0px -6% 0px' });
            const cnt = new Map();
            els.forEach(el => {
                el.dataset.rvq = '1';
                const p = el.parentElement, n = cnt.get(p) || 0;
                cnt.set(p, n + 1);
                el.style.setProperty('--d', (n % 4) * 100 + 'ms');
                rvIO.observe(el);
            });
        }
        document.addEventListener('DOMContentLoaded', () => requestAnimationFrame(initReveal));
        document.addEventListener('DOMContentLoaded', () => {
            let t;
            new MutationObserver(() => {
                cancelAnimationFrame(t);
                t = requestAnimationFrame(initReveal);
            }).observe(document.body, { childList: true, subtree: true });
        });

        window.addEventListener('scroll', () => {
            const d = document.documentElement;
            const max = d.scrollHeight - d.clientHeight;
            const n = document.getElementById('mainNav');
            const sb = document.getElementById('scrollBar');
            if (sb) sb.style.width = (max > 0 ? (window.scrollY / max * 100) : 0) + '%';
            if (n) n.classList.toggle('scrolled', window.scrollY > 20);
        }, { passive: true });
    </script>
</body>

</html>
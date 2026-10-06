@php
    $sitePengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SMKN 13 Bandung - Official Portal & CMS Sekolah' }}</title>

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
        .btn-animate:active {
            transform: scale(0.96);
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
            animation: marquee 30s linear infinite;
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

        /* 3D Coverflow Galeri */
        .gal-card {
            position: absolute;
            top: 10px;
            left: 50%;
            height: 440px;
            border-radius: 1.5rem;
            overflow: hidden;
            cursor: pointer;
            background: #E1ECEE;
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
    <!-- NAVBAR UTAMA -->
    <nav class="bg-navy-dark text-white shadow-md sticky top-0 z-50 border-b border-navy-mid">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Brand / Logo -->
                <a href="{{ route('beranda') }}" class="flex items-center space-x-3 btn-animate">
                    @if(!empty($sitePengaturan->logo) && file_exists(public_path('storage/' . $sitePengaturan->logo)))
                        <img src="{{ asset('storage/' . $sitePengaturan->logo) }}" alt="Logo SMKN 13 Bandung"
                            class="h-12 w-12 object-contain bg-white rounded-xl p-1 shadow">
                    @else
                        <img src="{{ asset('Assets/LOGOS.jpg') }}" alt="Logo SMKN 13 Bandung"
                            class="h-12 w-12 object-contain bg-white rounded-xl p-1 shadow">
                    @endif
                    <div>
                        <span class="text-lg font-black block leading-tight tracking-tight">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 BANDUNG' }}</span>
                        <span class="text-xs text-teal-accent tracking-wider uppercase font-semibold">Official Portal</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center space-x-7 text-sm font-bold uppercase tracking-wider">
                    <a href="{{ route('beranda') }}"
                        class="nav-item py-2 transition {{ request()->routeIs('beranda') ? 'text-teal-accent' : 'text-white hover:text-teal-accent' }}">
                        Beranda
                    </a>

                    <!-- Dropdown Profil -->
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

                <!-- Auth / Login Button -->
                <div class="hidden sm:flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="btn-animate bg-teal-primary hover:bg-teal-light text-white px-5 py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-2">
                                <i class="fa-solid fa-gauge"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        @elseif(auth()->user()->role === 'guru')
                            <a href="{{ route('guru.dashboard') }}"
                                class="btn-animate bg-teal-primary hover:bg-teal-light text-white px-5 py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-2">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                <span>Portal Guru</span>
                            </a>
                        @elseif(auth()->user()->role === 'sekretaris')
                            <a href="{{ route('sekretaris.dashboard') }}"
                                class="btn-animate bg-amber-600 hover:bg-amber-500 text-white px-5 py-2.5 rounded-full text-xs font-bold shadow-md flex items-center space-x-2">
                                <i class="fa-solid fa-user-pen"></i>
                                <span>Portal Sekretaris</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                            class="btn-animate bg-teal-primary hover:bg-teal-light px-6 py-2.5 rounded-full text-sm font-bold shadow-md text-white inline-flex items-center">
                            <i class="fa-solid fa-lock mr-2"></i>Login
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger Button -->
                <div class="lg:hidden flex items-center space-x-2">
                    <button type="button" onclick="toggleMobileMenu()" aria-label="Menu"
                        class="text-white p-2.5 rounded-xl hover:bg-navy-mid transition">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu"
            class="hidden lg:hidden bg-navy-dark px-5 pt-3 pb-6 space-y-2.5 border-t border-navy-mid text-sm font-semibold">
            <a href="{{ route('beranda') }}"
                class="block py-2 {{ request()->routeIs('beranda') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Beranda</a>
            <div class="py-1">
                <span class="text-xs uppercase tracking-wider text-teal-accent font-bold block mb-1">Profil Sekolah</span>
                <div class="pl-3 space-y-2 border-l-2 border-navy-mid">
                    <a href="{{ route('publik.profil') }}#visi-misi" class="block py-1 text-slate-200 hover:text-teal-accent">Visi & Misi</a>
                    <a href="{{ route('publik.profil') }}#sejarah" class="block py-1 text-slate-200 hover:text-teal-accent">Sejarah Sekolah</a>
                    <a href="{{ route('publik.profil') }}#struktur" class="block py-1 text-slate-200 hover:text-teal-accent">Struktur Organisasi</a>
                    <a href="{{ route('publik.profil') }}#pengajar" class="block py-1 text-slate-200 hover:text-teal-accent">Tenaga Pengajar</a>
                </div>
            </div>
            <a href="{{ route('publik.jurusan') }}"
                class="block py-2 {{ request()->routeIs('publik.jurusan') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Jurusan</a>
            <a href="{{ route('publik.berita') }}"
                class="block py-2 {{ request()->routeIs('publik.berita*') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Berita</a>
            <a href="{{ route('publik.galeri') }}"
                class="block py-2 {{ request()->routeIs('publik.galeri') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Galeri</a>
            <a href="{{ route('publik.ekstrakurikuler') }}"
                class="block py-2 {{ request()->routeIs('publik.ekstrakurikuler') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Ekskul</a>
            <a href="{{ route('publik.kontak') }}"
                class="block py-2 {{ request()->routeIs('publik.kontak') ? 'text-teal-accent font-bold' : 'text-white hover:text-teal-accent' }}">Kontak</a>
            <div class="pt-3 border-t border-navy-mid">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block w-full text-center bg-teal-primary text-white py-3 rounded-full font-bold">Dashboard Admin</a>
                    @elseif(auth()->user()->role === 'guru')
                        <a href="{{ route('guru.dashboard') }}"
                            class="block w-full text-center bg-teal-primary text-white py-3 rounded-full font-bold">Portal Guru</a>
                    @elseif(auth()->user()->role === 'sekretaris')
                        <a href="{{ route('sekretaris.dashboard') }}"
                            class="block w-full text-center bg-amber-600 text-white py-3 rounded-full font-bold">Portal Sekretaris</a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="w-full text-center bg-teal-primary text-white py-3 rounded-full font-bold flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-lock"></i>
                        <span>Login Portal</span>
                    </a>
                @endauth
            </div>
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
                    @if(!empty($sitePengaturan->logo) && file_exists(public_path('storage/' . $sitePengaturan->logo)))
                        <img src="{{ asset('storage/' . $sitePengaturan->logo) }}" alt="Logo SMKN 13"
                            class="h-10 w-10 object-contain bg-white rounded-xl p-1">
                    @else
                        <img src="{{ asset('Assets/LOGOS.jpg') }}" alt="Logo SMKN 13"
                            class="h-10 w-10 object-contain bg-white rounded-xl p-1">
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
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
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

    @include('partials.modal-pesan')

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }
    </script>
</body>

</html>
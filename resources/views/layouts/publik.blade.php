@php
    $sitePengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SMKN 13 Bandung - Portal & CMS Sekolah' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <nav class="bg-emerald-900 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ url('/') }}" class="flex items-center space-x-3">
                    <img src="{{ !empty($sitePengaturan->logo) && file_exists(public_path('storage/' . $sitePengaturan->logo)) ? asset('storage/' . $sitePengaturan->logo) : asset('images/logo-smkn13.png') }}" alt="Logo SMKN 13 Bandung" class="h-12 w-12 object-contain flex-shrink-0">
                    <div>
                        <span class="text-lg font-bold tracking-tight block leading-tight">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 BANDUNG' }}</span>
                        <span class="text-xs text-emerald-200 tracking-wider">Official Portal & CMS</span>
                    </div>
                </a>

                <div class="hidden lg:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Beranda</a>
                    <a href="{{ route('publik.profil') }}" class="{{ request()->routeIs('publik.profil') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Profil & Sejarah</a>
                    <a href="{{ route('publik.jurusan') }}" class="{{ request()->routeIs('publik.jurusan') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Jurusan</a>
                    <a href="{{ route('publik.berita') }}" class="{{ request()->routeIs('publik.berita*') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Berita & Informasi</a>
                    <a href="{{ route('publik.galeri') }}" class="{{ request()->routeIs('publik.galeri') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Galeri & Fasilitas</a>
                    <a href="{{ route('publik.kontak') }}" class="{{ request()->routeIs('publik.kontak') ? 'text-emerald-300 font-bold border-b-2 border-emerald-400 pb-1' : 'hover:text-emerald-200 transition' }}">Kontak</a>
                    <div class="relative group">
                        <button type="button" class="hover:text-emerald-200 transition flex items-center space-x-1 {{ request()->routeIs('publik.ekstrakurikuler') || request()->routeIs('publik.prestasi') ? 'text-emerald-300 font-bold' : '' }}">
                            <span>Kesiswaan</span>
                            <i class="fa-solid fa-chevron-down text-xs ml-1"></i>
                        </button>
                        <div class="absolute left-0 mt-2 w-48 bg-white text-slate-800 rounded-xl shadow-lg border border-slate-100 py-2 hidden group-hover:block z-50">
                            <a href="{{ route('publik.ekstrakurikuler') }}" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-emerald-700 {{ request()->routeIs('publik.ekstrakurikuler') ? 'text-emerald-700 font-bold bg-slate-50' : '' }}">Ekstrakurikuler</a>
                            <a href="{{ route('publik.prestasi') }}" class="block px-4 py-2 text-sm hover:bg-slate-50 hover:text-emerald-700 {{ request()->routeIs('publik.prestasi') ? 'text-emerald-700 font-bold bg-slate-50' : '' }}">Prestasi</a>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="bg-emerald-700 hover:bg-emerald-600 px-4 py-2 rounded-xl text-sm font-semibold transition shadow border border-emerald-600 flex items-center space-x-2">
                                <i class="fa-solid fa-gauge"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        @elseif(auth()->user()->role === 'guru')
                            <a href="{{ route('guru.dashboard') }}" class="bg-emerald-700 hover:bg-emerald-600 px-4 py-2 rounded-xl text-sm font-semibold transition shadow border border-emerald-600 flex items-center space-x-2">
                                <i class="fa-solid fa-gauge"></i>
                                <span>Portal Guru</span>
                            </a>
                        @elseif(auth()->user()->role === 'sekretaris')
                            <a href="{{ route('sekretaris.dashboard') }}" class="bg-amber-600 hover:bg-amber-500 px-4 py-2 rounded-xl text-sm font-semibold transition shadow border border-amber-500 flex items-center space-x-2">
                                <i class="fa-solid fa-gauge"></i>
                                <span>Portal Sekretaris</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-emerald-700 hover:bg-emerald-600 px-4 py-2 rounded-xl text-sm font-semibold transition shadow border border-emerald-600 inline-flex items-center">
                            <i class="fa-solid fa-user-shield mr-2"></i> Login Portal
                        </a>
                    @endauth
                </div>

                <div class="lg:hidden flex items-center space-x-2">
                    <button type="button" onclick="toggleMobileMenu()" class="text-white p-2 rounded-xl hover:bg-emerald-800 transition">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobileMenu" class="hidden lg:hidden bg-emerald-950 px-4 pt-2 pb-6 space-y-2 border-t border-emerald-800 text-sm">
            <a href="{{ route('beranda') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('beranda') ? 'text-emerald-300 font-bold' : '' }}">Beranda</a>
            <a href="{{ route('publik.profil') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.profil') ? 'text-emerald-300 font-bold' : '' }}">Profil & Sejarah</a>
            <a href="{{ route('publik.jurusan') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.jurusan') ? 'text-emerald-300 font-bold' : '' }}">Jurusan</a>
            <a href="{{ route('publik.berita') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.berita*') ? 'text-emerald-300 font-bold' : '' }}">Berita & Informasi</a>
            <a href="{{ route('publik.galeri') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.galeri') ? 'text-emerald-300 font-bold' : '' }}">Galeri & Fasilitas</a>
            <a href="{{ route('publik.kontak') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.kontak') ? 'text-emerald-300 font-bold' : '' }}">Kontak</a>
            <a href="{{ route('publik.ekstrakurikuler') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.ekstrakurikuler') ? 'text-emerald-300 font-bold' : '' }}">Ekstrakurikuler</a>
            <a href="{{ route('publik.prestasi') }}" class="block py-2 hover:text-emerald-300 {{ request()->routeIs('publik.prestasi') ? 'text-emerald-300 font-bold' : '' }}">Prestasi</a>
            <div class="pt-2 border-t border-emerald-800">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block w-full text-center bg-emerald-700 py-2.5 rounded-xl font-bold">Dashboard Admin</a>
                    @elseif(auth()->user()->role === 'guru')
                        <a href="{{ route('guru.dashboard') }}" class="block w-full text-center bg-emerald-700 py-2.5 rounded-xl font-bold">Portal Guru</a>
                    @elseif(auth()->user()->role === 'sekretaris')
                        <a href="{{ route('sekretaris.dashboard') }}" class="block w-full text-center bg-amber-600 py-2.5 rounded-xl font-bold">Portal Sekretaris</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="block w-full text-center bg-emerald-700 py-2.5 rounded-xl font-bold">
                        <i class="fa-solid fa-user-shield mr-2"></i> Login Portal
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="flex-grow flex flex-col">
        @yield('content')
    </div>

    <footer class="bg-slate-950 text-slate-400 py-12 border-t border-slate-800 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <img src="{{ !empty($sitePengaturan->logo) && file_exists(public_path('storage/' . $sitePengaturan->logo)) ? asset('storage/' . $sitePengaturan->logo) : asset('images/logo-smkn13.png') }}" alt="Logo SMKN 13 Bandung" class="h-10 w-10 object-contain flex-shrink-0">
                    <span class="text-white font-bold text-lg">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</span>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">{{ $sitePengaturan->alamat ?? 'Jl. Soekarno-Hatta KM. 10, Kelurahan Jatisari, Kecamatan Buahbatu, Kota Bandung, Jawa Barat, Kode Pos 40286' }}</p>
                @if(!empty($sitePengaturan->npsn))
                    <p class="text-xs text-emerald-400 mt-2 font-medium">NPSN: {{ $sitePengaturan->npsn }}</p>
                @endif
            </div>
            <div>
                <h4 class="text-white font-bold mb-3">Tautan Cepat</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('publik.profil') }}" class="hover:text-emerald-400">Profil & Sejarah</a></li>
                    <li><a href="{{ route('publik.jurusan') }}" class="hover:text-emerald-400">Jurusan Keahlian</a></li>
                    <li><a href="{{ route('publik.berita') }}" class="hover:text-emerald-400">Berita & Informasi</a></li>
                    <li><a href="{{ route('publik.galeri') }}" class="hover:text-emerald-400">Galeri Fasilitas</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-3">Jam Operasional</h4>
                <p class="text-sm text-slate-300">Senin – Jumat: 07.00 – 16.00 WIB</p>
                <p class="text-sm mt-1 text-slate-500">Sabtu – Minggu & Libur Nasional: Tutup</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-3">Kontak Resmi</h4>
                <p class="text-sm text-slate-300">Email: {{ $sitePengaturan->email ?? 'smk13bdg@gmail.com' }}</p>
                <p class="text-sm mt-1 text-slate-300">Telp/Fax: {{ $sitePengaturan->telepon ?? '(022) 7318960' }}</p>
                <p class="text-xs mt-2 text-emerald-400">{{ $sitePengaturan->social_media ?? 'Instagram: @smkn13bdg' }}</p>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 text-center text-xs">
            &copy; {{ date('Y') }} {{ $sitePengaturan->nama_sekolah ?? 'SMK Negeri 13 Bandung' }}. All rights reserved.
        </div>
    </footer>

    @include('partials.modal-login')
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

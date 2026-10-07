<aside id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex-col flex-shrink-0 h-full max-h-[100dvh] md:h-screen hidden md:relative md:flex transition-all duration-300 shadow-xl md:shadow-none">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between flex-shrink-0 sidebar-header-box transition-all duration-300">
        <div class="flex items-center space-x-3">
            @if(!empty($sitePengaturan?->logo_url))
                <img src="{{ $sitePengaturan->logo_url }}" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0 drop-shadow">
            @else
                <div class="bg-emerald-700 text-white w-10 h-10 rounded-xl font-bold shadow flex items-center justify-center flex-shrink-0">13</div>
            @endif
            <div class="min-w-0 sidebar-text transition-all duration-200">
                <span class="text-white font-bold block text-sm truncate">ADMINISTRATOR</span>
                <span class="text-xs text-slate-400 block truncate">{{ $sitePengaturan->nama_sekolah ?? 'CMS SMKN 13 Bandung' }}</span>
            </div>
        </div>
        <button type="button" onclick="toggleAdminSidebar()" class="md:hidden text-slate-400 hover:text-white p-1" title="Tutup Menu">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto no-scrollbar py-4 px-3 pb-8 space-y-4 text-sm" style="scrollbar-width: none; -ms-overflow-style: none;">
        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Utama</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ url('/') }}" title="Beranda Website"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-house w-5 text-center flex-shrink-0 text-emerald-400"></i>
                    <span class="sidebar-text truncate">Beranda Website</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" title="Dashboard"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Dashboard</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Master Data</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ url('/admin/siswa') }}" title="Data Siswa"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/siswa*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-graduate w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Data Siswa</span>
                </a>
                <a href="{{ url('/admin/kelas') }}" title="Manajemen Kelas"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/kelas*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chalkboard w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Manajemen Kelas</span>
                </a>
                <a href="{{ url('/admin/guru') }}" title="Data Guru"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/guru*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chalkboard-user w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Data Guru</span>
                </a>
                <a href="{{ url('/admin/ruangan') }}" title="Data Ruangan"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ruangan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-door-open w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Data Ruangan</span>
                </a>
                <a href="{{ url('/admin/mapel') }}" title="Mata Pelajaran"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/mapel*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-book w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Mata Pelajaran</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Akademik & Presensi</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ url('/admin/jadwal') }}" title="Jadwal Pelajaran"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jadwal*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Jadwal Pelajaran</span>
                </a>
                <a href="{{ url('/admin/barcode') }}" title="Barcode Guru"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/barcode*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Barcode Guru</span>
                </a>
                <a href="{{ url('/admin/izin') }}" title="Pengajuan Izin Guru"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/izin*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-envelope-open-text w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Pengajuan Izin Guru</span>
                </a>
                <a href="{{ url('/admin/laporan') }}" title="Laporan Absensi"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/laporan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-user w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Laporan Absensi</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Informasi Publik</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ url('/admin/berita') }}" title="Berita"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/berita*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-newspaper w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Berita</span>
                </a>
                <a href="{{ url('/admin/galeri') }}" title="Galeri & Fasilitas"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/galeri*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-images w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Galeri & Fasilitas</span>
                </a>
                <a href="{{ url('/admin/jurusan') }}" title="Jurusan"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jurusan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Jurusan</span>
                </a>
                <a href="{{ url('/admin/ekskul') }}" title="Ekstrakurikuler"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ekskul*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-volleyball w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Ekstrakurikuler</span>
                </a>
                <a href="{{ url('/admin/prestasi') }}" title="Prestasi"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/prestasi*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-trophy w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Prestasi</span>
                </a>
                <a href="{{ url('/admin/profil-sambutan') }}" title="Profil & Sambutan"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/profil-sambutan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-id-card w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Profil & Sambutan</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Pengaturan Sistem</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ url('/admin/user') }}" title="Manajemen Akun"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/user*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Manajemen Akun</span>
                </a>
                <a href="{{ url('/admin/pengaturan') }}" title="Pengaturan Sekolah"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/pengaturan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gears w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Pengaturan Sekolah</span>
                </a>
                <a href="{{ url('/admin/log') }}" title="Log Aktivitas"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/log*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Log Aktivitas</span>
                </a>
                <a href="{{ route('admin.chronos.index') }}" title="Chronos (Waktu Virtual)"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('admin.chronos*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-flask-vial w-5 text-center flex-shrink-0 text-indigo-400"></i>
                    <span class="sidebar-text truncate">Chronos (Simulasi)</span>
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-slate-800 flex-shrink-0 bg-slate-900 sticky bottom-0 z-10 shadow-lg">
        <button type="button" onclick="openLogoutModal()" title="Keluar ke Portal"
            class="sidebar-logout-btn w-full bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-semibold py-2.5 rounded-xl text-sm transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-right-from-bracket flex-shrink-0"></i>
            <span class="sidebar-text">Keluar ke Portal</span>
        </button>
    </div>
</aside>
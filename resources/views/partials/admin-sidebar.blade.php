<aside id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex-col flex-shrink-0 h-screen hidden md:relative md:flex transition-all duration-300 shadow-xl md:shadow-none">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-3">
            @if(!empty($sitePengaturan?->logo_url))
                <img src="{{ $sitePengaturan->logo_url }}" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0 drop-shadow">
            @else
                <div class="bg-emerald-700 text-white w-10 h-10 rounded-xl font-bold shadow flex items-center justify-center flex-shrink-0">13</div>
            @endif
            <div class="min-w-0">
                <span class="text-white font-bold block text-sm truncate">ADMINISTRATOR</span>
                <span class="text-xs text-slate-400 block truncate">{{ $sitePengaturan->nama_sekolah ?? 'CMS SMKN 13 Bandung' }}</span>
            </div>
        </div>
        <button type="button" onclick="toggleAdminSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto py-4 px-3 space-y-4 text-sm">
        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Utama</p>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Master Data</p>
            <div class="space-y-1">
                <a href="{{ url('/admin/siswa') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/siswa*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-graduate w-5 text-center"></i>
                    <span>Data Siswa</span>
                </a>
                <a href="{{ url('/admin/kelas') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/kelas*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chalkboard w-5 text-center"></i>
                    <span>Manajemen Kelas</span>
                </a>
                <a href="{{ url('/admin/guru') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/guru*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chalkboard-user w-5 text-center"></i>
                    <span>Data Guru</span>
                </a>
                <a href="{{ url('/admin/ruangan') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ruangan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-door-open w-5 text-center"></i>
                    <span>Data Ruangan</span>
                </a>
                <a href="{{ url('/admin/mapel') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/mapel*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-book w-5 text-center"></i>
                    <span>Mata Pelajaran</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Akademik & Presensi</p>
            <div class="space-y-1">
                <a href="{{ url('/admin/jadwal') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jadwal*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                    <span>Jadwal Pelajaran</span>
                </a>
                <a href="{{ url('/admin/barcode') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/barcode*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center"></i>
                    <span>Barcode Guru</span>
                </a>
                <a href="{{ url('/admin/laporan') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/laporan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-user w-5 text-center"></i>
                    <span>Laporan Absensi</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Informasi Publik</p>
            <div class="space-y-1">
                <a href="{{ url('/admin/berita') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/berita*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-newspaper w-5 text-center"></i>
                    <span>Berita</span>
                </a>
                <a href="{{ url('/admin/galeri') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/galeri*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-images w-5 text-center"></i>
                    <span>Galeri & Fasilitas</span>
                </a>
                <a href="{{ url('/admin/jurusan') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jurusan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i>
                    <span>Jurusan</span>
                </a>
                <a href="{{ url('/admin/ekskul') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ekskul*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-volleyball w-5 text-center"></i>
                    <span>Ekstrakurikuler</span>
                </a>
                <a href="{{ url('/admin/prestasi') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/prestasi*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-trophy w-5 text-center"></i>
                    <span>Prestasi</span>
                </a>
                <a href="{{ url('/admin/profil-sambutan') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/profil-sambutan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-id-card w-5 text-center"></i>
                    <span>Profil & Sambutan</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan Sistem</p>
            <div class="space-y-1">
                <a href="{{ url('/admin/user') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/user*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i>
                    <span>Manajemen Akun</span>
                </a>
                <a href="{{ url('/admin/pengaturan') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/pengaturan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gears w-5 text-center"></i>
                    <span>Pengaturan Sekolah</span>
                </a>
                <a href="{{ url('/admin/log') }}"
                    class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/log*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                    <span>Log Aktivitas</span>
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-slate-800 flex-shrink-0">
        <button type="button" onclick="openLogoutModal()"
            class="w-full bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-semibold py-2.5 rounded-xl text-sm transition flex items-center justify-center space-x-2">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar ke Portal</span>
        </button>
    </div>
</aside>
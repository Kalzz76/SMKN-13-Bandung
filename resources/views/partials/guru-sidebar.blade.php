<aside id="guruSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex-col flex-shrink-0 h-full max-h-[100dvh] md:h-screen hidden md:relative md:flex transition-all duration-300 shadow-xl md:shadow-none">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between flex-shrink-0 sidebar-header-box transition-all duration-300">
        <div class="flex items-center space-x-3">
            @if(!empty($sitePengaturan?->logo_url))
                <img src="{{ $sitePengaturan->logo_url }}" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0 drop-shadow">
            @else
                <div class="bg-emerald-700 text-white w-10 h-10 rounded-xl font-bold shadow flex items-center justify-center flex-shrink-0">13</div>
            @endif
            <div class="min-w-0 sidebar-text transition-all duration-200">
                <span class="text-white font-bold block text-sm truncate">Dashboard Guru</span>
                <span class="text-xs text-slate-400 block truncate">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</span>
            </div>
        </div>
        <button type="button" onclick="toggleGuruSidebar()" class="md:hidden text-slate-400 hover:text-white p-1" title="Tutup Menu">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto no-scrollbar py-4 px-3 pb-8 space-y-4 text-sm" style="scrollbar-width: none; -ms-overflow-style: none;">
        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400 sidebar-category-header">Akademik</p>
            <div class="sidebar-category-divider hidden border-t border-slate-800 my-2"></div>
            <div class="space-y-1">
                <a href="{{ route('guru.jadwal') }}" title="Jadwal Mengajar"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('guru.jadwal') || (request()->routeIs('guru.dashboard') && (!request()->has('tab') || request('tab') === 'jadwal')) ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Jadwal Mengajar</span>
                </a>

                <a href="{{ route('guru.validasi') }}" title="Validasi Absensi"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('guru.validasi') || (request()->routeIs('guru.dashboard') && request('tab') === 'validasi') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Validasi Absensi</span>
                </a>

                <a href="{{ route('guru.rekap') }}" title="Rekap Absensi"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('guru.rekap') || (request()->routeIs('guru.dashboard') && request('tab') === 'rekap') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-list w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Rekap Absensi</span>
                </a>

                <a href="{{ route('guru.absensi-saya') }}" title="Absensi Saya"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('guru.absensi-saya') || (request()->routeIs('guru.dashboard') && request('tab') === 'absensi-saya') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-qrcode w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Absensi Saya</span>
                </a>

                <a href="{{ route('guru.izin.index') }}" title="Pengajuan Izin"
                    class="sidebar-menu-link w-full text-left px-3 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('guru.izin.*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-envelope-open-text w-5 text-center flex-shrink-0"></i>
                    <span class="sidebar-text truncate">Pengajuan Izin</span>
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
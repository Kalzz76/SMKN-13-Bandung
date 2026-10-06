<aside id="sekretarisSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex-col flex-shrink-0 h-screen hidden md:relative md:flex transition-all duration-300 shadow-xl md:shadow-none">
    <div class="p-6 border-b border-slate-800 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-3">
            @if(!empty($sitePengaturan?->logo_url))
                <img src="{{ $sitePengaturan->logo_url }}" alt="Logo" class="w-10 h-10 object-contain rounded-xl bg-white p-1 shadow flex-shrink-0">
            @else
                <div class="bg-emerald-700 text-white w-10 h-10 rounded-xl font-bold shadow flex items-center justify-center flex-shrink-0">13</div>
            @endif
            <div class="min-w-0">
                <span class="text-white font-bold block text-sm truncate">Dashboard Siswa</span>
                <span class="text-xs text-slate-400 block truncate">{{ $sitePengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</span>
            </div>
        </div>
        <button type="button" onclick="toggleSekretarisSidebar()" class="md:hidden text-slate-400 hover:text-white p-1">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <div class="flex-grow overflow-y-auto py-4 px-3 space-y-4 text-sm">
        <div>
            <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Akademik</p>
            <div class="space-y-1">
                <a href="{{ route('sekretaris.dashboard') }}" class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('sekretaris.dashboard') && request('tab') !== 'laporan' ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center"></i>
                    <span>Jadwal & Presensi</span>
                </a>

                <a href="{{ route('sekretaris.rekap-presensi') }}" class="w-full text-left px-3 py-2 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('sekretaris.rekap-presensi') || request('tab') === 'laporan' ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Rekap Presensi</span>
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
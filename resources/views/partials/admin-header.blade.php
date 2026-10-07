<header class="bg-white border-b border-slate-200 px-3 sm:px-8 py-3 flex items-center justify-between flex-shrink-0 sticky top-0 z-30 shadow-xs">
    <div class="flex items-center space-x-2 sm:space-x-3 min-w-0">
        <button type="button" onclick="toggleAdminSidebar()" class="text-slate-600 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition shrink-0 cursor-pointer" title="Buka / Tutup Sidebar">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <div class="min-w-0">
            <span class="text-xs sm:text-sm font-semibold text-slate-800 block truncate max-w-[130px] xs:max-w-[180px] sm:max-w-none">{{ $title ?? 'CMS SMKN 13 Bandung' }}</span>
        </div>
    </div>

    <div class="flex items-center space-x-1.5 sm:space-x-3 shrink-0">
        @if(!empty($chronos['enabled']))
            <a href="{{ route('admin.chronos.index') }}" title="Chronos Waktu Virtual Aktif" class="inline-flex items-center space-x-1 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-2 sm:px-3 py-1.5 rounded-xl shadow-xs transition whitespace-nowrap shrink-0">
                <i class="fa-solid fa-flask-vial animate-pulse text-indigo-600 text-xs"></i>
                <span class="hidden md:inline font-bold">Chronos:</span>
                <span class="font-mono font-bold text-[11px] sm:text-xs">{{ $chronos['formatted_time'] }}</span>
            </a>
        @endif

        <a href="{{ url('/') }}" title="Lihat Website" class="flex items-center space-x-1.5 text-xs font-semibold text-slate-600 hover:text-emerald-700 bg-slate-100 hover:bg-emerald-50 px-2.5 sm:px-3 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-200 transition shrink-0">
            <i class="fa-solid fa-house"></i>
            <span class="hidden sm:inline">Beranda</span>
        </a>

        <div class="hidden md:flex items-center space-x-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
            <i class="fa-regular fa-calendar text-emerald-700"></i>
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
        </div>

        <a href="{{ route('admin.profil') }}" class="flex items-center space-x-2.5 sm:space-x-3 pl-1.5 sm:pl-3 border-l border-slate-200 hover:opacity-80 transition group cursor-pointer" title="Lihat Profil Saya">
            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-emerald-700 text-white font-bold flex items-center justify-center text-xs sm:text-sm shadow group-hover:ring-2 group-hover:ring-emerald-500/50 transition">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <span class="text-xs font-bold text-slate-900 block leading-tight group-hover:text-emerald-700 transition">{{ auth()->user()->name }}</span>
                <span class="text-[11px] text-slate-500 block capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
            </div>
        </a>
    </div>
</header>

<header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-3.5 flex items-center justify-between flex-shrink-0 sticky top-0 z-30 shadow-xs">
    <div class="flex items-center space-x-3">
        <button type="button" onclick="toggleAdminSidebar()" class="text-slate-600 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition shrink-0 cursor-pointer" title="Buka / Tutup Sidebar">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <div class="flex items-center space-x-2">
            <span class="text-sm font-semibold text-slate-800">{{ $title ?? 'CMS SMKN 13 Bandung' }}</span>
        </div>
    </div>

    <div class="flex items-center space-x-3 sm:space-x-4">
        @if(!empty($chronos['enabled']))
            <a href="{{ route('admin.chronos.index') }}" title="Chronos Waktu Virtual Aktif - Klik untuk mengatur" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-3 py-1.5 rounded-xl shadow-xs transition whitespace-nowrap shrink-0">
                <i class="fa-solid fa-flask-vial animate-pulse text-indigo-600 text-xs"></i>
                <span class="hidden sm:inline font-bold">Chronos:</span>
                <span class="font-mono font-bold">{{ $chronos['day_name'] }}, {{ $chronos['formatted_time'] }}</span>
            </a>
        @endif

        <a href="{{ url('/') }}" class="hidden lg:flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-emerald-700 bg-slate-100 hover:bg-emerald-50 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-200 transition">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
        </a>

        <div class="hidden md:flex items-center space-x-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
            <i class="fa-regular fa-calendar text-emerald-700"></i>
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
        </div>

        <a href="{{ route('admin.profil') }}" class="flex items-center space-x-3 pl-2 sm:pl-3 border-l border-slate-200 hover:opacity-80 transition group cursor-pointer" title="Lihat Profil Saya">
            <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white font-bold flex items-center justify-center text-sm shadow group-hover:ring-2 group-hover:ring-emerald-500/50 transition">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <span class="text-xs font-bold text-slate-900 block leading-tight group-hover:text-emerald-700 transition">{{ auth()->user()->name }}</span>
                <span class="text-[11px] text-slate-500 block capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
            </div>
        </a>
    </div>
</header>

<header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-3.5 flex items-center justify-between flex-shrink-0 sticky top-0 z-30 shadow-xs">
    <div class="flex items-center space-x-3">
        <button type="button" onclick="toggleAdminSidebar()" class="md:hidden text-slate-600 hover:text-slate-900 p-2 rounded-xl hover:bg-slate-100 transition">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100 hidden sm:inline-block">Panel Administrator</span>
            <span class="text-sm font-semibold text-slate-800">{{ $title ?? 'CMS SMKN 13 Bandung' }}</span>
        </div>
    </div>

    <div class="flex items-center space-x-3 sm:space-x-4">
        <a href="{{ url('/') }}" target="_blank" class="hidden lg:flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-emerald-700 bg-slate-100 hover:bg-emerald-50 px-3 py-1.5 rounded-xl border border-slate-200 hover:border-emerald-200 transition">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Lihat Portal</span>
        </a>

        <div class="hidden md:flex items-center space-x-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200">
            <i class="fa-regular fa-calendar text-emerald-700"></i>
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMM Y') }}</span>
        </div>

        <div class="flex items-center space-x-3 pl-2 sm:pl-3 border-l border-slate-200">
            <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white font-bold flex items-center justify-center text-sm shadow">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="hidden sm:block text-left">
                <span class="text-xs font-bold text-slate-900 block leading-tight">{{ auth()->user()->name }}</span>
                <span class="text-[11px] text-slate-500 block capitalize">{{ auth()->user()->role ?? 'Admin' }}</span>
            </div>
        </div>
    </div>
</header>

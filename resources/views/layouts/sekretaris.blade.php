<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Siswa & Presensi - SMKN 13 Bandung' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased h-screen overflow-hidden flex">
    <aside class="w-64 bg-[#0c1021] text-white flex-shrink-0 flex flex-col justify-between hidden md:flex z-20">
        <div>
            <div class="px-5 py-4 flex items-center space-x-3 border-b border-slate-800/80">
                <img src="{{ asset('storage/pengaturan/u1uuRkFr8pwEBbiYShmpK5yMGVU1S7ywCSBifCi0.jpg') }}" alt="Logo SMKN 13 Bandung" class="w-9 h-9 rounded-xl object-contain bg-white/10 p-0.5 shadow-xs flex-shrink-0">
                <div class="min-w-0">
                    <span class="text-sm font-black tracking-tight text-white block truncate">SMKN 13 BANDUNG</span>
                    <span class="text-[9.5px] font-bold text-slate-400 block tracking-wider uppercase">Portal Siswa & Presensi</span>
                </div>
            </div>

            <div class="px-5 pt-6 pb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">MENU AKADEMIK</span>
            </div>

            <nav class="space-y-1.5 px-3">
                <a href="{{ route('sekretaris.dashboard', ['tab' => 'jadwal']) }}" class="px-4 py-3 rounded-2xl flex items-center justify-between text-sm font-semibold transition {{ ($tabAktif ?? 'jadwal') === 'jadwal' ? 'bg-[#1c2438] text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-regular fa-calendar-days text-base text-indigo-400"></i>
                        <span>Jadwal & Presensi</span>
                    </div>
                    @if(($tabAktif ?? 'jadwal') === 'jadwal')
                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                    @endif
                </a>

                <a href="{{ route('sekretaris.dashboard', ['tab' => 'laporan']) }}" class="px-4 py-3 rounded-2xl flex items-center justify-between text-sm font-semibold transition {{ ($tabAktif ?? 'jadwal') === 'laporan' ? 'bg-[#1c2438] text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-chart-pie text-base text-emerald-400"></i>
                        <span>Rekap Presensi</span>
                    </div>
                    @if(($tabAktif ?? 'jadwal') === 'laporan')
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                    @endif
                </a>

                <a href="{{ route('sekretaris.dashboard', ['tab' => 'pengganti']) }}" class="px-4 py-3 rounded-2xl flex items-center justify-between text-sm font-semibold transition {{ ($tabAktif ?? 'jadwal') === 'pengganti' ? 'bg-[#1c2438] text-white shadow-xs' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-user-pen text-base text-amber-400"></i>
                        <span>Izin & Guru Pengganti</span>
                    </div>
                    @if(($tabAktif ?? 'jadwal') === 'pengganti')
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-400"></div>
                    @endif
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-slate-800/80 space-y-3">
            <div class="bg-[#141b2d] p-3 rounded-2xl border border-slate-800 flex items-center justify-between">
                <a href="{{ route('profil.index') }}" class="flex items-center space-x-3 min-w-0 hover:opacity-80 transition" title="Profil Saya">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <span class="text-xs font-bold text-white block truncate leading-tight">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-amber-400 font-semibold block uppercase">SEKRETARIS KELAS</span>
                    </div>
                </a>
                <button type="button" onclick="openLogoutModal()" class="text-slate-400 hover:text-rose-400 p-1.5 transition" title="Logout">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </button>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">PORTAL SISWA</span>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">JADWAL & PRESENSI KELAS</span>
            </div>

            <div class="flex items-center space-x-4">
                <div class="bg-slate-100 text-slate-700 text-xs font-bold font-mono px-3.5 py-1.5 rounded-xl flex items-center space-x-2 shadow-2xs">
                    <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                    <span id="liveClockDisplay">--:--:--</span>
                </div>

                <a href="{{ route('profil.index') }}" class="flex items-center space-x-2 bg-slate-50 hover:bg-amber-50 border border-slate-200 hover:border-amber-200 px-3 py-1.5 rounded-xl transition group" title="Kelola Profil Saya">
                    <div class="w-7 h-7 rounded-lg bg-amber-600 text-white font-bold flex items-center justify-center text-xs shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <span class="text-xs font-bold text-slate-700 group-hover:text-amber-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                </a>

                <button type="button" onclick="openLogoutModal()" class="bg-rose-50 text-rose-600 hover:bg-rose-100 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-1.5" title="Keluar">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 sm:p-10 space-y-6 sm:space-y-8 bg-[#f8fafc]">
            @if(session('sukses'))
                <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('sukses') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-50 text-rose-800 border border-rose-200 text-sm font-semibold flex items-center justify-between shadow-xs">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('partials.modal-pesan')
    @include('partials.modal-logout')

    <script>
        function updateLiveClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const el = document.getElementById('liveClockDisplay');
            if (el) {
                el.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();
    </script>
</body>
</html>

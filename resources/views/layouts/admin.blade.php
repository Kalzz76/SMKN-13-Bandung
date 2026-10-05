<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Administrator - CMS SMKN 13 Bandung' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col md:flex-row">
    <aside class="w-full md:w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 md:min-h-screen">
        <div class="p-6 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-emerald-700 text-white p-2 rounded-xl font-bold shadow">13</div>
                <div>
                    <span class="text-white font-bold block text-sm">ADMINISTRATOR</span>
                    <span class="text-xs text-slate-400">CMS SMKN 13 Bandung</span>
                </div>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto py-4 px-3 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chart-pie w-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ url('/admin/barcode') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/barcode*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-qrcode w-5"></i>
                <span>Barcode Absensi</span>
            </a>
            <a href="{{ url('/admin/siswa') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/siswa*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-user-graduate w-5"></i>
                <span>A. Data Siswa</span>
            </a>
            <a href="{{ url('/admin/kelas') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/kelas*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chalkboard w-5"></i>
                <span>B. Manajemen Kelas</span>
            </a>
            <a href="{{ url('/admin/guru') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/guru*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-chalkboard-user w-5"></i>
                <span>C. Data Guru</span>
            </a>
            <a href="{{ url('/admin/barcode') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/barcode*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-qrcode w-5"></i>
                <span>Barcode Guru</span>
            </a>
            <a href="{{ url('/admin/ruangan') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ruangan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-door-open w-5"></i>
                <span>D. Data Ruangan</span>
            </a>
            <a href="{{ url('/admin/mapel') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/mapel*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-book w-5"></i>
                <span>E. Mata Pelajaran</span>
            </a>
            <a href="{{ url('/admin/jadwal') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jadwal*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-calendar-days w-5"></i>
                <span>F. Jadwal Pelajaran</span>
            </a>
            <a href="{{ url('/admin/laporan') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/laporan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clipboard-user w-5"></i>
                <span>G. Laporan Absensi</span>
            </a>
            <a href="{{ url('/admin/berita') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/berita*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-newspaper w-5"></i>
                <span>H. Berita</span>
            </a>
            <a href="{{ url('/admin/galeri') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/galeri*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-images w-5"></i>
                <span>I. Galeri & Fasilitas</span>
            </a>
            <a href="{{ url('/admin/user') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/user*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-users-gear w-5"></i>
                <span>J. Manajemen Akun</span>
            </a>
            <a href="{{ url('/admin/pengaturan') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/pengaturan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gears w-5"></i>
                <span>K. Pengaturan Sekolah</span>
            </a>
            <a href="{{ url('/admin/profil-sambutan') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/profil-sambutan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-id-card w-5"></i>
                <span>L. Profil & Sambutan</span>
            </a>
            <a href="{{ url('/admin/jurusan') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/jurusan*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5"></i>
                <span>M. Jurusan</span>
            </a>
            <a href="{{ url('/admin/ekskul') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/ekskul*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-volleyball w-5"></i>
                <span>N. Ekstrakurikuler</span>
            </a>
            <a href="{{ url('/admin/prestasi') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/prestasi*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-trophy w-5"></i>
                <span>O. Prestasi</span>
            </a>
            <a href="{{ url('/admin/log') }}" class="w-full text-left px-4 py-2.5 rounded-xl font-semibold transition flex items-center space-x-3 {{ request()->is('admin/log*') ? 'bg-emerald-700 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clock-rotate-left w-5"></i>
                <span>P. Log Aktivitas</span>
            </a>
        </div>

        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-rose-600/20 hover:bg-rose-600 text-rose-300 hover:text-white font-semibold py-2.5 rounded-xl text-sm transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar ke Portal</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-grow p-6 sm:p-10 overflow-y-auto">
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.modal-pesan')
</body>
</html>

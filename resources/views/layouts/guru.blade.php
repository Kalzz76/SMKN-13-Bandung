<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Guru - SMKN 13 Bandung' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    <div class="flex-grow p-6 sm:p-10 max-w-7xl mx-auto w-full space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-100 gap-4">
            <div>
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase">Portal Guru Pengajar</span>
                <h1 class="text-2xl font-black text-slate-900 mt-1">Dashboard Guru: {{ auth()->user()->name }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('profil.index') }}" class="flex items-center space-x-3 bg-slate-50 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-200 px-3 py-1.5 rounded-xl transition group cursor-pointer" title="Kelola Profil Saya">
                    <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold flex items-center justify-center text-xs shadow">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="text-left hidden sm:block">
                        <span class="text-xs font-bold text-slate-800 block leading-tight group-hover:text-emerald-700">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-500 block">Profil Saya</span>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-100 px-3.5 py-2 rounded-xl text-sm font-semibold transition flex items-center space-x-2">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @include('partials.modal-pesan')
</body>
</html>

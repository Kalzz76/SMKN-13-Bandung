@extends('layouts.admin', ['title' => 'Dashboard Administrator - CMS SMKN 13 Bandung'])

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-emerald-900 via-emerald-850 to-slate-900 text-white rounded-3xl p-8 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <span class="bg-emerald-800/80 text-emerald-200 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-600/30">Panel Utama</span>
            <h1 class="text-3xl font-extrabold mt-2 tracking-tight">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-emerald-100 mt-1 font-light">Sistem Informasi Manajemen & Portal Resmi SMKN 13 Bandung</p>
        </div>
        <div class="bg-white/10 backdrop-blur px-5 py-3 rounded-2xl border border-white/10 text-right">
            <span class="text-[11px] text-emerald-300 block">Hari & Tanggal</span>
            <span class="text-sm font-semibold text-white">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase">Total Guru & Staff</span>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalGuru }}</h3>
                <span class="text-xs text-emerald-600 font-medium">Terdaftar di sistem</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-sky-100 text-sky-800 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase">Total Siswa</span>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalSiswa }}</h3>
                <span class="text-xs text-sky-600 font-medium">Siswa aktif</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-amber-100 text-amber-800 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-chalkboard"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase">Total Kelas</span>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalKelas }}</h3>
                <span class="text-xs text-amber-600 font-medium">Rombongan belajar</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-indigo-100 text-indigo-800 rounded-2xl flex items-center justify-center text-2xl font-bold flex-shrink-0">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <div>
                <span class="text-xs text-slate-500 font-semibold uppercase">Warta Berita</span>
                <h3 class="text-2xl font-black text-slate-900">{{ $totalBerita }}</h3>
                <span class="text-xs text-indigo-600 font-medium">Artikel publik</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-user-check text-emerald-700"></i>
                    <span>Status Presensi Guru Hari Ini ({{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }})</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemantauan langsung kehadiran tenaga pendidik dan staff pengajar.</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs text-emerald-700 font-bold hover:underline">
                Lihat Rekap Lengkap &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
                <span class="text-[11px] font-bold text-emerald-800 uppercase block">Hadir Tepat Waktu</span>
                <span class="text-2xl font-black text-emerald-950 mt-1 block font-mono">{{ $guruHadirHariIni }}</span>
                <span class="text-[11px] text-emerald-700">Tercatat Hadir</span>
            </div>
            <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl">
                <span class="text-[11px] font-bold text-amber-800 uppercase block">Terlambat</span>
                <span class="text-2xl font-black text-amber-950 mt-1 block font-mono">{{ $guruTerlambatHariIni }}</span>
                <span class="text-[11px] text-amber-700">Lewat jam masuk</span>
            </div>
            <div class="p-4 bg-sky-50 border border-sky-100 rounded-xl">
                <span class="text-[11px] font-bold text-sky-800 uppercase block">Izin / Sakit / Alpa</span>
                <span class="text-2xl font-black text-sky-950 mt-1 block font-mono">{{ $guruIzinSakitHariIni }}</span>
                <span class="text-[11px] text-sky-700">Via Sekretaris</span>
            </div>
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-bold text-slate-700 uppercase block">Belum Absen</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block font-mono">{{ $guruBelumAbsen->count() }}</span>
                <span class="text-[11px] text-slate-500">Guru pengajar</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                        <i class="fa-solid fa-user-clock text-amber-600"></i>
                        <span>Daftar Guru Belum Presensi Hari Ini</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar guru yang belum mencatat kehadiran per hari ini.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    {{ $guruBelumAbsen->count() }} Pendidik
                </span>
            </div>

            @if($guruBelumAbsen->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                                <th class="py-2.5 px-3">Nama Guru</th>
                                <th class="py-2.5 px-3 w-32">NIP</th>
                                <th class="py-2.5 px-3">Mata Pelajaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($guruBelumAbsen->take(6) as $gba)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $gba->nama }}</td>
                                    <td class="py-2.5 px-3 font-mono text-slate-500 text-[11px]">{{ $gba->nip ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-slate-600">{{ $gba->mapel_utama ?? 'Guru Pengajar' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($guruBelumAbsen->count() > 6)
                    <div class="pt-2 text-center border-t border-slate-100">
                        <span class="text-xs text-slate-400">Dan {{ $guruBelumAbsen->count() - 6 }} guru lainnya belum presensi.</span>
                    </div>
                @endif
            @else
                <div class="p-6 bg-emerald-50 text-emerald-800 rounded-xl text-center text-xs font-semibold">
                    <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i>
                    Luar biasa! Seluruh guru pengajar telah melakukan presensi hari ini.
                </div>
            @endif
        </div>

        <div class="lg:col-span-5 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                        <i class="fa-solid fa-newspaper text-emerald-700"></i>
                        <span>5 Warta Berita Terbaru</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Artikel dan warta terkini di portal sekolah.</p>
                </div>
                <a href="{{ route('admin.berita.index') }}" class="text-xs text-emerald-700 font-bold hover:underline">
                    Kelola Berita &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($beritaTerbaru as $b)
                    <div class="py-3 flex items-start space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex-shrink-0 overflow-hidden border border-slate-200">
                            @if($b->gambar)
                                <img src="{{ asset('storage/' . $b->gambar) }}" alt="{{ $b->judul }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <i class="fa-regular fa-image text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $b->status === 'Publish' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $b->status }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-mono">
                                    {{ \Carbon\Carbon::parse($b->tanggal)->isoFormat('D MMM Y') }}
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-xs mt-1 truncate hover:text-emerald-700">
                                <a href="{{ route('admin.berita.index') }}">{{ $b->judul }}</a>
                            </h4>
                            <span class="text-[10px] text-slate-500 block truncate">{{ $b->kategori }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-slate-400 text-xs">
                        Belum ada warta berita yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

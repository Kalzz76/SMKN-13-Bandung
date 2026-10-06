@extends('layouts.publik', ['title' => 'Beranda - SMKN 13 Bandung'])

@section('content')
<div class="relative bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900 text-white py-24 sm:py-28 px-4 overflow-hidden">
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
    <div class="max-w-7xl mx-auto relative z-10 text-center">
        <div class="mb-5 flex justify-center">
            <img src="{{ !empty($pengaturan->logo) && file_exists(public_path('storage/' . $pengaturan->logo)) ? asset('storage/' . $pengaturan->logo) : asset('images/logo-smkn13.png') }}" alt="Logo SMKN 13 Bandung" class="h-24 w-24 sm:h-28 sm:w-28 object-contain drop-shadow-2xl">
        </div>
        <span class="bg-emerald-800/80 text-emerald-200 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-widest border border-emerald-600/30 inline-block mb-4">
            {{ $pengaturan->slogan ?? 'Unggul, Berkarakter & Berdaya Saing' }}
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold mb-6 tracking-tight">{{ $pengaturan->nama_sekolah ?? 'SMK Negeri 13 Bandung' }}</h1>
        <p class="text-base sm:text-xl text-emerald-100 max-w-2xl mx-auto mb-8 font-light leading-relaxed">
            {{ $pengaturan->deskripsi_singkat ?? 'Pusat pendidikan kejuruan unggulan yang mencetak generasi kompeten di bidang teknologi, siap kerja, mandiri, dan berakhlak mulia.' }}
        </p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('publik.jurusan') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold px-6 py-3.5 rounded-xl shadow-lg transition">Jelajahi Jurusan</a>
            <a href="{{ route('publik.profil') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3.5 rounded-xl backdrop-blur border border-white/20 transition">Tentang Kami</a>
        </div>
    </div>
</div>

<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <div class="md:col-span-1 text-center">
            <div class="relative inline-block w-full max-w-xs">
                @if(!empty($pengaturan->foto_kepsek))
                    <img src="{{ asset('storage/' . $pengaturan->foto_kepsek) }}" alt="Kepala Sekolah" class="rounded-2xl shadow-md w-full object-cover h-80">
                @else
                    <div class="w-full h-80 rounded-2xl bg-gradient-to-br from-emerald-800 to-emerald-950 text-white flex flex-col items-center justify-center shadow-md p-6">
                        <div class="w-24 h-24 rounded-full bg-white/10 flex items-center justify-center text-4xl mb-4 text-emerald-300">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <span class="font-bold text-lg text-center">{{ $pengaturan->nama_kepsek ?? 'Agus Nugroho, S.Pd., M.T.' }}</span>
                    </div>
                @endif
                <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-emerald-900 text-white text-xs px-4 py-1.5 rounded-full shadow font-bold whitespace-nowrap">
                    Kepala SMKN 13 Bandung
                </div>
            </div>
        </div>
        <div class="md:col-span-2 space-y-4">
            <h2 class="text-xs font-bold text-emerald-700 tracking-widest uppercase">Sambutan Pimpinan</h2>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $pengaturan->judul_sambutan ?? 'Mewujudkan Pendidikan Vokasi Unggul, Berkarakter, dan Berdaya Saing Global' }}</h3>
            <p class="text-slate-600 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                {{ $pengaturan->sambutan ?? 'Assalamu’alaikum Warahmatullahi Wabarakatuh, Selamat datang di portal resmi SMK Negeri 13 Bandung...' }}
            </p>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-slate-900">{{ $pengaturan->nama_kepsek ?? 'Agus Nugroho, S.Pd., M.T.' }}</h4>
                    <p class="text-xs text-slate-500">Kepala SMK Negeri 13 Bandung</p>
                </div>
                <div class="text-emerald-800 font-serif italic text-xl hidden sm:block">{{ $pengaturan->nama_kepsek ?? 'Agus Nugroho, S.Pd., M.T.' }}</div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-100/50 w-full flex-grow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Warta Sekolah</h2>
                <h3 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-1">Berita & Informasi Terbaru</h3>
            </div>
            <a href="{{ route('publik.berita') }}" class="text-emerald-700 hover:text-emerald-800 font-bold text-sm flex items-center space-x-1">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($beritaTerbaru->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
                <i class="fa-solid fa-newspaper text-4xl text-slate-300 mb-3"></i>
                <p>Belum ada berita yang dipublikasikan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($beritaTerbaru as $b)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition">
                        @if(!empty($b->gambar))
                            <a href="{{ route('publik.detail-berita', $b->id) }}">
                                <img src="{{ asset('storage/' . $b->gambar) }}" class="h-48 w-full object-cover" alt="{{ $b->judul }}">
                            </a>
                        @else
                            <a href="{{ route('publik.detail-berita', $b->id) }}" class="h-48 w-full bg-slate-800 text-slate-400 flex flex-col items-center justify-center p-4">
                                <i class="fa-solid fa-newspaper text-3xl mb-2 text-emerald-500"></i>
                                <span class="text-xs">{{ $b->kategori }}</span>
                            </a>
                        @endif
                        <div class="p-6 flex flex-col flex-grow space-y-3">
                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full w-max">{{ $b->kategori }}</span>
                            <a href="{{ route('publik.detail-berita', $b->id) }}" class="text-lg font-bold text-slate-900 line-clamp-2 hover:text-emerald-700 transition">
                                {{ $b->judul }}
                            </a>
                            <p class="text-slate-600 text-sm line-clamp-2">{{ Str::limit($b->isi, 120) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

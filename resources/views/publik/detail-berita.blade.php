@extends('layouts.publik', ['title' => $berita->judul . ' - SMKN 13 Bandung'])

@section('content')
<div class="w-full flex-grow flex flex-col">
    <!-- Hero Banner Full Width -->
    <div class="relative h-[55vh] min-h-[360px] w-full overflow-hidden bg-navy-dark">
        @if($berita->gambar_url)
            <img src="{{ $berita->gambar_url }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $berita->judul }}">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-navy-dark via-navy-dark/75 to-navy-dark/25"></div>
        <div class="absolute bottom-0 inset-x-0 max-w-4xl mx-auto px-4 pb-10 text-white z-10">
            <a href="{{ route('publik.berita') }}{{ (!empty($berita->is_prestasi) || $berita->kategori === 'Prestasi') ? '?kategori=Prestasi' : '' }}"
                class="btn-animate inline-flex items-center mb-6 bg-white/15 hover:bg-white/30 backdrop-blur px-5 py-2 rounded-full text-sm font-bold text-white transition">
                <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Berita
            </a>
            <div class="flex items-center gap-4 mb-4 flex-wrap">
                @if(!empty($berita->is_prestasi) || $berita->kategori === 'Prestasi')
                    <span class="bg-amber-500 text-xs font-black px-3.5 py-1.5 rounded-full text-white flex items-center gap-1.5 shadow-md">
                        <i class="fa-solid fa-trophy text-xs"></i> Prestasi Siswa
                    </span>
                @else
                    <span class="bg-teal-primary text-xs font-bold px-3 py-1.5 rounded-full text-white">{{ $berita->kategori }}</span>
                @endif
                <span class="text-sm text-teal-tint flex items-center">
                    <i class="fa-regular fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($berita->tanggal)->isoFormat('D MMMM Y') }}
                </span>
                <span class="text-sm text-teal-tint flex items-center">
                    <i class="fa-regular fa-user mr-2"></i>{{ $berita->user->name ?? 'Admin' }}
                </span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-black leading-tight">{{ $berita->judul }}</h1>
        </div>
    </div>

    @if(!empty($berita->is_prestasi) || !empty($berita->nama_peraih))
        <!-- Box Penghargaan Prestasi -->
        <div class="max-w-4xl mx-auto px-4 mt-8 w-full">
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/80 rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-3xl shadow-md shrink-0">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-amber-800">Capaian Prestasi SMKN 13</span>
                        <h3 class="text-xl font-black text-navy-dark mt-0.5">{{ $berita->nama_peraih ?? 'Siswa Berprestasi' }}</h3>
                        <p class="text-xs text-text-muted mt-0.5">SMK Negeri 13 Bandung</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    @if(!empty($berita->tingkat))
                        <span class="bg-white border border-amber-300/80 text-amber-900 text-xs font-bold px-4 py-2 rounded-xl shadow-sm flex items-center gap-1.5">
                            <i class="fa-solid fa-medal text-amber-600"></i> Tingkat {{ $berita->tingkat }}
                        </span>
                    @endif
                    @if(!empty($berita->tahun))
                        <span class="bg-white border border-amber-300/80 text-navy-dark text-xs font-bold px-4 py-2 rounded-xl shadow-sm flex items-center gap-1.5">
                            <i class="fa-regular fa-calendar-check text-amber-600"></i> Tahun {{ $berita->tahun }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Artikel Body -->
    <article class="max-w-4xl mx-auto px-4 py-14 space-y-6 text-base sm:text-lg leading-loose text-text-main w-full">
        @foreach(explode("\n", $berita->isi) as $paragraph)
            @if(trim($paragraph))
                <p class="text-justify">{{ trim($paragraph) }}</p>
            @endif
        @endforeach
    </article>

    <!-- Berita Lainnya -->
    @if($beritaTerkait->isNotEmpty())
        <section class="bg-bg-card border-t border-teal-tint py-16 w-full mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-3xl font-black text-navy-dark">Berita Lainnya</h3>
                    <a href="{{ route('publik.berita') }}" class="text-teal-primary hover:text-teal-light font-bold text-sm flex items-center gap-1.5 transition">
                        <span>Lihat Semua</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($beritaTerkait as $bt)
                        <a href="{{ route('publik.detail-berita', $bt->id) }}"
                            class="bg-bg-card rounded-3xl overflow-hidden shadow-sm border border-teal-tint flex flex-col hover:shadow-lg transition-all hover:-translate-y-1 cursor-pointer group">
                            @if($bt->gambar_url)
                                <img src="{{ $bt->gambar_url }}" class="h-56 w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $bt->judul }}">
                            @else
                                <div class="h-56 w-full bg-navy-mid/10 flex items-center justify-center text-teal-primary">
                                    <i class="fa-solid fa-newspaper text-4xl"></i>
                                </div>
                            @endif
                            <div class="p-8 space-y-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-center">
                                    <span class="bg-teal-tint text-teal-primary text-xs font-bold px-3 py-1.5 rounded-full">{{ $bt->kategori }}</span>
                                    <span class="text-xs text-text-muted">{{ \Carbon\Carbon::parse($bt->tanggal)->isoFormat('D MMMM Y') }}</span>
                                </div>
                                <h4 class="text-xl font-black text-navy-dark leading-snug group-hover:text-teal-primary transition-colors line-clamp-2">{{ $bt->judul }}</h4>
                                <p class="text-text-muted text-sm leading-relaxed line-clamp-3">{{ Str::limit(strip_tags($bt->isi), 130) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
@endsection

@extends('layouts.publik', ['title' => $berita->judul . ' - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-10 w-full flex-grow">
    <!-- Breadcrumb / Tombol Kembali -->
    <div>
        <a href="{{ route('publik.berita') }}"
            class="btn-animate inline-flex items-center text-sm font-bold text-teal-primary hover:text-teal-light transition mb-6 bg-teal-tint/80 px-4 py-2 rounded-full border border-teal-accent/40">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Berita & Informasi
        </a>

        <!-- Metadata Berita -->
        <div class="flex items-center space-x-3 mb-4 flex-wrap gap-y-2">
            <span class="bg-teal-tint text-teal-primary text-xs font-black px-3.5 py-1.5 rounded-full uppercase tracking-wider">
                {{ $berita->kategori }}
            </span>
            <span class="text-xs text-text-muted flex items-center font-medium">
                <i class="fa-regular fa-calendar mr-1.5 text-teal-primary"></i>
                {{ \Carbon\Carbon::parse($berita->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
            <span class="text-xs text-text-muted flex items-center font-medium">
                <i class="fa-regular fa-user mr-1.5 text-teal-primary"></i>
                {{ $berita->user->name ?? 'Administrator' }}
            </span>
        </div>

        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-navy-dark leading-tight tracking-tight">
            {{ $berita->judul }}
        </h1>
    </div>

    <!-- Gambar Cover Berita -->
    @if(!empty($berita->gambar) && file_exists(public_path('storage/' . $berita->gambar)))
        <div class="rounded-3xl overflow-hidden shadow-md border border-teal-tint max-h-[500px] bg-teal-tint">
            <img src="{{ asset('storage/' . $berita->gambar) }}" class="w-full h-full object-cover" alt="{{ $berita->judul }}">
        </div>
    @endif

    <!-- Isi Lengkap Berita -->
    <div class="bg-bg-card p-8 sm:p-14 rounded-3xl shadow-sm border border-teal-tint">
        <div class="text-text-main leading-relaxed text-base sm:text-lg whitespace-pre-line space-y-5 text-justify">
            {{ $berita->isi }}
        </div>
    </div>

    <!-- Berita Terkait Lainnya -->
    @if($beritaTerkait->isNotEmpty())
        <div class="pt-10 border-t border-teal-tint">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Rekomendasi</span>
                    <h3 class="text-2xl font-black text-navy-dark mt-0.5">Berita Terkait Lainnya</h3>
                </div>
                <a href="{{ route('publik.berita') }}" class="text-teal-primary hover:text-teal-light text-xs font-bold flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($beritaTerkait as $bt)
                    <a href="{{ route('publik.detail-berita', $bt->id) }}"
                        class="bg-bg-card rounded-2xl overflow-hidden shadow-sm border border-teal-tint flex flex-col hover:shadow-md transition-all hover:-translate-y-1 group">
                        @if(!empty($bt->gambar) && file_exists(public_path('storage/' . $bt->gambar)))
                            <img src="{{ asset('storage/' . $bt->gambar) }}" class="h-40 w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $bt->judul }}">
                        @else
                            <div class="h-40 w-full bg-navy-mid/10 flex items-center justify-center text-teal-primary">
                                <i class="fa-solid fa-newspaper text-3xl"></i>
                            </div>
                        @endif
                        <div class="p-5 flex flex-col flex-grow space-y-2">
                            <span class="text-xs text-teal-primary font-bold">{{ $bt->kategori }}</span>
                            <h4 class="text-sm font-bold text-navy-dark line-clamp-2 group-hover:text-teal-primary transition-colors">
                                {{ $bt->judul }}
                            </h4>
                            <span class="text-[11px] text-text-muted mt-auto pt-2">
                                {{ \Carbon\Carbon::parse($bt->tanggal)->isoFormat('D MMM Y') }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

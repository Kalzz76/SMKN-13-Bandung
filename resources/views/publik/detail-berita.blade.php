@extends('layouts.publik', ['title' => $berita->judul . ' - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-8 w-full flex-grow">
    <div>
        <a href="{{ route('publik.berita') }}" class="inline-flex items-center text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition mb-6">
            <i class="fa-solid fa-arrow-left mr-2"></i>Kembali ke Berita & Informasi
        </a>
        <div class="flex items-center space-x-3 mb-3">
            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                {{ $berita->kategori }}
            </span>
            <span class="text-xs text-slate-500">
                <i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($berita->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
            <span class="text-xs text-slate-500">
                <i class="fa-regular fa-user mr-1"></i>{{ $berita->user->name ?? 'Administrator' }}
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight">
            {{ $berita->judul }}
        </h1>
    </div>

    @if(!empty($berita->gambar))
        <div class="rounded-3xl overflow-hidden shadow-sm border border-slate-100 max-h-[480px]">
            <img src="{{ asset('storage/' . $berita->gambar) }}" class="w-full h-full object-cover" alt="{{ $berita->judul }}">
        </div>
    @endif

    <div class="bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-slate-100">
        <div class="text-slate-700 leading-relaxed text-base sm:text-lg whitespace-pre-line space-y-4">
            {{ $berita->isi }}
        </div>
    </div>

    @if($beritaTerkait->isNotEmpty())
        <div class="pt-8 border-t border-slate-200">
            <h3 class="text-xl font-bold text-slate-900 mb-6">Berita Terkait Lainnya</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($beritaTerkait as $bt)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition">
                        @if(!empty($bt->gambar))
                            <a href="{{ route('publik.detail-berita', $bt->id) }}">
                                <img src="{{ asset('storage/' . $bt->gambar) }}" class="h-36 w-full object-cover" alt="{{ $bt->judul }}">
                            </a>
                        @else
                            <a href="{{ route('publik.detail-berita', $bt->id) }}" class="h-36 w-full bg-slate-800 text-slate-400 flex items-center justify-center p-3">
                                <i class="fa-solid fa-newspaper text-2xl text-emerald-500"></i>
                            </a>
                        @endif
                        <div class="p-4 flex flex-col flex-grow space-y-2">
                            <span class="text-xs text-emerald-700 font-bold">{{ $bt->kategori }}</span>
                            <h4 class="text-sm font-bold text-slate-900 line-clamp-2">
                                <a href="{{ route('publik.detail-berita', $bt->id) }}" class="hover:text-emerald-700 transition">
                                    {{ $bt->judul }}
                                </a>
                            </h4>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

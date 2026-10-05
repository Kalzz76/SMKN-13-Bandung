@extends('layouts.publik', ['title' => 'Berita & Informasi - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Warta & Informasi</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Berita, Kegiatan & Prestasi Sekolah</h1>
        <p class="text-slate-500 text-sm mt-2">Dapatkan kabar terbaru dan informasi terkini dari keluarga besar SMKN 13 Bandung.</p>
    </div>

    @if($daftarBerita->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-newspaper text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada berita yang dipublikasikan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($daftarBerita as $b)
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
                        <div class="flex items-center justify-between text-xs text-slate-500">
                            <span class="bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full">{{ $b->kategori }}</span>
                            <span><i class="fa-regular fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($b->tanggal)->locale('id')->isoFormat('D MMMM Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 line-clamp-2">
                            <a href="{{ route('publik.detail-berita', $b->id) }}" class="hover:text-emerald-700 transition">
                                {{ $b->judul }}
                            </a>
                        </h3>
                        <p class="text-slate-600 text-sm line-clamp-3 leading-relaxed flex-grow">{{ Str::limit($b->isi, 140) }}</p>
                        <div class="pt-3 border-t border-slate-100">
                            <a href="{{ route('publik.detail-berita', $b->id) }}" class="text-emerald-700 hover:text-emerald-800 text-xs font-bold flex items-center space-x-1">
                                <span>Baca Selengkapnya</span>
                                <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-6">
            {{ $daftarBerita->links() }}
        </div>
    @endif
</div>
@endsection

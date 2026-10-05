@extends('layouts.publik', ['title' => 'Galeri & Fasilitas - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Dokumentasi Visual</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Galeri Kegiatan & Fasilitas Sekolah</h1>
        <p class="text-slate-500 text-sm mt-2">Potret sarana prasarana modern dan dinamika kegiatan siswa SMKN 13 Bandung.</p>
    </div>

    <div class="flex justify-center space-x-2">
        <a href="{{ route('publik.galeri', ['kategori' => 'Semua']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? 'Semua') === 'Semua' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Semua Foto
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Fasilitas']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? '') === 'Fasilitas' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Fasilitas Sekolah
        </a>
        <a href="{{ route('publik.galeri', ['kategori' => 'Kegiatan']) }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition {{ ($kategori ?? '') === 'Kegiatan' ? 'bg-emerald-700 text-white shadow' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            Kegiatan Siswa
        </a>
    </div>

    @if($daftarGaleri->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-images text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada foto dalam kategori ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($daftarGaleri as $ga)
                <div class="relative rounded-2xl overflow-hidden shadow group min-h-[220px] bg-slate-800">
                    @if(!empty($ga->foto) && file_exists(public_path('storage/' . $ga->foto)))
                        <img src="{{ asset('storage/' . $ga->foto) }}" class="h-56 w-full object-cover group-hover:scale-105 transition duration-300" alt="{{ $ga->judul }}">
                    @else
                        <div class="h-56 w-full bg-gradient-to-br from-slate-800 to-slate-900 flex flex-col items-center justify-center p-4 text-center">
                            <i class="fa-solid {{ $ga->kategori === 'Fasilitas' ? 'fa-building' : 'fa-users' }} text-4xl text-emerald-500/60 mb-2 group-hover:scale-110 transition duration-300"></i>
                            <span class="text-xs text-slate-400">{{ $ga->kategori }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent flex flex-col justify-end p-4">
                        <span class="text-emerald-400 text-xs font-bold uppercase tracking-wider">{{ $ga->kategori }}</span>
                        <h3 class="text-white font-bold text-sm mt-0.5 line-clamp-2">{{ $ga->judul }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

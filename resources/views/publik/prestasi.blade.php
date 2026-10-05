@extends('layouts.publik', ['title' => 'Prestasi Sekolah - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Prestasi Siswa</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Torehan Prestasi SMKN 13 Bandung</h1>
        <p class="text-slate-500 text-sm mt-2">Pencapaian membanggakan siswa-siswi kami di berbagai ajang kompetisi kejuruan dan akademik.</p>
    </div>

    @if($daftarPrestasi->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-trophy text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada data prestasi yang tercatat.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($daftarPrestasi as $p)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition">
                    @if(!empty($p->gambar) && file_exists(public_path('storage/' . $p->gambar)))
                        <img src="{{ asset('storage/' . $p->gambar) }}" class="h-48 w-full object-cover" alt="{{ $p->judul }}">
                    @else
                        <div class="h-44 w-full bg-gradient-to-br from-amber-600 via-emerald-800 to-slate-900 text-white flex flex-col items-center justify-center p-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-3xl mb-2 text-amber-300">
                                <i class="fa-solid fa-trophy"></i>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-amber-200">{{ $p->tingkat }} • {{ $p->tahun }}</span>
                        </div>
                    @endif
                    <div class="p-6 flex flex-col flex-grow space-y-3">
                        <div class="flex items-center space-x-2">
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                Tingkat {{ $p->tingkat }}
                            </span>
                            <span class="bg-slate-100 text-slate-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                Tahun {{ $p->tahun }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 leading-snug">{{ $p->judul }}</h3>
                        <div class="text-xs text-emerald-800 font-semibold flex items-center space-x-1.5">
                            <i class="fa-solid fa-medal"></i>
                            <span>Peraih: {{ $p->nama_peraih }}</span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">{{ $p->deskripsi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

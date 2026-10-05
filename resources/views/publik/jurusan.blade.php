@extends('layouts.publik', ['title' => 'Jurusan & Program Keahlian - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Program Keahlian</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Jurusan di SMKN 13 Bandung</h1>
        <p class="text-slate-500 text-sm mt-2">Pilihan kompetensi keahlian yang disesuaikan dengan kebutuhan dunia industri terkini.</p>
    </div>

    @if($daftarJurusan->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-graduation-cap text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada data jurusan yang tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($daftarJurusan as $j)
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition flex flex-col">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center text-2xl font-bold">
                        <i class="fa-solid {{ $j->ikon }}"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">{{ $j->kode }}</span>
                        <h3 class="text-xl font-bold text-slate-900 mt-0.5">{{ $j->nama }}</h3>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed flex-grow">{{ $j->deskripsi }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

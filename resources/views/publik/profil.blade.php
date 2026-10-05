@extends('layouts.publik', ['title' => 'Profil & Sejarah - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <h2 class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Profil Sekolah</h2>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Sejarah, Visi & Misi SMKN 13 Bandung</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Sejarah Singkat SMKN 13 Bandung</h3>
            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                {{ $pengaturan->sejarah ?? 'Berdiri sejak tahun 2004 di Jalan Soekarno-Hatta Kota Bandung, SMK Negeri 13 Bandung konsisten menjadi rujukan pendidikan kejuruan bermutu tinggi. Dengan berfokus pada bidang teknologi informasi, rekayasa perangkat lunak, dan telekomunikasi, sekolah ini telah melahirkan ribuan alumni yang sukses berkarier di industri multinasional maupun wirausaha mandiri.' }}
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-bullseye"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Visi & Misi</h3>
            <div class="space-y-4 text-sm text-slate-600">
                <div>
                    <strong class="text-slate-900 block font-semibold mb-1">Visi:</strong>
                    <p class="leading-relaxed">{{ $pengaturan->visi ?? 'Menjadi pusat pendidikan kejuruan unggulan yang menghasilkan lulusan cerdas, kompetitif, berkarakter Pancasila, dan berwawasan global.' }}</p>
                </div>
                <div>
                    <strong class="text-slate-900 block font-semibold mb-1">Misi:</strong>
                    @if(!empty($daftarMisi))
                        <ul class="list-disc pl-5 space-y-1 mt-1">
                            @foreach($daftarMisi as $misi)
                                <li>{{ $misi }}</li>
                            @endforeach
                        </ul>
                    @else
                        <ul class="list-disc pl-5 space-y-1 mt-1">
                            <li>Menyelenggarakan pembelajaran berbasis teknologi industri modern.</li>
                            <li>Menjalin kemitraan strategis dengan Dunia Usaha dan Dunia Industri (DUDI).</li>
                            <li>Membentuk karakter disiplin, berakhlak mulia, dan siap kerja.</li>
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 text-center">
        <h3 class="text-xl font-bold text-slate-900 mb-2">Struktur Organisasi Sekolah</h3>
        <p class="text-slate-500 text-sm mb-6">Bagan manajemen kepemimpinan SMKN 13 Bandung</p>
        <div class="bg-slate-50 p-6 rounded-xl border border-dashed border-slate-300 inline-block w-full">
            @if(!empty($pengaturan->gambar_struktur))
                <img src="{{ asset('storage/' . $pengaturan->gambar_struktur) }}" alt="Struktur Organisasi" class="mx-auto rounded-lg max-h-96 object-contain">
            @else
                <div class="py-12 text-slate-400 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-sitemap text-5xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-semibold text-slate-600">Bagan Struktur Organisasi SMKN 13 Bandung</p>
                    <p class="text-xs text-slate-400 mt-1">Dapat diperbarui melalui panel Pengaturan Sekolah oleh Administrator</p>
                </div>
            @endif
        </div>
    </div>

    <div>
        <div class="text-center max-w-2xl mx-auto mb-8">
            <h3 class="text-2xl font-bold text-slate-900">Tenaga Pendidik & Staff Pengajar</h3>
            <p class="text-slate-500 text-sm mt-1">Guru dan tenaga kependidikan berdedikasi tinggi</p>
        </div>

        @if($daftarGuru->isEmpty())
            <div class="bg-white p-8 rounded-2xl text-center text-slate-500 border border-slate-200">
                <p>Belum ada data guru yang ditampilkan.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($daftarGuru as $g)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center space-y-3 hover:shadow-md transition">
                        @if(!empty($g->foto))
                            <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->nama }}" class="w-24 h-24 rounded-full mx-auto object-cover shadow border border-slate-100">
                        @else
                            <div class="w-24 h-24 rounded-full mx-auto bg-gradient-to-tr from-emerald-800 to-emerald-600 text-white flex items-center justify-center text-2xl font-bold shadow">
                                {{ strtoupper(substr($g->nama, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $g->nama }}</h4>
                            <p class="text-xs text-emerald-700 font-medium mt-0.5">{{ $g->mapel_utama ?? $g->jabatan ?? 'Tenaga Pengajar' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

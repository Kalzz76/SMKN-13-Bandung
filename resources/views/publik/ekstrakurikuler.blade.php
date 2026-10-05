@extends('layouts.publik', ['title' => 'Ekstrakurikuler - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Kesiswaan</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Ekstrakurikuler SMKN 13 Bandung</h1>
        <p class="text-slate-500 text-sm mt-2">Wadah pembinaan minat, bakat, kepemimpinan, dan karakter unggul siswa.</p>
    </div>

    @if($daftarEkskul->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center text-slate-500 border border-slate-200">
            <i class="fa-solid fa-volleyball text-4xl text-slate-300 mb-3"></i>
            <p>Belum ada data ekstrakurikuler yang tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($daftarEkskul as $e)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition">
                    @if(!empty($e->gambar) && file_exists(public_path('storage/' . $e->gambar)))
                        <img src="{{ asset('storage/' . $e->gambar) }}" class="h-48 w-full object-cover" alt="{{ $e->nama }}">
                    @else
                        <div class="h-44 w-full bg-gradient-to-br from-emerald-800 to-slate-900 text-white flex flex-col items-center justify-center p-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-3xl mb-2 text-emerald-300">
                                <i class="fa-solid fa-people-group"></i>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wider text-emerald-200">Organisasi & Ekskul</span>
                        </div>
                    @endif
                    <div class="p-6 flex flex-col flex-grow space-y-3">
                        <h3 class="text-xl font-bold text-slate-900">{{ $e->nama }}</h3>
                        <p class="text-slate-600 text-sm leading-relaxed flex-grow">{{ $e->deskripsi }}</p>
                        <div class="pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center space-x-2">
                                <i class="fa-solid fa-user-tie text-emerald-700 w-4"></i>
                                <span><strong>Pembina:</strong> {{ $e->pembina ?? '-' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fa-regular fa-clock text-emerald-700 w-4"></i>
                                <span><strong>Jadwal:</strong> {{ $e->jadwal ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

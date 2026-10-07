@extends('layouts.publik', ['title' => 'Warta & Berita - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-10 w-full flex-grow">
    <div class="relative rounded-3xl overflow-hidden shadow-lg bg-navy-dark min-h-[200px] sm:min-h-[240px] flex items-center justify-center text-center px-6 py-10 border-b-8 border-teal-primary">
        <div class="absolute inset-0" data-slider data-offset="2"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-navy-dark/90 via-navy-dark/75 to-navy-mid/85"></div>
        <div class="relative z-10 [text-shadow:0_2px_12px_rgba(0,0,0,.3)]">
            <span class="inline-block mb-3 px-4 py-1.5 rounded-full bg-teal-primary text-xs font-black uppercase tracking-widest text-white">
                Informasi Terkini
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white">Warta & Berita</h1>
            <p class="mt-3 text-sm sm:text-base text-teal-tint/90">Berita, Kegiatan & Prestasi Sekolah SMKN 13 Bandung.</p>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-bg-card p-6 rounded-3xl shadow-sm border border-teal-tint flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Kategori Filters -->
        <div class="flex items-center space-x-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0" id="beritaFilterBtns">
            <button type="button" onclick="filterByKategori('Semua')"
                class="filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-navy-dark text-white border-navy-dark"
                data-kategori="Semua">Semua</button>
            <button type="button" onclick="filterByKategori('Prestasi')"
                class="filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-white text-navy-dark border-teal-tint hover:bg-teal-tint"
                data-kategori="Prestasi">Prestasi</button>
            <button type="button" onclick="filterByKategori('Agenda')"
                class="filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-white text-navy-dark border-teal-tint hover:bg-teal-tint"
                data-kategori="Agenda">Agenda</button>
            <button type="button" onclick="filterByKategori('Ekskul')"
                class="filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-white text-navy-dark border-teal-tint hover:bg-teal-tint"
                data-kategori="Ekskul">Ekskul</button>
            <button type="button" onclick="filterByKategori('Pengumuman')"
                class="filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-white text-navy-dark border-teal-tint hover:bg-teal-tint"
                data-kategori="Pengumuman">Pengumuman</button>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:w-80">
            <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
            <input type="text" id="searchBeritaInput" onkeyup="filterBeritaList()"
                placeholder="Cari judul berita..."
                class="w-full pl-11 pr-4 py-3 rounded-full border border-teal-tint focus:outline-none focus:border-teal-primary bg-bg-page text-sm font-medium">
        </div>
    </div>

    <!-- Grid Berita -->
    @if($daftarBerita->isEmpty())
        <div class="bg-bg-card rounded-3xl p-16 text-center text-text-muted border border-teal-tint max-w-lg mx-auto">
            <i class="fa-solid fa-newspaper text-5xl text-teal-primary/40 mb-4"></i>
            <h3 class="text-xl font-bold text-navy-dark mb-1">Belum Ada Berita</h3>
            <p class="text-sm">Saat ini belum ada warta atau berita yang dipublikasikan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="beritaGridContainer">
            @foreach($daftarBerita as $b)
                <div class="berita-card bg-bg-card rounded-3xl overflow-hidden shadow-sm border border-teal-tint flex flex-col hover:shadow-lg transition-all hover:-translate-y-1 group"
                    data-kategori="{{ $b->kategori }}"
                    data-judul="{{ strtolower($b->judul) }}">
                    @if($b->gambar_url)
                        <a href="{{ route('publik.detail-berita', $b->id) }}" class="overflow-hidden block relative">
                            <img src="{{ $b->gambar_url }}" class="h-56 w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $b->judul }}">
                            @if(!empty($b->is_prestasi))
                                <div class="absolute top-4 right-4 bg-amber-500 text-white text-[11px] font-black px-3 py-1 rounded-full shadow-md flex items-center gap-1.5">
                                    <i class="fa-solid fa-trophy text-xs"></i>
                                    <span>Prestasi</span>
                                </div>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('publik.detail-berita', $b->id) }}" class="h-56 w-full {{ !empty($b->is_prestasi) ? 'bg-amber-500/10 text-amber-600' : 'bg-navy-mid/10 text-teal-primary' }} flex flex-col items-center justify-center group-hover:opacity-90 transition">
                            <i class="fa-solid {{ !empty($b->is_prestasi) ? 'fa-trophy' : 'fa-newspaper' }} text-4xl mb-2"></i>
                            <span class="text-xs font-bold text-navy-dark">{{ !empty($b->is_prestasi) ? 'Prestasi Siswa' : $b->kategori }}</span>
                        </a>
                    @endif

                    <div class="p-8 space-y-4 flex flex-col flex-grow">
                        <div class="flex justify-between items-center text-xs">
                            @if(!empty($b->is_prestasi))
                                <span class="bg-amber-100 text-amber-800 font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                    <i class="fa-solid fa-trophy text-amber-600 text-xs"></i> Prestasi
                                </span>
                            @else
                                <span class="bg-teal-tint text-teal-primary font-bold px-3 py-1.5 rounded-full">{{ $b->kategori }}</span>
                            @endif
                            <span class="text-text-muted flex items-center">
                                <i class="fa-regular fa-calendar mr-1.5 text-teal-primary"></i>
                                {{ \Carbon\Carbon::parse($b->tanggal)->isoFormat('D MMMM Y') }}
                            </span>
                        </div>

                        <h3 class="text-xl font-black text-navy-dark leading-snug group-hover:text-teal-primary transition-colors line-clamp-2">
                            <a href="{{ route('publik.detail-berita', $b->id) }}">
                                {{ $b->judul }}
                            </a>
                        </h3>

                        @if(!empty($b->is_prestasi) && (!empty($b->nama_peraih) || !empty($b->tingkat)))
                            <div class="flex flex-wrap gap-2 text-xs">
                                @if(!empty($b->nama_peraih))
                                    <span class="bg-slate-100 text-navy-dark font-medium px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-slate-200">
                                        <i class="fa-solid fa-user-graduate text-teal-primary"></i> {{ $b->nama_peraih }}
                                    </span>
                                @endif
                                @if(!empty($b->tingkat))
                                    <span class="bg-amber-50 text-amber-800 font-medium px-2.5 py-1 rounded-lg flex items-center gap-1.5 border border-amber-200">
                                        <i class="fa-solid fa-medal text-amber-600"></i> Tingkat {{ $b->tingkat }}
                                    </span>
                                @endif
                                @if(!empty($b->tahun))
                                    <span class="bg-slate-100 text-slate-600 font-medium px-2.5 py-1 rounded-lg flex items-center gap-1 border border-slate-200">
                                        {{ $b->tahun }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        <p class="text-text-muted text-sm leading-relaxed line-clamp-3 flex-grow">
                            {{ Str::limit(strip_tags($b->isi), 130) }}
                        </p>

                        <div class="pt-4 border-t border-teal-tint/60 flex items-center justify-between">
                            <a href="{{ route('publik.detail-berita', $b->id) }}"
                                class="text-teal-primary hover:text-teal-light font-bold text-xs flex items-center space-x-1.5 transition">
                                <span>Baca Selengkapnya</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </a>
                            <span class="text-[11px] text-text-muted flex items-center gap-1">
                                <i class="fa-regular fa-user mr-1"></i> {{ $b->user->name ?? 'Admin' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State Filter/Search -->
        <div id="beritaNoResult" class="hidden bg-bg-card rounded-3xl p-16 text-center text-text-muted border border-teal-tint max-w-lg mx-auto">
            <i class="fa-solid fa-magnifying-glass text-4xl text-teal-primary/40 mb-3"></i>
            <p class="font-bold text-navy-dark">Berita tidak ditemukan</p>
            <p class="text-xs text-text-muted mt-1">Coba kata kunci pencarian atau kategori lain.</p>
        </div>

        <!-- Pagination -->
        <div class="pt-8 flex justify-center" id="beritaPaginationWrapper">
            {{ $daftarBerita->links() }}
        </div>
    @endif
</div>

<script>
    let activeKategori = 'Semua';

    function filterByKategori(kat) {
        activeKategori = kat;

        document.querySelectorAll('.filter-btn').forEach(btn => {
            const btnKat = btn.getAttribute('data-kategori');
            if (btnKat && btnKat.toLowerCase() === kat.toLowerCase()) {
                btn.className = 'filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-navy-dark text-white border-navy-dark';
            } else {
                btn.className = 'filter-btn px-5 py-2.5 rounded-full text-xs font-bold border transition whitespace-nowrap bg-white text-navy-dark border-teal-tint hover:bg-teal-tint';
            }
        });

        filterBeritaList();
    }

    function filterBeritaList() {
        const query = (document.getElementById('searchBeritaInput').value || '').toLowerCase().trim();
        const cards = document.querySelectorAll('.berita-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardKat = card.getAttribute('data-kategori') || '';
            const cardJudul = card.getAttribute('data-judul') || '';

            const matchKat = (activeKategori === 'Semua' || cardKat.toLowerCase() === activeKategori.toLowerCase());
            const matchQuery = (query === '' || cardJudul.includes(query));

            if (matchKat && matchQuery) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const noResult = document.getElementById('beritaNoResult');
        const pagination = document.getElementById('beritaPaginationWrapper');

        if (noResult) {
            noResult.style.display = (visibleCount === 0) ? 'block' : 'none';
        }

        if (pagination) {
            pagination.style.display = (query !== '' || activeKategori !== 'Semua') ? 'none' : 'flex';
        }
    }

    // Auto-filter jika ada query param kategori (misal: /berita?kategori=Prestasi)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const katParam = urlParams.get('kategori');
        if (katParam) {
            filterByKategori(katParam);
        }
    });
</script>
@endsection

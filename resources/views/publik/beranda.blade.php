@extends('layouts.publik', ['title' => 'Beranda - SMKN 13 Bandung'])

@section('content')
    <!-- HERO SECTION -->
    <div class="relative bg-gradient-to-r from-navy-dark to-navy-mid py-28 sm:py-36 px-4 overflow-hidden text-white">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#9ED6CF_1px,transparent_1px)] [background-size:24px_24px]"></div>
        
        <div class="max-w-4xl mx-auto relative z-10 text-center">
            @if(!empty($pengaturan->slogan))
                <span class="inline-block bg-teal-primary/40 text-teal-accent text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full border border-teal-accent/30 mb-6">
                    {{ $pengaturan->slogan }}
                </span>
            @endif

            <h1 class="text-5xl sm:text-7xl font-black mb-6 tracking-tighter text-white leading-tight">
                SMK Negeri 13 <br><span class="text-teal-accent">Bandung.</span>
            </h1>

            <p class="text-lg sm:text-2xl text-teal-tint/90 max-w-2xl mx-auto font-medium leading-relaxed mb-8">
                {{ $pengaturan->deskripsi_singkat ?? 'Pusat Keunggulan Vokasi yang berfokus pada akhlak mulia, kompetensi, dan daya saing internasional.' }}
            </p>

            <div class="flex justify-center items-center gap-4 flex-wrap">
                <a href="{{ route('publik.jurusan') }}"
                    class="btn-animate bg-teal-primary hover:bg-teal-light text-white font-bold px-8 py-3.5 rounded-full shadow-lg transition flex items-center space-x-2">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Konsentrasi Keahlian</span>
                </a>
                <a href="{{ route('publik.profil') }}"
                    class="btn-animate bg-white/10 hover:bg-white/20 text-white font-bold px-8 py-3.5 rounded-full backdrop-blur border border-white/20 transition flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Tentang Sekolah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KARTU KEUNGGULAN -->
    <section class="py-20 max-w-7xl mx-auto px-4 text-center w-full">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
            <a href="{{ route('publik.jurusan') }}" class="flex flex-col items-center btn-animate group cursor-pointer">
                <div class="w-24 h-24 rounded-full bg-teal-tint flex items-center justify-center text-teal-primary text-4xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-flask"></i>
                </div>
                <h4 class="font-bold text-navy-dark text-lg uppercase mb-2 group-hover:text-teal-primary transition-colors">
                    Konsentrasi Keahlian
                </h4>
                <p class="text-sm text-text-muted">Kurikulum mendalam dengan standar industri nasional dan global.</p>
            </a>

            <a href="{{ route('publik.galeri') }}" class="flex flex-col items-center btn-animate group cursor-pointer">
                <div class="w-24 h-24 rounded-full bg-teal-tint flex items-center justify-center text-teal-primary text-4xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <h4 class="font-bold text-navy-dark text-lg uppercase mb-2 group-hover:text-teal-primary transition-colors">
                    Fasilitas Standar SNP
                </h4>
                <p class="text-sm text-text-muted">Laboratorium & sarana prasarana penunjang pembelajaran abad ke-21.</p>
            </a>

            <a href="{{ route('publik.berita') }}" class="flex flex-col items-center btn-animate group cursor-pointer">
                <div class="w-24 h-24 rounded-full bg-teal-tint flex items-center justify-center text-teal-primary text-4xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-award"></i>
                </div>
                <h4 class="font-bold text-navy-dark text-lg uppercase mb-2 group-hover:text-teal-primary transition-colors">
                    Prestasi & Asesmen
                </h4>
                <p class="text-sm text-text-muted">Evaluasi otentik berkesinambungan mencetak juara di berbagai bidang.</p>
            </a>

            <a href="{{ route('publik.profil') }}#sejarah" class="flex flex-col items-center btn-animate group cursor-pointer">
                <div class="w-24 h-24 rounded-full bg-teal-tint flex items-center justify-center text-teal-primary text-4xl mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <h4 class="font-bold text-navy-dark text-lg uppercase mb-2 group-hover:text-teal-primary transition-colors">
                    Kemitraan Global
                </h4>
                <p class="text-sm text-text-muted">Terhubung dengan dunia industri serta institusi pendidikan dalam & luar negeri.</p>
            </a>
        </div>
    </section>

    <!-- SAMBUTAN KEPALA SEKOLAH -->
    <section class="py-20 bg-bg-card border-y border-teal-tint/50 w-full">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-16">
            <div class="w-full md:w-5/12 flex justify-center">
                @if(!empty($pengaturan->foto_kepsek) && file_exists(public_path('storage/' . $pengaturan->foto_kepsek)))
                    <img src="{{ asset('storage/' . $pengaturan->foto_kepsek) }}"
                        alt="{{ $pengaturan->nama_kepsek ?? 'Agus Nugroho, S.Pd., M.T.' }}"
                        class="rounded-3xl shadow-xl w-full max-w-sm object-cover object-top h-[450px] bg-teal-tint border border-teal-tint">
                @else
                    <img src="{{ asset('Assets/agus.webp') }}"
                        alt="Agus Nugroho, S.Pd., M.T."
                        class="rounded-3xl shadow-xl w-full max-w-sm object-cover object-top h-[450px] bg-teal-tint border border-teal-tint">
                @endif
            </div>

            <div class="w-full md:w-7/12 space-y-6">
                <div class="inline-block bg-teal-tint text-teal-primary text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full">
                    Sambutan Pimpinan
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-navy-dark">
                    {{ $pengaturan->judul_sambutan ?? 'Sambutan Kepala Sekolah' }}
                </h2>

                <div class="space-y-4 text-text-muted leading-relaxed font-medium">
                    @if(!empty($pengaturan->sambutan))
                        <div class="whitespace-pre-line text-justify">{{ $pengaturan->sambutan }}</div>
                    @else
                        <p>Assalamu'alaikum warahmatullahi wabarakatuh, Salam sejahtera untuk kita semua.</p>
                        <p>Dengan rasa syukur, saya menyampaikan <b class="text-teal-primary">apresiasi kepada seluruh warga SMK Negeri 13 Bandung</b> atas dedikasi dan semangat dalam memajukan pendidikan. Kami berkomitmen mencetak lulusan yang unggul, berkarakter, dan siap menghadapi tantangan dunia kerja.</p>
                        <p>SMK Negeri 13 Bandung terus berkomitmen memberikan <b class="text-navy-dark">pendidikan berkualitas tinggi</b> dengan berbagai program keahlian unggulan yang diselaraskan dengan kebutuhan industri masa kini.</p>
                        <p>Wassalamu'alaikum warahmatullahi wabarakatuh.</p>
                    @endif
                </div>

                <div class="pt-4 border-t border-teal-tint">
                    <h4 class="font-bold text-xl text-navy-dark">
                        {{ $pengaturan->nama_kepsek ?? 'Agus Nugroho, S.Pd., M.T.' }}
                    </h4>
                    <p class="text-sm font-semibold text-teal-primary">Kepala SMK Negeri 13 Bandung</p>
                </div>
            </div>
        </div>
    </section>

    <!-- WARTA & BERITA TERKINI -->
    <section class="py-20 w-full">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-4xl font-black text-navy-dark mb-12">Warta & Berita Terkini</h3>

            @if($beritaTerbaru->isEmpty())
                <div class="bg-bg-card p-12 rounded-3xl text-center text-text-muted border border-teal-tint max-w-lg mx-auto">
                    <i class="fa-solid fa-newspaper text-4xl text-teal-primary/50 mb-3"></i>
                    <p class="font-medium">Belum ada warta berita yang dipublikasikan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
                    @foreach($beritaTerbaru as $b)
                        <a href="{{ route('publik.detail-berita', $b->id) }}"
                            class="bg-bg-card rounded-3xl overflow-hidden shadow-sm border border-teal-tint flex flex-col hover:shadow-lg transition-all hover:-translate-y-1 group">
                            @if(!empty($b->gambar) && file_exists(public_path('storage/' . $b->gambar)))
                                <img src="{{ asset('storage/' . $b->gambar) }}" class="h-56 w-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $b->judul }}">
                            @else
                                <div class="h-56 w-full bg-navy-mid/10 flex flex-col items-center justify-center text-teal-primary">
                                    <i class="fa-solid fa-newspaper text-4xl mb-2"></i>
                                    <span class="text-xs font-bold text-navy-dark">{{ $b->kategori }}</span>
                                </div>
                            @endif

                            <div class="p-8 space-y-4 flex flex-col flex-grow">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="bg-teal-tint text-teal-primary font-bold px-3 py-1.5 rounded-full">{{ $b->kategori }}</span>
                                    <span class="text-text-muted">{{ \Carbon\Carbon::parse($b->tanggal)->isoFormat('D MMMM Y') }}</span>
                                </div>
                                <h4 class="text-xl font-black text-navy-dark leading-snug group-hover:text-teal-primary transition-colors line-clamp-2">
                                    {{ $b->judul }}
                                </h4>
                                <p class="text-text-muted text-sm leading-relaxed line-clamp-3 flex-grow">
                                    {{ Str::limit(strip_tags($b->isi), 130) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('publik.berita') }}"
                class="mt-12 inline-block btn-animate bg-navy-dark hover:bg-navy-mid text-white font-bold py-3.5 px-8 rounded-full shadow-md transition">
                Lihat Semua Berita
            </a>
        </div>
    </section>
@endsection
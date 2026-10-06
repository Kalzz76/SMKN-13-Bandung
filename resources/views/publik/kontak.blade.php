@extends('layouts.publik', ['title' => 'Pusat Bantuan & Lokasi - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 w-full flex-grow">
    <!-- Header Halaman -->
    <div class="text-center max-w-3xl mx-auto">
        <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Layanan Informasi</span>
        <h1 class="text-4xl sm:text-5xl font-black text-navy-dark mt-1 mb-3">Pusat Bantuan & Lokasi</h1>
        <p class="text-base sm:text-lg text-text-muted">Hubungi layanan informasi resmi SMK Negeri 13 Bandung atau kunjungi kampus kami pada jam operasional kerja.</p>
    </div>

    <!-- Main Card Info & Maps Embed -->
    <div class="bg-bg-card rounded-3xl p-8 sm:p-14 shadow-sm border border-teal-tint flex flex-col lg:flex-row gap-12 items-stretch">
        <!-- Kolom Kiri: Informasi Kontak -->
        <div class="w-full lg:w-1/2 space-y-8">
            <!-- NPSN -->
            <div class="flex items-start space-x-5">
                <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-navy-dark">Nomor Pokok Sekolah Nasional (NPSN)</h4>
                    <p class="mt-1 text-teal-primary font-bold text-sm tracking-wider">{{ $pengaturan->npsn ?? '20219161' }}</p>
                </div>
            </div>

            <!-- Alamat Kampus -->
            <div class="flex items-start space-x-5">
                <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-navy-dark">Alamat Kampus</h4>
                    <p class="mt-1 text-text-muted text-sm sm:text-base leading-relaxed">
                        {{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta KM. 10, Babakan Penghulu, Cinambo, Kota Bandung, Jawa Barat 40294' }}
                    </p>
                </div>
            </div>

            <!-- Email Resmi -->
            <div class="flex items-start space-x-5">
                <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-navy-dark">Email Resmi</h4>
                    <div class="mt-1 space-y-0.5 text-sm sm:text-base">
                        <a href="mailto:smk13bdg@gmail.com" class="block text-teal-primary font-semibold hover:underline">
                            {{ $pengaturan->email ?? 'smk13bdg@gmail.com' }}
                        </a>
                        <a href="mailto:smkn13bandung@gmail.com" class="block text-text-muted hover:underline text-xs">
                            smkn13bandung@gmail.com
                        </a>
                    </div>
                </div>
            </div>

            <!-- Nomor Telepon -->
            <div class="flex items-start space-x-5">
                <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-navy-dark">Nomor Telepon</h4>
                    <p class="mt-1 text-text-muted text-sm sm:text-base">
                        <a href="tel:0227830182" class="text-teal-primary font-semibold hover:underline">
                            {{ $pengaturan->telepon ?? '(022) 7830182' }}
                        </a>
                    </p>
                </div>
            </div>

            <!-- Website Resmi -->
            <div class="flex items-start space-x-5">
                <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-navy-dark">Website Resmi</h4>
                    <p class="mt-1 text-sm text-teal-primary font-semibold space-x-3">
                        <a href="https://smkn13bdg.sch.id" target="_blank" rel="noopener noreferrer" class="hover:underline">https://smkn13bdg.sch.id</a>
                        <span class="text-text-muted">&bull;</span>
                        <a href="https://smkn13bandung.sch.id" target="_blank" rel="noopener noreferrer" class="hover:underline">https://smkn13bandung.sch.id</a>
                    </p>
                </div>
            </div>

            <!-- Jam Operasional -->
            <div class="p-6 rounded-2xl bg-bg-page border border-teal-tint space-y-2">
                <h5 class="font-bold text-navy-dark text-sm flex items-center space-x-2">
                    <i class="fa-regular fa-clock text-teal-primary"></i>
                    <span>Jam Operasional Layanan</span>
                </h5>
                <p class="text-xs text-text-muted leading-relaxed">
                    <strong>Senin – Jumat:</strong> 07.00 – 16.00 WIB<br>
                    <strong>Sabtu – Minggu & Hari Libur Nasional:</strong> Pelayanan Tutup
                </p>
            </div>
        </div>

        <!-- Kolom Kanan: Google Maps Embed -->
        <div class="w-full lg:w-1/2 flex flex-col">
            <div class="w-full bg-teal-tint rounded-3xl overflow-hidden min-h-[380px] lg:h-full border-2 border-teal-tint/60 shadow-inner">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.5979934865886!2d107.65442027499668!3d-6.938554693061444!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e8109c2647c7%3A0xe47eec0775b8d648!2sSMKN%2013%20Bandung!5e0!3m2!1sid!2sid!4v1791270201512!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0; min-height: 420px;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection

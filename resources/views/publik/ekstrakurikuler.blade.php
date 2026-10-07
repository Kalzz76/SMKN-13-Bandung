@extends('layouts.publik', ['title' => 'Ekstrakurikuler Pilihan - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 max-w-5xl mx-auto space-y-12 w-full flex-grow">
    <!-- BANNER HEADER PERSIS REFERENSI DESAIN -->
    <div class="bg-navy-dark rounded-3xl p-10 sm:p-16 text-center shadow-lg text-white border-b-8 border-teal-primary">
        <span class="bg-teal-primary text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block mb-4">
            Pengembangan Diri
        </span>
        <h1 class="text-4xl sm:text-5xl font-black mb-4">
            Ekstrakurikuler Pilihan
        </h1>
        <p class="text-teal-tint max-w-2xl mx-auto text-lg">
            Pilih wadah pengembangan bakat dan minatmu di Ekstrakurikuler SMKN 13 Bandung.
        </p>
    </div>

    @php
        $presetMeta = [
            'pmr' => [
                'icon' => 'fa-kit-medical',
                'ig' => '@pmrsmkn13_bdg',
                'link' => 'https://www.instagram.com/pmrsmkn13_bdg?stkn=Z3o4Z3FzdnU4MDc2',
                'default_img' => asset('Assets/ekskul/pmr.jpg'),
            ],
            'irma' => [
                'icon' => 'fa-mosque',
                'ig' => '@irmaalhikmah13',
                'link' => 'https://www.instagram.com/irmaalhikmah13?stkn=ZjllZWM3dmk3YW9h',
                'default_img' => asset('Assets/ekskul/irma.jpg'),
            ],
            'sastrala' => [
                'icon' => 'fa-feather-pointed',
                'ig' => '@sastrala.id',
                'link' => 'https://www.instagram.com/sastrala.id?stkn=ZzN6Ynlxdmg5ajE2',
                'default_img' => asset('Assets/ekskul/sastrala.jpeg'),
            ],
            'padus' => [
                'icon' => 'fa-music',
                'ig' => '@voice.smkn13',
                'link' => 'https://www.instagram.com/voice.smkn13?stkn=MXVmdWNheGVlbjk5eQ==',
                'default_img' => asset('Assets/ekskul/padus.jpeg'),
            ],
            'banzai' => [
                'icon' => 'fa-star',
                'ig' => '@banzai13vhs',
                'link' => 'https://www.instagram.com/banzai13vhs?stkn=aGNqZWphazBvMzRy',
                'default_img' => asset('Assets/ekskul/banzai.jpg'),
            ],
            'karawitan' => [
                'icon' => 'fa-drum',
                'ig' => '@aleutan_13',
                'link' => 'https://www.instagram.com/aleutan_13?stkn=MXU0YmwzeWJvMDNxdQ==',
                'default_img' => asset('Assets/ekskul/karawitan.jpg'),
            ],
            'pramuka' => [
                'icon' => 'fa-campground',
                'ig' => '@pramukasmkn13bdg',
                'link' => 'https://www.instagram.com/pramukasmkn13bdg?stkn=bDEyZXpsYXhrbTZx',
                'default_img' => asset('Assets/ekskul/pramuka.jpeg'),
            ],
            'paskibra' => [
                'icon' => 'fa-flag',
                'ig' => '@paskibra_paramartha13',
                'link' => 'https://www.instagram.com/paskibra_paramartha13?stkn=MXE1M2VvaHN4dTVvNQ==',
                'default_img' => asset('Assets/ekskul/paskibra.jpeg'),
            ],
            'english' => [
                'icon' => 'fa-language',
                'ig' => '@beengo.smkn13',
                'link' => 'https://www.instagram.com/beengo.smkn13?stkn=bjF5bmloODEwYWo4',
                'default_img' => asset('Assets/LOGOS.jpg'),
            ],
        ];

        $semuaEkskul = [];
        if ($daftarEkskul && $daftarEkskul->isNotEmpty()) {
            foreach ($daftarEkskul as $dbEk) {
                $lower = strtolower($dbEk->nama);
                $meta = [
                    'icon' => 'fa-star',
                    'ig' => '@smkn13bandung',
                    'link' => 'https://www.instagram.com/smkn13bandung?stkn=NnhydHlmaTV2NHMy',
                    'default_img' => asset('Assets/LOGOS.jpg'),
                ];

                foreach ($presetMeta as $key => $val) {
                    if (str_contains($lower, $key)) {
                        $meta = $val;
                        break;
                    }
                }

                $gambarSrc = $dbEk->gambar_url ?: $meta['default_img'];

                $semuaEkskul[] = [
                    'id' => 'db_' . $dbEk->id,
                    'nama' => $dbEk->nama,
                    'icon' => $meta['icon'],
                    'desc' => $dbEk->deskripsi ?? 'Wadah kreativitas dan minat bakat siswa SMKN 13 Bandung.',
                    'ig' => $meta['ig'],
                    'link' => $meta['link'],
                    'gambar' => $gambarSrc,
                ];
            }
        } else {
            $semuaEkskul = [
                ['id' => 'ek1', 'nama' => 'PMR (Palang Merah Remaja)', 'icon' => 'fa-kit-medical', 'desc' => 'Pelatihan pertolongan pertama, kesehatan remaja, dan kesiapsiagaan bencana.', 'ig' => '@pmrsmkn13_bdg', 'link' => 'https://www.instagram.com/pmrsmkn13_bdg?stkn=Z3o4Z3FzdnU4MDc2', 'gambar' => asset('Assets/ekskul/pmr.jpg')],
                ['id' => 'ek2', 'nama' => 'IRMA Al-Hikmah', 'icon' => 'fa-mosque', 'desc' => 'Wadah pembinaan keislaman dan akhlak mulia melalui kegiatan keagamaan di lingkungan sekolah.', 'ig' => '@irmaalhikmah13', 'link' => 'https://www.instagram.com/irmaalhikmah13?stkn=ZjllZWM3dmk3YW9h', 'gambar' => asset('Assets/ekskul/irma.jpg')],
                ['id' => 'ek3', 'nama' => 'Sastrala', 'icon' => 'fa-feather-pointed', 'desc' => 'Mengembangkan minat dan bakat siswa di bidang sastra, menulis, dan berkarya kreatif.', 'ig' => '@sastrala.id', 'link' => 'https://www.instagram.com/sastrala.id?stkn=ZzN6Ynlxdmg5ajE2', 'gambar' => asset('Assets/ekskul/sastrala.jpeg')],
                ['id' => 'ek4', 'nama' => 'Padus (Paduan Suara)', 'icon' => 'fa-music', 'desc' => 'Melatih olah vokal, harmoni, dan penampilan paduan suara untuk berbagai acara sekolah.', 'ig' => '@voice.smkn13', 'link' => 'https://www.instagram.com/voice.smkn13?stkn=MXVmdWNheGVlbjk5eQ==', 'gambar' => asset('Assets/ekskul/padus.jpeg')],
                ['id' => 'ek5', 'nama' => 'Banzai', 'icon' => 'fa-star', 'desc' => 'Wadah kreativitas dan pengembangan minat siswa SMKN 13 Bandung.', 'ig' => '@banzai13vhs', 'link' => 'https://www.instagram.com/banzai13vhs?stkn=aGNqZWphazBvMzRy', 'gambar' => asset('Assets/ekskul/banzai.jpg')],
                ['id' => 'ek6', 'nama' => 'Karawitan', 'icon' => 'fa-drum', 'desc' => 'Melestarikan seni musik tradisional Sunda melalui latihan dan pementasan karawitan.', 'ig' => '@aleutan_13', 'link' => 'https://www.instagram.com/aleutan_13?stkn=MXU0YmwzeWJvMDNxdQ==', 'gambar' => asset('Assets/ekskul/karawitan.jpg')],
                ['id' => 'ek7', 'nama' => 'Pramuka', 'icon' => 'fa-campground', 'desc' => 'Gerakan pramuka berlandaskan Dasa Darma untuk mencetak siswa berkarakter dan berjiwa kepemimpinan.', 'ig' => '@pramukasmkn13bdg', 'link' => 'https://www.instagram.com/pramukasmkn13bdg?stkn=bDEyZXpsYXhrbTZx', 'gambar' => asset('Assets/ekskul/pramuka.jpeg')],
                ['id' => 'ek8', 'nama' => 'Paskibra', 'icon' => 'fa-flag', 'desc' => 'Melatih kedisiplinan, baris-berbaris, dan nasionalisme untuk petugas upacara bendera.', 'ig' => '@paskibra_paramartha13', 'link' => 'https://www.instagram.com/paskibra_paramartha13?stkn=MXE1M2VvaHN4dTVvNQ==', 'gambar' => asset('Assets/ekskul/paskibra.jpeg')],
                ['id' => 'ek9', 'nama' => 'English Club', 'icon' => 'fa-language', 'desc' => 'Melatih kemampuan berbahasa Inggris lewat percakapan, debat, dan berbagai kegiatan seru.', 'ig' => '@beengo.smkn13', 'link' => 'https://www.instagram.com/beengo.smkn13?stkn=bjF5bmloODEwYWo4', 'gambar' => asset('Assets/LOGOS.jpg')]
            ];
        }
    @endphp

    <!-- DAFTAR EKSKUL ZIG-ZAG PERSIS REFERENSI DESAIN -->
    <div class="space-y-8" id="pubEkskulList">
        @foreach($semuaEkskul as $i => $e)
            <div class="bg-bg-card rounded-3xl overflow-hidden shadow-sm border border-teal-tint flex flex-col md:flex-row {{ $i % 2 ? 'md:flex-row-reverse' : '' }}">
                <div class="w-full md:w-5/12 h-64 md:h-auto relative bg-teal-tint">
                    <img src="{{ $e['gambar'] }}" alt="{{ $e['nama'] }}" class="w-full h-full object-cover absolute inset-0">
                </div>
                <div class="w-full md:w-7/12 p-8 sm:p-12 space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-teal-tint text-teal-primary flex items-center justify-center text-2xl flex-shrink-0">
                            <i class="fa-solid {{ $e['icon'] }}"></i>
                        </div>
                        <h4 class="text-3xl font-black text-navy-dark leading-tight">{{ $e['nama'] }}</h4>
                    </div>

                    <p class="font-medium leading-relaxed text-text-main text-base sm:text-lg">
                        {{ $e['desc'] }}
                    </p>

                    <div class="text-sm bg-bg-page p-5 rounded-2xl border border-teal-tint/50">
                        <span class="block text-text-muted text-xs font-bold uppercase mb-1 tracking-wider">Instagram</span>
                        <b class="text-navy-dark flex items-center text-sm">
                            <i class="fa-brands fa-instagram mr-2 text-rose-500 text-base"></i>
                            <span>{{ $e['ig'] }}</span>
                        </b>
                    </div>

                    <a href="{{ $e['link'] }}" target="_blank" rel="noopener noreferrer"
                        class="btn-animate inline-block bg-brick-red hover:bg-brick-dark text-white font-bold px-8 py-3.5 rounded-full shadow-md text-sm">
                        Bergabung Sekarang <i class="fa-solid fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

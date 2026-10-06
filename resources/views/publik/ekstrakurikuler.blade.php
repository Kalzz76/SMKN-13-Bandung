@extends('layouts.publik', ['title' => 'Ekstrakurikuler - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-12 w-full flex-grow">
    <!-- Header Banner Ekskul -->
    <div class="bg-navy-dark rounded-3xl p-10 sm:p-14 text-center shadow-lg text-white border-b-8 border-teal-primary">
        <span class="bg-teal-primary text-teal-tint text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block mb-4">
            Pengembangan Diri
        </span>
        <h1 class="text-4xl sm:text-5xl font-black mb-4 tracking-tight">
            Ekstrakurikuler SMKN 13 Bandung
        </h1>
        <p class="text-teal-tint/90 max-w-2xl mx-auto text-base sm:text-lg font-medium leading-relaxed">
            Pilih wadah pengembangan bakat, minat, kepemimpinan, dan kreativitasmu bersama organisasi kesiswaan unggulan.
        </p>
    </div>

    @php
        $presetEkskul = [
            [
                'nama' => 'PMR (Palang Merah Remaja)',
                'icon' => 'fa-kit-medical',
                'desc' => 'Pelatihan pertolongan pertama, kesehatan remaja, donor darah, dan kesiapsiagaan bencana di lingkungan sekolah.',
                'ig' => '@pmrsmkn13_bdg',
                'link' => 'https://www.instagram.com/pmrsmkn13_bdg?stkn=Z3o4Z3FzdnU4MDc2',
                'gambar' => asset('Assets/ekskul/pmr.jpg'),
                'jadwal' => 'Jumat, 15.30 WIB'
            ],
            [
                'nama' => 'Pramuka (Gudep 13)',
                'icon' => 'fa-campground',
                'desc' => 'Gerakan pramuka berlandaskan Dasa Darma untuk mencetak siswa berkarakter tangguh, disiplin, dan berjiwa kepemimpinan.',
                'ig' => '@pramukasmkn13bdg',
                'link' => 'https://www.instagram.com/pramukasmkn13bdg?stkn=bDEyZXpsYXhrbTZx',
                'gambar' => asset('Assets/ekskul/pramuka.jpeg'),
                'jadwal' => 'Sabtu, 08.00 WIB'
            ],
            [
                'nama' => 'Paskibra (Paramartha 13)',
                'icon' => 'fa-flag',
                'desc' => 'Melatih kedisiplinan baris-berbaris, tata upacara bendera, dan rasa nasionalisme patriotik.',
                'ig' => '@paskibra_paramartha13',
                'link' => 'https://www.instagram.com/paskibra_paramartha13?stkn=MXE1M2VvaHN4dTVvNQ==',
                'gambar' => asset('Assets/ekskul/paskibra.jpeg'),
                'jadwal' => 'Rabu & Jumat, 15.30 WIB'
            ],
            [
                'nama' => 'IRMA Al-Hikmah',
                'icon' => 'fa-mosque',
                'desc' => 'Wadah pembinaan kerohanian Islam, kajian remaja, dan akhlak mulia melalui kegiatan keagamaan sekolah.',
                'ig' => '@irmaalhikmah13',
                'link' => 'https://www.instagram.com/irmaalhikmah13?stkn=ZjllZWM3dmk3YW9h',
                'gambar' => asset('Assets/ekskul/irma.jpg'),
                'jadwal' => 'Kamis, 15.30 WIB'
            ],
            [
                'nama' => 'Sastrala (Klub Sastra & Literasi)',
                'icon' => 'fa-feather-pointed',
                'desc' => 'Mengembangkan minat dan bakat siswa di bidang sastra, cipta puisi, penulisan artikel, dan karya kreatif.',
                'ig' => '@sastrala.id',
                'link' => 'https://www.instagram.com/sastrala.id?stkn=ZzN6Ynlxdmg5ajE2',
                'gambar' => asset('Assets/ekskul/sastrala.jpeg'),
                'jadwal' => 'Selasa, 15.30 WIB'
            ],
            [
                'nama' => 'Padus (Voice of SMKN 13)',
                'icon' => 'fa-music',
                'desc' => 'Melatih olah vokal, harmoni paduan suara, dan penampilan vokal grup untuk upacara serta festival seni.',
                'ig' => '@voice.smkn13',
                'link' => 'https://www.instagram.com/voice.smkn13?stkn=MXVmdWNheGVlbjk5eQ==',
                'gambar' => asset('Assets/ekskul/padus.jpeg'),
                'jadwal' => 'Senin & Kamis, 15.30 WIB'
            ],
            [
                'nama' => 'Banzai (Japan Community)',
                'icon' => 'fa-star',
                'desc' => 'Wadah apresiasi budaya, bahasa, seni menggambar manga, dan pertukaran pengetahuan budaya Jepang.',
                'ig' => '@banzai13vhs',
                'link' => 'https://www.instagram.com/banzai13vhs?stkn=aGNqZWphazBvMzRy',
                'gambar' => asset('Assets/ekskul/banzai.jpg'),
                'jadwal' => 'Jumat, 15.30 WIB'
            ],
            [
                'nama' => 'Karawitan Sunda (Aleutan 13)',
                'icon' => 'fa-drum',
                'desc' => 'Melestarikan seni musik tradisional gamelan Sunda melalui latihan gamelan, degung, dan pentas kebudayaan.',
                'ig' => '@aleutan_13',
                'link' => 'https://www.instagram.com/aleutan_13?stkn=MXU0YmwzeWJvMDNxdQ==',
                'gambar' => asset('Assets/ekskul/karawitan.jpg'),
                'jadwal' => 'Rabu, 15.30 WIB'
            ],
            [
                'nama' => 'English Club (BeenGo 13)',
                'icon' => 'fa-language',
                'desc' => 'Melatih kemampuan berbahasa Inggris aktif lewat percakapan santai, storytelling, debate, dan fun games.',
                'ig' => '@beengo.smkn13',
                'link' => 'https://www.instagram.com/beengo.smkn13?stkn=bjF5bmloODEwYWo4',
                'gambar' => asset('Assets/LOGOS.jpg'),
                'jadwal' => 'Selasa, 15.30 WIB'
            ],
        ];
    @endphp

    <!-- List Kartu Ekskul -->
    <div class="space-y-8">
        {{-- Gabungkan ekskul preset referensi dan ekskul dinamis dari DB jika ada --}}
        @foreach($presetEkskul as $ek)
            <div class="bg-bg-card rounded-3xl p-6 sm:p-8 shadow-sm border border-teal-tint flex flex-col md:flex-row gap-8 items-center hover:shadow-md transition">
                <div class="w-full md:w-56 h-48 rounded-2xl overflow-hidden bg-teal-tint flex-shrink-0 shadow-xs border border-teal-tint/50">
                    <img src="{{ $ek['gambar'] }}" alt="{{ $ek['nama'] }}" class="w-full h-full object-cover">
                </div>

                <div class="flex-grow space-y-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-tint text-teal-primary flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid {{ $ek['icon'] }}"></i>
                            </div>
                            <h3 class="text-2xl font-black text-navy-dark">{{ $ek['nama'] }}</h3>
                        </div>
                        <a href="{{ $ek['link'] }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-full bg-teal-tint text-teal-primary hover:bg-teal-primary hover:text-white transition text-xs font-bold">
                            <i class="fa-brands fa-instagram text-sm"></i>
                            <span>{{ $ek['ig'] }}</span>
                        </a>
                    </div>

                    <p class="text-text-muted text-sm sm:text-base leading-relaxed">
                        {{ $ek['desc'] }}
                    </p>

                    <div class="pt-2 flex items-center text-xs text-text-muted font-semibold">
                        <i class="fa-regular fa-clock mr-1.5 text-teal-primary"></i>
                        <span>Jadwal Latihan: {{ $ek['jadwal'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Render ekskul tambahan dari database jika ada yang belum termasuk di preset --}}
        @foreach($daftarEkskul as $dbEk)
            @php
                $isPreset = collect($presetEkskul)->contains(function($p) use ($dbEk) {
                    return stripos($p['nama'], $dbEk->nama) !== false || stripos($dbEk->nama, $p['nama']) !== false;
                });
            @endphp
            @if(!$isPreset)
                <div class="bg-bg-card rounded-3xl p-6 sm:p-8 shadow-sm border border-teal-tint flex flex-col md:flex-row gap-8 items-center hover:shadow-md transition">
                    <div class="w-full md:w-56 h-48 rounded-2xl overflow-hidden bg-teal-tint flex-shrink-0 shadow-xs border border-teal-tint/50">
                        @if(!empty($dbEk->gambar) && file_exists(public_path('storage/' . $dbEk->gambar)))
                            <img src="{{ asset('storage/' . $dbEk->gambar) }}" alt="{{ $dbEk->nama }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-navy-mid/10 text-teal-primary">
                                <i class="fa-solid fa-people-group text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-tint text-teal-primary flex items-center justify-center text-lg flex-shrink-0">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <h3 class="text-2xl font-black text-navy-dark">{{ $dbEk->nama }}</h3>
                        </div>

                        <p class="text-text-muted text-sm sm:text-base leading-relaxed">
                            {{ $dbEk->deskripsi }}
                        </p>

                        <div class="pt-2 flex items-center space-x-4 text-xs text-text-muted font-semibold">
                            <span><i class="fa-solid fa-user-tie mr-1 text-teal-primary"></i> Pembina: {{ $dbEk->pembina ?? '-' }}</span>
                            <span><i class="fa-regular fa-clock mr-1 text-teal-primary"></i> Jadwal: {{ $dbEk->jadwal ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endsection

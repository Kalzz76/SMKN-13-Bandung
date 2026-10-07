@extends('layouts.publik', ['title' => 'Profil & Sejarah - SMKN 13 Bandung'])

@section('content')
    <div class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-24 w-full flex-grow">
        <div id="visi-misi" class="scroll-mt-32 space-y-12">
            <div class="text-center max-w-3xl mx-auto">
                <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Identitas Sekolah</span>
                <h3 class="text-3xl sm:text-5xl font-black text-navy-dark mt-1 mb-4">Profil & Visi Misi</h3>
                <p class="text-base sm:text-lg text-text-muted">Landasan, arah kebijakan, dan penggerak mutu pendidikan SMK Negeri 13 Bandung.</p>
            </div>

            <div class="bg-bg-card p-6 sm:p-10 rounded-3xl shadow-sm border-t-8 border-teal-primary border-x border-b border-teal-tint text-center max-w-4xl mx-auto">
                <span class="bg-teal-tint text-teal-primary text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block mb-4">VISI SEKOLAH</span>
                <h4 class="text-2xl sm:text-3xl font-black text-navy-dark leading-relaxed italic">
                    "{{ $pengaturan->visi ?? 'Terwujudnya lulusan yang berakhlak mulia, kompeten dan berdaya suai di tingkat internasional pada tahun 2030' }}"
                </h4>
            </div>

            <div class="bg-bg-card p-5 sm:p-10 md:p-14 rounded-3xl shadow-sm border border-teal-tint space-y-8">
                <div class="text-center">
                    <span class="bg-navy-dark text-teal-accent text-xs font-black uppercase tracking-widest px-4 py-1.5 rounded-full inline-block mb-2">MISI SEKOLAH</span>
                    <h4 class="text-3xl font-black text-navy-dark">Langkah Strategis Pencapaian</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    @php
                        $defaultMisi = [
                            ['Penguatan Karakter', 'Menyelenggarakan program penguatan pendidikan karakter Gapura Panca Waluya dan 8 Dimensi Profil Lulusan.'],
                            ['Pembelajaran Mendalam', 'Mengembangkan keterampilan abad ke-21: berpikir kritis, kreatif, komunikatif, dan kolaboratif.'],
                            ['Profesionalisme GTK', 'Meningkatkan profesionalisme Guru dan Tenaga Kependidikan secara berkelanjutan.'],
                            ['Sarana Prasarana', 'Meningkatkan sarana prasarana mengacu pada Standar Nasional Pendidikan dan Dunia Industri.'],
                            ['Digitalisasi Sekolah', 'Pengelolaan pendidikan berbasis Teknologi Informasi dan Komunikasi (TIK).'],
                            ['Kemitraan Luas', 'Kemitraan strategis dengan Dunia Industri dan Institusi Pendidikan di dalam maupun luar negeri.'],
                            ['Asesmen Berkualitas', 'Melaksanakan asesmen yang berkelanjutan dan otentik.'],
                            ['Budaya Lingkungan', 'Budaya ramah lingkungan melalui pengolahan limbah, pengelolaan sampah dan hemat energi.']
                        ];
                    @endphp
                    @if(!empty($daftarMisi))
                        @foreach($daftarMisi as $index => $misi)
                            @php
                                $parts = explode(':', $misi, 2);
                                $judulMisi = count($parts) === 2 ? trim($parts[0]) : 'Langkah ' . ($index + 1);
                                $isiMisi = count($parts) === 2 ? trim($parts[1]) : trim($misi);
                            @endphp
                            <div class="p-6 rounded-2xl bg-bg-page border border-teal-tint flex items-start space-x-4">
                                <span class="w-8 h-8 rounded-full bg-teal-primary text-white flex items-center justify-center font-bold flex-shrink-0 text-sm shadow">{{ $index + 1 }}</span>
                                <p class="leading-relaxed text-text-main"><strong class="text-navy-dark">{{ $judulMisi }}:</strong> {{ $isiMisi }}</p>
                            </div>
                        @endforeach
                    @else
                        @foreach($defaultMisi as $index => $item)
                            <div class="p-6 rounded-2xl bg-bg-page border border-teal-tint flex items-start space-x-4">
                                <span class="w-8 h-8 rounded-full bg-teal-primary text-white flex items-center justify-center font-bold flex-shrink-0 text-sm shadow">{{ $index + 1 }}</span>
                                <p class="leading-relaxed text-text-main"><strong class="text-navy-dark">{{ $item[0] }}:</strong> {{ $item[1] }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div id="sejarah" class="scroll-mt-32 bg-bg-card p-6 sm:p-10 lg:p-14 rounded-3xl shadow-sm border border-teal-tint">
            <h4 class="text-2xl sm:text-3xl font-black text-navy-dark mb-10 text-center">Sejarah Singkat SMKN 13 Bandung</h4>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-start">
                <div class="order-2 lg:order-1">
                    <div class="relative timeline-line space-y-8 pl-12 pr-2">
                        <div class="relative z-10">
                            <div class="absolute -left-12 mt-1 w-8 h-8 bg-teal-primary rounded-full border-4 border-bg-page flex items-center justify-center text-white shadow">
                                <i class="fa-solid fa-flask text-xs"></i>
                            </div>
                            <h5 class="text-xl font-bold text-navy-dark">Sekolah Analis Kimia ITB</h5>
                            <span class="text-sm font-bold text-teal-primary block mb-2">16 September 1938</span>
                            <p class="text-text-muted leading-relaxed text-justify text-sm">
                                SMKN 13 Bandung berdiri pada 16 September 1938 dengan nama Sekolah Analis Kimia ITB yang dipelopori oleh Prof. C. O. Schaeffer di bawah Departemen Kimia ITB. Pada masa awal, sekolah ini berfokus pada pendidikan dan pelatihan analis kimia untuk memenuhi kebutuhan industri dan penelitian.
                            </p>
                        </div>

                        <div class="relative z-10">
                            <div class="absolute -left-12 mt-1 w-8 h-8 bg-navy-mid rounded-full border-4 border-bg-page flex items-center justify-center text-white shadow">
                                <i class="fa-solid fa-building-columns text-xs"></i>
                            </div>
                            <h5 class="text-xl font-bold text-navy-dark">Pengalihan Pengelolaan & SMT Kimia</h5>
                            <span class="text-sm font-bold text-teal-primary block mb-2">1980 - 1988</span>
                            <p class="text-text-muted leading-relaxed text-justify text-sm">
                                Pada tahun 1980, pengelolaan sekolah dialihkan dari perguruan tinggi ke pemerintah sesuai kebijakan pendidikan nasional. Perubahan ini membuat sekolah berganti nama menjadi SMT Kimia pada 7 Maret 1988, berdasarkan kebijakan Direktorat Pendidikan Menengah Kejuruan di Jawa Barat.
                            </p>
                        </div>

                        <div class="relative z-10">
                            <div class="absolute -left-12 mt-1 w-8 h-8 bg-brick-red rounded-full border-4 border-bg-page flex items-center justify-center text-white shadow">
                                <i class="fa-solid fa-school text-xs"></i>
                            </div>
                            <h5 class="text-xl font-bold text-navy-dark">SMK Negeri 13 Bandung & Jurusan Baru</h5>
                            <span class="text-sm font-bold text-teal-primary block mb-2">1997 - 2007</span>
                            <p class="text-text-muted leading-relaxed text-justify text-sm">
                                Selanjutnya, melalui SK Mendikbud RI Nomor 036/0/1997, sekolah ini resmi menjadi SMK Negeri 13 Bandung dengan program keahlian utama Analisis Kimia. Seiring perkembangan teknologi, pada tahun 2007 dibuka dua konsentrasi keahlian baru yaitu Teknik Komputer dan Jaringan (TKJ) serta Rekayasa Perangkat Lunak (RPL).
                            </p>
                        </div>

                        <div class="relative z-10">
                            <div class="absolute -left-12 mt-1 w-8 h-8 bg-teal-light rounded-full border-4 border-bg-page flex items-center justify-center text-white shadow">
                                <i class="fa-solid fa-award text-xs"></i>
                            </div>
                            <h5 class="text-xl font-bold text-navy-dark">SMKN 13 Bandung Saat Ini</h5>
                            <span class="text-sm font-bold text-teal-primary block mb-2">Sekarang</span>
                            <p class="text-text-muted leading-relaxed text-justify text-sm">
                                Kini, SMKN 13 Bandung menjadi salah satu SMK Pusat Keunggulan di Kota Bandung dengan akreditasi A, menerapkan Kurikulum Merdeka, dan memiliki tiga kompetensi keahlian unggulan. Berlokasi strategis di Jl. Soekarno-Hatta Km. 10, Bandung.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 lg:sticky lg:top-28 self-start min-w-0" id="sejGallery">
                    <figure class="m-0">
                        <div class="relative aspect-[4/3] rounded-3xl overflow-hidden shadow-lg border border-teal-tint bg-teal-tint">
                            <img id="sejImgA" src="{{ asset('Assets/sejarah1.jpg.jpeg') }}" alt="Dokumentasi sejarah SMKN 13 Bandung 1"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-100">
                            <img id="sejImgB" alt="Dokumentasi sejarah"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 opacity-0">
                            <button id="sejPrev" type="button" aria-label="Foto sebelumnya" onclick="prevSejFoto()"
                                class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-navy-dark shadow flex items-center justify-center transition">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button id="sejNext" type="button" aria-label="Foto berikutnya" onclick="nextSejFoto()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-navy-dark shadow flex items-center justify-center transition">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                            <figcaption class="absolute inset-x-0 bottom-0 z-[5] p-4 sm:p-5 bg-gradient-to-t from-navy-dark/95 to-transparent text-white flex justify-between items-end gap-3">
                                <span class="font-semibold text-sm sm:text-base">Dokumentasi Sejarah SMKN 13 Bandung</span>
                                <span id="sejCounter" class="text-sm text-teal-accent font-bold whitespace-nowrap">1 / 6</span>
                            </figcaption>
                        </div>
                    </figure>
                    <div id="sejThumbs" class="mt-4 flex gap-3 overflow-x-auto snap-x pb-2">
                        @for($i = 1; $i <= 6; $i++)
                            <img src="{{ asset('Assets/sejarah' . $i . '.jpg.jpeg') }}"
                                onclick="setSejFoto({{ $i - 1 }})"
                                alt="Thumb {{ $i }}"
                                class="sej-thumb w-20 h-14 object-cover rounded-xl cursor-pointer border-2 border-transparent flex-shrink-0 {{ $i === 1 ? 'active' : '' }}"
                                id="sejThumb{{ $i - 1 }}">
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div id="struktur" class="scroll-mt-32">

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div
                class="p-6 sm:p-8 bg-slate-50 border-b border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold text-emerald-700 tracking-wider uppercase">Bagan Resmi
                        Kepemimpinan</span>
                    <h3 class="text-2xl font-black text-slate-900 mt-0.5">Struktur Organisasi Sekolah</h3>
                    <p class="text-xs text-slate-500 mt-1">Tahun Pelajaran
                        {{ $struktur['tahun_pelajaran'] ?? '2025 - 2026' }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="setTampilanStruktur('bagan')" id="btnTampilanBagan"
                        class="px-4 py-2 bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>Bagan Diagram</span>
                    </button>
                    <button type="button" onclick="setTampilanStruktur('kartu')" id="btnTampilanKartu"
                        class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span>Daftar Divisi</span>
                    </button>
                    @if(!empty($pengaturan->gambar_struktur))
                        <button type="button" onclick="setTampilanStruktur('poster')" id="btnTampilanPoster"
                            class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-image"></i>
                            <span>Poster Asli</span>
                        </button>
                    @endif
                </div>
            </div>

            <div id="kontainerBaganDiagram" class="p-4 sm:p-8 overflow-x-auto bg-slate-100/50">
                <div
                    class="min-w-[1100px] max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200 space-y-8">
                    <div class="flex items-center justify-between border-b-2 border-slate-800 pb-6 px-4">
                        <div
                            class="w-20 h-20 rounded-full border-2 border-emerald-600 p-1 flex items-center justify-center bg-white shadow-sm flex-shrink-0">
                            <div
                                class="w-full h-full rounded-full bg-emerald-800 text-white flex flex-col items-center justify-center text-center p-1">
                                <span class="text-[8px] font-black tracking-widest leading-none">JABAR</span>
                                <i class="fa-solid fa-shield text-xs my-0.5 text-yellow-300"></i>
                                <span class="text-[6px] uppercase leading-none font-bold">Gemah Ripah</span>
                            </div>
                        </div>
                        <div class="text-center px-4">
                            <h2 class="text-2xl font-black uppercase tracking-tight text-slate-900">Struktur Organisasi</h2>
                            <h3 class="text-base font-extrabold uppercase tracking-wide text-slate-800 mt-0.5">Sekolah
                                Menengah Kejuruan Negeri 13 Kota Bandung</h3>
                            <p class="text-sm font-black text-amber-700 uppercase tracking-widest mt-1">Tahun Pelajaran
                                {{ $struktur['tahun_pelajaran'] ?? '2025 – 2026' }}</p>
                        </div>
                        <div
                            class="w-20 h-20 rounded-full border-2 border-amber-600 p-1 flex items-center justify-center bg-white shadow-sm flex-shrink-0">
                            <div
                                class="w-full h-full rounded-full bg-slate-900 text-white flex flex-col items-center justify-center text-center p-1">
                                <span class="text-[8px] font-black tracking-widest leading-none text-emerald-400">SMKN
                                    13</span>
                                <span class="text-sm font-black text-yellow-400">13</span>
                                <span class="text-[6px] uppercase leading-none font-bold text-slate-300">Bandung</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2">
                        <div class="relative flex items-center justify-center gap-6 sm:gap-10">
                            <div
                                class="absolute top-1/2 left-[12%] right-[12%] -translate-y-1/2 border-t-2 border-dashed border-indigo-400 z-0">
                            </div>

                            <div
                                class="relative z-10 w-64 p-3 rounded-xl border-2 border-dashed border-indigo-400 bg-amber-50/90 flex items-center space-x-3 shadow-sm hover:shadow-md transition">
                                @if(!empty($struktur['foto_komite_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_komite_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_komite_sekolah']) }}"
                                        alt="{{ $struktur['komite_sekolah'] }}"
                                        class="w-11 h-11 rounded-full object-cover ring-2 ring-indigo-500 shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-11 h-11 rounded-full bg-indigo-700 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 ring-2 ring-white">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[9px] font-black uppercase tracking-wider text-indigo-900 block">Komite
                                        Sekolah</span>
                                    <span
                                        class="text-xs font-bold text-slate-900 block truncate">{{ $struktur['komite_sekolah'] ?? '-' }}</span>
                                </div>
                            </div>

                            <div
                                class="relative z-10 w-80 p-3.5 rounded-2xl border-2 border-amber-500 bg-gradient-to-r from-emerald-800 to-emerald-950 text-white flex items-center space-x-3.5 shadow-xl hover:scale-[1.02] transition">
                                @if(!empty($struktur['foto_kepala_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_kepala_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kepala_sekolah']) }}"
                                        alt="{{ $struktur['kepala_sekolah'] }}"
                                        class="w-14 h-14 rounded-full object-cover ring-4 ring-amber-400 shadow-md flex-shrink-0">
                                @else
                                    <div
                                        class="w-14 h-14 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center text-lg font-black flex-shrink-0 ring-4 ring-emerald-500/50 shadow">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-wider text-amber-300 block">Kepala
                                        Sekolah</span>
                                    <span
                                        class="text-sm font-black text-white block truncate">{{ $struktur['kepala_sekolah'] ?? '-' }}</span>
                                </div>
                            </div>

                            <div
                                class="relative z-10 w-64 p-3 rounded-xl border-2 border-dashed border-indigo-400 bg-amber-50/90 flex items-center space-x-3 shadow-sm hover:shadow-md transition">
                                @if(!empty($struktur['foto_pendamping_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_pendamping_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_pendamping_sekolah']) }}"
                                        alt="{{ $struktur['pendamping_sekolah'] }}"
                                        class="w-11 h-11 rounded-full object-cover ring-2 ring-indigo-500 shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-11 h-11 rounded-full bg-indigo-700 text-white flex items-center justify-center text-sm font-bold flex-shrink-0 ring-2 ring-white">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[9px] font-black uppercase tracking-wider text-indigo-900 block">Pendamping
                                        Sekolah</span>
                                    <span
                                        class="text-xs font-bold text-slate-900 block truncate">{{ $struktur['pendamping_sekolah'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative w-full">
                        <div class="h-6 w-1 bg-amber-500 mx-auto"></div>
                        <div class="relative h-6">
                            <div class="grid grid-cols-5 gap-3 h-full">
                                <div class="relative h-full">
                                    <div class="absolute right-[-12px] top-0 left-1/2 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-12px] right-[-12px] top-0 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-12px] right-[-12px] top-0 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-12px] right-[-12px] top-0 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-12px] top-0 right-1/2 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                            </div>
                            <div class="absolute inset-x-[10%] top-[-8px] border-t-2 border-dashed border-indigo-400"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-5 gap-3 items-start relative">
                        <div class="space-y-2">
                            <div
                                class="p-2.5 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_kurikulum']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_kurikulum'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_kurikulum']) }}"
                                        alt="{{ $struktur['wakasek_kurikulum'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[8.5px] font-black uppercase tracking-wider block text-amber-950">Wakasek
                                        Kurikulum</span>
                                    <span
                                        class="text-[11px] font-black block truncate">{{ $struktur['wakasek_kurikulum'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="w-0.5 h-3 bg-amber-500 mx-auto"></div>
                            <div class="space-y-1.5 border-t-2 border-amber-300 pt-2">
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">1</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Koordinator TEFA
                                            & BLUD</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['koor_tefa'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">2</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Sekretaris
                                            BLUD</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['sekretaris_blud'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">3</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Bendahara
                                            BLUD</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['bendahara_blud'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">4</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf Perencanaan
                                            Kurikulum</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_kurikulum'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">5</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf PBM &
                                            Evaluasi</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_pbm'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-100 text-amber-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">6</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf Adm.
                                            Akademik</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_adm_akademik'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-2 rounded-lg border border-amber-300 bg-amber-50/70 shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-200 text-amber-950 text-[9px] font-bold flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-book-bookmark text-[8px]"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-amber-800 block">Kepala
                                            Perpustakaan</span>
                                        <span
                                            class="text-[10px] font-black text-slate-900 block truncate">{{ $struktur['kepala_perpus'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div
                                class="p-2.5 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_kesiswaan']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_kesiswaan'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_kesiswaan']) }}"
                                        alt="{{ $struktur['wakasek_kesiswaan'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-sky-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[8.5px] font-black uppercase tracking-wider block text-amber-950">Wakasek
                                        Kesiswaan</span>
                                    <span
                                        class="text-[11px] font-black block truncate">{{ $struktur['wakasek_kesiswaan'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="w-0.5 h-3 bg-amber-500 mx-auto"></div>
                            <div class="space-y-1.5 border-t-2 border-sky-300 pt-2">
                                <div
                                    class="p-2 rounded-lg border border-sky-200 bg-sky-50/70 shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-700 text-white text-[9px] font-bold flex items-center justify-center flex-shrink-0">BK</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-sky-800 block">Koordinator
                                            BK</span>
                                        <span
                                            class="text-[10px] font-black text-slate-900 block truncate">{{ $struktur['koor_bk'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-100 text-sky-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">1</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Pembiasaan Akad
                                            & Non</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_pembiasaan'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-100 text-sky-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">2</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Disiplin &
                                            Tatib</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_tatib'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-100 text-sky-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">3</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Pembina OSIS &
                                            MPK</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_osis'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-100 text-sky-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">4</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf
                                            Ekstrakurikuler</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_ekskul'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-sky-100 text-sky-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">5</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Pembinaan
                                            Karakter</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_karakter'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div
                                class="p-2.5 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_sarpras']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_sarpras'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_sarpras']) }}"
                                        alt="{{ $struktur['wakasek_sarpras'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[8.5px] font-black uppercase tracking-wider block text-amber-950">Wakasek
                                        Sarpras</span>
                                    <span
                                        class="text-[11px] font-black block truncate">{{ $struktur['wakasek_sarpras'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="w-0.5 h-3 bg-amber-500 mx-auto"></div>
                            <div class="space-y-1.5 border-t-2 border-emerald-300 pt-2">
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">1</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Gedung &
                                            Bangunan</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_gedung'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">2</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Lab Kimia</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_lab_kimia'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">3</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Lab TEI &
                                            RPL</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_lab_rpl'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">4</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Lingkungan
                                            Hidup</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['koor_lh'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-2 rounded-lg border border-amber-300 bg-amber-50/70 shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-amber-400 text-slate-950 text-[9px] font-black flex items-center justify-center flex-shrink-0">KP</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-amber-800 block">Koordinator
                                            KOPMYNTA</span>
                                        <span
                                            class="text-[10px] font-black text-slate-900 block truncate">{{ $struktur['koor_kopmynta'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-slate-100 text-slate-600 text-[9px] font-bold flex items-center justify-center flex-shrink-0">•</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Sekretaris
                                            KOPMYNTA</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['sekretaris_kopmynta'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-slate-100 text-slate-600 text-[9px] font-bold flex items-center justify-center flex-shrink-0">•</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Bendahara
                                            KOPMYNTA</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['bendahara_kopmynta'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div
                                class="p-2.5 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_hubinmas']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_hubinmas'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_hubinmas']) }}"
                                        alt="{{ $struktur['wakasek_hubinmas'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-purple-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-handshake"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[8.5px] font-black uppercase tracking-wider block text-amber-950">Wakasek
                                        Hubinmas</span>
                                    <span
                                        class="text-[11px] font-black block truncate">{{ $struktur['wakasek_hubinmas'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="w-0.5 h-3 bg-amber-500 mx-auto"></div>
                            <div class="space-y-1.5 border-t-2 border-purple-300 pt-2">
                                <div
                                    class="p-2 rounded-lg border border-purple-200 bg-purple-50/70 shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-800 text-white text-[9px] font-bold flex items-center justify-center flex-shrink-0">BKK</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-purple-800 block">Koordinator
                                            BKK</span>
                                        <span
                                            class="text-[10px] font-black text-slate-900 block truncate">{{ $struktur['koor_bkk'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-100 text-purple-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">1</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf BKK</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_bkk'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-100 text-purple-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">2</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf Hubin
                                            Humas</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_hubin_1'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-100 text-purple-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">3</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Staf Hubin
                                            Humas</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_hubin_2'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-100 text-purple-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">4</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Kemitraan &
                                            Magang</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['staf_magang'] ?? '-' }}</span>
                                    </div>
                                </div>
                                <div
                                    class="p-1.5 rounded-lg border border-slate-200 bg-white shadow-xs flex items-center space-x-2">
                                    <span
                                        class="w-5 h-5 rounded-full bg-purple-100 text-purple-900 text-[9px] font-bold flex items-center justify-center flex-shrink-0">5</span>
                                    <div class="min-w-0">
                                        <span class="text-[7.5px] uppercase font-bold text-slate-400 block">Koordinator
                                            SPW</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-900 block truncate">{{ $struktur['koor_spw'] ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div
                                class="p-2.5 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center space-x-2">
                                @if(!empty($struktur['foto_koor_tu']) && file_exists(public_path('storage/' . $struktur['foto_koor_tu'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_koor_tu']) }}"
                                        alt="{{ $struktur['koor_tu'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-folder"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="text-[8.5px] font-black uppercase tracking-wider block text-amber-950">Koordinator
                                        TU</span>
                                    <span
                                        class="text-[11px] font-black block truncate">{{ $struktur['koor_tu'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="w-0.5 h-3 bg-amber-500 mx-auto"></div>
                            <div class="space-y-1.5 border-t-2 border-slate-300 pt-2">
                                <div class="p-3 rounded-xl border border-slate-200 bg-white shadow-xs text-center">
                                    <span class="text-[8px] uppercase font-bold text-slate-400 block">Staf Pendukung</span>
                                    <span
                                        class="text-xs font-bold text-slate-900 block mt-0.5">{{ $struktur['tenaga_adm'] ?? 'Tenaga Administrasi' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2">
                        <div class="h-8 w-1 bg-amber-500 mx-auto"></div>
                        <div class="max-w-md mx-auto space-y-2">
                            <div
                                class="p-3 rounded-xl border-2 border-amber-500 bg-amber-400 text-slate-900 shadow-md flex items-center justify-center space-x-3.5">
                                @if(!empty($struktur['foto_wakil_mutu']) && file_exists(public_path('storage/' . $struktur['foto_wakil_mutu'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakil_mutu']) }}"
                                        alt="{{ $struktur['wakil_mutu'] }}"
                                        class="w-11 h-11 rounded-full object-cover ring-2 ring-white shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center text-sm font-bold ring-2 ring-white flex-shrink-0">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                @endif
                                <div class="text-left">
                                    <span class="text-[9px] font-black uppercase tracking-wider block text-amber-950">Wakil
                                        Manajemen Mutu & SDM</span>
                                    <span class="text-xs font-black block">{{ $struktur['wakil_mutu'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="p-2 rounded-lg border border-slate-200 bg-white shadow-xs text-center">
                                    <span class="text-[7.5px] uppercase font-bold text-slate-400 block leading-none">Aset
                                        Digital</span>
                                    <span
                                        class="text-[9.5px] font-bold text-slate-800 block truncate mt-1">{{ $struktur['pj_aset_digital'] ?? '-' }}</span>
                                </div>
                                <div class="p-2 rounded-lg border border-slate-200 bg-white shadow-xs text-center">
                                    <span class="text-[7.5px] uppercase font-bold text-slate-400 block leading-none">Audit
                                        Mutu</span>
                                    <span
                                        class="text-[9.5px] font-bold text-slate-800 block truncate mt-1">{{ $struktur['staf_mutu'] ?? '-' }}</span>
                                </div>
                                <div class="p-2 rounded-lg border border-slate-200 bg-white shadow-xs text-center">
                                    <span class="text-[7.5px] uppercase font-bold text-slate-400 block leading-none">Staf
                                        SDM</span>
                                    <span
                                        class="text-[9.5px] font-bold text-slate-800 block truncate mt-1">{{ $struktur['staf_sdm'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2">
                        <div class="h-6 w-1 bg-amber-500 mx-auto"></div>
                        <div class="relative max-w-3xl mx-auto h-6">
                            <div class="grid grid-cols-3 gap-4 h-full">
                                <div class="relative h-full">
                                    <div class="absolute right-[-16px] top-0 left-1/2 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-16px] right-[-16px] top-0 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                                <div class="relative h-full">
                                    <div class="absolute left-[-16px] top-0 right-1/2 h-1 bg-amber-500"></div>
                                    <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-3xl mx-auto">
                            <div
                                class="p-2.5 rounded-2xl border-2 border-indigo-700 bg-white text-slate-900 shadow-sm flex items-center space-x-2.5">
                                @if(!empty($struktur['foto_kaprog_kimia']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_kimia'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_kimia']) }}"
                                        alt="{{ $struktur['kaprog_kimia'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-400 shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-emerald-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-amber-400 flex-shrink-0">
                                        <i class="fa-solid fa-flask"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-300 border border-amber-500/40 rounded-lg px-2 py-0.5 inline-block text-[8px] font-black uppercase tracking-wider text-slate-950">Kaprog
                                        Kimia Analisis</span>
                                    <span
                                        class="text-xs font-black block truncate text-slate-900 mt-0.5">{{ $struktur['kaprog_kimia'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div
                                class="p-2.5 rounded-2xl border-2 border-indigo-700 bg-white text-slate-900 shadow-sm flex items-center space-x-2.5">
                                @if(!empty($struktur['foto_kaprog_tjkt']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_tjkt'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_tjkt']) }}"
                                        alt="{{ $struktur['kaprog_tjkt'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-400 shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-sky-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-amber-400 flex-shrink-0">
                                        <i class="fa-solid fa-network-wired"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-300 border border-amber-500/40 rounded-lg px-2 py-0.5 inline-block text-[8px] font-black uppercase tracking-wider text-slate-950">Kaprog
                                        TJKT</span>
                                    <span
                                        class="text-xs font-black block truncate text-slate-900 mt-0.5">{{ $struktur['kaprog_tjkt'] ?? '-' }}</span>
                                </div>
                            </div>
                            <div
                                class="p-2.5 rounded-2xl border-2 border-indigo-700 bg-white text-slate-900 shadow-sm flex items-center space-x-2.5">
                                @if(!empty($struktur['foto_kaprog_pplg']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_pplg'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_pplg']) }}"
                                        alt="{{ $struktur['kaprog_pplg'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-amber-400 shadow flex-shrink-0">
                                @else
                                    <div
                                        class="w-9 h-9 rounded-full bg-purple-800 text-white flex items-center justify-center text-xs font-bold ring-2 ring-amber-400 flex-shrink-0">
                                        <i class="fa-solid fa-code"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <span
                                        class="bg-gradient-to-r from-amber-300 via-yellow-400 to-amber-300 border border-amber-500/40 rounded-lg px-2 py-0.5 inline-block text-[8px] font-black uppercase tracking-wider text-slate-950">Kaprog
                                        PPLG</span>
                                    <span
                                        class="text-xs font-black block truncate text-slate-900 mt-0.5">{{ $struktur['kaprog_pplg'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-3">
                        <div class="h-8 w-1 bg-amber-500 mx-auto"></div>

                        <div class="max-w-xl mx-auto flex items-center justify-between relative px-2 sm:px-4">
                            <div class="relative w-36 sm:w-48 flex-shrink-0">
                                <div class="bg-white border-2 border-indigo-700 rounded-2xl p-1.5 shadow-sm text-center">
                                    <div
                                        class="bg-gradient-to-b from-amber-300 via-yellow-400 to-amber-400 border border-amber-500/60 rounded-xl py-2 px-3 shadow-xs">
                                        <span
                                            class="text-xs sm:text-sm font-black text-slate-950 uppercase tracking-wider block">GURU</span>
                                    </div>
                                    <div class="h-3 sm:h-4"></div>
                                </div>
                                <div
                                    class="absolute -right-1.5 top-6 w-3 h-3 rounded-full border-2 border-indigo-700 bg-white z-10">
                                </div>
                                <div
                                    class="absolute -right-1.5 top-10 w-3 h-3 rounded-full border-2 border-amber-500 bg-white z-10">
                                </div>
                            </div>

                            <div class="flex-1 relative h-16 mx-1 sm:mx-2">
                                <div class="absolute inset-x-0 top-[29px] border-t-2 border-dashed border-indigo-700"></div>
                                <div class="absolute inset-x-0 top-[45px] border-t-2 border-amber-500"></div>
                                <div class="absolute top-0 bottom-0 left-1/2 -translate-x-1/2 w-1 bg-amber-500"></div>
                                <div
                                    class="absolute left-1/2 -translate-x-1/2 top-[42px] w-2.5 h-2.5 rounded-full bg-amber-500 z-10">
                                </div>
                            </div>

                            <div class="relative w-36 sm:w-48 flex-shrink-0">
                                <div class="bg-white border-2 border-indigo-700 rounded-2xl p-1.5 shadow-sm text-center">
                                    <div
                                        class="bg-gradient-to-b from-amber-300 via-yellow-400 to-amber-400 border border-amber-500/60 rounded-xl py-2 px-3 shadow-xs">
                                        <span
                                            class="text-xs sm:text-sm font-black text-slate-950 uppercase tracking-wider block">WALI
                                            KELAS</span>
                                    </div>
                                    <div class="h-3 sm:h-4"></div>
                                </div>
                                <div
                                    class="absolute -left-1.5 top-6 w-3 h-3 rounded-full border-2 border-indigo-700 bg-white z-10">
                                </div>
                                <div
                                    class="absolute -left-1.5 top-10 w-3 h-3 rounded-full border-2 border-amber-500 bg-white z-10">
                                </div>
                            </div>
                        </div>

                        <div class="w-1 h-8 bg-amber-500 mx-auto"></div>

                        <div class="relative w-36 sm:w-48 mx-auto">
                            <div
                                class="w-3 h-3 rounded-full border-2 border-amber-500 bg-white mx-auto -mb-1.5 relative z-10">
                            </div>
                            <div class="bg-white border-2 border-indigo-700 rounded-2xl p-1.5 shadow-sm text-center">
                                <div
                                    class="bg-gradient-to-b from-amber-300 via-yellow-400 to-amber-400 border border-amber-500/60 rounded-xl py-2 px-3 shadow-xs">
                                    <span
                                        class="text-xs sm:text-sm font-black text-slate-950 uppercase tracking-wider block">SISWA</span>
                                </div>
                                <div class="h-3 sm:h-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="kontainerKartuRuntun" class="p-6 sm:p-8 hidden space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800 block">Puncak
                            Pimpinan</span>
                        <div class="space-y-2">
                            <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Kepala Sekolah</span>
                                <span
                                    class="text-sm font-black text-slate-900 block">{{ $struktur['kepala_sekolah'] ?? '-' }}</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Komite Sekolah</span>
                                <span
                                    class="text-xs font-bold text-slate-900 block">{{ $struktur['komite_sekolah'] ?? '-' }}</span>
                            </div>
                            <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Pendamping Sekolah</span>
                                <span
                                    class="text-xs font-bold text-slate-900 block">{{ $struktur['pendamping_sekolah'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-amber-50 rounded-2xl border border-amber-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-amber-800 block">Bidang
                            Kurikulum</span>
                        <div class="space-y-2">
                            <div class="p-3 bg-white rounded-xl border border-amber-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Wakasek Kurikulum</span>
                                <span
                                    class="text-sm font-black text-slate-900 block">{{ $struktur['wakasek_kurikulum'] ?? '-' }}</span>
                            </div>
                            <div
                                class="p-2.5 bg-white rounded-xl border border-amber-100 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">TEFA & BLUD:</strong>
                                    {{ $struktur['koor_tefa'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Perpustakaan:</strong>
                                    {{ $struktur['kepala_perpus'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Kurikulum:</strong>
                                    {{ $struktur['staf_kurikulum'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">PBM & Evaluasi:</strong>
                                    {{ $struktur['staf_pbm'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-sky-50 rounded-2xl border border-sky-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-sky-800 block">Bidang Kesiswaan</span>
                        <div class="space-y-2">
                            <div class="p-3 bg-white rounded-xl border border-sky-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Wakasek Kesiswaan</span>
                                <span
                                    class="text-sm font-black text-slate-900 block">{{ $struktur['wakasek_kesiswaan'] ?? '-' }}</span>
                            </div>
                            <div
                                class="p-2.5 bg-white rounded-xl border border-sky-100 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">Koord. BK:</strong> {{ $struktur['koor_bk'] ?? '-' }}
                                </div>
                                <div><strong class="text-slate-900">Tatib & Disiplin:</strong>
                                    {{ $struktur['staf_tatib'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">OSIS & MPK:</strong> {{ $struktur['staf_osis'] ?? '-' }}
                                </div>
                                <div><strong class="text-slate-900">Ekstrakurikuler:</strong>
                                    {{ $struktur['staf_ekskul'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-emerald-50 rounded-2xl border border-emerald-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800 block">Sarana &
                            Prasarana</span>
                        <div class="space-y-2">
                            <div class="p-3 bg-white rounded-xl border border-emerald-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Wakasek Sarpras</span>
                                <span
                                    class="text-sm font-black text-slate-900 block">{{ $struktur['wakasek_sarpras'] ?? '-' }}</span>
                            </div>
                            <div
                                class="p-2.5 bg-white rounded-xl border border-emerald-100 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">Gedung:</strong> {{ $struktur['staf_gedung'] ?? '-' }}
                                </div>
                                <div><strong class="text-slate-900">Lab Kimia:</strong>
                                    {{ $struktur['staf_lab_kimia'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Lab RPL/TEI:</strong>
                                    {{ $struktur['staf_lab_rpl'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">KOPMYNTA:</strong>
                                    {{ $struktur['koor_kopmynta'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-purple-50 rounded-2xl border border-purple-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-purple-800 block">Hubungan Industri
                            (Hubinmas)</span>
                        <div class="space-y-2">
                            <div class="p-3 bg-white rounded-xl border border-purple-100 shadow-xs">
                                <span class="text-[10px] uppercase font-bold text-slate-400 block">Wakasek Hubinmas</span>
                                <span
                                    class="text-sm font-black text-slate-900 block">{{ $struktur['wakasek_hubinmas'] ?? '-' }}</span>
                            </div>
                            <div
                                class="p-2.5 bg-white rounded-xl border border-purple-100 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">BKK:</strong> {{ $struktur['koor_bkk'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Humas:</strong> {{ $struktur['staf_hubin_1'] ?? '-' }}
                                </div>
                                <div><strong class="text-slate-900">Kemitraan & Magang:</strong>
                                    {{ $struktur['staf_magang'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">SPW:</strong> {{ $struktur['koor_spw'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-800 block">Program Keahlian &
                            Mutu</span>
                        <div class="space-y-2">
                            <div
                                class="p-2.5 bg-white rounded-xl border border-slate-200 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">Manajemen Mutu:</strong>
                                    {{ $struktur['wakil_mutu'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Tata Usaha:</strong> {{ $struktur['koor_tu'] ?? '-' }}
                                </div>
                                <div><strong class="text-slate-900">Kaprog Kimia:</strong>
                                    {{ $struktur['kaprog_kimia'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Kaprog TJKT:</strong>
                                    {{ $struktur['kaprog_tjkt'] ?? '-' }}</div>
                                <div><strong class="text-slate-900">Kaprog PPLG:</strong>
                                    {{ $struktur['kaprog_pplg'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-indigo-50 rounded-2xl border border-indigo-200 space-y-3">
                        <span class="text-xs font-black uppercase tracking-wider text-indigo-900 block">Pelaksana & Peserta
                            Didik</span>
                        <div class="space-y-2">
                            <div
                                class="p-2.5 bg-white rounded-xl border border-indigo-100 shadow-xs text-xs space-y-1 text-slate-700">
                                <div><strong class="text-slate-900">Guru:</strong> Tenaga Pendidik SMKN 13 Bandung</div>
                                <div><strong class="text-slate-900">Wali Kelas:</strong> Pembina & Pengelola Rombel</div>
                                <div><strong class="text-slate-900">Siswa:</strong> Peserta Didik SMKN 13 Bandung</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(!empty($pengaturan->gambar_struktur))
                <div id="kontainerPosterAsli" class="p-6 sm:p-8 hidden text-center bg-slate-50">
                    <img src="{{ asset('storage/' . $pengaturan->gambar_struktur) }}"
                        alt="Poster Struktur Organisasi SMKN 13 Bandung"
                        class="mx-auto rounded-xl max-h-[600px] shadow border border-slate-200">
                </div>
            @endif
        </div>
        </div>

        <div id="pengajar" class="scroll-mt-32 text-center w-full space-y-4">
            <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Guru & Tenaga Kependidikan</span>
            <h4 class="text-3xl sm:text-4xl font-black text-navy-dark mt-1 mb-2">Tenaga Pendidik & Staff Pengajar</h4>
            <p class="text-text-muted text-sm max-w-xl mx-auto">Guru dan tenaga kependidikan SMKN 13 Bandung berdedikasi tinggi membimbing generasi masa depan. Arahkan kursor ke kartu untuk menjeda animasi.</p>

            @if($daftarGuru->isEmpty())
                <div class="bg-bg-card p-10 rounded-3xl border border-teal-tint max-w-md mx-auto text-text-muted shadow-sm mt-6">
                    <i class="fa-solid fa-chalkboard-user text-3xl text-teal-primary/50 mb-2"></i>
                    <p class="font-medium">Belum ada data tenaga pendidik yang ditampilkan.</p>
                </div>
            @else
                @php
                    $guruBaris1 = $daftarGuru->filter(fn($g, $i) => $i % 2 === 0)->values();
                    $guruBaris2 = $daftarGuru->filter(fn($g, $i) => $i % 2 === 1)->values();
                    if ($guruBaris2->isEmpty()) {
                        $guruBaris2 = $guruBaris1;
                    }
                    $durasi1 = max(40, $guruBaris1->count() * 3);
                    $durasi2 = max(40, $guruBaris2->count() * 3);
                @endphp

                <div class="space-y-4 sm:space-y-6 mt-8">
                    <div class="marquee">
                        <div class="marquee-track" style="animation-duration: {{ $durasi1 }}s;">
                            <div class="marquee-group">
                                @foreach($guruBaris1 as $guru)
                                    @php
                                        $fotoGuru = null;
                                        if (!empty($guru->foto)) {
                                            if (\Illuminate\Support\Str::startsWith($guru->foto, ['http://', 'https://'])) {
                                                $fotoGuru = $guru->foto;
                                            } elseif (file_exists(public_path('storage/' . $guru->foto))) {
                                                $fotoGuru = asset('storage/' . $guru->foto);
                                            } elseif (file_exists(public_path('Assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('Assets/' . basename($guru->foto));
                                            } elseif (file_exists(public_path('assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('assets/' . basename($guru->foto));
                                            }
                                        }
                                    @endphp
                                    <div class="w-56 sm:w-60 flex-shrink-0 bg-bg-card p-6 rounded-3xl border border-teal-tint text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-300 group cursor-pointer shadow-xs">
                                        <div class="w-28 h-28 mx-auto rounded-full mb-4 border-4 border-white shadow-md relative overflow-hidden bg-teal-primary text-white font-black flex items-center justify-center flex-shrink-0">
                                            <span class="text-3xl select-none">{{ strtoupper(substr($guru->nama ?? 'G', 0, 1)) }}</span>
                                            @if($fotoGuru)
                                                <img src="{{ $fotoGuru }}" alt="{{ $guru->nama }}" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-500">
                                            @endif
                                        </div>
                                        <h5 class="font-bold text-navy-dark text-base group-hover:text-teal-primary transition-colors line-clamp-1">{{ $guru->nama }}</h5>
                                        <p class="text-xs text-text-muted font-bold mt-2 bg-teal-tint py-1 px-3 rounded-full inline-block truncate max-w-[180px] border border-teal-light/20">
                                            {{ !empty($guru->daftar_badge_mapel) ? $guru->daftar_badge_mapel[0]['nama'] : ($guru->mapel?->nama_mapel ?? 'Tenaga Pendidik') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="marquee-group">
                                @foreach($guruBaris1 as $guru)
                                    @php
                                        $fotoGuru = null;
                                        if (!empty($guru->foto)) {
                                            if (\Illuminate\Support\Str::startsWith($guru->foto, ['http://', 'https://'])) {
                                                $fotoGuru = $guru->foto;
                                            } elseif (file_exists(public_path('storage/' . $guru->foto))) {
                                                $fotoGuru = asset('storage/' . $guru->foto);
                                            } elseif (file_exists(public_path('Assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('Assets/' . basename($guru->foto));
                                            } elseif (file_exists(public_path('assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('assets/' . basename($guru->foto));
                                            }
                                        }
                                    @endphp
                                    <div class="w-56 sm:w-60 flex-shrink-0 bg-bg-card p-6 rounded-3xl border border-teal-tint text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-300 group cursor-pointer shadow-xs">
                                        <div class="w-28 h-28 mx-auto rounded-full mb-4 border-4 border-white shadow-md relative overflow-hidden bg-teal-primary text-white font-black flex items-center justify-center flex-shrink-0">
                                            <span class="text-3xl select-none">{{ strtoupper(substr($guru->nama ?? 'G', 0, 1)) }}</span>
                                            @if($fotoGuru)
                                                <img src="{{ $fotoGuru }}" alt="{{ $guru->nama }}" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-500">
                                            @endif
                                        </div>
                                        <h5 class="font-bold text-navy-dark text-base group-hover:text-teal-primary transition-colors line-clamp-1">{{ $guru->nama }}</h5>
                                        <p class="text-xs text-text-muted font-bold mt-2 bg-teal-tint py-1 px-3 rounded-full inline-block truncate max-w-[180px] border border-teal-light/20">
                                            {{ !empty($guru->daftar_badge_mapel) ? $guru->daftar_badge_mapel[0]['nama'] : ($guru->mapel?->nama_mapel ?? 'Tenaga Pendidik') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="marquee">
                        <div class="marquee-track-reverse" style="animation-duration: {{ $durasi2 }}s;">
                            <div class="marquee-group">
                                @foreach($guruBaris2 as $guru)
                                    @php
                                        $fotoGuru = null;
                                        if (!empty($guru->foto)) {
                                            if (\Illuminate\Support\Str::startsWith($guru->foto, ['http://', 'https://'])) {
                                                $fotoGuru = $guru->foto;
                                            } elseif (file_exists(public_path('storage/' . $guru->foto))) {
                                                $fotoGuru = asset('storage/' . $guru->foto);
                                            } elseif (file_exists(public_path('Assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('Assets/' . basename($guru->foto));
                                            } elseif (file_exists(public_path('assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('assets/' . basename($guru->foto));
                                            }
                                        }
                                    @endphp
                                    <div class="w-56 sm:w-60 flex-shrink-0 bg-bg-card p-6 rounded-3xl border border-teal-tint text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-300 group cursor-pointer shadow-xs">
                                        <div class="w-28 h-28 mx-auto rounded-full mb-4 border-4 border-white shadow-md relative overflow-hidden bg-teal-primary text-white font-black flex items-center justify-center flex-shrink-0">
                                            <span class="text-3xl select-none">{{ strtoupper(substr($guru->nama ?? 'G', 0, 1)) }}</span>
                                            @if($fotoGuru)
                                                <img src="{{ $fotoGuru }}" alt="{{ $guru->nama }}" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-500">
                                            @endif
                                        </div>
                                        <h5 class="font-bold text-navy-dark text-base group-hover:text-teal-primary transition-colors line-clamp-1">{{ $guru->nama }}</h5>
                                        <p class="text-xs text-text-muted font-bold mt-2 bg-teal-tint py-1 px-3 rounded-full inline-block truncate max-w-[180px] border border-teal-light/20">
                                            {{ !empty($guru->daftar_badge_mapel) ? $guru->daftar_badge_mapel[0]['nama'] : ($guru->mapel?->nama_mapel ?? 'Tenaga Pendidik') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="marquee-group">
                                @foreach($guruBaris2 as $guru)
                                    @php
                                        $fotoGuru = null;
                                        if (!empty($guru->foto)) {
                                            if (\Illuminate\Support\Str::startsWith($guru->foto, ['http://', 'https://'])) {
                                                $fotoGuru = $guru->foto;
                                            } elseif (file_exists(public_path('storage/' . $guru->foto))) {
                                                $fotoGuru = asset('storage/' . $guru->foto);
                                            } elseif (file_exists(public_path('Assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('Assets/' . basename($guru->foto));
                                            } elseif (file_exists(public_path('assets/' . basename($guru->foto)))) {
                                                $fotoGuru = asset('assets/' . basename($guru->foto));
                                            }
                                        }
                                    @endphp
                                    <div class="w-56 sm:w-60 flex-shrink-0 bg-bg-card p-6 rounded-3xl border border-teal-tint text-center hover:-translate-y-2 hover:shadow-xl transition-all duration-300 group cursor-pointer shadow-xs">
                                        <div class="w-28 h-28 mx-auto rounded-full mb-4 border-4 border-white shadow-md relative overflow-hidden bg-teal-primary text-white font-black flex items-center justify-center flex-shrink-0">
                                            <span class="text-3xl select-none">{{ strtoupper(substr($guru->nama ?? 'G', 0, 1)) }}</span>
                                            @if($fotoGuru)
                                                <img src="{{ $fotoGuru }}" alt="{{ $guru->nama }}" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-110 transition-transform duration-500">
                                            @endif
                                        </div>
                                        <h5 class="font-bold text-navy-dark text-base group-hover:text-teal-primary transition-colors line-clamp-1">{{ $guru->nama }}</h5>
                                        <p class="text-xs text-text-muted font-bold mt-2 bg-teal-tint py-1 px-3 rounded-full inline-block truncate max-w-[180px] border border-teal-light/20">
                                            {{ !empty($guru->daftar_badge_mapel) ? $guru->daftar_badge_mapel[0]['nama'] : ($guru->mapel?->nama_mapel ?? 'Tenaga Pendidik') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function setTampilanStruktur(mode) {
            const cBagan = document.getElementById('kontainerBaganDiagram');
            const cKartu = document.getElementById('kontainerKartuRuntun');
            const cPoster = document.getElementById('kontainerPosterAsli');

            const btnBagan = document.getElementById('btnTampilanBagan');
            const btnKartu = document.getElementById('btnTampilanKartu');
            const btnPoster = document.getElementById('btnTampilanPoster');

            if (cBagan) cBagan.classList.add('hidden');
            if (cKartu) cKartu.classList.add('hidden');
            if (cPoster) cPoster.classList.add('hidden');

            if (btnBagan) btnBagan.className = 'px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5';
            if (btnKartu) btnKartu.className = 'px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5';
            if (btnPoster) btnPoster.className = 'px-4 py-2 bg-white text-slate-700 hover:bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5';

            if (mode === 'bagan' && cBagan && btnBagan) {
                cBagan.classList.remove('hidden');
                btnBagan.className = 'px-4 py-2 bg-teal-primary text-white rounded-xl text-xs font-bold shadow transition flex items-center space-x-1.5';
            } else if (mode === 'kartu' && cKartu && btnKartu) {
                cKartu.classList.remove('hidden');
                btnKartu.className = 'px-4 py-2 bg-teal-primary text-white rounded-xl text-xs font-bold shadow transition flex items-center space-x-1.5';
            } else if (mode === 'poster' && cPoster && btnPoster) {
                cPoster.classList.remove('hidden');
                btnPoster.className = 'px-4 py-2 bg-teal-primary text-white rounded-xl text-xs font-bold shadow transition flex items-center space-x-1.5';
            }
        }

        const sejImages = [
            "{{ asset('Assets/sejarah1.jpg.jpeg') }}",
            "{{ asset('Assets/sejarah2.jpg.jpeg') }}",
            "{{ asset('Assets/sejarah3.jpg.jpeg') }}",
            "{{ asset('Assets/sejarah4.jpg.jpeg') }}",
            "{{ asset('Assets/sejarah5.jpg.jpeg') }}",
            "{{ asset('Assets/sejarah6.jpg.jpeg') }}"
        ];
        let currentSejIdx = 0;
        let isImgAActive = true;

        function setSejFoto(idx) {
            if (idx < 0) idx = sejImages.length - 1;
            if (idx >= sejImages.length) idx = 0;
            currentSejIdx = idx;

            const imgA = document.getElementById('sejImgA');
            const imgB = document.getElementById('sejImgB');
            const counter = document.getElementById('sejCounter');

            if (isImgAActive) {
                imgB.src = sejImages[idx];
                imgB.classList.remove('opacity-0');
                imgB.classList.add('opacity-100');
                imgA.classList.remove('opacity-100');
                imgA.classList.add('opacity-0');
            } else {
                imgA.src = sejImages[idx];
                imgA.classList.remove('opacity-0');
                imgA.classList.add('opacity-100');
                imgB.classList.remove('opacity-100');
                imgB.classList.add('opacity-0');
            }
            isImgAActive = !isImgAActive;

            if (counter) counter.innerText = (idx + 1) + ' / ' + sejImages.length;

            document.querySelectorAll('.sej-thumb').forEach((thumb, i) => {
                if (i === idx) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }

        function nextSejFoto() {
            setSejFoto(currentSejIdx + 1);
        }

        function prevSejFoto() {
            setSejFoto(currentSejIdx - 1);
        }
    </script>
@endsection
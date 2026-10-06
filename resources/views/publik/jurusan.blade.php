@extends('layouts.publik', ['title' => 'Konsentrasi Keahlian - SMKN 13 Bandung'])

@section('content')
<div class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12 w-full flex-grow">
    <!-- Header Halaman -->
    <div class="text-center max-w-3xl mx-auto">
        <span class="text-xs font-black uppercase tracking-widest text-teal-primary">Program Pendidikan Vokasi</span>
        <h1 class="text-4xl sm:text-5xl font-black text-navy-dark mt-1 mb-4">Konsentrasi Keahlian</h1>
        <p class="text-base sm:text-lg text-text-muted">Informasi mendalam seputar kurikulum, materi pembelajaran, sarana laboratorium, dan pimpinan program studi.</p>
    </div>

    <!-- Accordion Jurusan List -->
    <div class="space-y-6" id="jurusanAccordionList">

        <!-- 1. KIMIA ANALISIS -->
        <div class="bg-bg-card rounded-3xl shadow-sm border border-teal-tint overflow-hidden">
            <button class="accordion-btn w-full px-6 sm:px-8 py-6 flex justify-between items-center text-left hover:bg-bg-page transition expanded"
                onclick="toggleAccordion('j_apl')">
                <div class="flex items-center space-x-5">
                    <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-vial"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-primary">Program Keahlian 4 Tahun</span>
                        <h3 class="text-2xl font-black text-navy-dark">Kimia Analisis</h3>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down accordion-icon text-teal-primary text-xl"></i>
            </button>

            <div id="acc_j_apl" class="accordion-content px-4 sm:px-10 bg-white border-t border-teal-tint expanded">
                <div class="relative md:min-h-[580px]">
                    <div class="md:w-[55%] md:h-[280px] pt-4 pb-6 md:pb-0">
                        <span class="text-xs font-bold text-teal-primary uppercase tracking-widest">Pusat Keunggulan Kimia</span>
                        <h4 class="text-3xl lg:text-4xl font-black text-navy-dark leading-tight mt-1">Kimia Analisis</h4>
                        <p class="text-lg font-bold text-teal-light mt-3">Analisis, Pengujian & Kontrol Mutu</p>
                    </div>

                    <div class="md:absolute md:top-0 md:right-4 md:bottom-12 md:w-[38%] bg-navy-mid rounded-3xl overflow-hidden shadow-xl h-80 md:h-auto relative z-10 mb-6 md:mb-0 border border-teal-tint">
                        <img src="{{ asset('Assets/kaprog ka.png') }}" alt="Dra. Hj. Sri Wahyuni, M.Si."
                            class="w-full h-full object-cover object-top">
                        <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-navy-dark/95 to-transparent text-white text-center">
                            <span class="text-xs text-teal-accent font-bold block uppercase tracking-wider">Kepala Konsentrasi Keahlian</span>
                            <strong class="text-sm">Dra. Hj. Sri Wahyuni, M.Si.</strong>
                        </div>
                    </div>

                    <div class="bg-navy-dark text-white rounded-3xl p-8 md:p-10 md:pr-[44%] md:min-h-[300px] flex items-center shadow-md">
                        <p class="text-justify leading-relaxed text-teal-tint text-sm sm:text-base">
                            Mencetak pakar analisis kimia konvensional dan instrumen untuk standar kontrol mutu industri dan laboratorium modern. Siswa dilatih melakukan pengujian, identifikasi, dan pengukuran kandungan zat pada bahan pangan, farmasi, lingkungan, dan manufaktur.
                        </p>
                    </div>
                </div>

                <div class="mt-10 bg-bg-page border border-teal-tint rounded-3xl p-6 sm:p-8">
                    <h5 class="text-2xl font-black text-navy-dark mb-3">Apa itu Kimia Analisis?</h5>
                    <p class="leading-relaxed text-text-muted text-sm sm:text-base text-justify whitespace-pre-line">
                        Kimia Analisis di SMK Negeri 13 Bandung adalah program keahlian berstandar nasional dan industri yang berfokus pada keterampilan pengujian laboratorium kimia dasar hingga instrumen modern seperti Spektrofotometri UV-Vis, AAS, dan Kromatografi (GC/HPLC).

                        Lulusan Kimia Analisis memiliki peluang karir yang sangat luas di industri petrokimia, farmasi, kosmetik, pangan (Quality Control/Quality Assurance), laboratorium lingkungan hidup, balai penelitian pemerintah, maupun melanjutkan studi ke jenjang perguruan tinggi negeri teknik kimia dan farmasi.
                    </p>
                </div>

                <div class="mt-10">
                    <h5 class="text-2xl font-black text-navy-dark">Materi Pokok yang Dipelajari</h5>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">1</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Metode Analisis Kualitatif & Kuantitatif</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Identifikasi kation/anion serta penentuan konsentrasi zat melalui titrimetri dan gravimetri.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">2</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Analisis Instrumen Kimia Modern</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Pengoperasian alat spektrofotometri, kromatografi gas, refractometer, dan pH meter digital.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">3</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Keselamatan Kerja & Pengelolaan Limbah</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Penerapan standar K3 laboratorium, penanganan MSDS, dan netralisasi limbah kimia ramah lingkungan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TEKNIK KOMPUTER JARINGAN (TKJ) -->
        <div class="bg-bg-card rounded-3xl shadow-sm border border-teal-tint overflow-hidden">
            <button class="accordion-btn w-full px-6 sm:px-8 py-6 flex justify-between items-center text-left hover:bg-bg-page transition"
                onclick="toggleAccordion('j_tkj')">
                <div class="flex items-center space-x-5">
                    <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-primary">Teknologi Jaringan & Telekomunikasi</span>
                        <h3 class="text-2xl font-black text-navy-dark">Teknik Komputer Jaringan (TKJ)</h3>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down accordion-icon text-teal-primary text-xl"></i>
            </button>

            <div id="acc_j_tkj" class="accordion-content px-4 sm:px-10 bg-white border-t border-teal-tint">
                <div class="relative md:min-h-[580px]">
                    <div class="md:w-[55%] md:h-[280px] pt-4 pb-6 md:pb-0">
                        <span class="text-xs font-bold text-teal-primary uppercase tracking-widest">Infrastruktur & Cloud</span>
                        <h4 class="text-3xl lg:text-4xl font-black text-navy-dark leading-tight mt-1">Teknik Komputer Jaringan</h4>
                        <p class="text-lg font-bold text-teal-light mt-3">Komputer, Jaringan Fiber Optic & Keamanan Siber</p>
                    </div>

                    <div class="md:absolute md:top-0 md:right-4 md:bottom-12 md:w-[38%] bg-navy-mid rounded-3xl overflow-hidden shadow-xl h-80 md:h-auto relative z-10 mb-6 md:mb-0 border border-teal-tint">
                        <img src="{{ asset('Assets/kaprog tkj.png') }}" alt="Hendra Setiawan, S.T., M.Kom."
                            class="w-full h-full object-cover object-top">
                        <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-navy-dark/95 to-transparent text-white text-center">
                            <span class="text-xs text-teal-accent font-bold block uppercase tracking-wider">Kepala Konsentrasi Keahlian</span>
                            <strong class="text-sm">Hendra Setiawan, S.T., M.Kom.</strong>
                        </div>
                    </div>

                    <div class="bg-navy-dark text-white rounded-3xl p-8 md:p-10 md:pr-[44%] md:min-h-[300px] flex items-center shadow-md">
                        <p class="text-justify leading-relaxed text-teal-tint text-sm sm:text-base">
                            Keahlian merancang, membangun, mengonfigurasi dan mengelola infrastruktur jaringan enterprise hingga keamanan jaringan komputer. Siswa dibekali kompetensi routing MikroTik, Cisco, switching, sistem server Linux, dan transmisi fiber optic berstandar industri.
                        </p>
                    </div>
                </div>

                <div class="mt-10 bg-bg-page border border-teal-tint rounded-3xl p-6 sm:p-8">
                    <h5 class="text-2xl font-black text-navy-dark mb-3">Apa itu TKJ?</h5>
                    <p class="leading-relaxed text-text-muted text-sm sm:text-base text-justify whitespace-pre-line">
                        Teknik Komputer dan Jaringan membekali peserta didik dengan keahlian praktis merakit workstation, mengelola administrasi sistem server, instalasi jaringan kabel LAN/MAN/WAN, wireless outdoor, serta teknik penyambungan fiber optic (splicing & OTDR).

                        Peluang karir lulusan sangat dibutuhkan di berbagai sektor sebagai Network Administrator, IT Support Specialist, System Administrator, Cloud Engineer, hingga Teknisi Fiber Optic ISP ternama.
                    </p>
                </div>

                <div class="mt-10">
                    <h5 class="text-2xl font-black text-navy-dark">Subjek & Kompetensi Utama</h5>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">1</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Perakitan & Troubleshooting PC/Server</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Diagnosa hardware komputer, instalasi OS enterprise, optimasi kinerja dan manajemen penyimpanan.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">2</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Administrasi Jaringan & Routing</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Konfigurasi router MikroTik & Cisco, VLAN, Subnetting, QoS, Firewall, dan VPN inter-koneksi.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">3</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Sistem Operasi Server & Cloud</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Instalasi Web Server, DNS Server, Mail Server, Virtualisasi Proxmox/VMware, dan keamanan jaringan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. REKAYASA PERANGKAT LUNAK (RPL) -->
        <div class="bg-bg-card rounded-3xl shadow-sm border border-teal-tint overflow-hidden">
            <button class="accordion-btn w-full px-6 sm:px-8 py-6 flex justify-between items-center text-left hover:bg-bg-page transition"
                onclick="toggleAccordion('j_rpl')">
                <div class="flex items-center space-x-5">
                    <div class="w-14 h-14 bg-teal-tint text-teal-primary rounded-2xl flex items-center justify-center text-2xl flex-shrink-0 shadow-sm">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-primary">Software Engineering & Development</span>
                        <h3 class="text-2xl font-black text-navy-dark">Rekayasa Perangkat Lunak (RPL)</h3>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down accordion-icon text-teal-primary text-xl"></i>
            </button>

            <div id="acc_j_rpl" class="accordion-content px-4 sm:px-10 bg-white border-t border-teal-tint">
                <div class="relative md:min-h-[580px]">
                    <div class="md:w-[55%] md:h-[280px] pt-4 pb-6 md:pb-0">
                        <span class="text-xs font-bold text-teal-primary uppercase tracking-widest">Koding & Pengembangan Aplikasi</span>
                        <h4 class="text-3xl lg:text-4xl font-black text-navy-dark leading-tight mt-1">Rekayasa Perangkat Lunak</h4>
                        <p class="text-lg font-bold text-teal-light mt-3">Pemrograman Web, Mobile, Database & API</p>
                    </div>

                    <div class="md:absolute md:top-0 md:right-4 md:bottom-12 md:w-[38%] bg-navy-mid rounded-3xl overflow-hidden shadow-xl h-80 md:h-auto relative z-10 mb-6 md:mb-0 border border-teal-tint">
                        <img src="{{ asset('Assets/kaprog rpl.webp') }}" alt="Refky, M.Kom."
                            class="w-full h-full object-cover object-top">
                        <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-navy-dark/95 to-transparent text-white text-center">
                            <span class="text-xs text-teal-accent font-bold block uppercase tracking-wider">Kepala Konsentrasi Keahlian</span>
                            <strong class="text-sm">Refky, M.Kom.</strong>
                        </div>
                    </div>

                    <div class="bg-navy-dark text-white rounded-3xl p-8 md:p-10 md:pr-[44%] md:min-h-[300px] flex items-center shadow-md">
                        <p class="text-justify leading-relaxed text-teal-tint text-sm sm:text-base">
                            Pemrograman basis data, rekayasa perangkat lunak mobile, desktop dan integrasi sistem web terdistribusi. Dibuka sejak tahun 2015 untuk menjawab kebutuhan industri perangkat lunak dan startup teknologi dengan fokus pada keahlian koding mutakhir.
                        </p>
                    </div>
                </div>

                <div class="mt-10 bg-bg-page border border-teal-tint rounded-3xl p-6 sm:p-8">
                    <h5 class="text-2xl font-black text-navy-dark mb-3">Tujuan Pembelajaran & Ruang Lingkup</h5>
                    <p class="leading-relaxed text-text-muted text-sm sm:text-base text-justify whitespace-pre-line">
                        Rekayasa Perangkat Lunak SMKN 13 Bandung bertujuan membekali murid dengan hard skills pemrograman modern (HTML, CSS, JavaScript, PHP/Laravel, MySQL, Flutter/React Native) dan soft skills berupa manajemen proyek perangkat lunak, Agile/Scrum, kolaborasi Git/GitHub, serta problem-solving komputasional.

                        Lulusan siap berkarir sebagai Web Developer, Fullstack Developer, Mobile App Developer, UI/UX Designer, Database Specialist, atau melanjutkan ke universitas ternama bidang Ilmu Komputer dan Informatika.
                    </p>
                </div>

                <div class="mt-10">
                    <h5 class="text-2xl font-black text-navy-dark">Kurikulum & Materi Inti</h5>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-6">
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">1</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Basis Data & SQL</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Perancangan skema relasional, normalisasi basis data, indexing, dan manipulasi query SQL kompleks.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">2</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Pemrograman Web & Framework</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Frontend interaktif dan backend modern dengan Laravel, RESTful API, dan otentikasi aman.</p>
                            </div>
                        </div>
                        <div class="bg-bg-page border border-teal-tint rounded-2xl p-6 flex gap-4">
                            <span class="w-8 h-8 rounded-full bg-teal-primary text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">3</span>
                            <div>
                                <h6 class="font-bold text-navy-dark mb-1">Aplikasi Bergerak (Mobile Development)</h6>
                                <p class="text-xs text-text-muted leading-relaxed">Pembuatan aplikasi Android/iOS, konsumsi endpoint API, deployment dan integrasi cloud services.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function toggleAccordion(id) {
        const targetContent = document.getElementById('acc_' + id);
        const targetBtn = targetContent.previousElementSibling;
        const isOpen = targetContent.classList.contains('expanded');

        // Tutup semua accordion terlebih dahulu
        document.querySelectorAll('.accordion-content').forEach(c => c.classList.remove('expanded'));
        document.querySelectorAll('.accordion-btn').forEach(b => b.classList.remove('expanded'));

        // Jika sebelumnya tertutup, maka buka
        if (!isOpen) {
            targetContent.classList.add('expanded');
            targetBtn.classList.add('expanded');
        }
    }
</script>
@endsection

@extends('layouts.admin', ['title' => 'Profil & Sambutan - Admin SMKN 13 Bandung'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Profil, Sambutan & Struktur Organisasi</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola narasi beranda, visi misi, sejarah, sambutan pimpinan, dan struktur organisasi sekolah.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profil-sambutan.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-bullhorn text-emerald-700"></i>
                <span>Teks Beranda (Hero)</span>
            </h2>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Slogan Sekolah (Pill Badge)</label>
                <input type="text" name="slogan" value="{{ old('slogan', $pengaturan->slogan) }}" placeholder="Contoh: Unggul, Berkarakter & Berdaya Saing" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat (Paragraf Hero)</label>
                <textarea name="deskripsi_singkat" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('deskripsi_singkat', $pengaturan->deskripsi_singkat) }}</textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-compass text-emerald-700"></i>
                <span>Visi, Misi & Sejarah</span>
            </h2>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Visi Sekolah (Kalimat Utama)</label>
                <textarea name="visi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('visi', $pengaturan->visi) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Kalimat visi utama yang ditampilkan di kartu visi halaman profil.</p>
            </div>

            <!-- Pilar Penjabaran Visi (Dinamis: Tambah & Hapus) -->
            @php
                $availableIcons = [
                    'fa-award' => 'Piala / Prestasi (fa-award)',
                    'fa-hand-holding-heart' => 'Akhlak / Karakter (fa-hand-holding-heart)',
                    'fa-earth-asia' => 'Internasional / Global (fa-earth-asia)',
                    'fa-lightbulb' => 'Inovasi / Kreativitas (fa-lightbulb)',
                    'fa-shield-heart' => 'Integritas / Nilai (fa-shield-heart)',
                    'fa-laptop-code' => 'Teknologi / IT (fa-laptop-code)',
                    'fa-user-graduate' => 'Pendidikan / Lulusan (fa-user-graduate)',
                    'fa-star' => 'Bintang / Keunggulan (fa-star)',
                    'fa-compass' => 'Kompas / Visi (fa-compass)',
                    'fa-handshake' => 'Kerjasama / Kemitraan (fa-handshake)',
                    'fa-bolt' => 'Daya Saing / Kecepatan (fa-bolt)',
                    'fa-bullseye' => 'Target / Fokus (fa-bullseye)',
                ];

                $pilarData = old('pilar_visi');
                if ($pilarData === null) {
                    if (is_array($pengaturan->pilar_visi)) {
                        $pilarData = $pengaturan->pilar_visi;
                    } else {
                        $pilarData = \App\Models\PengaturanSekolah::defaultPilarVisi();
                    }
                }
                if (!is_array($pilarData)) {
                    $pilarData = [];
                }
            @endphp
            <div class="pt-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 pb-2 border-b border-slate-100">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">
                            Pilar Penjabaran Visi (Kartu di Bawah Visi)
                        </label>
                        <p class="text-xs text-slate-400 mt-0.5">Jumlah kartu fleksibel — Anda bebas menambah atau menghapus kartu pilar sesuai kebutuhan sekolah.</p>
                    </div>
                    <button type="button" onclick="tambahPilar()" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 font-bold text-xs transition self-start sm:self-auto shadow-sm cursor-pointer">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Pilar</span>
                    </button>
                </div>

                <input type="hidden" name="pilar_visi_submitted" value="1">

                <div id="pilarVisiContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($pilarData as $i => $curPilar)
                        @php
                            $selectedIcon = $curPilar['icon'] ?? 'fa-award';
                        @endphp
                        <div class="pilar-card relative p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3 transition-all hover:border-slate-300">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
                                <div class="flex items-center space-x-2">
                                    <span class="pilar-nomor-badge w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xs">{{ $loop->iteration }}</span>
                                    <span class="pilar-label text-xs font-bold text-emerald-800 uppercase tracking-wider">Pilar {{ $loop->iteration }}</span>
                                </div>
                                <button type="button" onclick="hapusPilar(this)" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 px-2.5 py-1 rounded-lg transition cursor-pointer" title="Hapus Pilar ini">
                                    <i class="fa-solid fa-trash-can text-rose-500 text-xs"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Pilar</label>
                                <input type="text" name="pilar_visi[{{ $i }}][judul]" 
                                    value="{{ $curPilar['judul'] ?? '' }}"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white"
                                    placeholder="Contoh: Berakhlak Mulia">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi Singkat</label>
                                <textarea name="pilar_visi[{{ $i }}][deskripsi]" rows="2"
                                    class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs bg-white"
                                    placeholder="Penjelasan pilar...">{{ $curPilar['deskripsi'] ?? '' }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Ikon Pilar</label>
                                <div class="flex items-center space-x-2">
                                    <div class="pilar-icon-preview w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm border border-emerald-100 flex-shrink-0">
                                        <i class="fa-solid {{ $selectedIcon }}"></i>
                                    </div>
                                    <select name="pilar_visi[{{ $i }}][icon]" onchange="updatePilarIconPreview(this)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs bg-white">
                                        @if(!array_key_exists($selectedIcon, $availableIcons))
                                            <option value="{{ $selectedIcon }}" selected>{{ $selectedIcon }}</option>
                                        @endif
                                        @foreach($availableIcons as $val => $lbl)
                                            <option value="{{ $val }}" {{ $selectedIcon === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="pilarEmptyState" class="{{ count($pilarData) === 0 ? '' : 'hidden' }} text-center py-8 px-4 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 text-slate-500 text-sm space-y-2 mt-2">
                    <div class="w-10 h-10 mx-auto rounded-full bg-slate-200 text-slate-400 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </div>
                    <p class="font-medium text-slate-600">Belum ada kartu pilar visi.</p>
                    <p class="text-xs text-slate-400">Klik tombol "Tambah Pilar" di atas untuk menambahkan kartu baru, atau simpan untuk menampilkan visi tanpa kartu sub-pilar.</p>
                </div>

                <p class="text-xs text-slate-400 mt-2">Kartu-kartu di atas akan tampil secara interaktif dan otomatis tertata rapi di dalam bingkai visi pada halaman Profil publik.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Misi Sekolah (Pisahkan Setiap Poin dengan Baris Baru)</label>
                <textarea name="misi" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono text-xs">{{ old('misi', $pengaturan->misi) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Satu baris akan ditampilkan sebagai satu nomor/poin misi di halaman profil.</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Sejarah Singkat Sekolah</label>
                <textarea name="sejarah" rows="5" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('sejarah', $pengaturan->sejarah) }}</textarea>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-3 border-b border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-sitemap text-emerald-700"></i>
                    <span>Bagan Struktur Organisasi Sekolah (Dinamis)</span>
                </h2>
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-slate-600">Tahun Pelajaran:</label>
                    <input type="text" name="struktur_organisasi[tahun_pelajaran]" value="{{ old('struktur_organisasi.tahun_pelajaran', $struktur['tahun_pelajaran'] ?? '2025 - 2026') }}" class="px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-bold w-36 text-center focus:border-emerald-600 focus:outline-none bg-slate-50">
                </div>
            </div>

            <p class="text-xs text-slate-500">
                Ubah nama pejabat di bawah ini untuk memperbarui bagan organisasi sekolah tanpa perlu mendesain atau mengunggah ulang gambar.
            </p>

            <div class="space-y-6">
                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-emerald-950 flex items-center space-x-1.5">
                        <i class="fa-solid fa-crown text-emerald-700"></i>
                        <span>1. Puncak Pimpinan & Pendamping</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kepala Sekolah</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_kepala_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_kepala_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kepala_sekolah']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[kepala_sekolah]" value="{{ old('struktur_organisasi.kepala_sekolah', $struktur['kepala_sekolah'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-emerald-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[kepala_sekolah]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Komite Sekolah</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_komite_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_komite_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_komite_sekolah']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[komite_sekolah]" value="{{ old('struktur_organisasi.komite_sekolah', $struktur['komite_sekolah'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[komite_sekolah]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pendamping Sekolah</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_pendamping_sekolah']) && file_exists(public_path('storage/' . $struktur['foto_pendamping_sekolah'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_pendamping_sekolah']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[pendamping_sekolah]" value="{{ old('struktur_organisasi.pendamping_sekolah', $struktur['pendamping_sekolah'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[pendamping_sekolah]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-100 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-amber-950 flex items-center space-x-1.5">
                        <i class="fa-solid fa-book-open text-amber-700"></i>
                        <span>2. Bidang Kurikulum & Pembelajaran</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Wakasek Bid. Kurikulum</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_kurikulum']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_kurikulum'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_kurikulum']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-amber-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-book"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[wakasek_kurikulum]" value="{{ old('struktur_organisasi.wakasek_kurikulum', $struktur['wakasek_kurikulum'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-amber-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[wakasek_kurikulum]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kepala Perpustakaan</label>
                            <input type="text" name="struktur_organisasi[kepala_perpus]" value="{{ old('struktur_organisasi.kepala_perpus', $struktur['kepala_perpus'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator TEFA & BLUD</label>
                            <input type="text" name="struktur_organisasi[koor_tefa]" value="{{ old('struktur_organisasi.koor_tefa', $struktur['koor_tefa'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Sekretaris BLUD</label>
                            <input type="text" name="struktur_organisasi[sekretaris_blud]" value="{{ old('struktur_organisasi.sekretaris_blud', $struktur['sekretaris_blud'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Bendahara BLUD</label>
                            <input type="text" name="struktur_organisasi[bendahara_blud]" value="{{ old('struktur_organisasi.bendahara_blud', $struktur['bendahara_blud'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Perencanaan Kurikulum</label>
                            <input type="text" name="struktur_organisasi[staf_kurikulum]" value="{{ old('struktur_organisasi.staf_kurikulum', $struktur['staf_kurikulum'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf PBM & Evaluasi</label>
                            <input type="text" name="struktur_organisasi[staf_pbm]" value="{{ old('struktur_organisasi.staf_pbm', $struktur['staf_pbm'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Administrasi Akademik</label>
                            <input type="text" name="struktur_organisasi[staf_adm_akademik]" value="{{ old('struktur_organisasi.staf_adm_akademik', $struktur['staf_adm_akademik'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-amber-600">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-sky-50/60 rounded-xl border border-sky-100 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-sky-950 flex items-center space-x-1.5">
                        <i class="fa-solid fa-user-group text-sky-700"></i>
                        <span>3. Bidang Kesiswaan & Kedisiplinan</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Wakasek Bid. Kesiswaan</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_kesiswaan']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_kesiswaan'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_kesiswaan']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-sky-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[wakasek_kesiswaan]" value="{{ old('struktur_organisasi.wakasek_kesiswaan', $struktur['wakasek_kesiswaan'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-sky-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[wakasek_kesiswaan]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-sky-100 file:text-sky-800 hover:file:bg-sky-200 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator Bimbingan & Konseling (BK)</label>
                            <input type="text" name="struktur_organisasi[koor_bk]" value="{{ old('struktur_organisasi.koor_bk', $struktur['koor_bk'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Pembiasaan Akademik & Non</label>
                            <input type="text" name="struktur_organisasi[staf_pembiasaan]" value="{{ old('struktur_organisasi.staf_pembiasaan', $struktur['staf_pembiasaan'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Penegakan Disiplin & Tatib</label>
                            <input type="text" name="struktur_organisasi[staf_tatib]" value="{{ old('struktur_organisasi.staf_tatib', $struktur['staf_tatib'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Pembina OSIS & MPK</label>
                            <input type="text" name="struktur_organisasi[staf_osis]" value="{{ old('struktur_organisasi.staf_osis', $struktur['staf_osis'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Ekstrakurikuler</label>
                            <input type="text" name="struktur_organisasi[staf_ekskul]" value="{{ old('struktur_organisasi.staf_ekskul', $struktur['staf_ekskul'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Karakter & Budi Pekerti</label>
                            <input type="text" name="struktur_organisasi[staf_karakter]" value="{{ old('struktur_organisasi.staf_karakter', $struktur['staf_karakter'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-sky-600">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-emerald-950 flex items-center space-x-1.5">
                        <i class="fa-solid fa-building text-emerald-700"></i>
                        <span>4. Bidang Sarana & Prasarana</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Wakasek Bid. Sarana & Prasarana</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_sarpras']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_sarpras'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_sarpras']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[wakasek_sarpras]" value="{{ old('struktur_organisasi.wakasek_sarpras', $struktur['wakasek_sarpras'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-emerald-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[wakasek_sarpras]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator Lingkungan Hidup</label>
                            <input type="text" name="struktur_organisasi[koor_lh]" value="{{ old('struktur_organisasi.koor_lh', $struktur['koor_lh'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Gedung & Bangunan</label>
                            <input type="text" name="struktur_organisasi[staf_gedung]" value="{{ old('struktur_organisasi.staf_gedung', $struktur['staf_gedung'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Lab Kimia</label>
                            <input type="text" name="struktur_organisasi[staf_lab_kimia]" value="{{ old('struktur_organisasi.staf_lab_kimia', $struktur['staf_lab_kimia'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Lab TEI & RPL</label>
                            <input type="text" name="struktur_organisasi[staf_lab_rpl]" value="{{ old('struktur_organisasi.staf_lab_rpl', $struktur['staf_lab_rpl'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator KOPMYNTA</label>
                            <input type="text" name="struktur_organisasi[koor_kopmynta]" value="{{ old('struktur_organisasi.koor_kopmynta', $struktur['koor_kopmynta'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Sekretaris KOPMYNTA</label>
                            <input type="text" name="struktur_organisasi[sekretaris_kopmynta]" value="{{ old('struktur_organisasi.sekretaris_kopmynta', $struktur['sekretaris_kopmynta'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Bendahara KOPMYNTA</label>
                            <input type="text" name="struktur_organisasi[bendahara_kopmynta]" value="{{ old('struktur_organisasi.bendahara_kopmynta', $struktur['bendahara_kopmynta'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-emerald-600">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-purple-50/60 rounded-xl border border-purple-100 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-purple-950 flex items-center space-x-1.5">
                        <i class="fa-solid fa-handshake text-purple-700"></i>
                        <span>5. Bidang Hubungan Industri & Masyarakat (Hubinmas)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Wakasek Bid. Hubinmas</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakasek_hubinmas']) && file_exists(public_path('storage/' . $struktur['foto_wakasek_hubinmas'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakasek_hubinmas']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-purple-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-handshake"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[wakasek_hubinmas]" value="{{ old('struktur_organisasi.wakasek_hubinmas', $struktur['wakasek_hubinmas'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-purple-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[wakasek_hubinmas]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator BKK</label>
                            <input type="text" name="struktur_organisasi[koor_bkk]" value="{{ old('struktur_organisasi.koor_bkk', $struktur['koor_bkk'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf BKK</label>
                            <input type="text" name="struktur_organisasi[staf_bkk]" value="{{ old('struktur_organisasi.staf_bkk', $struktur['staf_bkk'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Hubin Humas 1</label>
                            <input type="text" name="struktur_organisasi[staf_hubin_1]" value="{{ old('struktur_organisasi.staf_hubin_1', $struktur['staf_hubin_1'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Hubin Humas 2</label>
                            <input type="text" name="struktur_organisasi[staf_hubin_2]" value="{{ old('struktur_organisasi.staf_hubin_2', $struktur['staf_hubin_2'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Kemitraan & Magang</label>
                            <input type="text" name="struktur_organisasi[staf_magang]" value="{{ old('struktur_organisasi.staf_magang', $struktur['staf_magang'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator SPW</label>
                            <input type="text" name="struktur_organisasi[koor_spw]" value="{{ old('struktur_organisasi.koor_spw', $struktur['koor_spw'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-purple-600">
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                    <h3 class="font-bold text-xs uppercase tracking-wider text-slate-900 flex items-center space-x-1.5">
                        <i class="fa-solid fa-shield-halved text-slate-700"></i>
                        <span>6. Tata Usaha, Manajemen Mutu & Program Keahlian</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Koordinator Tata Usaha</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_koor_tu']) && file_exists(public_path('storage/' . $struktur['foto_koor_tu'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_koor_tu']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-slate-400 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-folder"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[koor_tu]" value="{{ old('struktur_organisasi.koor_tu', $struktur['koor_tu'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-slate-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[koor_tu]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-slate-200 file:text-slate-800 hover:file:bg-slate-300 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Tenaga Administrasi</label>
                            <input type="text" name="struktur_organisasi[tenaga_adm]" value="{{ old('struktur_organisasi.tenaga_adm', $struktur['tenaga_adm'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-slate-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Wakil Manajemen Mutu & SDM</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_wakil_mutu']) && file_exists(public_path('storage/' . $struktur['foto_wakil_mutu'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_wakil_mutu']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-amber-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-award"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[wakil_mutu]" value="{{ old('struktur_organisasi.wakil_mutu', $struktur['wakil_mutu'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-slate-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[wakil_mutu]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-amber-100 file:text-amber-800 hover:file:bg-amber-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">PJ Pengelola Aset Digital</label>
                            <input type="text" name="struktur_organisasi[pj_aset_digital]" value="{{ old('struktur_organisasi.pj_aset_digital', $struktur['pj_aset_digital'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-slate-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Audit Mutu Internal</label>
                            <input type="text" name="struktur_organisasi[staf_mutu]" value="{{ old('struktur_organisasi.staf_mutu', $struktur['staf_mutu'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-slate-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Staf Bidang SDM</label>
                            <input type="text" name="struktur_organisasi[staf_sdm]" value="{{ old('struktur_organisasi.staf_sdm', $struktur['staf_sdm'] ?? '') }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs focus:border-slate-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kepala Program Kimia Analisis</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_kaprog_kimia']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_kimia'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_kimia']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-flask"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[kaprog_kimia]" value="{{ old('struktur_organisasi.kaprog_kimia', $struktur['kaprog_kimia'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-slate-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[kaprog_kimia]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kepala Program TJKT</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_kaprog_tjkt']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_tjkt'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_tjkt']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-sky-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-network-wired"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[kaprog_tjkt]" value="{{ old('struktur_organisasi.kaprog_tjkt', $struktur['kaprog_tjkt'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-slate-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[kaprog_tjkt]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-sky-100 file:text-sky-800 hover:file:bg-sky-200 cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Kepala Program PPLG</label>
                            <div class="flex items-center space-x-2">
                                @if(!empty($struktur['foto_kaprog_pplg']) && file_exists(public_path('storage/' . $struktur['foto_kaprog_pplg'])))
                                    <img src="{{ asset('storage/' . $struktur['foto_kaprog_pplg']) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-purple-500 flex-shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fa-solid fa-code"></i>
                                    </div>
                                @endif
                                <input type="text" name="struktur_organisasi[kaprog_pplg]" value="{{ old('struktur_organisasi.kaprog_pplg', $struktur['kaprog_pplg'] ?? '') }}" class="flex-1 px-3 py-2 rounded-lg border border-slate-200 outline-none text-xs font-bold focus:border-slate-600">
                            </div>
                            <div class="mt-1.5">
                                <label class="text-[10px] text-slate-500 font-medium block">Foto Wajah (Opsional):</label>
                                <input type="file" name="foto_struktur[kaprog_pplg]" accept="image/*" class="text-[10px] text-slate-500 file:mr-2 file:py-0.5 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:bg-purple-100 file:text-purple-800 hover:file:bg-purple-200 cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Opsi Cadangan: Unggah Gambar Bagan (Opsional)</label>
                <div class="flex items-center space-x-6 mt-2">
                    @if($pengaturan->gambar_struktur)
                        <div class="w-24 h-24 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-50 flex items-center justify-center p-1">
                            <img src="{{ asset('storage/' . $pengaturan->gambar_struktur) }}" alt="Struktur Organisasi" class="max-h-full max-w-full object-contain">
                        </div>
                    @endif
                    <div class="flex-grow">
                        <input type="file" name="gambar_struktur" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-[11px] text-slate-400 mt-1">Jika diunggah, gambar bagan ini dapat digunakan sebagai alternatif tampilan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-user-tie text-emerald-700"></i>
                <span>Sambutan Kepala Sekolah</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar Kepala Sekolah</label>
                    <input type="text" name="nama_kepsek" value="{{ old('nama_kepsek', $pengaturan->nama_kepsek) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Sambutan</label>
                    <input type="text" name="judul_sambutan" value="{{ old('judul_sambutan', $pengaturan->judul_sambutan) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Teks Sambutan</label>
                <textarea name="sambutan" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('sambutan', $pengaturan->sambutan) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Resmi Kepala Sekolah</label>
                <div class="flex items-center space-x-6 mt-2">
                    @if($pengaturan->foto_kepsek)
                        <div class="w-20 h-24 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-50 flex items-center justify-center p-1">
                            <img src="{{ asset('storage/' . $pengaturan->foto_kepsek) }}" alt="Foto Kepala Sekolah" class="max-h-full max-w-full object-cover">
                        </div>
                    @else
                        <div class="w-20 h-24 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0 border border-slate-200">
                            <i class="fa-solid fa-user text-3xl"></i>
                        </div>
                    @endif
                    <div class="flex-grow">
                        <input type="file" name="foto_kepsek" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-8 py-3 rounded-xl shadow-lg transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    renumberPilar();
});

const PILAR_ICON_OPTIONS = [
    { value: 'fa-award', label: 'Piala / Prestasi' },
    { value: 'fa-hand-holding-heart', label: 'Akhlak / Karakter' },
    { value: 'fa-earth-asia', label: 'Internasional / Global' },
    { value: 'fa-lightbulb', label: 'Inovasi / Kreativitas' },
    { value: 'fa-shield-heart', label: 'Integritas / Nilai' },
    { value: 'fa-laptop-code', label: 'Teknologi / IT' },
    { value: 'fa-user-graduate', label: 'Pendidikan / Lulusan' },
    { value: 'fa-star', label: 'Bintang / Keunggulan' },
    { value: 'fa-compass', label: 'Kompas / Visi' },
    { value: 'fa-handshake', label: 'Kerjasama / Kemitraan' },
    { value: 'fa-bolt', label: 'Daya Saing / Kecepatan' },
    { value: 'fa-bullseye', label: 'Target / Fokus' }
];

function tambahPilar() {
    const container = document.getElementById('pilarVisiContainer');
    const emptyState = document.getElementById('pilarEmptyState');
    if (!container) return;

    const uniqueId = 'new_' + Date.now() + '_' + Math.floor(Math.random() * 1000);

    let iconOptionsHtml = '';
    PILAR_ICON_OPTIONS.forEach((item, idx) => {
        const selected = idx === 0 ? 'selected' : '';
        iconOptionsHtml += `<option value="${item.value}" ${selected}>${item.label} (${item.value})</option>`;
    });

    const card = document.createElement('div');
    card.className = 'pilar-card relative p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3 transition-all hover:border-slate-300';
    card.innerHTML = `
        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
            <div class="flex items-center space-x-2">
                <span class="pilar-nomor-badge w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xs">?</span>
                <span class="pilar-label text-xs font-bold text-emerald-800 uppercase tracking-wider">Pilar ?</span>
            </div>
            <button type="button" onclick="hapusPilar(this)" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-white hover:bg-rose-50 border border-slate-200 hover:border-rose-200 px-2.5 py-1 rounded-lg transition cursor-pointer" title="Hapus Pilar ini">
                <i class="fa-solid fa-trash-can text-rose-500 text-xs"></i>
                <span>Hapus</span>
            </button>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Judul Pilar</label>
            <input type="text" name="pilar_visi[${uniqueId}][judul]" 
                value=""
                class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white"
                placeholder="Contoh: Berakhlak Mulia">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Deskripsi Singkat</label>
            <textarea name="pilar_visi[${uniqueId}][deskripsi]" rows="2"
                class="w-full px-3 py-2 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs bg-white"
                placeholder="Penjelasan pilar..."></textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-600 mb-1">Ikon Pilar</label>
            <div class="flex items-center space-x-2">
                <div class="pilar-icon-preview w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm border border-emerald-100 flex-shrink-0">
                    <i class="fa-solid ${PILAR_ICON_OPTIONS[0].value}"></i>
                </div>
                <select name="pilar_visi[${uniqueId}][icon]" onchange="updatePilarIconPreview(this)" class="w-full px-3 py-1.5 rounded-lg border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs bg-white">
                    ${iconOptionsHtml}
                </select>
            </div>
        </div>
    `;

    container.appendChild(card);
    if (emptyState) emptyState.classList.add('hidden');
    renumberPilar();

    // Auto-focus the new title input
    const inputTitle = card.querySelector('input[type="text"]');
    if (inputTitle) inputTitle.focus();
}

function hapusPilar(buttonEl) {
    const card = buttonEl.closest('.pilar-card');
    if (card) {
        card.remove();
        renumberPilar();
    }
}

function renumberPilar() {
    const cards = document.querySelectorAll('.pilar-card');
    const emptyState = document.getElementById('pilarEmptyState');
    cards.forEach((card, index) => {
        const num = index + 1;
        const badge = card.querySelector('.pilar-nomor-badge');
        const label = card.querySelector('.pilar-label');
        if (badge) badge.textContent = num;
        if (label) label.textContent = 'Pilar ' + num;
    });

    if (emptyState) {
        if (cards.length === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
}

function updatePilarIconPreview(selectEl) {
    const previewContainer = selectEl.closest('.flex')?.querySelector('.pilar-icon-preview i');
    if (previewContainer) {
        previewContainer.className = 'fa-solid ' + selectEl.value;
    }
}
</script>
@endsection

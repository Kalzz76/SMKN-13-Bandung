@extends('layouts.publik', ['title' => 'Kontak & Lokasi - SMKN 13 Bandung'])

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8 w-full flex-grow">
    <div class="text-center max-w-2xl mx-auto">
        <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">Hubungi Kami</span>
        <h1 class="text-3xl font-extrabold text-slate-900 mt-1">Informasi Kontak & Lokasi</h1>
        <p class="text-slate-500 text-sm mt-2">Silakan hubungi layanan informasi sekolah kami pada jam operasional kerja.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-6">
            @if(!empty($pengaturan->npsn))
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900">Nomor Pokok Sekolah Nasional (NPSN)</h3>
                        <p class="text-sm text-slate-600 mt-1 font-semibold">
                            {{ $pengaturan->npsn }}
                        </p>
                    </div>
                </div>
            @endif

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Alamat Kampus</h3>
                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                        {{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta KM. 10, Kelurahan Jatisari, Kecamatan Buahbatu, Kota Bandung, Jawa Barat, Kode Pos 40286' }}
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Email Resmi</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $pengaturan->email ?? 'smk13bdg@gmail.com / info@smkn13bdg.sch.id' }}
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Telepon / Fax</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $pengaturan->telepon ?? '(022) 7318960' }}
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Website Resmi</h3>
                    <div class="text-sm text-emerald-700 mt-1 space-y-1">
                        <div><a href="https://smkn13bdg.sch.id" target="_blank" rel="noopener noreferrer" class="hover:underline">https://smkn13bdg.sch.id</a></div>
                        <div><a href="https://smkn13bandung.sch.id" target="_blank" rel="noopener noreferrer" class="hover:underline">https://smkn13bandung.sch.id</a></div>
                    </div>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Media Sosial Resmi</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $pengaturan->social_media ?? 'Instagram: @smkn13bdg / @smkn13bandung | YouTube: SMKN 13 Bandung Official' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-slate-100 rounded-2xl p-8 flex flex-col justify-center text-center space-y-4">
            <div class="w-14 h-14 bg-emerald-800 text-white rounded-2xl flex items-center justify-center text-2xl mx-auto shadow">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3 class="text-emerald-900 font-bold text-xl">Jam Operasional Layanan</h3>
                <div class="mt-4 space-y-3 text-sm text-slate-700 text-left">
                    <div class="bg-white p-3.5 rounded-xl shadow-sm border border-slate-200">
                        <span class="font-bold text-slate-900 block">Senin – Jumat</span>
                        <span class="text-emerald-700 font-semibold">07.00 – 16.00 WIB</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-xl shadow-sm border border-slate-200">
                        <span class="font-bold text-slate-900 block">Sabtu – Minggu & Hari Libur Nasional</span>
                        <span class="text-rose-600 font-semibold">Tutup</span>
                    </div>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-200 text-xs text-slate-500">
                Pelayanan tata usaha dan administrasi sekolah siap melayani Anda sesuai jadwal di atas.
            </div>
        </div>
    </div>
</div>
@endsection

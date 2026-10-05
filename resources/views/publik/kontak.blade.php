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
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Alamat Kampus</h3>
                    <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                        {{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta Km. 10 Gedebage, Kota Bandung, Jawa Barat 40286' }}
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
                        {{ $pengaturan->email ?? 'info@smkn13bandung.sch.id' }}
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Nomor Telepon</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $pengaturan->telepon ?? '(022) 7801234 / 7805678' }}
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                    <i class="fa-solid fa-hashtag"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900">Media Sosial</h3>
                    <p class="text-sm text-slate-600 mt-1">
                        {{ $pengaturan->social_media ?? '@smkn13bandung' }}
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
                <p class="text-sm text-slate-600 mt-2 font-medium">
                    {{ $pengaturan->jam_operasional ?? 'Senin - Jumat: 07.00 - 16.00 WIB' }}
                </p>
                <p class="text-xs text-slate-500 mt-1">Sabtu - Minggu: Libur</p>
            </div>
            <div class="pt-4 border-t border-slate-200 text-xs text-slate-500">
                Pelayanan tata usaha dan administrasi sekolah siap melayani Anda sesuai jadwal di atas.
            </div>
        </div>
    </div>
</div>
@endsection

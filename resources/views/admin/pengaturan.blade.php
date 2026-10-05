@extends('layouts.admin', ['title' => 'K. Pengaturan Sekolah - Admin SMKN 13 Bandung'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">K. Pengaturan Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola identitas resmi sekolah, kontak, dan parameter lokasi absensi guru.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pengaturan.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-school text-emerald-700"></i>
                <span>Identitas & Informasi Resmi</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Instansi / Sekolah</label>
                    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $pengaturan->nama_sekolah) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NPSN</label>
                    <input type="text" name="npsn" value="{{ old('npsn', $pengaturan->npsn) }}" placeholder="Contoh: 20219154" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat Resmi Kampus</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('alamat', $pengaturan->alamat) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $pengaturan->email) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $pengaturan->telepon) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Social Media (Instagram / YouTube)</label>
                    <input type="text" name="social_media" value="{{ old('social_media', $pengaturan->social_media) }}" placeholder="Contoh: @smkn13bandung" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Operasional Layanan</label>
                    <input type="text" name="jam_operasional" value="{{ old('jam_operasional', $pengaturan->jam_operasional) }}" placeholder="Senin - Jumat: 07.00 - 16.00 WIB" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Logo Sekolah</label>
                <div class="flex items-center space-x-6 mt-2">
                    @if($pengaturan->logo)
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-50 flex items-center justify-center p-1">
                            <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div class="w-16 h-16 rounded-xl bg-emerald-700 text-white font-black text-xl flex items-center justify-center flex-shrink-0 shadow">
                            13
                        </div>
                    @endif
                    <div class="flex-grow">
                        <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-location-dot text-emerald-700"></i>
                <span>Pengaturan Lokasi Absensi & Jam Masuk</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Latitude Sekolah</label>
                    <input type="number" step="any" name="lat_sekolah" value="{{ old('lat_sekolah', $pengaturan->lat_sekolah) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <p class="text-xs text-slate-400 mt-1">Koordinat garis lintang pusat sekolah.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Longitude Sekolah</label>
                    <input type="number" step="any" name="long_sekolah" value="{{ old('long_sekolah', $pengaturan->long_sekolah) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <p class="text-xs text-slate-400 mt-1">Koordinat garis bujur pusat sekolah.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Radius Toleransi Absensi (Meter)</label>
                    <input type="number" name="radius_meter" value="{{ old('radius_meter', $pengaturan->radius_meter) }}" min="10" max="5000" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <p class="text-xs text-slate-400 mt-1">Jarak maksimum scan barcode dari lokasi sekolah (misal: 100 meter).</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Masuk (Batas Hadir Tepat Waktu)</label>
                    <input type="time" name="jam_masuk" value="{{ old('jam_masuk', \Illuminate\Support\Str::substr($pengaturan->jam_masuk, 0, 5)) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <p class="text-xs text-slate-400 mt-1">Scan lewat jam ini otomatis berstatus Terlambat.</p>
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
@endsection

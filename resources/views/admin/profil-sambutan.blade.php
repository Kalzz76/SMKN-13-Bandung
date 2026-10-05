@extends('layouts.admin', ['title' => 'L. Profil & Sambutan - Admin SMKN 13 Bandung'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900">L. Profil & Sambutan Pimpinan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola konten narasi beranda, visi misi, sejarah, dan sambutan kepala sekolah.</p>
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">Visi Sekolah</label>
                <textarea name="visi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">{{ old('visi', $pengaturan->visi) }}</textarea>
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

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Bagan Struktur Organisasi Sekolah</label>
                <div class="flex items-center space-x-6 mt-2">
                    @if($pengaturan->gambar_struktur)
                        <div class="w-24 h-24 rounded-xl overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-50 flex items-center justify-center p-1">
                            <img src="{{ asset('storage/' . $pengaturan->gambar_struktur) }}" alt="Struktur Organisasi" class="max-h-full max-w-full object-contain">
                        </div>
                    @endif
                    <div class="flex-grow">
                        <input type="file" name="gambar_struktur" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
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
@endsection

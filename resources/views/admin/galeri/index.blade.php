@extends('layouts.admin', ['title' => 'Manajemen Galeri - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Galeri & Fasilitas</h1>
            <p class="text-sm text-slate-500 mt-1">Dokumentasi sarana prasarana sekolah dan galeri aktivitas belajar siswa.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-camera"></i>
            <span>Tambah Foto</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <a href="{{ route('admin.galeri.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('kategori') ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Fasilitas']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('kategori') === 'Fasilitas' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Fasilitas
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Kegiatan']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('kategori') === 'Kegiatan' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Kegiatan
                </a>
            </div>

            <form method="GET" action="{{ route('admin.galeri.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari foto..." class="px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs w-full sm:w-64">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Cari
                </button>
            </form>
        </div>

        @if($daftarGaleri->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-images text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada foto galeri yang tersimpan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($daftarGaleri as $g)
                    <div class="group relative rounded-2xl overflow-hidden bg-slate-900 aspect-video shadow-sm border border-slate-100">
                        @if($g->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($g->foto))
                            <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent flex flex-col justify-end p-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 mb-1">
                                {{ $g->kategori }} &bull; {{ \Carbon\Carbon::parse($g->tanggal)->isoFormat('D MMM Y') }}
                            </span>
                            <h4 class="text-white font-bold text-sm leading-snug line-clamp-1">{{ $g->judul }}</h4>

                            <div class="flex items-center space-x-2 mt-3 pt-2 border-t border-white/10">
                                <button type="button"
                                    data-id="{{ $g->id }}"
                                    data-judul="{{ $g->judul }}"
                                    data-kategori="{{ $g->kategori }}"
                                    data-tanggal="{{ $g->tanggal }}"
                                    onclick="openEditModal(this)"
                                    class="bg-white/20 hover:bg-white text-white hover:text-slate-900 px-3 py-1 rounded-lg text-xs font-semibold backdrop-blur transition flex items-center space-x-1">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                    <span>Edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.galeri.destroy', $g->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-600/80 hover:bg-rose-600 text-white px-3 py-1 rounded-lg text-xs font-semibold backdrop-blur transition flex items-center space-x-1">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $daftarGaleri->links() }}
            </div>
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Foto Galeri</h3>
        <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan / Nama Fasilitas</label>
                <input type="text" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Galeri</label>
                <select name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="Fasilitas">Fasilitas Sekolah</option>
                    <option value="Kegiatan">Kegiatan Siswa</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Dokumentasi</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">File Foto</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
                <p class="text-xs text-slate-400 mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Foto</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Foto Galeri</h3>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Keterangan / Nama Fasilitas</label>
                <input type="text" id="editJudul" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Galeri</label>
                <select id="editKategori" name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="Fasilitas">Fasilitas Sekolah</option>
                    <option value="Kegiatan">Kegiatan Siswa</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Dokumentasi</label>
                <input type="date" id="editTanggal" name="tanggal" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Foto (Opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti file foto.</p>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
}
function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}
function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const judul = button.getAttribute('data-judul');
    const kategori = button.getAttribute('data-kategori');
    const tanggal = button.getAttribute('data-tanggal');

    document.getElementById('editForm').action = '/admin/galeri/' + id;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editKategori').value = kategori;
    document.getElementById('editTanggal').value = tanggal;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

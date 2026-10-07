@extends('layouts.admin', ['title' => 'Manajemen Galeri & Fasilitas - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Galeri & Fasilitas</h1>
            <p class="text-sm text-slate-500 mt-1">Dokumentasi sarana prasarana sekolah dan galeri aktivitas belajar siswa dalam bentuk album foto.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm shrink-0">
            <i class="fa-solid fa-camera"></i>
            <span>Tambah Album Foto</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
                <a href="{{ route('admin.galeri.index') }}"
                   data-filter-name="kategori" data-filter-value=""
                   class="live-filter-tab px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('kategori') ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Akademik']) }}"
                   data-filter-name="kategori" data-filter-value="Akademik"
                   class="live-filter-tab px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('kategori') === 'Akademik' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Akademik
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Lomba']) }}"
                   data-filter-name="kategori" data-filter-value="Lomba"
                   class="live-filter-tab px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('kategori') === 'Lomba' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Lomba
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Kegiatan']) }}"
                   data-filter-name="kategori" data-filter-value="Kegiatan"
                   class="live-filter-tab px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('kategori') === 'Kegiatan' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Kegiatan
                </a>
                <a href="{{ route('admin.galeri.index', ['kategori' => 'Fasilitas']) }}"
                   data-filter-name="kategori" data-filter-value="Fasilitas"
                   class="live-filter-tab px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('kategori') === 'Fasilitas' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Fasilitas
                </a>
            </div>

            <form method="GET" action="{{ route('admin.galeri.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
                @if(request('kategori'))
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                @endif
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari album foto..." class="px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs w-full sm:w-64">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Cari
                </button>
                <button type="button" id="liveResetBtn" class="bg-slate-100 text-slate-600 px-3 py-2 rounded-xl text-xs font-bold hover:bg-slate-200 transition {{ request('cari') ? '' : 'hidden' }}">
                    Reset
                </button>
            </form>
        </div>

        <div id="liveDataContainer">

        @if($daftarGaleri->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-images text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada album foto galeri yang tersimpan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($daftarGaleri as $g)
                    @php
                        $coverUrl = $g->foto_utama_url;
                        $fotoCount = $g->fotos->count() ?: ($g->foto ? 1 : 0);
                        $fotosData = $g->fotos->map(function($f) {
                            return [
                                'id' => $f->id,
                                'url' => $f->foto_url,
                            ];
                        });
                        if ($fotosData->isEmpty() && $g->foto_url) {
                            $fotosData = collect([
                                ['id' => 0, 'url' => $g->foto_url]
                            ]);
                        }
                    @endphp
                    <div class="group relative rounded-2xl overflow-hidden bg-slate-900 aspect-video shadow-sm border border-slate-100">
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="{{ $g->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 z-10">
                            <span class="bg-black/65 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1.5 shadow-sm">
                                <i class="fa-solid fa-images text-emerald-400"></i>
                                <span>{{ $fotoCount }} Foto</span>
                            </span>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/40 to-transparent flex flex-col justify-end p-4">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 mb-1">
                                {{ $g->kategori }} &bull; {{ \Carbon\Carbon::parse($g->tanggal)->isoFormat('D MMM Y') }}
                            </span>
                            <h4 class="text-white font-bold text-sm leading-snug line-clamp-1">{{ $g->judul }}</h4>

                            <div class="flex items-center space-x-1.5 mt-3 pt-2 border-t border-white/10">
                                <button type="button"
                                    data-id="{{ $g->id }}"
                                    data-judul="{{ $g->judul }}"
                                    data-kategori="{{ $g->kategori }}"
                                    data-fotos="{{ json_encode($fotosData) }}"
                                    onclick="openKelolaModal(this)"
                                    class="bg-emerald-600/90 hover:bg-emerald-600 text-white px-2.5 py-1 rounded-lg text-xs font-semibold backdrop-blur transition flex items-center space-x-1"
                                    title="Kelola Semua Foto dalam Album">
                                    <i class="fa-solid fa-images text-[10px]"></i>
                                    <span>Kelola ({{ $fotoCount }})</span>
                                </button>
                                <button type="button"
                                    data-id="{{ $g->id }}"
                                    data-judul="{{ $g->judul }}"
                                    data-kategori="{{ $g->kategori }}"
                                    data-tanggal="{{ $g->tanggal }}"
                                    onclick="openEditModal(this)"
                                    class="bg-white/20 hover:bg-white text-white hover:text-slate-900 px-2.5 py-1 rounded-lg text-xs font-semibold backdrop-blur transition flex items-center space-x-1"
                                    title="Edit Informasi Album">
                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                    <span>Edit</span>
                                </button>
                                <form method="POST" action="{{ route('admin.galeri.destroy', $g->id) }}" data-confirm="Apakah Anda yakin ingin menghapus seluruh album foto ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-600/80 hover:bg-rose-600 text-white px-2 py-1 rounded-lg text-xs font-semibold backdrop-blur transition flex items-center cursor-pointer" title="Hapus Seluruh Album">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
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
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-1">Tambah Album Galeri</h3>
        <p class="text-xs text-slate-500 mb-4">Unggah satu atau beberapa foto sekaligus untuk sebuah kegiatan atau fasilitas.</p>

        <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Fasilitas / Kegiatan</label>
                <input type="text" name="judul" placeholder="Contoh: Laboratorium Kimia Analisis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Galeri</label>
                    <select name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Kegiatan">Kegiatan Siswa</option>
                        <option value="Fasilitas">Fasilitas Sekolah</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Lomba">Lomba & Prestasi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Dokumentasi</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-semibold text-slate-700">Unggah Foto (Bisa Pilih Banyak)</label>
                    <span id="previewCountBadge" class="text-xs font-semibold text-emerald-700 hidden"></span>
                </div>
                <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 transition rounded-2xl p-4 text-center cursor-pointer bg-slate-50/50" onclick="document.getElementById('inputFotoMultiple').click()">
                    <i class="fa-solid fa-cloud-arrow-up text-3xl text-slate-400 mb-2"></i>
                    <p class="text-xs font-semibold text-slate-700">Klik untuk memilih foto (Multiple File didukung)</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tahan tombol <kbd class="px-1 py-0.5 bg-slate-200 rounded text-[10px]">Ctrl</kbd> atau <kbd class="px-1 py-0.5 bg-slate-200 rounded text-[10px]">Shift</kbd> untuk memilih banyak foto sekaligus</p>
                    <input type="file" id="inputFotoMultiple" name="foto[]" multiple accept="image/*" class="hidden" onchange="handlePreviewMultiple(event)" required>
                </div>
                <div id="previewContainer" class="grid grid-cols-3 sm:grid-cols-4 gap-2 mt-3 hidden max-h-44 overflow-y-auto p-1"></div>
                <p class="text-xs text-slate-400 mt-1.5">Format: JPG, JPEG, PNG, WEBP. Maksimal 3MB per foto.</p>
            </div>

            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Album</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="kelolaModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-2xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeKelolaModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-images"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900" id="kelolaJudulAlbum">Kelola Foto Album</h3>
                <span id="kelolaKategoriBadge" class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full"></span>
            </div>
        </div>

        <div class="my-5 border-t border-slate-100 pt-4">
            <div class="flex justify-between items-center mb-3">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Foto Dalam Album Ini</h4>
                <span id="kelolaCountText" class="text-xs text-slate-500 font-semibold"></span>
            </div>
            <div id="kelolaFotoGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-1"></div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <h4 class="text-xs font-bold text-slate-800 mb-2 flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-emerald-600"></i>
                <span>Tambah Foto Baru ke Album Ini</span>
            </h4>
            <form id="tambahFotoForm" method="POST" action="" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div class="flex items-center gap-3">
                    <input type="file" name="foto[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200" required>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2 rounded-xl text-xs whitespace-nowrap transition">
                        Unggah
                    </button>
                </div>
                <p class="text-[11px] text-slate-400">Pilih satu atau beberapa foto untuk ditambahkan ke album ini.</p>
            </form>
        </div>

        <div class="flex justify-end pt-5">
            <button type="button" onclick="closeKelolaModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Tutup</button>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Informasi Album</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Fasilitas / Kegiatan</label>
                <input type="text" id="editJudul" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori Galeri</label>
                <select id="editKategori" name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="Kegiatan">Kegiatan Siswa</option>
                    <option value="Fasilitas">Fasilitas Sekolah</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Lomba">Lomba & Prestasi</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Dokumentasi</label>
                <input type="date" id="editTanggal" name="tanggal" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<form id="deleteFotoForm" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
}
function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}

function handlePreviewMultiple(e) {
    const files = e.target.files;
    const container = document.getElementById('previewContainer');
    const badge = document.getElementById('previewCountBadge');
    container.innerHTML = '';

    if (files && files.length > 0) {
        badge.textContent = files.length + ' foto dipilih';
        badge.classList.remove('hidden');
        container.classList.remove('hidden');

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(evt) {
                const item = document.createElement('div');
                item.className = 'relative aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shadow-xs group';
                item.innerHTML = `
                    <img src="${evt.target.result}" class="w-full h-full object-cover">
                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">${index === 0 ? 'Cover' : '#' + (index + 1)}</span>
                `;
                container.appendChild(item);
            };
            reader.readAsDataURL(file);
        });
    } else {
        badge.classList.add('hidden');
        container.classList.add('hidden');
    }
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

function openKelolaModal(button) {
    const id = button.getAttribute('data-id');
    const judul = button.getAttribute('data-judul');
    const kategori = button.getAttribute('data-kategori');
    const fotos = JSON.parse(button.getAttribute('data-fotos') || '[]');

    document.getElementById('kelolaJudulAlbum').textContent = judul;
    document.getElementById('kelolaKategoriBadge').textContent = kategori;
    document.getElementById('kelolaCountText').textContent = fotos.length + ' Foto';
    document.getElementById('tambahFotoForm').action = '/admin/galeri/' + id + '/tambah-foto';

    const grid = document.getElementById('kelolaFotoGrid');
    grid.innerHTML = '';

    if (fotos.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center py-6 text-slate-400 text-xs">Belum ada foto dalam album ini.</div>';
    } else {
        fotos.forEach((f, idx) => {
            const card = document.createElement('div');
            card.className = 'relative aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 group shadow-xs';
            card.innerHTML = `
                <img src="${f.url}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-1.5 p-2">
                    ${f.id > 0 && fotos.length > 1 ? `
                        <button type="button" onclick="hapusFotoSatuan(${f.id})" class="bg-rose-600 hover:bg-rose-700 text-white w-7 h-7 rounded-lg flex items-center justify-center text-xs transition shadow" title="Hapus foto ini">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
                <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">${idx === 0 ? 'Cover' : '#' + (idx + 1)}</span>
            `;
            grid.appendChild(card);
        });
    }

    document.getElementById('kelolaModal').classList.remove('hidden');
}

function closeKelolaModal() {
    document.getElementById('kelolaModal').classList.add('hidden');
}

function hapusFotoSatuan(fotoId) {
    bukaKonfirmasi('Apakah Anda yakin ingin menghapus foto ini dari album?', function() {
        const form = document.getElementById('deleteFotoForm');
        form.action = '/admin/galeri/foto/' + fotoId;
        form.submit();
    }, { warna: 'rose', tombolTeks: 'Ya, Hapus' });
}
</script>
@endsection

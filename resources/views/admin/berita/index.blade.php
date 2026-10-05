@extends('layouts.admin', ['title' => 'H. Manajemen Berita - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">H. Manajemen Berita</h1>
            <p class="text-sm text-slate-500 mt-1">Publikasi warta sekolah, kegiatan ekstrakurikuler, dan informasi prestasi.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Tulis Berita</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.berita.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul, kategori, atau isi berita..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            @if(request('cari'))
                <a href="{{ route('admin.berita.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>

        @if($daftarBerita->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-newspaper text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data berita yang sesuai.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">No</th>
                            <th class="p-3.5">Gambar</th>
                            <th class="p-3.5">Judul & Kategori</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarBerita as $index => $b)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400">{{ $daftarBerita->firstItem() + $index }}</td>
                                <td class="p-3.5">
                                    @if($b->gambar)
                                        <img src="{{ asset('storage/' . $b->gambar) }}" alt="{{ $b->judul }}" class="w-14 h-10 object-cover rounded-lg border border-slate-200">
                                    @else
                                        <div class="w-14 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 border border-slate-200 text-xs">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded uppercase">{{ $b->kategori }}</span>
                                    <div class="font-bold text-slate-900 mt-1 line-clamp-1">{{ $b->judul }}</div>
                                </td>
                                <td class="p-3.5 text-slate-500 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($b->tanggal)->isoFormat('D MMMM Y') }}</td>
                                <td class="p-3.5">
                                    @if($b->status === 'Publish')
                                        <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full">Publish</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">Draft</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $b->id }}"
                                            data-judul="{{ $b->judul }}"
                                            data-kategori="{{ $b->kategori }}"
                                            data-tanggal="{{ $b->tanggal }}"
                                            data-status="{{ $b->status }}"
                                            data-isi="{{ $b->isi }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.berita.destroy', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $daftarBerita->links() }}
            </div>
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tulis Berita Baru</h3>
        <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Berita</label>
                <input type="text" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" name="kategori" placeholder="Prestasi / Kegiatan / Kesiswaan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Publikasi</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="Publish">Publish (Tampil ke Publik)</option>
                    <option value="Draft">Draft (Simpan Sementara)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Gambar Header Berita</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Isi Berita Lengkap</label>
                <textarea name="isi" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Publikasikan Berita</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Berita</h3>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul Berita</label>
                <input type="text" id="editJudul" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <input type="text" id="editKategori" name="kategori" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="editTanggal" name="tanggal" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Publikasi</label>
                <select id="editStatus" name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="Publish">Publish (Tampil ke Publik)</option>
                    <option value="Draft">Draft (Simpan Sementara)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Gambar Header (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Isi Berita Lengkap</label>
                <textarea id="editIsi" name="isi" rows="6" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required></textarea>
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
    const status = button.getAttribute('data-status');
    const isi = button.getAttribute('data-isi');

    document.getElementById('editForm').action = '/admin/berita/' + id;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editKategori').value = kategori;
    document.getElementById('editTanggal').value = tanggal;
    document.getElementById('editStatus').value = status;
    document.getElementById('editIsi').value = isi;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

@extends('layouts.admin', ['title' => 'Prestasi - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Torehan Prestasi</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar kejuaraan dan penghargaan yang diraih siswa maupun sekolah.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Prestasi</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.prestasi.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm leading-none"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari judul prestasi, peraih, atau tingkat..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            <button type="button" id="liveResetBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center {{ request('cari') ? '' : 'hidden' }}">
                Reset
            </button>
        </form>

        <div id="liveDataContainer">

        @if($daftarPrestasi->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-trophy text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data prestasi yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">No</th>
                            <th class="p-3.5">Gambar</th>
                            <th class="p-3.5">Judul Prestasi</th>
                            <th class="p-3.5">Tingkat</th>
                            <th class="p-3.5">Tahun</th>
                            <th class="p-3.5">Nama Peraih</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarPrestasi as $index => $p)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400">{{ $daftarPrestasi->firstItem() + $index }}</td>
                                <td class="p-3.5">
                                    @if($p->gambar)
                                        <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->judul }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-base">
                                            <i class="fa-solid fa-trophy"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3.5 font-bold text-slate-900">{{ $p->judul }}</td>
                                <td class="p-3.5">
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                        {{ $p->tingkat }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-700 font-semibold text-xs">{{ $p->tahun }}</td>
                                <td class="p-3.5 text-emerald-800 font-semibold text-xs">{{ $p->nama_peraih }}</td>
                                <td class="p-3.5 text-slate-500 text-xs max-w-xs">{{ $p->deskripsi }}</td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $p->id }}"
                                            data-judul="{{ $p->judul }}"
                                            data-tingkat="{{ $p->tingkat }}"
                                            data-tahun="{{ $p->tahun }}"
                                            data-peraih="{{ $p->nama_peraih }}"
                                            data-deskripsi="{{ $p->deskripsi }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.prestasi.destroy', $p->id) }}" data-confirm="Apakah Anda yakin ingin menghapus prestasi ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
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
                {{ $daftarPrestasi->links() }}
            </div>
        @endif
        </div>
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Prestasi Baru</h3>
        <form method="POST" action="{{ route('admin.prestasi.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul / Capaian Prestasi</label>
                <input type="text" name="judul" placeholder="Contoh: Juara 1 LKS Web Technologies" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tingkat</label>
                    <select name="tingkat" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Kota">Kota</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun</label>
                    <input type="text" name="tahun" value="{{ date('Y') }}" placeholder="2026" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Siswa / Tim Peraih</label>
                <input type="text" name="nama_peraih" placeholder="Contoh: Rizky Pratama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Dokumentasi / Piagam</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Tambahan</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Prestasi</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Prestasi</h3>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Judul / Capaian Prestasi</label>
                <input type="text" id="editJudul" name="judul" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tingkat</label>
                    <select id="editTingkat" name="tingkat" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Sekolah">Sekolah</option>
                        <option value="Kota">Kota</option>
                        <option value="Provinsi">Provinsi</option>
                        <option value="Nasional">Nasional</option>
                        <option value="Internasional">Internasional</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tahun</label>
                    <input type="text" id="editTahun" name="tahun" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Siswa / Tim Peraih</label>
                <input type="text" id="editPeraih" name="nama_peraih" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Foto Dokumentasi (Opsional)</label>
                <input type="file" name="gambar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Tambahan</label>
                <textarea id="editDeskripsi" name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm"></textarea>
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
    const tingkat = button.getAttribute('data-tingkat');
    const tahun = button.getAttribute('data-tahun');
    const peraih = button.getAttribute('data-peraih');
    const deskripsi = button.getAttribute('data-deskripsi');

    document.getElementById('editForm').action = '/admin/prestasi/' + id;
    document.getElementById('editJudul').value = judul;
    document.getElementById('editTingkat').value = tingkat;
    document.getElementById('editTahun').value = tahun;
    document.getElementById('editPeraih').value = peraih;
    document.getElementById('editDeskripsi').value = deskripsi;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

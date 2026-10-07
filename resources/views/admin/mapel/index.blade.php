@extends('layouts.admin', ['title' => 'Mata Pelajaran - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Mata Pelajaran</h1>
            <p class="text-sm text-slate-500 mt-1">Struktur kurikulum mata pelajaran umum dan produktif.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Mapel</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.mapel.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm leading-none"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode, nama, atau jenis mapel..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            <button type="button" id="liveResetBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center {{ request('cari') ? '' : 'hidden' }}">
                Reset
            </button>
        </form>

        <div id="liveDataContainer">

        @if($daftarMapel->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-book text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data mata pelajaran yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">No</th>
                            <th class="p-3.5">Kode Mapel</th>
                            <th class="p-3.5">Nama Mata Pelajaran</th>
                            <th class="p-3.5">Jenis</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarMapel as $index => $m)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400">{{ $daftarMapel->firstItem() + $index }}</td>
                                <td class="p-3.5 font-bold text-emerald-700">{{ $m->kode }}</td>
                                <td class="p-3.5 font-bold text-slate-900">{{ $m->nama }}</td>
                                <td class="p-3.5">
                                    <span class="{{ $m->jenis === 'Produktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }} text-xs font-bold px-2.5 py-1 rounded-full uppercase">
                                        {{ $m->jenis }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $m->id }}"
                                            data-kode="{{ $m->kode }}"
                                            data-nama="{{ $m->nama }}"
                                            data-jenis="{{ $m->jenis }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.mapel.destroy', $m->id) }}" data-confirm="Apakah Anda yakin ingin menghapus mata pelajaran ini?">
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
                {{ $daftarMapel->links() }}
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
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Mata Pelajaran</h3>
        <form method="POST" action="{{ route('admin.mapel.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Mapel</label>
                <input type="text" name="kode" placeholder="Contoh: DPK, MATH, BING" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Mata Pelajaran</label>
                <input type="text" name="nama" placeholder="Contoh: Dasar Program Keahlian" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Mata Pelajaran</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="Umum">Umum</option>
                    <option value="Produktif">Produktif</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Mapel</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Mata Pelajaran</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Mapel</label>
                <input type="text" id="editKode" name="kode" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Mata Pelajaran</label>
                <input type="text" id="editNama" name="nama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Mata Pelajaran</label>
                <select id="editJenis" name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="Umum">Umum</option>
                    <option value="Produktif">Produktif</option>
                </select>
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
    const kode = button.getAttribute('data-kode');
    const nama = button.getAttribute('data-nama');
    const jenis = button.getAttribute('data-jenis');

    document.getElementById('editForm').action = '/admin/mapel/' + id;
    document.getElementById('editKode').value = kode;
    document.getElementById('editNama').value = nama;
    document.getElementById('editJenis').value = jenis;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

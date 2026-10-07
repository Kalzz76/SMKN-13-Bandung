@extends('layouts.admin', ['title' => 'Jurusan Keahlian - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Program Keahlian / Jurusan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar jurusan dan kompetensi keahlian peserta didik.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Jurusan</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.jurusan.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm leading-none"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kode atau nama jurusan..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            <button type="button" id="liveResetBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center {{ request('cari') ? '' : 'hidden' }}">
                Reset
            </button>
        </form>

        <div id="liveDataContainer">

        @if($daftarJurusan->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-layer-group text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data jurusan yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">No</th>
                            <th class="p-3.5">Ikon</th>
                            <th class="p-3.5">Kode</th>
                            <th class="p-3.5">Nama Program Keahlian</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarJurusan as $index => $j)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400">{{ $daftarJurusan->firstItem() + $index }}</td>
                                <td class="p-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-lg">
                                        <i class="fa-solid {{ $j->ikon }}"></i>
                                    </div>
                                </td>
                                <td class="p-3.5 font-bold text-emerald-700">{{ $j->kode }}</td>
                                <td class="p-3.5 font-bold text-slate-900">{{ $j->nama }}</td>
                                <td class="p-3.5 text-slate-600 text-xs max-w-xs">{{ $j->deskripsi }}</td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $j->id }}"
                                            data-kode="{{ $j->kode }}"
                                            data-nama="{{ $j->nama }}"
                                            data-deskripsi="{{ $j->deskripsi }}"
                                            data-ikon="{{ $j->ikon }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.jurusan.destroy', $j->id) }}" data-confirm="Apakah Anda yakin ingin menghapus jurusan ini?">
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
                {{ $daftarJurusan->links() }}
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
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Jurusan Baru</h3>
        <form method="POST" action="{{ route('admin.jurusan.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Jurusan</label>
                <input type="text" name="kode" placeholder="Contoh: RPL" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Program Keahlian</label>
                <input type="text" name="nama" placeholder="Contoh: Rekayasa Perangkat Lunak" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ikon Font Awesome</label>
                <input type="text" name="ikon" placeholder="Contoh: fa-code, fa-network-wired" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                <p class="text-xs text-slate-400 mt-1">Nama kelas Font Awesome (contoh: fa-code, fa-microchip, dll)</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm"></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Jurusan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Jurusan</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kode Jurusan</label>
                <input type="text" id="editKode" name="kode" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Program Keahlian</label>
                <input type="text" id="editNama" name="nama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ikon Font Awesome</label>
                <input type="text" id="editIkon" name="ikon" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
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
    const kode = button.getAttribute('data-kode');
    const nama = button.getAttribute('data-nama');
    const deskripsi = button.getAttribute('data-deskripsi');
    const ikon = button.getAttribute('data-ikon');

    document.getElementById('editForm').action = '/admin/jurusan/' + id;
    document.getElementById('editKode').value = kode;
    document.getElementById('editNama').value = nama;
    document.getElementById('editDeskripsi').value = deskripsi;
    document.getElementById('editIkon').value = ikon;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

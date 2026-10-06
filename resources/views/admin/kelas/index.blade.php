@extends('layouts.admin', ['title' => 'Manajemen Kelas - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Kelas</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar rombongan belajar, penugasan ruang tetap, dan guru wali kelas.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Kelas</span>
        </button>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-6">
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama kelas, ruangan, atau wali kelas..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            @if(request('cari'))
                <a href="{{ route('admin.kelas.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>

        @if($daftarKelas->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-chalkboard text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data kelas yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[640px]">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl whitespace-nowrap">No</th>
                            <th class="p-3.5 whitespace-nowrap">Nama Kelas</th>
                            <th class="p-3.5 whitespace-nowrap">Ruang Kelas</th>
                            <th class="p-3.5 whitespace-nowrap">Wali Kelas</th>
                            <th class="p-3.5 whitespace-nowrap">Jumlah Siswa</th>
                            <th class="p-3.5 rounded-r-xl text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarKelas as $index => $k)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400 whitespace-nowrap">{{ $daftarKelas->firstItem() + $index }}</td>
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap inline-block">
                                        {{ $k->nama }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-700 font-semibold text-xs whitespace-nowrap">
                                    {{ $k->ruangan ? $k->ruangan->nama . ' (' . $k->ruangan->kode . ')' : '-' }}
                                </td>
                                <td class="p-3.5 text-slate-800 text-xs font-semibold whitespace-nowrap">
                                    {{ $k->waliKelas ? $k->waliKelas->nama : '-' }}
                                </td>
                                <td class="p-3.5 text-slate-600 text-xs whitespace-nowrap">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full font-semibold whitespace-nowrap inline-block">
                                        {{ $k->siswa->count() }} siswa
                                    </span>
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.kelas.show', $k->id) }}"
                                            class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center">
                                            <i class="fa-solid fa-eye mr-1"></i> Show
                                        </a>
                                        <button type="button"
                                            data-id="{{ $k->id }}"
                                            data-nama="{{ $k->nama }}"
                                            data-ruangan="{{ $k->id_ruangan }}"
                                            data-wali="{{ $k->id_wali_kelas }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.kelas.destroy', $k->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
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
                {{ $daftarKelas->links() }}
            </div>
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Kelas Baru</h3>
        <form method="POST" action="{{ route('admin.kelas.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kelas</label>
                <input type="text" name="nama" placeholder="Contoh: XII RPL 1, X KA 2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruang Teori / Praktik</label>
                <select name="id_ruangan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Pilih Ruangan Tetap --</option>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Wali Kelas</label>
                <select name="id_wali_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Pilih Guru Wali Kelas --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?? 'No NIP' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Kelas</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kelas</label>
                <input type="text" id="editNama" name="nama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruang Teori / Praktik</label>
                <select id="editRuangan" name="id_ruangan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Pilih Ruangan Tetap --</option>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Wali Kelas</label>
                <select id="editWali" name="id_wali_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Pilih Guru Wali Kelas --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?? 'No NIP' }})</option>
                    @endforeach
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
    const nama = button.getAttribute('data-nama');
    const ruangan = button.getAttribute('data-ruangan');
    const wali = button.getAttribute('data-wali');

    document.getElementById('editForm').action = '/admin/kelas/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editRuangan').value = ruangan || '';
    document.getElementById('editWali').value = wali || '';

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

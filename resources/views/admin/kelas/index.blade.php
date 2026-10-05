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

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <form method="GET" action="{{ route('admin.kelas.index') }}" class="flex flex-col sm:flex-row gap-4 mb-6">
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
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">No</th>
                            <th class="p-3.5">Nama Kelas</th>
                            <th class="p-3.5">Ruang Kelas</th>
                            <th class="p-3.5">Wali Kelas</th>
                            <th class="p-3.5">Jumlah Siswa</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarKelas as $index => $k)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400">{{ $daftarKelas->firstItem() + $index }}</td>
                                <td class="p-3.5 font-bold text-slate-900">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $k->nama }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-700 font-semibold text-xs">
                                    {{ $k->ruangan ? $k->ruangan->nama . ' (' . $k->ruangan->kode . ')' : '-' }}
                                </td>
                                <td class="p-3.5 text-slate-800 text-xs font-semibold">
                                    {{ $k->waliKelas ? $k->waliKelas->nama : '-' }}
                                </td>
                                <td class="p-3.5 text-slate-600 text-xs">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full font-semibold">
                                        {{ $k->siswa->count() }} siswa
                                    </span>
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $k->id }}"
                                            data-nama="{{ $k->nama }}"
                                            data-siswa='@json($k->siswa)'
                                            onclick="openShowModal(this)"
                                            class="bg-sky-50 hover:bg-sky-100 text-sky-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center">
                                            <i class="fa-solid fa-eye mr-1"></i> Show
                                        </button>
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

<div id="showModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl relative max-h-[90vh] flex flex-col">
        <button type="button" onclick="closeShowModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                    <span>Daftar Siswa Kelas</span>
                    <span id="showNamaKelas" class="text-emerald-700 font-black"></span>
                </h3>
                <p id="showTotalSiswa" class="text-xs text-slate-500"></p>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto">
            <div id="showTableContainer" class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="p-3 rounded-l-lg text-center w-12">No</th>
                            <th class="p-3">NIS</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3 text-center">L/P</th>
                            <th class="p-3 rounded-r-lg">Tahun Ajaran</th>
                        </tr>
                    </thead>
                    <tbody id="showTbodySiswa" class="divide-y divide-slate-100">
                    </tbody>
                </table>
            </div>
            <div id="showEmptySiswa" class="p-10 text-center text-slate-400 hidden">
                <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-300"></i>
                <p class="text-sm font-medium">Belum ada siswa yang terdaftar di kelas ini.</p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-slate-100 mt-4 flex-shrink-0">
            <button type="button" onclick="closeShowModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                Tutup
            </button>
        </div>
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
function openShowModal(button) {
    const nama = button.getAttribute('data-nama');
    let siswa = [];
    try {
        siswa = JSON.parse(button.getAttribute('data-siswa') || '[]');
    } catch (e) {
        siswa = [];
    }

    document.getElementById('showNamaKelas').textContent = nama;
    document.getElementById('showTotalSiswa').textContent = siswa.length + ' Siswa Terdaftar';

    const tbody = document.getElementById('showTbodySiswa');
    const emptyState = document.getElementById('showEmptySiswa');
    const tableContainer = document.getElementById('showTableContainer');

    tbody.innerHTML = '';

    if (siswa.length === 0) {
        tableContainer.classList.add('hidden');
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
        tableContainer.classList.remove('hidden');

        siswa.forEach((s, idx) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-slate-50 transition border-b border-slate-100 text-xs';
            tr.innerHTML = `
                <td class="p-3 text-slate-400 font-medium text-center font-mono">${idx + 1}</td>
                <td class="p-3 font-mono font-bold text-slate-700">${escapeHtml(s.nis || '-')}</td>
                <td class="p-3 font-semibold text-slate-900">${escapeHtml(s.nama || '-')}</td>
                <td class="p-3 text-center">
                    <span class="px-2 py-0.5 rounded-md font-bold text-[10px] ${s.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">
                        ${s.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}
                    </span>
                </td>
                <td class="p-3 text-slate-600 font-medium font-mono">${escapeHtml(s.tahun_ajaran || '-')}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    document.getElementById('showModal').classList.remove('hidden');
}
function closeShowModal() {
    document.getElementById('showModal').classList.add('hidden');
}
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endsection

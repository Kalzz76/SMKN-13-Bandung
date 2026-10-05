@extends('layouts.admin', ['title' => 'Data Guru & Staff - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Data Guru & Staff</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data tenaga pendidik, jabatan dinas, dan tautan akun portal guru.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Guru / Staff</span>
        </button>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <a href="{{ route('admin.guru.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('jenis') ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.guru.index', ['jenis' => 'Guru']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('jenis') === 'Guru' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Guru Pengajar
                </a>
                <a href="{{ route('admin.guru.index', ['jenis' => 'Staff']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('jenis') === 'Staff' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Staff Tata Usaha
                </a>
            </div>

            <form method="GET" action="{{ route('admin.guru.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
                @if(request('jenis'))
                    <input type="hidden" name="jenis" value="{{ request('jenis') }}">
                @endif
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, NIP, mapel..." class="px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs w-full sm:w-64">
                <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Cari
                </button>
            </form>
        </div>

        @if($daftarGuru->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-chalkboard-user text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data guru/staff yang sesuai.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($daftarGuru as $g)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center relative hover:shadow-md transition">
                        <div class="absolute top-4 right-4 flex space-x-1">
                            <button type="button"
                                data-id="{{ $g->id }}"
                                data-nama="{{ $g->nama }}"
                                data-nip="{{ $g->nip }}"
                                data-jenis="{{ $g->jenis }}"
                                data-jabatan="{{ $g->jabatan }}"
                                data-mapel="{{ $g->mapel_utama }}"
                                data-publik="{{ $g->tampil_publik }}"
                                data-userid="{{ $g->user_id }}"
                                onclick="openEditModal(this)"
                                class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs transition">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.guru.destroy', $g->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center text-xs transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-emerald-600/30 mb-4 bg-slate-50 flex items-center justify-center flex-shrink-0 shadow-inner">
                            @if($g->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($g->foto))
                                <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->nama }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xl">
                                    {{ \Illuminate\Support\Str::substr($g->nama, 0, 2) }}
                                </div>
                            @endif
                        </div>

                        <div class="space-y-1 w-full">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $g->jenis === 'Guru' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800' }}">
                                {{ $g->jenis }}
                            </span>
                            <h3 class="font-bold text-slate-900 text-sm mt-1 line-clamp-1" title="{{ $g->nama }}">{{ $g->nama }}</h3>
                            <p class="text-xs text-slate-400 font-mono">{{ $g->nip ?? 'NIP: -' }}</p>
                        </div>

                        <div class="w-full my-3 pt-3 border-t border-slate-100 text-xs text-slate-600 space-y-1">
                            <div><strong class="text-slate-800">Jabatan:</strong> {{ $g->jabatan }}</div>
                            @if($g->mapel_utama)
                                <div class="line-clamp-1"><strong class="text-slate-800">Mapel:</strong> {{ $g->mapel_utama }}</div>
                            @endif
                        </div>

                        <div class="w-full pt-2 flex items-center justify-between text-[11px] text-slate-500 border-t border-slate-50">
                            <div>
                                @if($g->user)
                                    <span class="text-emerald-700 font-semibold" title="Akun: {{ $g->user->username }}">
                                        <i class="fa-solid fa-circle-check mr-1"></i> {{ $g->user->username }}
                                    </span>
                                @else
                                    <span class="text-slate-400">
                                        <i class="fa-solid fa-circle-xmark mr-1"></i> No Akun
                                    </span>
                                @endif
                            </div>
                            <div>
                                @if($g->tampil_publik)
                                    <span class="text-emerald-600" title="Tampil di web publik"><i class="fa-solid fa-globe"></i> Publik</span>
                                @else
                                    <span class="text-slate-400" title="Tersembunyi dari publik"><i class="fa-solid fa-eye-slash"></i> Privat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $daftarGuru->links() }}
            </div>
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Guru / Tenaga Pendidik</h3>
        <form method="POST" action="{{ route('admin.guru.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" placeholder="Contoh: Refky, M.Kom." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NIP (Opsional)</label>
                    <input type="text" name="nip" placeholder="1985..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Tenaga</label>
                    <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Guru">Guru Pengajar</option>
                        <option value="Staff">Staff Tata Usaha</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jabatan Tugas</label>
                    <input type="text" name="jabatan" placeholder="Contoh: Guru Pengajar / Ka. Lab" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran Utama</label>
                    <input type="text" name="mapel_utama" placeholder="Contoh: Produktif RPL" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Hubungkan Akun Login Guru</label>
                <select name="user_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Belum Dihubungkan --</option>
                    @foreach($akunGuruTersedia as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} (username: {{ $u->username }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Hanya menampilkan akun role 'guru' yang belum terhubung ke guru lain.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Foto Formal</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" id="tampil_publik" name="tampil_publik" value="1" checked class="w-4 h-4 text-emerald-600 rounded">
                <label for="tampil_publik" class="text-sm font-medium text-slate-700">Tampilkan di halaman profil sekolah publik</label>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Guru</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Data Guru / Staff</h3>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap & Gelar</label>
                <input type="text" id="editNama" name="nama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NIP (Opsional)</label>
                    <input type="text" id="editNip" name="nip" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Tenaga</label>
                    <select id="editJenis" name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Guru">Guru Pengajar</option>
                        <option value="Staff">Staff Tata Usaha</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jabatan Tugas</label>
                    <input type="text" id="editJabatan" name="jabatan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran Utama</label>
                    <input type="text" id="editMapel" name="mapel_utama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Hubungkan Akun Login Guru</label>
                <select id="editUserId" name="user_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Belum Dihubungkan --</option>
                    @foreach($semuaAkunGuru as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} (username: {{ $u->username }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Foto Formal (Opsional)</label>
                <input type="file" name="foto" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" id="editTampilPublik" name="tampil_publik" value="1" class="w-4 h-4 text-emerald-600 rounded">
                <label for="editTampilPublik" class="text-sm font-medium text-slate-700">Tampilkan di halaman profil sekolah publik</label>
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
    const nip = button.getAttribute('data-nip');
    const jenis = button.getAttribute('data-jenis');
    const jabatan = button.getAttribute('data-jabatan');
    const mapel = button.getAttribute('data-mapel');
    const publik = button.getAttribute('data-publik');
    const userid = button.getAttribute('data-userid');

    document.getElementById('editForm').action = '/admin/guru/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editNip').value = nip || '';
    document.getElementById('editJenis').value = jenis;
    document.getElementById('editJabatan').value = jabatan;
    document.getElementById('editMapel').value = mapel || '';
    document.getElementById('editUserId').value = userid || '';
    document.getElementById('editTampilPublik').checked = publik == '1';

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

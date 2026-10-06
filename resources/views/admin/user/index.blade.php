@extends('layouts.admin', ['title' => 'Manajemen Akun - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Akun Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola kredensial akses login untuk Administrator, Guru, dan Sekretaris.</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-user-shield"></i>
            <span>Tambah Akun</span>
        </button>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4 sm:space-y-5">
        <!-- Form Pencarian (Di Atas) -->
        <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau username..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm flex-1 sm:flex-initial cursor-pointer">
                    Cari
                </button>
                @if(request('cari'))
                    <a href="{{ route('admin.user.index', array_filter(['role' => request('role')])) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Filter Role (Di Bawah Search) -->
        <div class="grid grid-cols-4 gap-2 sm:flex sm:items-center sm:space-x-2">
            <a href="{{ route('admin.user.index', array_filter(['cari' => request('cari')])) }}"
               class="h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ !request('role') ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'admin', 'cari' => request('cari')])) }}"
               class="h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'admin' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Admin
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'guru', 'cari' => request('cari')])) }}"
               class="h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'guru' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Guru
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'sekretaris', 'cari' => request('cari')])) }}"
               class="h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'sekretaris' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sekretaris
            </a>
        </div>

        @if($daftarUser->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-users-gear text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada akun pengguna yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[780px]">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl whitespace-nowrap">No</th>
                            <th class="p-3.5 whitespace-nowrap">Nama Lengkap</th>
                            <th class="p-3.5 whitespace-nowrap">Username</th>
                            <th class="p-3.5 whitespace-nowrap">Hak Akses (Role)</th>
                            <th class="p-3.5 whitespace-nowrap">Terhubung Profil Guru</th>
                            <th class="p-3.5 whitespace-nowrap">Status</th>
                            <th class="p-3.5 rounded-r-xl text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarUser as $index => $u)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400 whitespace-nowrap">{{ $daftarUser->firstItem() + $index }}</td>
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">{{ $u->name }}</td>
                                <td class="p-3.5 font-mono text-emerald-700 font-semibold whitespace-nowrap">{{ $u->username }}</td>
                                <td class="p-3.5 whitespace-nowrap">
                                    @if($u->role === 'admin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200/80 shadow-xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            <i class="fa-solid fa-shield-halved text-[11px]"></i>
                                            <span>Administrator</span>
                                        </span>
                                    @elseif($u->role === 'guru')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 shadow-xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <i class="fa-solid fa-chalkboard-user text-[11px]"></i>
                                            <span>Guru Pengajar</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80 shadow-xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <i class="fa-solid fa-user-pen text-[11px]"></i>
                                            <span>Sekretaris</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-xs text-slate-600 whitespace-nowrap">
                                    {{ $u->guru ? $u->guru->nama : '-' }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    @if($u->status === 'Aktif')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 shadow-xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Nonaktif</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            data-username="{{ $u->username }}"
                                            data-role="{{ $u->role }}"
                                            data-status="{{ $u->status }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        @if(auth()->id() != $u->id)
                                            <form method="POST" action="{{ route('admin.user.destroy', $u->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $daftarUser->links() }}
            </div>
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Akun Pengguna Baru</h3>
        <form method="POST" action="{{ route('admin.user.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role / Peran</label>
                    <select id="tambahRole" name="role" onchange="toggleRoleForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="guru">Guru</option>
                        <option value="admin">Administrator</option>
                        <option value="sekretaris">Sekretaris</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div id="containerNamaGuru">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru (Belum Punya Akun)</label>
                <select id="tambahGuruSelect" name="guru_id" onchange="pilihGuruOtomatis(this)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @if($guruBelumPunyaAkun->isEmpty())
                        <option value="">Semua guru sudah memiliki akun</option>
                    @else
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruBelumPunyaAkun as $g)
                            <option value="{{ $g->id }}" data-nama="{{ $g->nama }}">{{ $g->nama }} (NIP: {{ $g->nip ?? '-' }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div id="containerNamaBiasa" class="hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="tambahNamaInput" name="name" placeholder="Nama pengguna" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Username (Huruf Kecil Tanpa Spasi)</label>
                <input type="text" id="tambahUsername" name="username" placeholder="Contoh: refky, admin, staf1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Akun Pengguna</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" id="editName" name="name" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                <input type="text" id="editUsername" name="username" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role / Peran</label>
                    <select id="editRole" name="role" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="guru">Guru</option>
                        <option value="admin">Administrator</option>
                        <option value="sekretaris">Sekretaris</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select id="editStatus" name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ganti Password (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
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
    toggleRoleForm();
}
function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}
function toggleRoleForm() {
    const role = document.getElementById('tambahRole').value;
    const containerGuru = document.getElementById('containerNamaGuru');
    const selectGuru = document.getElementById('tambahGuruSelect');
    const containerBiasa = document.getElementById('containerNamaBiasa');
    const inputBiasa = document.getElementById('tambahNamaInput');

    if (role === 'guru') {
        containerGuru.classList.remove('hidden');
        selectGuru.required = true;
        selectGuru.disabled = false;

        containerBiasa.classList.add('hidden');
        inputBiasa.required = false;
        inputBiasa.disabled = true;
    } else {
        containerGuru.classList.add('hidden');
        selectGuru.required = false;
        selectGuru.disabled = true;

        containerBiasa.classList.remove('hidden');
        inputBiasa.required = true;
        inputBiasa.disabled = false;
    }
}
function pilihGuruOtomatis(select) {
    const option = select.options[select.selectedIndex];
    if (!option) return;
    const nama = option.getAttribute('data-nama');
    const usernameInput = document.getElementById('tambahUsername');
    if (nama && !usernameInput.value) {
        const clean = nama.split(',')[0].trim().split(' ')[0].toLowerCase().replace(/[^a-z0-9]/g, '');
        usernameInput.value = clean;
    }
}
function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name');
    const username = button.getAttribute('data-username');
    const role = button.getAttribute('data-role');
    const status = button.getAttribute('data-status');

    document.getElementById('editForm').action = '/admin/user/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editUsername').value = username;
    document.getElementById('editRole').value = role;
    document.getElementById('editStatus').value = status;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection

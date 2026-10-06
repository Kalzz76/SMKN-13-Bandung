@extends('layouts.admin', ['title' => 'Detail Kelas ' . $kelas->nama . ' - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ route('admin.kelas.index') }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition flex-shrink-0 backdrop-blur-sm shadow">
                    <i class="fa-solid fa-arrow-left text-base sm:text-lg"></i>
                </a>
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-wide truncate">{{ $kelas->nama }}</h1>
                    <div class="flex items-center space-x-3 sm:space-x-4 text-xs sm:text-sm text-emerald-100 font-medium mt-0.5 sm:mt-1">
                        <span class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-location-dot text-emerald-300"></i>
                            <span>{{ $kelas->ruangan ? $kelas->ruangan->kode : 'Belum Ada Ruangan' }}</span>
                        </span>
                        <span class="flex items-center space-x-1.5">
                            <i class="fa-solid fa-user-group text-emerald-300"></i>
                            <span>{{ $kelas->siswa->count() }} Siswa</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-row items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                <div class="bg-white/15 backdrop-blur-md border border-white/20 rounded-xl sm:rounded-2xl px-3 sm:px-3.5 py-1.5 sm:py-2 flex items-center space-x-2.5 sm:space-x-3 text-left flex-1 sm:flex-initial min-w-0">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/25 text-white font-black text-xs flex items-center justify-center uppercase shadow-inner flex-shrink-0">
                        {{ $kelas->waliKelas ? \Illuminate\Support\Str::substr($kelas->waliKelas->nama, 0, 1) : '?' }}
                    </div>
                    <div class="min-w-0">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-200 block leading-tight">Wali Kelas</span>
                        <span class="text-xs font-bold text-white block truncate leading-tight">{{ $kelas->waliKelas ? $kelas->waliKelas->nama : 'Belum Ditentukan' }}</span>
                    </div>
                </div>

                <button type="button" onclick="openKelolaKelasModal()" class="bg-white hover:bg-emerald-50 text-emerald-800 font-bold px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-md transition flex items-center justify-center space-x-1.5 sm:space-x-2 flex-1 sm:flex-initial whitespace-nowrap">
                    <i class="fa-solid fa-gear"></i>
                    <span>Kelola Kelas</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-6 border-b border-slate-200 text-sm">
        <button type="button" id="tabBtnStruktur" onclick="switchTab('struktur')" class="tab-link pb-3 font-bold flex items-center space-x-2 text-emerald-700 border-b-2 border-emerald-600 transition">
            <i class="fa-solid fa-layer-group"></i>
            <span>Struktur Kelas</span>
        </button>
        <button type="button" id="tabBtnSiswa" onclick="switchTab('siswa')" class="tab-link pb-3 font-semibold flex items-center space-x-2 text-slate-400 hover:text-slate-600 border-b-2 border-transparent transition">
            <i class="fa-solid fa-user-group"></i>
            <span>Daftar Siswa</span>
        </button>
    </div>

    @php
        $strukturData = $kelas->struktur ?? [];
        $posisiList = [
            [
                'key' => 'km',
                'label' => 'Ketua Murid',
                'icon' => 'fa-regular fa-user',
                'color' => 'text-emerald-700',
                'bg' => 'bg-emerald-50',
                'nama' => $strukturData['km'] ?? ($kelas->siswa->firstWhere('jabatan', 'Ketua Murid')?->nama ?? null),
            ],
            [
                'key' => 'wakil_km',
                'label' => 'Wakil Ketua Murid',
                'icon' => 'fa-solid fa-user-check',
                'color' => 'text-teal-700',
                'bg' => 'bg-teal-50',
                'nama' => $strukturData['wakil_km'] ?? ($kelas->siswa->firstWhere('jabatan', 'Wakil Ketua Murid')?->nama ?? null),
            ],
            [
                'key' => 'bendahara_1',
                'label' => 'Bendahara 1',
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'text-emerald-700',
                'bg' => 'bg-emerald-50',
                'nama' => $strukturData['bendahara_1'] ?? ($kelas->siswa->firstWhere('jabatan', 'Bendahara 1')?->nama ?? null),
            ],
            [
                'key' => 'bendahara_2',
                'label' => 'Bendahara 2',
                'icon' => 'fa-solid fa-user-plus',
                'color' => 'text-emerald-700',
                'bg' => 'bg-emerald-50',
                'nama' => $strukturData['bendahara_2'] ?? ($kelas->siswa->firstWhere('jabatan', 'Bendahara 2')?->nama ?? null),
            ],
            [
                'key' => 'sekretaris_1',
                'label' => 'Sekretaris 1',
                'icon' => 'fa-regular fa-file-lines',
                'color' => 'text-amber-700',
                'bg' => 'bg-amber-50',
                'nama' => $strukturData['sekretaris_1'] ?? ($kelas->siswa->firstWhere('jabatan', 'Sekretaris 1')?->nama ?? null),
            ],
            [
                'key' => 'sekretaris_2',
                'label' => 'Sekretaris 2',
                'icon' => 'fa-regular fa-file-lines',
                'color' => 'text-orange-700',
                'bg' => 'bg-orange-50',
                'nama' => $strukturData['sekretaris_2'] ?? ($kelas->siswa->firstWhere('jabatan', 'Sekretaris 2')?->nama ?? null),
            ],
            [
                'key' => 'pj_keagamaan',
                'label' => 'PJ Keagamaan',
                'icon' => 'fa-regular fa-star',
                'color' => 'text-violet-700',
                'bg' => 'bg-violet-50',
                'nama' => $strukturData['pj_keagamaan'] ?? ($kelas->siswa->firstWhere('jabatan', 'PJ Keagamaan')?->nama ?? null),
            ],
            [
                'key' => 'pj_keamanan',
                'label' => 'PJ Keamanan',
                'icon' => 'fa-solid fa-shield-halved',
                'color' => 'text-rose-700',
                'bg' => 'bg-rose-50',
                'nama' => $strukturData['pj_keamanan'] ?? ($kelas->siswa->firstWhere('jabatan', 'PJ Keamanan')?->nama ?? null),
            ],
        ];
    @endphp

    <div id="panelStruktur" class="tab-panel">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($posisiList as $p)
                <div class="bg-white rounded-2xl sm:rounded-3xl p-3.5 sm:p-6 border border-slate-100 shadow-sm hover:shadow-md transition text-center flex flex-col items-center justify-center min-h-[130px] sm:min-h-[150px]">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full {{ $p['bg'] }} {{ $p['color'] }} flex items-center justify-center text-base sm:text-lg mb-2 sm:mb-3 shadow-inner">
                        <i class="{{ $p['icon'] }}"></i>
                    </div>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $p['label'] }}</p>
                    @if($p['nama'])
                        <h4 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-tight line-clamp-2">{{ $p['nama'] }}</h4>
                    @else
                        <span class="text-[11px] sm:text-xs text-slate-400 italic">Belum ditentukan</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div id="panelSiswa" class="tab-panel hidden space-y-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative flex-grow w-full">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="cariSiswaInput" oninput="filterSiswaTable()" placeholder="Cari nama siswa di kelas ini..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <div class="flex items-center space-x-2 w-full sm:w-auto justify-end">
                <button type="button" onclick="sortSiswaTable()" class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center space-x-2">
                    <span id="sortLabel">A - Z</span>
                    <i class="fa-solid fa-arrow-down-a-z"></i>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if($kelas->siswa->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <i class="fa-solid fa-user-slash text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm font-medium">Belum ada siswa yang terdaftar di kelas ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm min-w-[560px]" id="tableSiswaKelas">
                        <thead class="bg-slate-50 text-slate-700 uppercase text-xs border-b border-slate-100">
                            <tr>
                                <th class="p-3.5 sm:p-4 rounded-l-xl whitespace-nowrap">NIS / NISN</th>
                                <th class="p-3.5 sm:p-4 whitespace-nowrap">Nama Siswa</th>
                                <th class="p-3.5 sm:p-4 whitespace-nowrap">L/P</th>
                                <th class="p-3.5 sm:p-4 whitespace-nowrap">Jabatan</th>
                                <th class="p-3.5 sm:p-4 rounded-r-xl text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="tbodySiswaKelas">
                            @foreach($kelas->siswa as $s)
                                <tr class="hover:bg-slate-50/50 transition row-siswa" data-nama="{{ strtolower($s->nama) }}">
                                    <td class="p-3.5 sm:p-4 font-mono font-medium text-slate-700 whitespace-nowrap">
                                        <span class="font-bold text-slate-900">{{ $s->nis }}</span>
                                        <span class="text-slate-400"> / </span>
                                        <span class="text-slate-600">{{ $s->nisn ?? '-' }}</span>
                                    </td>
                                    <td class="p-3.5 sm:p-4 font-bold text-slate-900 uppercase student-name whitespace-nowrap">{{ $s->nama }}</td>
                                    <td class="p-3.5 sm:p-4 whitespace-nowrap">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap {{ $s->jenis_kelamin === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $s->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 sm:p-4 whitespace-nowrap">
                                        @if($s->jabatan && $s->jabatan !== 'Anggota')
                                            <span class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold shadow-sm whitespace-nowrap inline-block">
                                                {{ $s->jabatan }}
                                            </span>
                                        @else
                                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap inline-block">
                                                Anggota
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 sm:p-4 text-right whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.siswa.destroy', $s->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-500 hover:text-rose-700 transition inline-flex items-center justify-center text-xs">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<div id="kelolaKelasModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeKelolaKelasModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="mb-5">
            <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-sitemap text-emerald-700"></i>
                <span>Kelola Struktur Kelas</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Pilih nama siswa dari kelas {{ $kelas->nama }} untuk menduduki struktur kepengurusan kelas.</p>
        </div>

        <form method="POST" action="{{ route('admin.kelas.update-struktur', $kelas->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ketua Murid (KM)</label>
                    <select name="km" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['km'] ?? ($kelas->siswa->firstWhere('jabatan', 'Ketua Murid')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Wakil Ketua Murid</label>
                    <select name="wakil_km" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['wakil_km'] ?? ($kelas->siswa->firstWhere('jabatan', 'Wakil Ketua Murid')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bendahara 1</label>
                    <select name="bendahara_1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['bendahara_1'] ?? ($kelas->siswa->firstWhere('jabatan', 'Bendahara 1')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Bendahara 2</label>
                    <select name="bendahara_2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['bendahara_2'] ?? ($kelas->siswa->firstWhere('jabatan', 'Bendahara 2')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sekretaris 1</label>
                    <select name="sekretaris_1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['sekretaris_1'] ?? ($kelas->siswa->firstWhere('jabatan', 'Sekretaris 1')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Sekretaris 2</label>
                    <select name="sekretaris_2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['sekretaris_2'] ?? ($kelas->siswa->firstWhere('jabatan', 'Sekretaris 2')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PJ Keagamaan</label>
                    <select name="pj_keagamaan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['pj_keagamaan'] ?? ($kelas->siswa->firstWhere('jabatan', 'PJ Keagamaan')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">PJ Keamanan</label>
                    <select name="pj_keamanan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($kelas->siswa as $s)
                            <option value="{{ $s->nama }}" {{ ($strukturData['pj_keamanan'] ?? ($kelas->siswa->firstWhere('jabatan', 'PJ Keamanan')?->nama ?? '')) === $s->nama ? 'selected' : '' }}>
                                {{ $s->nama }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeKelolaKelasModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Struktur</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    const tabBtnStruktur = document.getElementById('tabBtnStruktur');
    const tabBtnSiswa = document.getElementById('tabBtnSiswa');
    const panelStruktur = document.getElementById('panelStruktur');
    const panelSiswa = document.getElementById('panelSiswa');

    if (tab === 'struktur') {
        tabBtnStruktur.className = 'tab-link pb-3 font-bold flex items-center space-x-2 text-emerald-700 border-b-2 border-emerald-600 transition';
        tabBtnSiswa.className = 'tab-link pb-3 font-semibold flex items-center space-x-2 text-slate-400 hover:text-slate-600 border-b-2 border-transparent transition';
        panelStruktur.classList.remove('hidden');
        panelSiswa.classList.add('hidden');
    } else {
        tabBtnSiswa.className = 'tab-link pb-3 font-bold flex items-center space-x-2 text-emerald-700 border-b-2 border-emerald-600 transition';
        tabBtnStruktur.className = 'tab-link pb-3 font-semibold flex items-center space-x-2 text-slate-400 hover:text-slate-600 border-b-2 border-transparent transition';
        panelSiswa.classList.remove('hidden');
        panelStruktur.classList.add('hidden');
    }
}

function openKelolaKelasModal() {
    document.getElementById('kelolaKelasModal').classList.remove('hidden');
}

function closeKelolaKelasModal() {
    document.getElementById('kelolaKelasModal').classList.add('hidden');
}

function filterSiswaTable() {
    const q = document.getElementById('cariSiswaInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tbodySiswaKelas tr');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}

let sortAsc = true;
function sortSiswaTable() {
    const tbody = document.getElementById('tbodySiswaKelas');
    if (!tbody) return;
    const rows = Array.from(tbody.querySelectorAll('tr'));
    rows.sort((a, b) => {
        const nameA = a.querySelector('.student-name') ? a.querySelector('.student-name').textContent.trim() : '';
        const nameB = b.querySelector('.student-name') ? b.querySelector('.student-name').textContent.trim() : '';
        return sortAsc ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
    });
    rows.forEach(r => tbody.appendChild(r));
    sortAsc = !sortAsc;
    document.getElementById('sortLabel').textContent = sortAsc ? 'A - Z' : 'Z - A';
}
</script>
@endsection

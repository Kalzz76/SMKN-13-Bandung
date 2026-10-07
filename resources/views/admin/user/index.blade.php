@extends('layouts.admin', ['title' => 'Manajemen Akun - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Manajemen Akun Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola kredensial akses login untuk Administrator, Guru, dan Sekretaris.</p>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 flex-nowrap shrink-0 overflow-x-auto max-w-full pb-1 xl:pb-0">
            <button type="button" onclick="openGenerateGuruModal()" class="whitespace-nowrap bg-white hover:bg-slate-50 text-slate-700 font-semibold px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-200/90 shadow-sm transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-user-plus text-slate-500"></i>
                <span>Generate Akun Guru</span>
                @if($guruTanpaAkunCount > 0)
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full ml-1">{{ $guruTanpaAkunCount }}</span>
                @endif
            </button>
            <button type="button" onclick="openGenerateSiswaModal()" class="whitespace-nowrap bg-white hover:bg-slate-50 text-slate-700 font-semibold px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-200/90 shadow-sm transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-user-check text-slate-500"></i>
                <span>Generate Akun Siswa</span>
                @if($sekreTanpaAkunCount > 0)
                    <span class="bg-indigo-100 text-indigo-800 text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full ml-1">{{ $sekreTanpaAkunCount }}</span>
                @endif
            </button>
            <button type="button" onclick="openTambahModal()" class="whitespace-nowrap bg-[#5B4DF0] hover:bg-[#4d3ee3] text-white font-semibold px-4 py-2.5 rounded-xl shadow-sm transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah Akun Baru</span>
            </button>
        </div>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4 sm:space-y-5">
        <!-- Form Pencarian (Di Atas) -->
        <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-col sm:flex-row gap-3">
            @if(request('role'))
                <input type="hidden" name="role" value="{{ request('role') }}">
            @endif
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-sm leading-none"></i>
                </div>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama atau username..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm flex-1 sm:flex-initial cursor-pointer">
                    Cari
                </button>
                <button type="button" id="liveResetBtn" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center {{ request('cari') ? '' : 'hidden' }}">
                    Reset
                </button>
            </div>
        </form>

        <!-- Filter Role (Di Bawah Search) -->
        <div class="grid grid-cols-4 gap-2 sm:flex sm:items-center sm:space-x-2">
            <a href="{{ route('admin.user.index', array_filter(['cari' => request('cari')])) }}"
               data-filter-name="role" data-filter-value=""
               class="live-filter-tab h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ !request('role') ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'admin', 'cari' => request('cari')])) }}"
               data-filter-name="role" data-filter-value="admin"
               class="live-filter-tab h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'admin' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Admin
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'guru', 'cari' => request('cari')])) }}"
               data-filter-name="role" data-filter-value="guru"
               class="live-filter-tab h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'guru' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Guru
            </a>
            <a href="{{ route('admin.user.index', array_filter(['role' => 'sekretaris', 'cari' => request('cari')])) }}"
               data-filter-name="role" data-filter-value="sekretaris"
               class="live-filter-tab h-10 sm:h-9 sm:px-4 px-1.5 rounded-xl text-xs font-bold transition flex items-center justify-center text-center {{ request('role') === 'sekretaris' ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sekretaris
            </a>
        </div>

        <div id="liveDataContainer">

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
                            <th class="py-3 px-3 rounded-l-xl text-center w-12 whitespace-nowrap">No</th>
                            <th class="py-3 px-3 whitespace-nowrap">Nama Lengkap</th>
                            <th class="py-3 px-3 whitespace-nowrap">Username</th>
                            <th class="py-3 px-3 text-center whitespace-nowrap">Hak Akses (Role)</th>
                            <th class="py-3 px-3 whitespace-nowrap">Terhubung Data</th>
                            <th class="py-3 px-3 text-center whitespace-nowrap w-24">Status</th>
                            <th class="py-3 px-3 rounded-r-xl text-center whitespace-nowrap w-36">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarUser as $index => $u)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-3 font-medium text-slate-400 text-center whitespace-nowrap">{{ $daftarUser->firstItem() + $index }}</td>
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-slate-900 leading-snug">{{ $u->name }}</div>
                                </td>
                                <td class="py-3.5 px-3 whitespace-nowrap">
                                    <span class="font-mono text-emerald-700 font-semibold text-xs">{{ $u->username }}</span>
                                </td>
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
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
                                <td class="py-3.5 px-3 text-xs">
                                    @if($u->guru)
                                        <div class="font-bold text-slate-900 leading-snug">{{ $u->guru->nama }}</div>
                                        <div class="text-slate-400 font-mono text-[11px] mt-0.5">NIP: {{ $u->guru->nip ?? '-' }}</div>
                                    @elseif($u->siswa)
                                        <div class="font-bold text-slate-900 leading-snug">{{ $u->siswa->nama }}</div>
                                        <div class="text-amber-700 font-medium text-[11px] mt-0.5">{{ $u->siswa->jabatan ?? 'Sekretaris' }} &bull; {{ $u->siswa->kelas ? $u->siswa->kelas->nama : '-' }}</div>
                                    @else
                                        <span class="text-slate-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
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
                                <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                        <button type="button"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->name }}"
                                            data-username="{{ $u->username }}"
                                            data-role="{{ $u->role }}"
                                            data-status="{{ $u->status }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer flex items-center gap-1.5">
                                            <i class="fa-solid fa-pen text-slate-500"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form method="POST" action="{{ route('admin.user.reset-password', $u->id) }}" data-confirm="Apakah Anda yakin ingin mereset password akun {{ $u->name }} ({{ $u->username }}) ke default? Password baru: {{ $u->role === 'guru' ? 'guru123' : ($u->role === 'sekretaris' ? 'sekretaris123' : 'admin123') }}">
                                            @csrf
                                            <button type="submit" title="Reset Password ke Default" class="bg-amber-50 hover:bg-amber-100 text-amber-700 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap border border-amber-200/60 shadow-sm cursor-pointer">
                                                <i class="fa-solid fa-arrows-rotate text-amber-600"></i>
                                                <span>Reset</span>
                                            </button>
                                        </form>

                                        @if(auth()->id() != $u->id)
                                            <form method="POST" action="{{ route('admin.user.destroy', $u->id) }}" data-confirm="Apakah Anda yakin ingin menghapus akun pengguna ini?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition flex items-center gap-1.5 whitespace-nowrap border border-rose-200/60 shadow-sm cursor-pointer">
                                                    <i class="fa-solid fa-trash text-rose-500"></i>
                                                    <span>Hapus</span>
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
</div>

<div id="generateGuruModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeGenerateGuruModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">Generate Akun Guru</h3>
                <p class="text-xs text-slate-500">Pembuatan akun otomatis untuk guru yang belum memiliki akses</p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2 text-xs text-slate-600 mb-5">
            <div class="flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-emerald-600 mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-800">Aturan Generate:</span>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-slate-600">
                        <li><strong>Username:</strong> Nama depan (huruf kecil) + 4 angka pertama NIP</li>
                        <li><strong>Password Awal:</strong> <code class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded font-mono font-bold">guru123</code> (dapat diubah nanti)</li>
                        <li><strong>Role:</strong> Guru Pengajar &bull; <strong>Status:</strong> Aktif</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($guruBelumPunyaAkun->isEmpty())
            <div class="p-6 text-center bg-emerald-50/60 rounded-2xl border border-emerald-100 text-emerald-800 mb-6">
                <i class="fa-solid fa-circle-check text-2xl mb-2 text-emerald-600"></i>
                <p class="text-sm font-semibold">Semua data guru sudah memiliki akun pengguna.</p>
                <p class="text-xs text-emerald-600 mt-1">Tidak ada akun guru baru yang perlu digenerate saat ini.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeGenerateGuruModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Tutup</button>
            </div>
        @else
            <div class="mb-5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-slate-700 uppercase">Daftar Guru Siap Generate ({{ $guruTanpaAkunCount }})</span>
                </div>
                <div class="max-h-48 overflow-y-auto divide-y divide-slate-100 border border-slate-200 rounded-xl bg-white text-xs">
                    @foreach($guruBelumPunyaAkun as $g)
                        @php
                            $namaClean = preg_replace('/^(drs?|dra|ir|prof|h|hj)\.?\s+/i', '', trim($g->nama));
                            $words = preg_split('/[\s,]+/', $namaClean);
                            $firstWord = $words[0] ?? 'guru';
                            $namaDepan = \Illuminate\Support\Str::slug($firstWord, '');
                            if (empty($namaDepan)) $namaDepan = 'guru';
                            $nipClean = preg_replace('/[^0-9]/', '', (string)$g->nip);
                            $empatDigit = strlen($nipClean) >= 4 ? substr($nipClean, 0, 4) : str_pad($nipClean, 4, '0', STR_PAD_RIGHT);
                            $usernamePreview = strtolower($namaDepan . $empatDigit);
                        @endphp
                        <div class="p-3 flex justify-between items-center hover:bg-slate-50">
                            <div>
                                <div class="font-bold text-slate-800">{{ $g->nama }}</div>
                                <div class="text-slate-400 font-mono text-[11px]">NIP: {{ $g->nip ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <span class="bg-slate-100 text-slate-700 font-mono px-2 py-0.5 rounded text-[11px] font-semibold">{{ $usernamePreview }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('admin.user.generate-guru') }}">
                @csrf
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeGenerateGuruModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm flex items-center space-x-2">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Generate {{ $guruTanpaAkunCount }} Akun Guru</span>
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<div id="generateSiswaModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeGenerateSiswaModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">Generate Akun Siswa (Sekretaris)</h3>
                <p class="text-xs text-slate-500">Khusus siswa berjabatan Sekretaris di kelas untuk absensi siswa</p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2 text-xs text-slate-600 mb-5">
            <div class="flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-indigo-600 mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-800">Aturan Generate:</span>
                    <ul class="list-disc list-inside mt-1 space-y-0.5 text-slate-600">
                        <li><strong>Target:</strong> Khusus siswa dengan jabatan Sekretaris yang belum memiliki akun</li>
                        <li><strong>Username:</strong> Nama depan (huruf kecil) + 2 angka terakhir NISN</li>
                        <li><strong>Password Awal:</strong> <code class="bg-indigo-100 text-indigo-800 px-1.5 py-0.5 rounded font-mono font-bold">sekretaris123</code> (dapat diubah nanti)</li>
                        <li><strong>Role:</strong> Sekretaris &bull; <strong>Status:</strong> Aktif</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($sekreBelumPunyaAkun->isEmpty())
            <div class="p-6 text-center bg-indigo-50/60 rounded-2xl border border-indigo-100 text-indigo-800 mb-6">
                <i class="fa-solid fa-circle-check text-2xl mb-2 text-indigo-600"></i>
                <p class="text-sm font-semibold">Tidak ada siswa sekretaris yang belum memiliki akun.</p>
                <p class="text-xs text-indigo-600 mt-1">Pastikan siswa memiliki jabatan 'Sekretaris' di Data Siswa jika ingin digenerate.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeGenerateSiswaModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Tutup</button>
            </div>
        @else
            <div class="mb-5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-bold text-slate-700 uppercase">Daftar Sekretaris Siap Generate ({{ $sekreTanpaAkunCount }})</span>
                </div>
                <div class="max-h-48 overflow-y-auto divide-y divide-slate-100 border border-slate-200 rounded-xl bg-white text-xs">
                    @foreach($sekreBelumPunyaAkun as $s)
                        @php
                            $words = preg_split('/[\s,]+/', trim($s->nama));
                            $firstWord = $words[0] ?? 'sekre';
                            $namaDepan = \Illuminate\Support\Str::slug($firstWord, '');
                            if (empty($namaDepan)) $namaDepan = 'sekre';
                            $nisnClean = preg_replace('/[^0-9]/', '', (string)$s->nisn);
                            if (strlen($nisnClean) >= 2) {
                                $duaDigit = substr($nisnClean, -2);
                            } else {
                                $nisClean = preg_replace('/[^0-9]/', '', (string)$s->nis);
                                $duaDigit = strlen($nisClean) >= 2 ? substr($nisClean, -2) : sprintf('%02d', $s->id % 100);
                            }
                            $usernamePreview = strtolower($namaDepan . $duaDigit);
                        @endphp
                        <div class="p-3 flex justify-between items-center hover:bg-slate-50">
                            <div>
                                <div class="font-bold text-slate-800">{{ $s->nama }}</div>
                                <div class="text-slate-400 text-[11px]">{{ $s->kelas ? $s->kelas->nama : '-' }} &bull; NISN: {{ $s->nisn ?? '-' }}</div>
                            </div>
                            <div class="text-right">
                                <span class="bg-indigo-50 text-indigo-700 font-mono px-2 py-0.5 rounded text-[11px] font-semibold">{{ $usernamePreview }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form method="POST" action="{{ route('admin.user.generate-siswa') }}">
                @csrf
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeGenerateSiswaModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm flex items-center space-x-2">
                        <i class="fa-solid fa-bolt"></i>
                        <span>Generate {{ $sekreTanpaAkunCount }} Akun Siswa</span>
                    </button>
                </div>
            </form>
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
                            <option value="{{ $g->id }}" data-nama="{{ $g->nama }}" data-nip="{{ $g->nip }}">{{ $g->nama }} (NIP: {{ $g->nip ?? '-' }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div id="containerNamaSekre" class="hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Siswa Sekretaris (Opsional)</label>
                <select id="tambahSekreSelect" name="siswa_id" onchange="pilihSekreOtomatis(this)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    @if($sekreBelumPunyaAkun->isEmpty())
                        <option value="">Semua sekretaris sudah memiliki akun (ketik nama manual di bawah)</option>
                    @else
                        <option value="">-- Pilih Siswa Sekretaris --</option>
                        @foreach($sekreBelumPunyaAkun as $s)
                            <option value="{{ $s->id }}" data-nama="{{ $s->nama }}" data-nisn="{{ $s->nisn }}">{{ $s->nama }} ({{ $s->kelas ? $s->kelas->nama : '-' }} - NISN: {{ $s->nisn ?? '-' }})</option>
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
                <input type="text" id="tambahUsername" name="username" placeholder="Contoh: uli, admin, staf1" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
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
function openGenerateGuruModal() {
    document.getElementById('generateGuruModal').classList.remove('hidden');
}
function closeGenerateGuruModal() {
    document.getElementById('generateGuruModal').classList.add('hidden');
}
function openGenerateSiswaModal() {
    document.getElementById('generateSiswaModal').classList.remove('hidden');
}
function closeGenerateSiswaModal() {
    document.getElementById('generateSiswaModal').classList.add('hidden');
}
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
    const containerSekre = document.getElementById('containerNamaSekre');
    const selectSekre = document.getElementById('tambahSekreSelect');
    const containerBiasa = document.getElementById('containerNamaBiasa');
    const inputBiasa = document.getElementById('tambahNamaInput');

    if (role === 'guru') {
        containerGuru.classList.remove('hidden');
        selectGuru.required = true;
        selectGuru.disabled = false;

        containerSekre.classList.add('hidden');
        selectSekre.disabled = true;

        containerBiasa.classList.add('hidden');
        inputBiasa.required = false;
        inputBiasa.disabled = true;
    } else if (role === 'sekretaris') {
        containerGuru.classList.add('hidden');
        selectGuru.required = false;
        selectGuru.disabled = true;

        containerSekre.classList.remove('hidden');
        selectSekre.disabled = false;

        containerBiasa.classList.remove('hidden');
        inputBiasa.required = false;
        inputBiasa.disabled = false;
    } else {
        containerGuru.classList.add('hidden');
        selectGuru.required = false;
        selectGuru.disabled = true;

        containerSekre.classList.add('hidden');
        selectSekre.disabled = true;

        containerBiasa.classList.remove('hidden');
        inputBiasa.required = true;
        inputBiasa.disabled = false;
    }
}
function pilihGuruOtomatis(select) {
    const option = select.options[select.selectedIndex];
    if (!option) return;
    const nama = option.getAttribute('data-nama');
    const nip = option.getAttribute('data-nip') || '';
    const usernameInput = document.getElementById('tambahUsername');
    if (nama) {
        const clean = nama.replace(/^(drs?|dra|ir|prof|h|hj)\.?\s+/i, '').split(',')[0].trim().split(' ')[0].toLowerCase().replace(/[^a-z0-9]/g, '');
        const nipDigits = nip.replace(/[^0-9]/g, '');
        const prefixNip = nipDigits.length >= 4 ? nipDigits.substring(0, 4) : '';
        usernameInput.value = clean + prefixNip;
    }
}
function pilihSekreOtomatis(select) {
    const option = select.options[select.selectedIndex];
    if (!option) return;
    const nama = option.getAttribute('data-nama');
    const nisn = option.getAttribute('data-nisn') || '';
    const namaInput = document.getElementById('tambahNamaInput');
    const usernameInput = document.getElementById('tambahUsername');
    if (nama) {
        namaInput.value = nama;
        const clean = nama.split(',')[0].trim().split(' ')[0].toLowerCase().replace(/[^a-z0-9]/g, '');
        const nisnDigits = nisn.replace(/[^0-9]/g, '');
        const suffixNisn = nisnDigits.length >= 2 ? nisnDigits.slice(-2) : '';
        usernameInput.value = clean + suffixNisn;
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


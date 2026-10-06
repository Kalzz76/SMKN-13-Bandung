@extends('layouts.admin', ['title' => 'Data Siswa - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Data Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Buku induk peserta didik, nomor induk siswa, dan pembagian kelas.</p>
        </div>
        <div class="flex items-center gap-2 sm:gap-2.5 flex-nowrap shrink-0 overflow-x-auto max-w-full pb-1 sm:pb-0">
            <button type="button" onclick="openBulkDeleteModal()" class="whitespace-nowrap bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold px-3.5 sm:px-4 py-2.5 rounded-xl border border-rose-200 shadow-sm transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-trash-can text-rose-600"></i>
                <span>Hapus Massal</span>
            </button>
            <button type="button" onclick="openImportModal()" class="whitespace-nowrap bg-white hover:bg-slate-50 text-slate-700 font-semibold px-3.5 sm:px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                <span>Import Excel</span>
            </button>
            <button type="button" onclick="openTambahModal()" class="whitespace-nowrap bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-3.5 sm:px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-xs sm:text-sm shrink-0">
                <i class="fa-solid fa-user-plus"></i>
                <span>Tambah Siswa</span>
            </button>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-64">
                <select name="id_kelas" onchange="this.form.submit()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ request('id_kelas') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari NIS, NISN, nama siswa, atau kelas..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-6 py-2.5 rounded-xl transition text-sm">
                Cari
            </button>
            @if(request('cari') || request('id_kelas'))
                <a href="{{ route('admin.siswa.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </form>

        @if($daftarSiswa->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="fa-solid fa-user-graduate text-4xl mb-3 text-slate-300"></i>
                <p class="text-sm">Belum ada data siswa yang tersimpan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm min-w-[620px]">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl whitespace-nowrap">No</th>
                            <th class="p-3.5 whitespace-nowrap">NIS / NISN</th>
                            <th class="p-3.5 whitespace-nowrap">Nama Lengkap</th>
                            <th class="p-3.5 whitespace-nowrap">L/P</th>
                            <th class="p-3.5 whitespace-nowrap">Kelas</th>
                            <th class="p-3.5 rounded-r-xl text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarSiswa as $index => $s)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-medium text-slate-400 whitespace-nowrap">{{ $daftarSiswa->firstItem() + $index }}</td>
                                <td class="p-3.5 font-mono text-slate-900 whitespace-nowrap"><span class="font-bold">{{ $s->nis }}</span><span class="text-slate-400 font-normal"> / </span><span class="text-slate-600">{{ $s->nisn ?? '-' }}</span></td>
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">{{ $s->nama }}</td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded whitespace-nowrap inline-block {{ $s->jenis_kelamin === 'L' ? 'bg-sky-100 text-sky-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap inline-block">
                                        {{ $s->kelas ? $s->kelas->nama : '-' }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $s->id }}"
                                            data-nis="{{ $s->nis }}"
                                            data-nisn="{{ $s->nisn }}"
                                            data-nama="{{ $s->nama }}"
                                            data-jk="{{ $s->jenis_kelamin }}"
                                            data-kelas="{{ $s->id_kelas }}"
                                            onclick="openEditModal(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.siswa.destroy', $s->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
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
                {{ $daftarSiswa->links() }}
            </div>
        @endif
    </div>
</div>

<div id="importModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeImportModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-excel"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">Import Data Siswa</h3>
                <p class="text-xs text-slate-500">Unggah data siswa massal menggunakan Excel (.xlsx/.xls) atau CSV</p>
            </div>
        </div>

        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3 mb-5">
            <div class="flex items-start space-x-2 text-xs text-slate-600">
                <i class="fa-solid fa-circle-info text-emerald-600 mt-0.5"></i>
                <div>
                    <span class="font-bold text-slate-800">Unduh Template Resmi:</span>
                    <p class="mt-0.5">Format file telah disesuaikan agar proses import berjalan lancar dan anti-gagal.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 pt-1">
                <a href="{{ route('admin.siswa.template') }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-3.5 py-2 rounded-xl text-xs flex items-center space-x-2 shadow-sm transition">
                    <i class="fa-solid fa-download"></i>
                    <span>Download Template (.XLSX)</span>
                </a>
                <a href="{{ route('admin.siswa.template', ['format' => 'csv']) }}" class="bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold px-3.5 py-2 rounded-xl text-xs flex items-center space-x-2 transition">
                    <i class="fa-solid fa-file-csv text-slate-500"></i>
                    <span>Download (.CSV)</span>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Berkas Excel / CSV <span class="text-rose-500">*</span></label>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 border border-slate-200 rounded-xl p-2 cursor-pointer" required>
                <p class="text-[11px] text-slate-400 mt-1">Mendukung format: .xlsx, .xls, atau .csv (Maks. 10MB)</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas Tujuan <span class="text-xs font-normal text-slate-400">(Opsional)</span></label>
                <select name="id_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Otomatis Deteksi (dari kolom atau nama berkas) --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Jika berkas tidak memiliki kolom kelas, sistem otomatis mendeteksi dari nama berkas (misal: <em>X RPL 1</em>) atau gunakan pilihan ini.</p>
            </div>

            <div class="text-[11px] text-slate-500 bg-slate-50/80 p-3 rounded-xl space-y-1">
                <div class="font-semibold text-slate-700">Ketentuan Import Cerdas:</div>
                <div>&bull; Kolom standar: <strong>NIS</strong>, <strong>NISN</strong>, <strong>Nama Lengkap</strong>, <strong>L/P</strong>, <strong>Kelas</strong>, <strong>Tahun Ajaran</strong>.</div>
                <div>&bull; Mendukung format kolom gabungan <strong>NIS / NISN</strong> (misal: <em>102419349 / 0089761823</em>).</div>
                <div>&bull; Jika NIS sudah ada di sistem, data siswa otomatis diperbarui tanpa error duplikat.</div>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Unggah & Impor Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Siswa Baru</h3>
        <form method="POST" action="{{ route('admin.siswa.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NIS <span class="text-rose-500">*</span></label>
                    <input type="text" name="nis" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Contoh: 100123" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" name="nisn" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Contoh: 0081234567" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" placeholder="Contoh: Rizky Pratama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select name="jenis_kelamin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas <span class="text-rose-500">*</span></label>
                    <select name="id_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Siswa</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Data Siswa</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NIS <span class="text-rose-500">*</span></label>
                    <input type="text" id="editNis" name="nis" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">NISN <span class="text-rose-500">*</span></label>
                    <input type="text" id="editNisn" name="nisn" inputmode="numeric" pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-mono" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap Siswa <span class="text-rose-500">*</span></label>
                <input type="text" id="editNama" name="nama" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select id="editJk" name="jenis_kelamin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas <span class="text-rose-500">*</span></label>
                    <select id="editKelas" name="id_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="bulkDeleteModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeBulkDeleteModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-900">Hapus Data Siswa Massal</h3>
                <p class="text-xs text-slate-500">Pilih lingkup data siswa yang ingin dihapus sekaligus</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.bulk-delete') }}" class="space-y-4" onsubmit="return confirmBulkDeleteSubmit(this)">
            @csrf
            @method('DELETE')

            <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Pilih Lingkup Penghapusan</label>
                
                <label class="flex items-start space-x-3 p-3 rounded-xl bg-white border border-slate-200 cursor-pointer hover:border-rose-300 transition">
                    <input type="radio" name="scope" value="per_kelas" checked onchange="toggleBulkScope(this.value)" class="mt-1 text-rose-600 focus:ring-rose-500">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-slate-800">Hapus Siswa Berdasarkan Kelas</div>
                        <p class="text-xs text-slate-500 mt-0.5">Hanya menghapus seluruh data siswa di kelas tertentu.</p>
                        <div id="bulkKelasContainer" class="mt-2.5">
                            <select name="id_kelas" id="bulkKelasSelect" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs sm:text-sm outline-none focus:ring-2 focus:ring-rose-500 bg-slate-50 font-semibold text-slate-700">
                                @foreach($daftarKelas as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }} ({{ $k->siswa_count }} siswa)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </label>

                <label class="flex items-start space-x-3 p-3 rounded-xl bg-white border border-slate-200 cursor-pointer hover:border-rose-300 transition">
                    <input type="radio" name="scope" value="semua" onchange="toggleBulkScope(this.value)" class="mt-1 text-rose-600 focus:ring-rose-500">
                    <div class="flex-1">
                        <div class="text-sm font-bold text-rose-700">Hapus Seluruh Data Siswa (Semua Kelas)</div>
                        <p class="text-xs text-slate-500 mt-0.5">Akan menghapus total seluruh data siswa ({{ $totalSiswa ?? 0 }} siswa).</p>
                    </div>
                </label>
            </div>

            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5 text-amber-900">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Peringatan Keamanan</span>
                </div>
                <p>Data siswa yang telah dihapus <strong>tidak dapat dipulihkan kembali</strong>.</p>
            </div>

            <div class="space-y-2 pt-1">
                <label class="flex items-center space-x-2.5 text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="hapus_absensi" value="1" checked class="rounded text-rose-600 focus:ring-rose-500">
                    <span>Sertakan hapus riwayat absensi siswa terkait jika ada</span>
                </label>

                <label class="flex items-start space-x-2.5 text-xs text-slate-900 font-semibold cursor-pointer p-2.5 rounded-xl bg-rose-50/70 border border-rose-200">
                    <input type="checkbox" name="konfirmasi" id="konfirmasiHapusBulk" value="1" required class="mt-0.5 rounded text-rose-600 focus:ring-rose-500" onchange="toggleBulkDeleteBtn(this)">
                    <span>Saya memahami bahwa tindakan ini permanen dan yakin ingin menghapus data siswa yang dipilih.</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="closeBulkDeleteModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" id="btnSubmitBulkDelete" disabled class="bg-rose-600 hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Hapus Data Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openBulkDeleteModal() {
    document.getElementById('bulkDeleteModal').classList.remove('hidden');
}
function closeBulkDeleteModal() {
    document.getElementById('bulkDeleteModal').classList.add('hidden');
}
function toggleBulkScope(val) {
    const container = document.getElementById('bulkKelasContainer');
    const select = document.getElementById('bulkKelasSelect');
    if (val === 'per_kelas') {
        container.classList.remove('hidden');
        select.disabled = false;
    } else {
        container.classList.add('hidden');
        select.disabled = true;
    }
}
function toggleBulkDeleteBtn(cb) {
    const btn = document.getElementById('btnSubmitBulkDelete');
    btn.disabled = !cb.checked;
}
function confirmBulkDeleteSubmit(form) {
    const scope = form.elements['scope'].value;
    const msg = scope === 'per_kelas' 
        ? 'Apakah Anda benar-benar yakin ingin menghapus SELURUH data siswa di kelas yang dipilih? Tindakan ini permanen!'
        : 'PERINGATAN: Anda akan menghapus SELURUH data siswa di SEMUA kelas! Lanjutkan penghapusan permanen?';
    return confirm(msg);
}
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}
function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}
function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
}
function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}
function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const nis = button.getAttribute('data-nis');
    const nisn = button.getAttribute('data-nisn');
    const nama = button.getAttribute('data-nama');
    const jk = button.getAttribute('data-jk');
    const kelas = button.getAttribute('data-kelas');

    document.getElementById('editForm').action = '/admin/siswa/' + id;
    document.getElementById('editNis').value = nis;
    document.getElementById('editNisn').value = nisn || '';
    document.getElementById('editNama').value = nama;
    document.getElementById('editJk').value = jk;
    document.getElementById('editKelas').value = kelas;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>
@endsection


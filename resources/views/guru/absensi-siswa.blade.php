@extends('layouts.guru', ['title' => 'Absensi Siswa ' . ($jadwal->kelas->nama ?? '') . ' - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <a href="{{ route('guru.dashboard', ['tab' => 'validasi']) }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-700 mb-2 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            <h2 class="text-xl font-black text-slate-900 flex items-center space-x-2">
                <span>Absensi Siswa & Jurnal Pembelajaran</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Catat presensi kehadiran peserta didik dan jurnal materi yang diajarkan pada sesi hari ini.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-emerald-100 text-emerald-800 border border-emerald-200">
                <i class="fa-solid fa-calendar-day mr-1.5"></i>
                {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($tanggalHariIni)->isoFormat('D MMMM Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kelas</span>
            <span class="text-lg font-black text-slate-900 mt-1 block">{{ $jadwal->kelas ? $jadwal->kelas->nama : '-' }}</span>
            <span class="text-xs text-slate-500">{{ $daftarSiswa->count() }} Total Siswa</span>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Mata Pelajaran</span>
            <span class="text-lg font-black text-slate-900 mt-1 block truncate">{{ $jadwal->mapel ? $jadwal->mapel->nama : '-' }}</span>
            <span class="text-xs text-slate-500 font-mono">{{ $jadwal->mapel ? $jadwal->mapel->kode : '-' }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Jam Ke</span>
            <span class="text-lg font-black text-slate-900 mt-1 block font-mono">Ke {{ $jadwal->jam_ke_mulai }} - {{ $jadwal->jam_ke_selesai }}</span>
            <span class="text-xs text-slate-500">{{ $jadwal->ruangan ? $jadwal->ruangan->nama : 'Ruang Umum' }}</span>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Status Pengisian</span>
            @if($absensiTersimpan->isNotEmpty())
                <span class="text-lg font-black text-emerald-700 mt-1 block flex items-center space-x-1.5">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                    <span>Tersimpan</span>
                </span>
                <span class="text-xs text-slate-500">Terakhir diperbarui hari ini</span>
            @else
                <span class="text-lg font-black text-amber-600 mt-1 block flex items-center space-x-1.5">
                    <i class="fa-solid fa-clock text-sm"></i>
                    <span>Belum Diisi</span>
                </span>
                <span class="text-xs text-slate-500">Silakan input presensi</span>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('guru.absensi-siswa.simpan', $jadwal->id) }}" class="space-y-6">
        @csrf

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-base flex items-center space-x-2">
                        <i class="fa-solid fa-users text-emerald-700"></i>
                        <span>Daftar Presensi Siswa</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pilih status kehadiran masing-masing siswa di bawah ini.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="setSemuaStatus('Hadir')" class="px-3 py-1.5 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Set Semua Hadir</span>
                    </button>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
                <span class="font-bold text-slate-700">Ringkasan Status:</span>
                <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-slate-700">
                    Total: <strong class="font-mono text-slate-900">{{ $daftarSiswa->count() }}</strong>
                </span>
                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-lg">
                    Hadir: <strong id="hitungHadir" class="font-mono">0</strong>
                </span>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 border border-blue-200 rounded-lg">
                    Sakit: <strong id="hitungSakit" class="font-mono">0</strong>
                </span>
                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 border border-amber-200 rounded-lg">
                    Izin: <strong id="hitungIzin" class="font-mono">0</strong>
                </span>
                <span class="px-2.5 py-1 bg-rose-100 text-rose-800 border border-rose-200 rounded-lg">
                    Alpa: <strong id="hitungAlpa" class="font-mono">0</strong>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-3 w-10 text-center">No</th>
                            <th class="py-3 px-3 w-28">NIS</th>
                            <th class="py-3 px-3">Nama Siswa</th>
                            <th class="py-3 px-3 w-80 text-center">Status Kehadiran</th>
                            <th class="py-3 px-3 w-48">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($daftarSiswa as $index => $siswa)
                            @php
                                $statusSiswa = $absensiTersimpan[$siswa->id]->status ?? 'Hadir';
                                $keteranganSiswa = $absensiTersimpan[$siswa->id]->keterangan ?? '';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3 px-3 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                                <td class="py-3 px-3 font-mono font-medium text-slate-600">{{ $siswa->nis }}</td>
                                <td class="py-3 px-3 font-semibold text-slate-900">
                                    {{ $siswa->nama }}
                                    @if($siswa->jenis_kelamin)
                                        <span class="text-[10px] font-normal text-slate-400 ml-1">({{ $siswa->jenis_kelamin }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <label class="cursor-pointer flex items-center space-x-1 px-2.5 py-1 rounded-lg border border-slate-200 has-[:checked]:bg-emerald-700 has-[:checked]:text-white has-[:checked]:border-emerald-700 transition">
                                            <input type="radio" name="status[{{ $siswa->id }}]" value="Hadir" {{ $statusSiswa === 'Hadir' ? 'checked' : '' }} onchange="perbaruiHitungan()" class="siswa-status-radio w-3.5 h-3.5 text-emerald-700 focus:ring-0">
                                            <span class="font-bold">H</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-1 px-2.5 py-1 rounded-lg border border-slate-200 has-[:checked]:bg-blue-600 has-[:checked]:text-white has-[:checked]:border-blue-600 transition">
                                            <input type="radio" name="status[{{ $siswa->id }}]" value="Sakit" {{ $statusSiswa === 'Sakit' ? 'checked' : '' }} onchange="perbaruiHitungan()" class="siswa-status-radio w-3.5 h-3.5 text-blue-600 focus:ring-0">
                                            <span class="font-bold">S</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-1 px-2.5 py-1 rounded-lg border border-slate-200 has-[:checked]:bg-amber-600 has-[:checked]:text-white has-[:checked]:border-amber-600 transition">
                                            <input type="radio" name="status[{{ $siswa->id }}]" value="Izin" {{ $statusSiswa === 'Izin' ? 'checked' : '' }} onchange="perbaruiHitungan()" class="siswa-status-radio w-3.5 h-3.5 text-amber-600 focus:ring-0">
                                            <span class="font-bold">I</span>
                                        </label>
                                        <label class="cursor-pointer flex items-center space-x-1 px-2.5 py-1 rounded-lg border border-slate-200 has-[:checked]:bg-rose-600 has-[:checked]:text-white has-[:checked]:border-rose-600 transition">
                                            <input type="radio" name="status[{{ $siswa->id }}]" value="Alpa" {{ $statusSiswa === 'Alpa' ? 'checked' : '' }} onchange="perbaruiHitungan()" class="siswa-status-radio w-3.5 h-3.5 text-rose-600 focus:ring-0">
                                            <span class="font-bold">A</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <input type="text" name="keterangan[{{ $siswa->id }}]" value="{{ $keteranganSiswa }}" placeholder="Ket (opsional)" class="w-full text-xs p-1.5 border border-slate-200 rounded-lg focus:border-emerald-600 focus:outline-none">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">
                                    Tidak ada data siswa yang terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center space-x-2 pb-3 border-b border-slate-100">
                <i class="fa-solid fa-book-open text-emerald-700"></i>
                <h3 class="font-bold text-slate-900 text-base">Jurnal Materi Pembelajaran</h3>
            </div>
            <div>
                <label for="materi" class="block text-xs font-bold text-slate-700 mb-1">
                    Uraian Materi / Pokok Bahasan Hari Ini
                </label>
                <textarea id="materi" name="materi" rows="4" placeholder="Tuliskan materi pembelajaran, capaian kompetensi, atau tugas yang diberikan pada sesi kelas ini..." class="w-full text-xs p-3 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none leading-relaxed">{{ old('materi', $jurnalTersimpan->materi ?? '') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan tersimpan ke dalam arsip Jurnal Kelas harian sekolah.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end items-center gap-3">
            <a href="{{ route('guru.dashboard', ['tab' => 'validasi']) }}" class="w-full sm:w-auto px-5 py-2.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition text-center">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-700 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs transition shadow flex items-center justify-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Absensi & Jurnal</span>
            </button>
        </div>
    </form>
</div>

<script>
function perbaruiHitungan() {
    let hadir = 0;
    let sakit = 0;
    let izin = 0;
    let alpa = 0;

    const checkedRadios = document.querySelectorAll('.siswa-status-radio:checked');
    checkedRadios.forEach(radio => {
        if (radio.value === 'Hadir') hadir++;
        else if (radio.value === 'Sakit') sakit++;
        else if (radio.value === 'Izin') izin++;
        else if (radio.value === 'Alpa') alpa++;
    });

    const elHadir = document.getElementById('hitungHadir');
    const elSakit = document.getElementById('hitungSakit');
    const elIzin = document.getElementById('hitungIzin');
    const elAlpa = document.getElementById('hitungAlpa');

    if (elHadir) elHadir.textContent = hadir;
    if (elSakit) elSakit.textContent = sakit;
    if (elIzin) elIzin.textContent = izin;
    if (elAlpa) elAlpa.textContent = alpa;
}

function setSemuaStatus(status) {
    const radios = document.querySelectorAll('.siswa-status-radio[value="' + status + '"]');
    radios.forEach(radio => {
        radio.checked = true;
    });
    perbaruiHitungan();
}

document.addEventListener('DOMContentLoaded', function() {
    perbaruiHitungan();
});
</script>
@endsection

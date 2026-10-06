@extends('layouts.guru', ['title' => 'Absensi Siswa ' . ($jadwal->kelas->nama ?? '') . ' - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Header & Info Kelas --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200">
        <div>
            <a href="{{ route('guru.jadwal') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-700 mb-2 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Jadwal</span>
            </a>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Absensi Siswa — {{ $jadwal->kelas ? $jadwal->kelas->nama : 'Kelas' }}
            </h2>
            <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                <span>{{ $jadwal->mapel ? $jadwal->mapel->nama : 'Mata Pelajaran' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-door-open text-slate-400 mr-1"></i>{{ $jadwal->ruangan ? $jadwal->ruangan->nama : 'Ruang Kelas' }}</span>
                <span>•</span>
                <span>Jam ke {{ $jadwal->jam_ke_mulai }} - {{ $jadwal->jam_ke_selesai }}</span>
            </p>
        </div>
        <div class="flex items-center self-start sm:self-auto">
            <span class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-emerald-50 text-emerald-800 border border-emerald-200">
                <i class="fa-solid fa-calendar-day mr-1.5 text-emerald-600"></i>
                {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('D MMMM Y') }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('guru.absensi-siswa.simpan', $jadwal->id) }}" id="formAbsensi" class="space-y-6">
        @csrf

        {{-- 1. Card Kehadiran Guru Pengajar (Persis sesuai referensi) --}}
        @php
            $guruHadirDefault = !($absensiGuruHariIni && $absensiGuruHariIni->status === 'Tidak Hadir');
        @endphp
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-4 sm:p-7 shadow-xs space-y-5">
            <div class="flex items-center space-x-2.5 text-slate-900">
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-regular fa-user"></i>
                </div>
                <h3 class="font-extrabold text-base text-slate-900 tracking-tight">Kehadiran Guru Pengajar</h3>
            </div>

            <input type="hidden" name="kehadiran_guru" id="inputKehadiranGuru" value="{{ $guruHadirDefault ? 'Hadir' : 'Tidak Hadir' }}">

            {{-- Toggle Button: Hadir vs Tidak Hadir --}}
            <div class="grid grid-cols-2 gap-2.5 sm:gap-4">
                <button type="button" onclick="setKehadiranGuru('Hadir')" id="btnGuruHadir" class="w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 {{ $guruHadirDefault ? 'border-emerald-500 bg-emerald-50/50 text-emerald-700 shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                    <i class="fa-solid fa-check text-sm sm:text-base text-emerald-600"></i>
                    <span>Hadir</span>
                </button>

                <button type="button" onclick="setKehadiranGuru('Tidak Hadir')" id="btnGuruTidakHadir" class="w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 {{ !$guruHadirDefault ? 'border-rose-500 bg-rose-50/50 text-rose-700 shadow-xs' : 'border-slate-200 bg-white text-slate-600 hover:border-rose-200 hover:text-rose-600' }}">
                    <i class="fa-solid fa-xmark text-sm sm:text-base text-rose-500"></i>
                    <span>Tidak Hadir</span>
                </button>
            </div>

            {{-- Bagian Alasan & Jam Tidak Hadir (Muncul jika Tidak Hadir dipilih) --}}
            <div id="sectionGuruTidakHadir" class="{{ $guruHadirDefault ? 'hidden' : '' }} space-y-4 pt-4 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Jam Ketidakhadiran Guru:</label>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($daftarSlotJam as $slot)
                            <div class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl font-bold text-xs bg-rose-50 text-rose-700 border border-rose-300 shadow-2xs">
                                <span>Jam {{ $slot }}</span>
                                <i class="fa-solid fa-xmark text-xs text-rose-500"></i>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan Tidak Hadir <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <select name="alasan_guru" id="alasanGuruSelect" class="w-full text-xs font-medium p-3.5 pr-10 border border-slate-200 rounded-xl bg-white focus:border-rose-500 focus:outline-none appearance-none">
                                <option value="">Pilih alasan</option>
                                <option value="Sakit" {{ ($absensiGuruHariIni->alasan ?? '') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Izin" {{ ($absensiGuruHariIni->alasan ?? '') === 'Izin' ? 'selected' : '' }}>Izin / Keperluan Mendesak</option>
                                <option value="Dinas Luar" {{ ($absensiGuruHariIni->alasan ?? '') === 'Dinas Luar' ? 'selected' : '' }}>Dinas Luar / Rapat</option>
                                <option value="Terlambat" {{ ($absensiGuruHariIni->alasan ?? '') === 'Terlambat' ? 'selected' : '' }}>Terlambat / Kendala Teknis</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-4 top-4 text-xs text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div>
                        <input type="text" name="keterangan_guru" placeholder="Keterangan tambahan (opsional)" class="w-full text-xs p-3.5 border border-slate-200 rounded-xl focus:border-rose-500 focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Daftar Siswa (Tabel Presensi dengan gaya Pill Buttons seperti referensi) --}}
        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            {{-- Header Table --}}
            <div class="hidden sm:flex px-6 py-4 bg-slate-50/70 border-b border-slate-200/80 items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Nama Siswa</span>
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 pr-2">
                    Status Kehadiran
                </span>
            </div>

            {{-- Ringkasan Status & Set Semua Hadir --}}
            <div class="p-3.5 sm:px-6 sm:py-3 bg-white border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                    <span class="font-bold text-slate-700 bg-slate-100 px-2.5 py-1 rounded-lg">Total: <strong class="ml-0.5">{{ $daftarSiswa->count() }}</strong></span>
                    <span class="text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-1 rounded-lg font-semibold">Hadir: <strong id="hitungHadir">0</strong></span>
                    <span class="text-amber-700 bg-amber-50 border border-amber-200/60 px-2 py-1 rounded-lg font-semibold">Izin: <strong id="hitungIzin">0</strong></span>
                    <span class="text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-1 rounded-lg font-semibold">Sakit: <strong id="hitungSakit">0</strong></span>
                    <span class="text-rose-700 bg-rose-50 border border-rose-200/60 px-2 py-1 rounded-lg font-semibold">Alpa: <strong id="hitungAlpa">0</strong></span>
                </div>
                <button type="button" onclick="setSemuaStatus('Hadir')" class="w-full sm:w-auto justify-center px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Set Semua Hadir</span>
                </button>
            </div>

            {{-- Baris Tiap Siswa --}}
            <div class="divide-y divide-slate-100">
                @forelse($daftarSiswa as $siswa)
                    @php
                        $statusSiswa = $absensiTersimpan[$siswa->id]->status ?? 'Hadir';
                        $initial = strtoupper(substr($siswa->nama, 0, 1));
                    @endphp
                    <div class="p-3.5 sm:px-6 sm:py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 hover:bg-slate-50/50 transition">
                        {{-- Avatar & Nama Siswa --}}
                        <div class="flex items-center space-x-3 min-w-0">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-extrabold flex items-center justify-center shrink-0 text-xs sm:text-sm shadow-2xs">
                                {{ $initial }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 leading-snug truncate">
                                    {{ $siswa->nama }}
                                </h4>
                                <div class="flex items-center space-x-2 text-[11px] sm:text-xs text-slate-400 font-mono mt-0.5">
                                    <span>{{ $siswa->nis }}</span>
                                    @if($siswa->jenis_kelamin)
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $siswa->jenis_kelamin }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 4 Pill Status Buttons: Hadir, Izin, Sakit, Alpa --}}
                        <div class="grid grid-cols-4 gap-1.5 w-full sm:w-auto sm:flex sm:items-center sm:space-x-2 shrink-0">
                            {{-- Input radio tersembunyi --}}
                            <input type="hidden" name="status[{{ $siswa->id }}]" id="statusInput_{{ $siswa->id }}" value="{{ $statusSiswa }}" class="siswa-status-hidden">

                            {{-- Button Hadir --}}
                            <button type="button" onclick="setStatusSiswa({{ $siswa->id }}, 'Hadir')" id="btn_{{ $siswa->id }}_Hadir" class="btn-status-siswa py-2 px-1 sm:px-4 rounded-xl text-xs font-bold transition text-center flex items-center justify-center {{ $statusSiswa === 'Hadir' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                                Hadir
                            </button>

                            {{-- Button Izin --}}
                            <button type="button" onclick="setStatusSiswa({{ $siswa->id }}, 'Izin')" id="btn_{{ $siswa->id }}_Izin" class="btn-status-siswa py-2 px-1 sm:px-4 rounded-xl text-xs font-bold transition text-center flex items-center justify-center {{ $statusSiswa === 'Izin' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                                Izin
                            </button>

                            {{-- Button Sakit --}}
                            <button type="button" onclick="setStatusSiswa({{ $siswa->id }}, 'Sakit')" id="btn_{{ $siswa->id }}_Sakit" class="btn-status-siswa py-2 px-1 sm:px-4 rounded-xl text-xs font-bold transition text-center flex items-center justify-center {{ $statusSiswa === 'Sakit' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                                Sakit
                            </button>

                            {{-- Button Alpa --}}
                            <button type="button" onclick="setStatusSiswa({{ $siswa->id }}, 'Alpa')" id="btn_{{ $siswa->id }}_Alpa" class="btn-status-siswa py-2 px-1 sm:px-4 rounded-xl text-xs font-bold transition text-center flex items-center justify-center {{ $statusSiswa === 'Alpa' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300' }}">
                                Alpa
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400">
                        <i class="fa-solid fa-user-slash text-3xl mb-2 text-slate-300"></i>
                        <p class="text-sm font-medium">Tidak ada data siswa yang terdaftar di kelas ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tombol Aksi Submit --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end items-center gap-3 pt-2">
            <a href="{{ route('guru.jadwal') }}" class="w-full sm:w-auto px-6 py-3.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-2xl text-xs transition text-center">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold rounded-2xl text-xs transition shadow-md flex items-center justify-center space-x-2">
                <i class="fa-solid fa-floppy-disk text-sm"></i>
                <span>Simpan Absensi</span>
            </button>
        </div>
    </form>
</div>

<script>
// Logic Toggle Kehadiran Guru
function setKehadiranGuru(status) {
    const input = document.getElementById('inputKehadiranGuru');
    const btnHadir = document.getElementById('btnGuruHadir');
    const btnTidakHadir = document.getElementById('btnGuruTidakHadir');
    const sectionDetail = document.getElementById('sectionGuruTidakHadir');

    input.value = status;

    if (status === 'Hadir') {
        btnHadir.className = 'w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 border-emerald-500 bg-emerald-50/50 text-emerald-700 shadow-xs';
        btnTidakHadir.className = 'w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 border-slate-200 bg-white text-slate-600 hover:border-rose-200 hover:text-rose-600';
        if (sectionDetail) sectionDetail.classList.add('hidden');
    } else {
        btnHadir.className = 'w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 border-slate-200 bg-white text-slate-600 hover:border-slate-300';
        btnTidakHadir.className = 'w-full py-3 sm:py-3.5 px-3 sm:px-6 rounded-2xl border-2 font-bold text-xs sm:text-sm transition flex items-center justify-center space-x-1.5 sm:space-x-2 border-rose-500 bg-rose-50/50 text-rose-700 shadow-xs';
        if (sectionDetail) sectionDetail.classList.remove('hidden');
    }
}



// Logic Status Tiap Siswa
const activeClasses = {
    'Hadir': 'bg-emerald-600 text-white shadow-xs',
    'Izin': 'bg-amber-500 text-white shadow-xs',
    'Sakit': 'bg-blue-600 text-white shadow-xs',
    'Alpa': 'bg-rose-600 text-white shadow-xs'
};
const inactiveClass = 'bg-white border border-slate-200 text-slate-600 hover:border-slate-300';

function setStatusSiswa(siswaId, status) {
    const hiddenInput = document.getElementById('statusInput_' + siswaId);
    if (hiddenInput) hiddenInput.value = status;

    ['Hadir', 'Izin', 'Sakit', 'Alpa'].forEach(st => {
        const btn = document.getElementById('btn_' + siswaId + '_' + st);
        if (btn) {
            btn.className = 'btn-status-siswa py-2 px-1 sm:px-4 rounded-xl text-xs font-bold transition text-center flex items-center justify-center ' + (st === status ? activeClasses[st] : inactiveClass);
        }
    });

    perbaruiHitungan();
}

    perbaruiHitungan();
}

function setSemuaStatus(status) {
    const inputs = document.querySelectorAll('.siswa-status-hidden');
    inputs.forEach(input => {
        const id = input.id.replace('statusInput_', '');
        setStatusSiswa(id, status);
    });
}

function perbaruiHitungan() {
    let hadir = 0, izin = 0, sakit = 0, alpa = 0;
    const inputs = document.querySelectorAll('.siswa-status-hidden');
    inputs.forEach(input => {
        if (input.value === 'Hadir') hadir++;
        else if (input.value === 'Izin') izin++;
        else if (input.value === 'Sakit') sakit++;
        else if (input.value === 'Alpa') alpa++;
    });

    const elHadir = document.getElementById('hitungHadir');
    const elIzin = document.getElementById('hitungIzin');
    const elSakit = document.getElementById('hitungSakit');
    const elAlpa = document.getElementById('hitungAlpa');

    if (elHadir) elHadir.textContent = hadir;
    if (elIzin) elIzin.textContent = izin;
    if (elSakit) elSakit.textContent = sakit;
    if (elAlpa) elAlpa.textContent = alpa;
}

document.addEventListener('DOMContentLoaded', function() {
    perbaruiHitungan();
});
</script>
@endsection

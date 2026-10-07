@extends('layouts.sekretaris', ['title' => 'Absensi Siswa ' . ($jadwal->kelas->nama ?? '') . ' - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    {{-- Header & Info Kelas --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200">
        <div>
            <a href="{{ route('sekretaris.dashboard', ['tab' => 'jadwal']) }}" class="inline-flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-700 mb-2 transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Jadwal</span>
            </a>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                Absensi Siswa — {{ $jadwal->kelas ? $jadwal->kelas->nama : 'Kelas' }}
            </h2>
            <p class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-2">
                <span>{{ $jadwal->mapel ? $jadwal->mapel->nama : 'Mata Pelajaran' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-chalkboard-user text-slate-400 mr-1"></i>{{ $jadwal->guru ? $jadwal->guru->nama : 'Pengajar' }}</span>
                <span>•</span>
                <span><i class="fa-solid fa-door-open text-slate-400 mr-1"></i>{{ $jadwal->ruangan ? $jadwal->ruangan->nama : 'Ruang Kelas' }}</span>
                <span>•</span>
                <span><i class="fa-regular fa-clock text-slate-400 mr-1"></i>{{ $jamKeLabel ?? ($jadwal->jam_ke_mulai == $jadwal->jam_ke_selesai ? 'Jam ke ' . $jadwal->jam_ke_mulai : 'Jam ke ' . $jadwal->jam_ke_mulai . ' - ' . $jadwal->jam_ke_selesai) }} @if(!empty($rentangWaktu)) ({{ $rentangWaktu }}) @endif</span>
            </p>
        </div>
        <div class="flex items-center self-start sm:self-auto">
            <span class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-emerald-50 text-emerald-800 border border-emerald-200">
                <i class="fa-solid fa-calendar-day mr-1.5 text-emerald-600"></i>
                {{ $jadwal->hari }}, {{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('D MMMM Y') }}
            </span>
        </div>
    </div>

    <form method="POST" action="{{ route('sekretaris.absensi-siswa.simpan', $jadwal->id) }}" id="formAbsensi" class="space-y-6">
        @csrf



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
            <a href="{{ route('sekretaris.dashboard', ['tab' => 'jadwal']) }}" class="w-full sm:w-auto px-6 py-3.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold rounded-2xl text-xs transition text-center">
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

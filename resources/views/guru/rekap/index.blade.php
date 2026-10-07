@extends('layouts.guru', ['title' => 'Rekap Absensi - Portal Guru SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Rekap Absensi</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau performa dan riwayat kehadiran siswa pada mata pelajaran Anda serta riwayat kehadiran mandiri.</p>
        </div>
        <button type="button" onclick="window.print()" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition cursor-pointer">
            <i class="fa-solid fa-print"></i>
            <span>Cetak Rekap</span>
        </button>
    </div>

    <div class="inline-flex rounded-xl bg-white border border-slate-200 p-1 text-xs shadow-2xs">
        <button type="button" onclick="switchSubRekap('siswa')" id="btnSubRekap_siswa" class="px-4 py-2 rounded-lg font-bold bg-emerald-50 text-emerald-800 transition flex items-center space-x-2">
            <i class="fa-solid fa-users"></i>
            <span>Absensi Siswa</span>
        </button>
        <button type="button" onclick="switchSubRekap('saya')" id="btnSubRekap_saya" class="px-4 py-2 rounded-lg font-semibold text-slate-500 hover:text-slate-900 transition flex items-center space-x-2">
            <i class="fa-regular fa-calendar-check"></i>
            <span>Kehadiran Saya</span>
        </button>
    </div>

    <div id="subRekapView_siswa" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center space-x-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl border border-emerald-100">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Total Absensi</span>
                    <span class="text-2xl font-black text-slate-900 block mt-0.5">{{ $totalAbsensiSiswa ?? 0 }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center space-x-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center text-xl border border-teal-100">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Siswa Hadir</span>
                    <span class="text-2xl font-black text-slate-900 block mt-0.5">{{ $totalHadirSiswa ?? 0 }}</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex items-center space-x-4 shadow-xs">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl border border-amber-100">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-semibold block uppercase">Rasio Kehadiran</span>
                    <span class="text-2xl font-black text-slate-900 block mt-0.5">{{ $rasioKehadiranSiswa ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('guru.rekap') }}" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4">
            <input type="hidden" name="tab" value="rekap">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Rentang Waktu</label>
                    <select name="rentang" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="1 Bulan" {{ ($rentang ?? '') === '1 Bulan' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        <option value="1 Semester" {{ ($rentang ?? '') === '1 Semester' ? 'selected' : '' }}>1 Semester (6 Bulan)</option>
                        <option value="1 Tahun" {{ ($rentang ?? '') === '1 Tahun' ? 'selected' : '' }}>1 Tahun</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kelas</label>
                    <select name="id_kelas" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="">Semua Kelas</option>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" {{ ($idKelasPilihan ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mata Pelajaran</label>
                    <select name="id_mapel" onchange="this.form.submit()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="">Semua Mapel</option>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}" {{ ($idMapelPilihan ?? '') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        @if(!isset($daftarRekapSiswa) || $daftarRekapSiswa->isEmpty())
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-sm shadow-xs">
                Belum ada catatan presensi siswa pada rentang waktu ini.
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3.5">Tanggal</th>
                                <th class="p-3.5">Kelas</th>
                                <th class="p-3.5">Mata Pelajaran</th>
                                <th class="p-3.5 text-center">Hadir</th>
                                <th class="p-3.5 text-center">Sakit</th>
                                <th class="p-3.5 text-center">Izin</th>
                                <th class="p-3.5 text-center">Alpa</th>
                                <th class="p-3.5 text-center">Total Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($daftarRekapSiswa as $rs)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3.5 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($rs['tanggal'])->locale('id')->isoFormat('dddd, D MMM Y') }}</td>
                                    <td class="p-3.5 font-bold text-slate-900">{{ $rs['kelas'] }}</td>
                                    <td class="p-3.5 font-semibold text-emerald-700">{{ $rs['mapel'] }}</td>
                                    <td class="p-3.5 text-center font-bold text-emerald-600">{{ $rs['hadir'] }}</td>
                                    <td class="p-3.5 text-center font-bold text-sky-600">{{ $rs['sakit'] }}</td>
                                    <td class="p-3.5 text-center font-bold text-amber-600">{{ $rs['izin'] }}</td>
                                    <td class="p-3.5 text-center font-bold text-rose-600">{{ $rs['alpa'] }}</td>
                                    <td class="p-3.5 text-center font-bold text-slate-800">{{ $rs['total'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div id="subRekapView_saya" class="space-y-6 hidden">
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Riwayat Kehadiran Saya</h4>
                    <p class="text-xs text-slate-500">Catatan riwayat scan barcode mandiri dan kehadiran tercatat.</p>
                </div>
                <span class="text-xs text-slate-500 font-mono">Total data: {{ $rekapAbsensi ? $rekapAbsensi->total() : 0 }}</span>
            </div>

            <form method="GET" action="{{ route('guru.rekap') }}" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs items-end">
                <input type="hidden" name="sub" value="saya">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Bulan</label>
                    <select name="bulan" onchange="this.form.submit()" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="">Semua Bulan</option>
                        @for($b = 1; $b <= 12; $b++)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($b)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tahun</label>
                    <select name="tahun" onchange="this.form.submit()" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="">Semua Tahun</option>
                        @foreach(range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 4) as $y)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl transition text-center shadow-xs cursor-pointer">
                        <i class="fa-solid fa-filter text-[11px] mr-1"></i> Terapkan
                    </button>
                    <a href="{{ route('guru.rekap', ['sub' => 'saya']) }}" class="py-2 px-3 bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold rounded-xl transition text-center">
                        Reset
                    </a>
                </div>
            </form>

            @if(!$rekapAbsensi || $rekapAbsensi->isEmpty())
                <div class="p-12 text-center text-slate-400 text-sm">Belum ada riwayat absensi guru.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                            <tr>
                                <th class="p-3.5 rounded-l-xl">Tanggal</th>
                                <th class="p-3.5">Jam Masuk</th>
                                <th class="p-3.5">Metode</th>
                                <th class="p-3.5 rounded-r-xl">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rekapAbsensi as $ra)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3.5 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($ra->tanggal)->isoFormat('dddd, D MMMM Y') }}</td>
                                    <td class="p-3.5 font-mono text-xs text-slate-600">{{ $ra->jam_masuk ?? '-' }}</td>
                                    <td class="p-3.5 text-xs">
                                        <span class="bg-slate-100 px-2.5 py-1 rounded-md text-slate-700 font-semibold">{{ $ra->metode }}</span>
                                    </td>
                                    <td class="p-3.5">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $ra->status === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $ra->status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-4">
                    {{ $rekapAbsensi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function switchSubRekap(sub) {
    const btnSiswa = document.getElementById('btnSubRekap_siswa');
    const btnSaya = document.getElementById('btnSubRekap_saya');
    const viewSiswa = document.getElementById('subRekapView_siswa');
    const viewSaya = document.getElementById('subRekapView_saya');

    if (sub === 'siswa') {
        if (viewSiswa) viewSiswa.classList.remove('hidden');
        if (viewSaya) viewSaya.classList.add('hidden');
        if (btnSiswa) btnSiswa.className = 'px-4 py-2 rounded-lg font-bold bg-emerald-50 text-emerald-800 transition flex items-center space-x-2';
        if (btnSaya) btnSaya.className = 'px-4 py-2 rounded-lg font-semibold text-slate-500 hover:text-slate-900 transition flex items-center space-x-2';
    } else {
        if (viewSiswa) viewSiswa.classList.add('hidden');
        if (viewSaya) viewSaya.classList.remove('hidden');
        if (btnSaya) btnSaya.className = 'px-4 py-2 rounded-lg font-bold bg-emerald-50 text-emerald-800 transition flex items-center space-x-2';
        if (btnSiswa) btnSiswa.className = 'px-4 py-2 rounded-lg font-semibold text-slate-500 hover:text-slate-900 transition flex items-center space-x-2';
    }

    try {
        const url = new URL(window.location);
        url.searchParams.set('sub', sub);
        window.history.replaceState({}, '', url);
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    if (params.get('sub') === 'saya') {
        switchSubRekap('saya');
    }
});
</script>
@endsection

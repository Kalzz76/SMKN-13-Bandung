@extends('layouts.admin', ['title' => 'Jadwal Pelajaran - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Jadwal Pelajaran Sekolah</h1>
            <p class="text-sm text-slate-500 mt-1">Matriks jadwal harian (Kelas di kiri, Jam Ke & Waktu di atas)</p>
        </div>
        <button type="button" onclick="openTambahModal()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Jadwal</span>
        </button>
    </div>

    <div class="flex items-center space-x-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto">
        @foreach($daftarHari as $h)
            <a href="{{ route('admin.jadwal.index', ['hari' => $h]) }}" class="px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center space-x-2 whitespace-nowrap {{ $hariTerpilih === $h ? 'bg-emerald-700 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-calendar-day"></i>
                <span>{{ $h }}</span>
            </a>
        @endforeach
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h3 class="font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-table-cells text-emerald-700"></i>
                    <span>Matriks Jadwal Pelajaran - Hari {{ $hariTerpilih }}</span>
                </h3>
                <span class="text-xs text-slate-400">Klik sel jadwal untuk mengedit, atau klik sel kosong di matriks ruangan untuk menjadwalkan</span>
            </div>
            <div class="flex items-center space-x-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
                <button type="button" id="tabBtnKelas" onclick="switchMatriksView('kelas')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-emerald-700 text-white shadow">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Matriks Kelas</span>
                </button>
                <button type="button" id="tabBtnRuang" onclick="switchMatriksView('ruang')" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 text-slate-600 hover:bg-slate-200">
                    <i class="fa-solid fa-door-open"></i>
                    <span>Matriks Ruangan (PDF)</span>
                </button>
            </div>
        </div>

        <div id="viewMatriksKelas" class="overflow-x-auto border border-slate-200 rounded-2xl">
            <table class="w-full text-center text-xs border-collapse min-w-[950px]">
                <thead>
                    <tr>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 w-28">KELAS</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 w-20">JAM KE</th>
                        <th class="p-2.5 bg-yellow-300 text-slate-900 font-black border border-yellow-400 w-28">CARABIKA</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 1</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 2</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 3</th>
                        <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 w-24">ISTIRAHAT</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 4</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 5</th>
                        <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 w-24">ISTIRAHAT</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 6</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 7</th>
                    </tr>
                    <tr class="text-[11px] text-slate-600 bg-slate-50">
                        <th class="p-1 border border-slate-200 font-medium">06.30 - 07.30</th>
                        <th class="p-1 border border-slate-200 font-medium">07.30 - 08.15</th>
                        <th class="p-1 border border-slate-200 font-medium">08.15 - 09.00</th>
                        <th class="p-1 border border-slate-200 font-medium">09.00 - 09.45</th>
                        <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700">09.45 - 10.00</th>
                        <th class="p-1 border border-slate-200 font-medium">10.00 - 10.45</th>
                        <th class="p-1 border border-slate-200 font-medium">10.45 - 11.30</th>
                        <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700">11.30 - 12.30</th>
                        <th class="p-1 border border-slate-200 font-medium">12.30 - 13.15</th>
                        <th class="p-1 border border-slate-200 font-medium">13.15 - 14.00</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarKelas as $k)
                        <tr>
                            <td rowspan="3" class="p-3 font-black text-slate-900 bg-slate-50 border border-slate-200 whitespace-nowrap">
                                {{ $k->nama }}
                            </td>
                            <td class="p-2 font-bold text-slate-500 bg-slate-100/70 border border-slate-200 text-[10px]">
                                MAPEL
                            </td>
                            <td rowspan="3" class="p-2 bg-yellow-100/70 text-amber-900 font-bold border border-slate-200 text-xs">
                                {{ $hariTerpilih == 'Senin' ? 'UPACARA' : 'PERWALIAN' }}
                            </td>

                            @php $skip1_1 = 0; @endphp
                            @foreach([1, 2, 3] as $jam)
                                @if($jam <= $skip1_1) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip1_1 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $jdw->id }}"
                                        data-hari="{{ $jdw->hari }}"
                                        data-kelas="{{ $jdw->id_kelas }}"
                                        data-mapel="{{ $jdw->id_mapel }}"
                                        data-ruang="{{ $jdw->id_ruangan }}"
                                        data-guru="{{ $jdw->id_guru }}"
                                        data-mulai="{{ $jdw->jam_ke_mulai }}"
                                        data-selesai="{{ $jdw->jam_ke_selesai }}"
                                        class="p-2 font-bold bg-emerald-100 text-emerald-900 border border-emerald-200 hover:bg-emerald-200 cursor-pointer transition text-xs" title="Klik untuk edit">
                                        {{ $jdw->mapel ? $jdw->mapel->nama : '-' }}
                                    </td>
                                @else
                                    <td class="p-2 text-slate-400 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            <td rowspan="3" class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]">
                                ISTIRAHAT
                            </td>

                            @php $skip1_2 = 0; @endphp
                            @foreach([4, 5] as $jam)
                                @if($jam <= $skip1_2) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip1_2 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $jdw->id }}"
                                        data-hari="{{ $jdw->hari }}"
                                        data-kelas="{{ $jdw->id_kelas }}"
                                        data-mapel="{{ $jdw->id_mapel }}"
                                        data-ruang="{{ $jdw->id_ruangan }}"
                                        data-guru="{{ $jdw->id_guru }}"
                                        data-mulai="{{ $jdw->jam_ke_mulai }}"
                                        data-selesai="{{ $jdw->jam_ke_selesai }}"
                                        class="p-2 font-bold bg-emerald-100 text-emerald-900 border border-emerald-200 hover:bg-emerald-200 cursor-pointer transition text-xs" title="Klik untuk edit">
                                        {{ $jdw->mapel ? $jdw->mapel->nama : '-' }}
                                    </td>
                                @else
                                    <td class="p-2 text-slate-400 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            <td rowspan="3" class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]">
                                ISTIRAHAT
                            </td>

                            @php $skip1_3 = 0; @endphp
                            @foreach([6, 7] as $jam)
                                @if($jam <= $skip1_3) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip1_3 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $jdw->id }}"
                                        data-hari="{{ $jdw->hari }}"
                                        data-kelas="{{ $jdw->id_kelas }}"
                                        data-mapel="{{ $jdw->id_mapel }}"
                                        data-ruang="{{ $jdw->id_ruangan }}"
                                        data-guru="{{ $jdw->id_guru }}"
                                        data-mulai="{{ $jdw->jam_ke_mulai }}"
                                        data-selesai="{{ $jdw->jam_ke_selesai }}"
                                        class="p-2 font-bold bg-emerald-100 text-emerald-900 border border-emerald-200 hover:bg-emerald-200 cursor-pointer transition text-xs" title="Klik untuk edit">
                                        {{ $jdw->mapel ? $jdw->mapel->nama : '-' }}
                                    </td>
                                @else
                                    <td class="p-2 text-slate-400 border border-slate-200">-</td>
                                @endif
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-2 font-bold text-slate-500 bg-slate-100/70 border border-slate-200 text-[10px]">
                                RUANG
                            </td>

                            @php $skip2_1 = 0; @endphp
                            @foreach([1, 2, 3] as $jam)
                                @if($jam <= $skip2_1) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip2_1 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 font-semibold text-slate-600 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $jdw->ruangan ? $jdw->ruangan->kode : '-' }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            @php $skip2_2 = 0; @endphp
                            @foreach([4, 5] as $jam)
                                @if($jam <= $skip2_2) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip2_2 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 font-semibold text-slate-600 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $jdw->ruangan ? $jdw->ruangan->kode : '-' }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            @php $skip2_3 = 0; @endphp
                            @foreach([6, 7] as $jam)
                                @if($jam <= $skip2_3) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip2_3 = $jdw->jam_ke_selesai;
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 font-semibold text-slate-600 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $jdw->ruangan ? $jdw->ruangan->kode : '-' }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach
                        </tr>

                        <tr>
                            <td class="p-2 font-bold text-slate-500 bg-slate-100/70 border border-slate-200 text-[10px]">
                                GURU
                            </td>

                            @php $skip3_1 = 0; @endphp
                            @foreach([1, 2, 3] as $jam)
                                @if($jam <= $skip3_1) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip3_1 = $jdw->jam_ke_selesai;
                                        $namaGuru = $jdw->guru ? explode(',', $jdw->guru->nama)[0] : '-';
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 text-slate-700 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $namaGuru }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            @php $skip3_2 = 0; @endphp
                            @foreach([4, 5] as $jam)
                                @if($jam <= $skip3_2) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip3_2 = $jdw->jam_ke_selesai;
                                        $namaGuru = $jdw->guru ? explode(',', $jdw->guru->nama)[0] : '-';
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 text-slate-700 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $namaGuru }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach

                            @php $skip3_3 = 0; @endphp
                            @foreach([6, 7] as $jam)
                                @if($jam <= $skip3_3) @continue @endif
                                @php
                                    $jdw = $daftarJadwal->first(fn($i) => $i->id_kelas == $k->id && $i->jam_ke_mulai == $jam);
                                @endphp
                                @if($jdw)
                                    @php
                                        $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                        $skip3_3 = $jdw->jam_ke_selesai;
                                        $namaGuru = $jdw->guru ? explode(',', $jdw->guru->nama)[0] : '-';
                                    @endphp
                                    <td colspan="{{ $span }}" class="p-1 text-slate-700 bg-slate-50 border border-slate-200 text-[11px]">
                                        {{ $namaGuru }}
                                    </td>
                                @else
                                    <td class="p-1 text-slate-300 border border-slate-200">-</td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="p-8 text-center text-slate-400">Belum ada data kelas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="viewMatriksRuangan" class="hidden overflow-x-auto border border-slate-200 rounded-2xl">
            <table class="w-full text-center text-xs border-collapse min-w-[1050px]">
                <thead>
                    <tr>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 w-12">NO</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 w-48 text-left">NAMA RUANG</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 w-24">KODE</th>
                        <th class="p-2.5 bg-yellow-300 text-slate-900 font-black border border-yellow-400 w-28">CARABIKA</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 1</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 2</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 3</th>
                        <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 w-24">ISTIRAHAT</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 4</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 5</th>
                        <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 w-24">ISTIRAHAT</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 6</th>
                        <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 w-24">JAM 7</th>
                    </tr>
                    <tr class="text-[11px] text-slate-600 bg-slate-50">
                        <th class="p-1 border border-slate-200 font-medium">06.30 - 07.30</th>
                        <th class="p-1 border border-slate-200 font-medium">07.30 - 08.15</th>
                        <th class="p-1 border border-slate-200 font-medium">08.15 - 09.00</th>
                        <th class="p-1 border border-slate-200 font-medium">09.00 - 09.45</th>
                        <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700">09.45 - 10.00</th>
                        <th class="p-1 border border-slate-200 font-medium">10.00 - 10.45</th>
                        <th class="p-1 border border-slate-200 font-medium">10.45 - 11.30</th>
                        <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700">11.30 - 12.30</th>
                        <th class="p-1 border border-slate-200 font-medium">12.30 - 13.15</th>
                        <th class="p-1 border border-slate-200 font-medium">13.15 - 14.00</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rekapTerpakai = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];
                        $rekapKosong = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0];
                    @endphp
                    @forelse($daftarRuangan as $index => $r)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="p-2.5 font-bold text-slate-500 border border-slate-200 bg-slate-50/50">{{ $index + 1 }}</td>
                            <td class="p-2.5 font-bold text-slate-900 border border-slate-200 text-left whitespace-nowrap">{{ $r->nama }}</td>
                            <td class="p-2.5 font-mono font-bold text-slate-600 border border-slate-200 bg-slate-50/30">{{ $r->kode }}</td>
                            <td class="p-2 bg-yellow-50 text-amber-900 font-medium border border-slate-200 text-[11px]">-</td>
                            
                            @foreach([1, 2, 3] as $jam)
                                @php
                                    $pakai = $daftarJadwal->first(fn($j) => $j->id_ruangan == $r->id && $jam >= $j->jam_ke_mulai && $jam <= $j->jam_ke_selesai);
                                    if ($pakai) {
                                        $rekapTerpakai[$jam]++;
                                    } else {
                                        $rekapKosong[$jam]++;
                                    }
                                @endphp
                                @if($pakai)
                                    <td class="p-2 bg-rose-100 text-rose-900 font-bold border border-rose-200 text-xs cursor-pointer hover:bg-rose-200 transition" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $pakai->id }}"
                                        data-hari="{{ $pakai->hari }}"
                                        data-kelas="{{ $pakai->id_kelas }}"
                                        data-mapel="{{ $pakai->id_mapel }}"
                                        data-ruang="{{ $pakai->id_ruangan }}"
                                        data-guru="{{ $pakai->id_guru }}"
                                        data-mulai="{{ $pakai->jam_ke_mulai }}"
                                        data-selesai="{{ $pakai->jam_ke_selesai }}"
                                        title="Terpakai oleh {{ $pakai->kelas ? $pakai->kelas->nama : 'Kelas' }} - Klik untuk edit">
                                        <span class="block font-black text-rose-700">1</span>
                                        <span class="text-[10px] leading-tight block truncate max-w-[85px] mx-auto">{{ $pakai->kelas ? $pakai->kelas->nama : '-' }}</span>
                                    </td>
                                @else
                                    <td class="p-2 bg-emerald-50/60 text-emerald-700 font-bold border border-slate-200 text-xs hover:bg-emerald-100 cursor-pointer transition"
                                        onclick="tambahJadwalUntukRuangan('{{ $hariTerpilih }}', {{ $r->id }}, {{ $jam }})"
                                        title="Ruangan Kosong - Klik untuk jadwalkan">
                                        <span class="block font-extrabold text-emerald-600">0</span>
                                        <span class="text-[9px] text-emerald-500 font-medium">Kosong</span>
                                    </td>
                                @endif
                            @endforeach

                            <td class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]">ISTIRAHAT</td>

                            @foreach([4, 5] as $jam)
                                @php
                                    $pakai = $daftarJadwal->first(fn($j) => $j->id_ruangan == $r->id && $jam >= $j->jam_ke_mulai && $jam <= $j->jam_ke_selesai);
                                    if ($pakai) {
                                        $rekapTerpakai[$jam]++;
                                    } else {
                                        $rekapKosong[$jam]++;
                                    }
                                @endphp
                                @if($pakai)
                                    <td class="p-2 bg-rose-100 text-rose-900 font-bold border border-rose-200 text-xs cursor-pointer hover:bg-rose-200 transition" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $pakai->id }}"
                                        data-hari="{{ $pakai->hari }}"
                                        data-kelas="{{ $pakai->id_kelas }}"
                                        data-mapel="{{ $pakai->id_mapel }}"
                                        data-ruang="{{ $pakai->id_ruangan }}"
                                        data-guru="{{ $pakai->id_guru }}"
                                        data-mulai="{{ $pakai->jam_ke_mulai }}"
                                        data-selesai="{{ $pakai->jam_ke_selesai }}"
                                        title="Terpakai oleh {{ $pakai->kelas ? $pakai->kelas->nama : 'Kelas' }} - Klik untuk edit">
                                        <span class="block font-black text-rose-700">1</span>
                                        <span class="text-[10px] leading-tight block truncate max-w-[85px] mx-auto">{{ $pakai->kelas ? $pakai->kelas->nama : '-' }}</span>
                                    </td>
                                @else
                                    <td class="p-2 bg-emerald-50/60 text-emerald-700 font-bold border border-slate-200 text-xs hover:bg-emerald-100 cursor-pointer transition"
                                        onclick="tambahJadwalUntukRuangan('{{ $hariTerpilih }}', {{ $r->id }}, {{ $jam }})"
                                        title="Ruangan Kosong - Klik untuk jadwalkan">
                                        <span class="block font-extrabold text-emerald-600">0</span>
                                        <span class="text-[9px] text-emerald-500 font-medium">Kosong</span>
                                    </td>
                                @endif
                            @endforeach

                            <td class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]">ISTIRAHAT</td>

                            @foreach([6, 7] as $jam)
                                @php
                                    $pakai = $daftarJadwal->first(fn($j) => $j->id_ruangan == $r->id && $jam >= $j->jam_ke_mulai && $jam <= $j->jam_ke_selesai);
                                    if ($pakai) {
                                        $rekapTerpakai[$jam]++;
                                    } else {
                                        $rekapKosong[$jam]++;
                                    }
                                @endphp
                                @if($pakai)
                                    <td class="p-2 bg-rose-100 text-rose-900 font-bold border border-rose-200 text-xs cursor-pointer hover:bg-rose-200 transition" onclick="openEditModalFromCell(this)"
                                        data-id="{{ $pakai->id }}"
                                        data-hari="{{ $pakai->hari }}"
                                        data-kelas="{{ $pakai->id_kelas }}"
                                        data-mapel="{{ $pakai->id_mapel }}"
                                        data-ruang="{{ $pakai->id_ruangan }}"
                                        data-guru="{{ $pakai->id_guru }}"
                                        data-mulai="{{ $pakai->jam_ke_mulai }}"
                                        data-selesai="{{ $pakai->jam_ke_selesai }}"
                                        title="Terpakai oleh {{ $pakai->kelas ? $pakai->kelas->nama : 'Kelas' }} - Klik untuk edit">
                                        <span class="block font-black text-rose-700">1</span>
                                        <span class="text-[10px] leading-tight block truncate max-w-[85px] mx-auto">{{ $pakai->kelas ? $pakai->kelas->nama : '-' }}</span>
                                    </td>
                                @else
                                    <td class="p-2 bg-emerald-50/60 text-emerald-700 font-bold border border-slate-200 text-xs hover:bg-emerald-100 cursor-pointer transition"
                                        onclick="tambahJadwalUntukRuangan('{{ $hariTerpilih }}', {{ $r->id }}, {{ $jam }})"
                                        title="Ruangan Kosong - Klik untuk jadwalkan">
                                        <span class="block font-extrabold text-emerald-600">0</span>
                                        <span class="text-[9px] text-emerald-500 font-medium">Kosong</span>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="p-8 text-center text-slate-400">Belum ada data ruangan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-extrabold text-xs">
                    <tr class="bg-rose-50 text-rose-800 border-t-2 border-rose-300">
                        <td colspan="3" class="p-2.5 text-left font-black tracking-wider border border-slate-200">JUMLAH RUANGAN TERPAKAI</td>
                        <td class="p-2 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[1] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[2] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[3] }}</td>
                        <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[4] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[5] }}</td>
                        <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[6] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[7] }}</td>
                    </tr>
                    <tr class="bg-emerald-50 text-emerald-800 border-b-2 border-emerald-300">
                        <td colspan="3" class="p-2.5 text-left font-black tracking-wider border border-slate-200">JUMLAH RUANGAN KOSONG</td>
                        <td class="p-2 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[1] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[2] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[3] }}</td>
                        <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[4] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[5] }}</td>
                        <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[6] }}</td>
                        <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[7] }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center space-x-2">
            <i class="fa-solid fa-list-check text-emerald-700"></i>
            <span>Daftar Rincian Jadwal - Hari {{ $hariTerpilih }}</span>
        </h3>

        @if($daftarJadwal->isEmpty())
            <div class="p-8 text-center text-slate-400">
                <p class="text-sm">Belum ada jadwal pelajaran yang diatur untuk hari {{ $hariTerpilih }}.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                        <tr>
                            <th class="p-3.5 rounded-l-xl">Kelas</th>
                            <th class="p-3.5">Mata Pelajaran</th>
                            <th class="p-3.5">Guru Pengajar</th>
                            <th class="p-3.5">Ruangan</th>
                            <th class="p-3.5">Jam Ke</th>
                            <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarJadwal as $j)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-bold text-slate-900">
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ $j->kelas ? $j->kelas->nama : '-' }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900">{{ $j->mapel ? $j->mapel->nama : '-' }}</td>
                                <td class="p-3.5 text-slate-700 text-xs font-semibold">{{ $j->guru ? $j->guru->nama : '-' }}</td>
                                <td class="p-3.5 text-slate-600 text-xs">{{ $j->ruangan ? $j->ruangan->nama : '-' }}</td>
                                <td class="p-3.5 font-mono text-emerald-700 font-bold text-xs">
                                    Jam {{ $j->jam_ke_mulai }} s/d {{ $j->jam_ke_selesai }}
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $j->id }}"
                                            data-hari="{{ $j->hari }}"
                                            data-kelas="{{ $j->id_kelas }}"
                                            data-mapel="{{ $j->id_mapel }}"
                                            data-ruang="{{ $j->id_ruangan }}"
                                            data-guru="{{ $j->id_guru }}"
                                            data-mulai="{{ $j->jam_ke_mulai }}"
                                            data-selesai="{{ $j->jam_ke_selesai }}"
                                            onclick="openEditModalFromCell(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
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
        @endif
    </div>
</div>

<div id="tambahModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-xl w-full shadow-2xl relative max-h-[92vh] overflow-y-auto">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="mb-4">
            <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-calendar-plus text-emerald-700"></i>
                <span>Tambah Jadwal Pelajaran</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Sistem otomatis mendeteksi ketersediaan ruangan, guru, dan jadwal kelas agar tidak bentrok.</p>
        </div>

        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
            @csrf
            
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Hari</label>
                        <select name="hari" id="tambahHari" onchange="evaluasiFormJadwal()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($daftarHari as $h)
                                <option value="{{ $h }}" {{ $hariTerpilih === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Mulai</label>
                        <select name="jam_ke_mulai" id="tambahMulai" onchange="onJamMulaiChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}">Jam {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Selesai</label>
                        <select name="jam_ke_selesai" id="tambahSelesai" onchange="evaluasiFormJadwal()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}">Jam {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                    <span><i class="fa-solid fa-clock mr-1 text-slate-400"></i>Pagi: Jam 1-3 | Siang: Jam 4-5 | Sore: Jam 6-7</span>
                    <span class="text-amber-700 font-medium"><i class="fa-solid fa-mug-hot mr-1"></i>Istirahat setelah Jam 3 & Jam 5</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                    <select name="id_kelas" id="tambahKelas" onchange="onKelasChange()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" data-ruangan="{{ $k->id_ruangan }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran</label>
                    <select name="id_mapel" id="tambahMapel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->kode }} - {{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-semibold text-slate-700">Ruang Belajar</label>
                    <label class="flex items-center space-x-1.5 text-xs text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" id="checkFilterRuangKosong" onchange="renderDropdownRuangan()" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="font-medium">Sembunyikan ruangan terpakai</span>
                    </label>
                </div>
                <select name="id_ruangan" id="tambahRuangan" onchange="evaluasiFormJadwal()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
                <div id="statusRuangText" class="text-xs mt-1"></div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar</label>
                <select name="id_guru" id="tambahGuru" onchange="evaluasiFormJadwal()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->mapel_utama ?? 'Guru' }})</option>
                    @endforeach
                </select>
                <div id="statusGuruText" class="text-xs mt-1"></div>
            </div>

            <div id="alertBentrokJadwal" class="p-3.5 rounded-2xl text-xs font-semibold hidden"></div>

            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" id="btnSimpanJadwal" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-xl w-full shadow-2xl relative max-h-[92vh] overflow-y-auto">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="mb-4">
            <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square text-emerald-700"></i>
                <span>Edit Jadwal Pelajaran</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Periksa ketersediaan ruangan dan guru jika ingin memindahkan jadwal.</p>
        </div>

        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Hari</label>
                        <select id="editHari" name="hari" onchange="evaluasiEditForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($daftarHari as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Mulai</label>
                        <select id="editMulai" name="jam_ke_mulai" onchange="onEditJamMulaiChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}">Jam {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Selesai</label>
                        <select id="editSelesai" name="jam_ke_selesai" onchange="evaluasiEditForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @for($i = 1; $i <= 7; $i++)
                                <option value="{{ $i }}">Jam {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                    <select id="editKelas" name="id_kelas" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran</label>
                    <select id="editMapel" name="id_mapel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->kode }} - {{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruang Belajar</label>
                <select id="editRuang" name="id_ruangan" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
                <div id="editStatusRuangText" class="text-xs mt-1"></div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar</label>
                <select id="editGuru" name="id_guru" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->mapel_utama ?? 'Guru' }})</option>
                    @endforeach
                </select>
                <div id="editStatusGuruText" class="text-xs mt-1"></div>
            </div>

            <div id="editAlertBentrok" class="p-3.5 rounded-2xl text-xs font-semibold hidden"></div>

            <div class="flex items-center justify-between pt-3">
                <button type="button" id="btnHapusJadwal" onclick="hapusJadwalAktif()" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold transition">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Jadwal
                </button>
                <div class="flex space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" id="btnSimpanEditJadwal" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-5 py-2.5 rounded-xl shadow transition text-sm">Simpan</button>
                </div>
            </div>
        </form>
        <form id="deleteForm" method="POST" action="" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
const SEMUA_JADWAL = @json($semuaJadwal);
const DAFTAR_RUANGAN = @json($daftarRuangan);
const DAFTAR_GURU = @json($daftarGuru);
const DAFTAR_KELAS = @json($daftarKelas);
let currentEditId = null;

function switchMatriksView(view) {
    const vKelas = document.getElementById('viewMatriksKelas');
    const vRuang = document.getElementById('viewMatriksRuangan');
    const btnKelas = document.getElementById('tabBtnKelas');
    const btnRuang = document.getElementById('tabBtnRuang');

    if (view === 'ruang') {
        vKelas.classList.add('hidden');
        vRuang.classList.remove('hidden');
        btnRuang.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-emerald-700 text-white shadow';
        btnKelas.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 text-slate-600 hover:bg-slate-200';
    } else {
        vRuang.classList.add('hidden');
        vKelas.classList.remove('hidden');
        btnKelas.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 bg-emerald-700 text-white shadow';
        btnRuang.className = 'px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center space-x-1.5 text-slate-600 hover:bg-slate-200';
    }
}

function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
    evaluasiFormJadwal();
}

function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}

function onJamMulaiChange() {
    const mulai = parseInt(document.getElementById('tambahMulai').value) || 1;
    const selesaiSelect = document.getElementById('tambahSelesai');
    if (parseInt(selesaiSelect.value) < mulai) {
        selesaiSelect.value = mulai;
    }
    evaluasiFormJadwal();
}

function onKelasChange() {
    const kSelect = document.getElementById('tambahKelas');
    const opt = kSelect.options[kSelect.selectedIndex];
    const ruangId = opt ? opt.getAttribute('data-ruangan') : null;
    if (ruangId) {
        document.getElementById('tambahRuangan').value = ruangId;
    }
    evaluasiFormJadwal();
}

function tambahJadwalUntukRuangan(hari, idRuangan, jam) {
    document.getElementById('tambahHari').value = hari;
    document.getElementById('tambahMulai').value = jam;
    document.getElementById('tambahSelesai').value = jam;
    document.getElementById('tambahModal').classList.remove('hidden');
    renderDropdownRuangan();
    document.getElementById('tambahRuangan').value = idRuangan;
    evaluasiFormJadwal();
}

function renderDropdownRuangan() {
    const hari = document.getElementById('tambahHari').value;
    const mulai = parseInt(document.getElementById('tambahMulai').value) || 1;
    const selesai = parseInt(document.getElementById('tambahSelesai').value) || mulai;
    const filterKosong = document.getElementById('checkFilterRuangKosong').checked;
    const selectRuangan = document.getElementById('tambahRuangan');
    const nilaiLama = selectRuangan.value;

    let ruanganKosong = [];
    let ruanganTerpakai = [];

    DAFTAR_RUANGAN.forEach(r => {
        const bentrok = SEMUA_JADWAL.find(j => 
            j.hari === hari &&
            j.id_ruangan === r.id &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );

        if (bentrok) {
            ruanganTerpakai.push({ ruangan: r, jadwal: bentrok });
        } else {
            ruanganKosong.push(r);
        }
    });

    selectRuangan.innerHTML = '';

    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- Pilih Ruangan --';
    selectRuangan.appendChild(defaultOpt);

    if (ruanganKosong.length > 0) {
        const groupKosong = document.createElement('optgroup');
        groupKosong.label = 'Ruangan Tersedia (Kosong)';
        ruanganKosong.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = `${r.kode} - ${r.nama}`;
            if (String(r.id) === String(nilaiLama)) {
                opt.selected = true;
            }
            groupKosong.appendChild(opt);
        });
        selectRuangan.appendChild(groupKosong);
    }

    if (!filterKosong && ruanganTerpakai.length > 0) {
        const groupTerpakai = document.createElement('optgroup');
        groupTerpakai.label = 'Ruangan Sedang Terpakai (Bentrok)';
        ruanganTerpakai.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.ruangan.id;
            const namaKelas = item.jadwal.kelas ? item.jadwal.kelas.nama : 'Kelas Lain';
            opt.textContent = `${item.ruangan.kode} - ${item.ruangan.nama} (Terpakai: ${namaKelas} Jam ${item.jadwal.jam_ke_mulai}-${item.jadwal.jam_ke_selesai})`;
            opt.disabled = true;
            groupTerpakai.appendChild(opt);
        });
        selectRuangan.appendChild(groupTerpakai);
    }

    const statusEl = document.getElementById('statusRuangText');
    if (statusEl) {
        statusEl.innerHTML = `
            <div class="flex items-center space-x-3 text-xs font-semibold mt-1">
                <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                    <i class="fa-solid fa-circle-check mr-1"></i>${ruanganKosong.length} Tersedia
                </span>
                <span class="text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                    <i class="fa-solid fa-circle-xmark mr-1"></i>${ruanganTerpakai.length} Terpakai
                </span>
            </div>
        `;
    }
}

function renderDropdownGuru() {
    const hari = document.getElementById('tambahHari').value;
    const mulai = parseInt(document.getElementById('tambahMulai').value) || 1;
    const selesai = parseInt(document.getElementById('tambahSelesai').value) || mulai;
    const selectGuru = document.getElementById('tambahGuru');
    const nilaiLama = selectGuru.value;

    let guruTersedia = [];
    let guruMengajar = [];

    DAFTAR_GURU.forEach(g => {
        const bentrok = SEMUA_JADWAL.find(j => 
            j.hari === hari &&
            j.id_guru === g.id &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );

        if (bentrok) {
            guruMengajar.push({ guru: g, jadwal: bentrok });
        } else {
            guruTersedia.push(g);
        }
    });

    selectGuru.innerHTML = '';
    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- Pilih Guru --';
    selectGuru.appendChild(defaultOpt);

    if (guruTersedia.length > 0) {
        const groupBebas = document.createElement('optgroup');
        groupBebas.label = 'Guru Tersedia (Bebas Jam Mengajar)';
        guruTersedia.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = `${g.nama} (${g.mapel_utama || 'Guru'})`;
            if (String(g.id) === String(nilaiLama)) {
                opt.selected = true;
            }
            groupBebas.appendChild(opt);
        });
        selectGuru.appendChild(groupBebas);
    }

    if (guruMengajar.length > 0) {
        const groupSibuk = document.createElement('optgroup');
        groupSibuk.label = 'Guru Sedang Mengajar (Bentrok)';
        guruMengajar.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.guru.id;
            const namaKelas = item.jadwal.kelas ? item.jadwal.kelas.nama : 'Kelas Lain';
            opt.textContent = `${item.guru.nama} (Mengajar di ${namaKelas} Jam ${item.jadwal.jam_ke_mulai}-${item.jadwal.jam_ke_selesai})`;
            opt.disabled = true;
            groupSibuk.appendChild(opt);
        });
        selectGuru.appendChild(groupSibuk);
    }

    const statusGuruEl = document.getElementById('statusGuruText');
    if (statusGuruEl) {
        statusGuruEl.innerHTML = `
            <div class="flex items-center space-x-3 text-xs font-semibold mt-1">
                <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                    <i class="fa-solid fa-circle-check mr-1"></i>${guruTersedia.length} Bebas
                </span>
                <span class="text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                    <i class="fa-solid fa-chalkboard-user mr-1"></i>${guruMengajar.length} Sedang Mengajar
                </span>
            </div>
        `;
    }
}

function evaluasiFormJadwal() {
    renderDropdownRuangan();
    renderDropdownGuru();

    const hari = document.getElementById('tambahHari').value;
    const mulai = parseInt(document.getElementById('tambahMulai').value) || 1;
    const selesai = parseInt(document.getElementById('tambahSelesai').value) || mulai;
    const idKelas = parseInt(document.getElementById('tambahKelas').value);
    const idRuang = parseInt(document.getElementById('tambahRuangan').value);
    const idGuru = parseInt(document.getElementById('tambahGuru').value);
    const btnSimpan = document.getElementById('btnSimpanJadwal');
    const alertBox = document.getElementById('alertBentrokJadwal');

    let errors = [];

    if (selesai < mulai) {
        errors.push('Jam selesai tidak boleh lebih awal dari jam mulai.');
    }

    if ((mulai <= 3 && selesai >= 4) || (mulai <= 5 && selesai >= 6)) {
        errors.push('Jadwal tidak boleh melewati jam istirahat. Harap pecah menjadi dua jadwal terpisah sebelum dan sesudah istirahat.');
    }

    if (idKelas) {
        const bentrokKelas = SEMUA_JADWAL.find(j => 
            j.hari === hari &&
            j.id_kelas === idKelas &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokKelas) {
            const namaMapel = bentrokKelas.mapel ? bentrokKelas.mapel.nama : 'Mapel Lain';
            errors.push(`Kelas ini sudah memiliki jadwal '${namaMapel}' pada jam tersebut.`);
        }
    }

    if (idRuang) {
        const bentrokRuang = SEMUA_JADWAL.find(j => 
            j.hari === hari &&
            j.id_ruangan === idRuang &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokRuang) {
            const namaKelas = bentrokRuang.kelas ? bentrokRuang.kelas.nama : 'Kelas Lain';
            errors.push(`Ruangan sudah digunakan oleh kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    if (idGuru) {
        const bentrokGuru = SEMUA_JADWAL.find(j => 
            j.hari === hari &&
            j.id_guru === idGuru &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokGuru) {
            const namaKelas = bentrokGuru.kelas ? bentrokGuru.kelas.nama : 'Kelas Lain';
            errors.push(`Guru yang dipilih sudah memiliki jadwal mengajar di kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    if (errors.length > 0) {
        alertBox.className = 'p-3.5 rounded-2xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800 flex items-start space-x-2';
        alertBox.innerHTML = `
            <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 text-sm"></i>
            <div>
                <p class="font-bold text-rose-900">Perhatian Bentrok Jadwal:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-700">
                    ${errors.map(e => `<li>${e}</li>`).join('')}
                </ul>
            </div>
        `;
        alertBox.classList.remove('hidden');
        btnSimpan.disabled = true;
        btnSimpan.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        alertBox.className = 'p-3.5 rounded-2xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-2';
        alertBox.innerHTML = `
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>Jadwal aman! Tidak ada bentrok ruangan, guru, maupun kelas pada jam ini.</span>
        `;
        alertBox.classList.remove('hidden');
        btnSimpan.disabled = false;
        btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function openEditModalFromCell(el) {
    currentEditId = el.getAttribute('data-id');
    const hari = el.getAttribute('data-hari');
    const kelas = el.getAttribute('data-kelas');
    const mapel = el.getAttribute('data-mapel');
    const ruang = el.getAttribute('data-ruang');
    const guru = el.getAttribute('data-guru');
    const mulai = el.getAttribute('data-mulai');
    const selesai = el.getAttribute('data-selesai');

    document.getElementById('editForm').action = '/admin/jadwal/' + currentEditId;
    document.getElementById('deleteForm').action = '/admin/jadwal/' + currentEditId;
    document.getElementById('editHari').value = hari;
    document.getElementById('editKelas').value = kelas;
    document.getElementById('editMapel').value = mapel;
    document.getElementById('editRuang').value = ruang;
    document.getElementById('editGuru').value = guru;
    document.getElementById('editMulai').value = mulai;
    document.getElementById('editSelesai').value = selesai;

    document.getElementById('editModal').classList.remove('hidden');
    evaluasiEditForm();
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function onEditJamMulaiChange() {
    const mulai = parseInt(document.getElementById('editMulai').value) || 1;
    const selesaiSelect = document.getElementById('editSelesai');
    if (parseInt(selesaiSelect.value) < mulai) {
        selesaiSelect.value = mulai;
    }
    evaluasiEditForm();
}

function evaluasiEditForm() {
    const hari = document.getElementById('editHari').value;
    const mulai = parseInt(document.getElementById('editMulai').value) || 1;
    const selesai = parseInt(document.getElementById('editSelesai').value) || mulai;
    const idKelas = parseInt(document.getElementById('editKelas').value);
    const idRuang = parseInt(document.getElementById('editRuang').value);
    const idGuru = parseInt(document.getElementById('editGuru').value);
    const btnSimpan = document.getElementById('btnSimpanEditJadwal');
    const alertBox = document.getElementById('editAlertBentrok');

    let errors = [];

    if (selesai < mulai) {
        errors.push('Jam selesai tidak boleh lebih awal dari jam mulai.');
    }

    if ((mulai <= 3 && selesai >= 4) || (mulai <= 5 && selesai >= 6)) {
        errors.push('Jadwal tidak boleh melewati jam istirahat. Harap pecah menjadi dua jadwal terpisah.');
    }

    if (idKelas) {
        const bentrokKelas = SEMUA_JADWAL.find(j => 
            String(j.id) !== String(currentEditId) &&
            j.hari === hari &&
            j.id_kelas === idKelas &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokKelas) {
            const namaMapel = bentrokKelas.mapel ? bentrokKelas.mapel.nama : 'Mapel Lain';
            errors.push(`Kelas ini sudah memiliki jadwal '${namaMapel}' pada jam tersebut.`);
        }
    }

    if (idRuang) {
        const bentrokRuang = SEMUA_JADWAL.find(j => 
            String(j.id) !== String(currentEditId) &&
            j.hari === hari &&
            j.id_ruangan === idRuang &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokRuang) {
            const namaKelas = bentrokRuang.kelas ? bentrokRuang.kelas.nama : 'Kelas Lain';
            errors.push(`Ruangan sudah digunakan oleh kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    if (idGuru) {
        const bentrokGuru = SEMUA_JADWAL.find(j => 
            String(j.id) !== String(currentEditId) &&
            j.hari === hari &&
            j.id_guru === idGuru &&
            j.jam_ke_mulai <= selesai &&
            j.jam_ke_selesai >= mulai
        );
        if (bentrokGuru) {
            const namaKelas = bentrokGuru.kelas ? bentrokGuru.kelas.nama : 'Kelas Lain';
            errors.push(`Guru yang dipilih sudah memiliki jadwal mengajar di kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    if (errors.length > 0) {
        alertBox.className = 'p-3.5 rounded-2xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-800 flex items-start space-x-2';
        alertBox.innerHTML = `
            <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5 text-sm"></i>
            <div>
                <p class="font-bold text-rose-900">Perhatian Bentrok Jadwal:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-700">
                    ${errors.map(e => `<li>${e}</li>`).join('')}
                </ul>
            </div>
        `;
        alertBox.classList.remove('hidden');
        btnSimpan.disabled = true;
        btnSimpan.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        alertBox.className = 'p-3.5 rounded-2xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center space-x-2';
        alertBox.innerHTML = `
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span>Jadwal aman! Tidak ada bentrok ruangan, guru, maupun kelas pada jam ini.</span>
        `;
        alertBox.classList.remove('hidden');
        btnSimpan.disabled = false;
        btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function hapusJadwalAktif() {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection

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
        <div class="flex items-center justify-between">
            <h3 class="font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-table-cells text-emerald-700"></i>
                <span>Matriks Jadwal Pelajaran - Hari {{ $hariTerpilih }}</span>
            </h3>
            <span class="text-xs text-slate-400">Klik blok mata pelajaran untuk mengedit</span>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-2xl">
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
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Tambah Jadwal Pelajaran</h3>
        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Hari Pelajaran</label>
                <select name="hari" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarHari as $h)
                        <option value="{{ $h }}" {{ $hariTerpilih === $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                <select name="id_kelas" id="tambahKelas" onchange="updateDefaultRuangan(this)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" data-ruangan="{{ $k->id_ruangan }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Mata Pelajaran</label>
                <select name="id_mapel" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($daftarMapel as $m)
                        <option value="{{ $m->id }}">{{ $m->kode }} - {{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar</label>
                <select name="id_guru" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->mapel_utama ?? 'Guru' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruang Belajar</label>
                <select name="id_ruangan" id="tambahRuangan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    <option value="">-- Pilih Ruangan --</option>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Ke Mulai</label>
                    <select name="jam_ke_mulai" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">Jam {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Ke Selesai</label>
                    <select name="jam_ke_selesai" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">Jam {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Jadwal Pelajaran</h3>
        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Hari Pelajaran</label>
                <select id="editHari" name="hari" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarHari as $h)
                        <option value="{{ $h }}">{{ $h }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                <select id="editKelas" name="id_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
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
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar</label>
                <select id="editGuru" name="id_guru" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarGuru as $g)
                        <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->mapel_utama ?? 'Guru' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Ruang Belajar</label>
                <select id="editRuang" name="id_ruangan" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                    @foreach($daftarRuangan as $r)
                        <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Ke Mulai</label>
                    <select id="editMulai" name="jam_ke_mulai" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">Jam {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jam Ke Selesai</label>
                    <select id="editSelesai" name="jam_ke_selesai" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @for($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}">Jam {{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3">
                <button type="button" id="btnHapusJadwal" onclick="hapusJadwalAktif()" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold transition">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Jadwal
                </button>
                <div class="flex space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-5 py-2.5 rounded-xl shadow transition text-sm">Simpan</button>
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
function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
}
function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}
function updateDefaultRuangan(select) {
    const opt = select.options[select.selectedIndex];
    const ruangId = opt.getAttribute('data-ruangan');
    if (ruangId) {
        document.getElementById('tambahRuangan').value = ruangId;
    }
}
function openEditModalFromCell(el) {
    const id = el.getAttribute('data-id');
    const hari = el.getAttribute('data-hari');
    const kelas = el.getAttribute('data-kelas');
    const mapel = el.getAttribute('data-mapel');
    const ruang = el.getAttribute('data-ruang');
    const guru = el.getAttribute('data-guru');
    const mulai = el.getAttribute('data-mulai');
    const selesai = el.getAttribute('data-selesai');

    document.getElementById('editForm').action = '/admin/jadwal/' + id;
    document.getElementById('deleteForm').action = '/admin/jadwal/' + id;
    document.getElementById('editHari').value = hari;
    document.getElementById('editKelas').value = kelas;
    document.getElementById('editMapel').value = mapel;
    document.getElementById('editRuang').value = ruang;
    document.getElementById('editGuru').value = guru;
    document.getElementById('editMulai').value = mulai;
    document.getElementById('editSelesai').value = selesai;

    document.getElementById('editModal').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
function hapusJadwalAktif() {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection

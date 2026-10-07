@extends('layouts.admin', ['title' => 'Jadwal Pelajaran - Admin SMKN 13 Bandung'])

@section('content')
@php
    $formatWaktu = fn ($waktu) => str_replace(':', '.', (string) $waktu);
    $slotPelajaran = $slotHari->where('jenis', 'Pelajaran');
    $jumlahKolom = $slotHari->count();
    $kelompokLabel = ['A' => 'Tingkat XII & XIII', 'B' => 'Tingkat X & XI'];
    $aturanSesiAktif = $aturanRotasi->where('sesi', $sesiTerpilih)->sortBy('kelompok');
@endphp
<style>
    #viewMatriksKelas,
    #viewMatriksRuangan {
        overflow-x: auto !important;
        scrollbar-width: auto !important;
        scrollbar-color: #047857 #f1f5f9 !important;
        -ms-overflow-style: auto !important;
    }
    #viewMatriksKelas::-webkit-scrollbar,
    #viewMatriksRuangan::-webkit-scrollbar {
        display: block !important;
        height: 14px !important;
        width: 14px !important;
    }
    #viewMatriksKelas::-webkit-scrollbar-track,
    #viewMatriksRuangan::-webkit-scrollbar-track {
        background: #f1f5f9 !important;
        border-radius: 9999px !important;
        border: 1px solid #e2e8f0 !important;
    }
    #viewMatriksKelas::-webkit-scrollbar-thumb,
    #viewMatriksRuangan::-webkit-scrollbar-thumb {
        background: #047857 !important;
        border-radius: 9999px !important;
        border: 3px solid #f1f5f9 !important;
    }
    #viewMatriksKelas::-webkit-scrollbar-thumb:hover,
    #viewMatriksRuangan::-webkit-scrollbar-thumb:hover {
        background: #065f46 !important;
    }
</style>
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
            <a href="{{ route('admin.jadwal.index', array_merge(['hari' => $h], $parameterSesi)) }}" class="px-5 py-2.5 rounded-xl text-sm font-bold transition flex items-center space-x-2 whitespace-nowrap {{ $hariTerpilih === $h ? 'bg-emerald-700 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-calendar-day"></i>
                <span>{{ $h }}</span>
            </a>
        @endforeach
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 space-y-3">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 rounded-xl bg-yellow-300 text-slate-900 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Pembiasaan Pagi Hari {{ $hariTerpilih }}</p>
                    @if($ringkasanHari['pembiasaan'])
                        <p class="font-black text-slate-900">
                            {{ strtoupper($ringkasanHari['pembiasaan']['nama']) }}
                            <span class="font-semibold text-slate-500 text-xs ml-1">({{ $formatWaktu($ringkasanHari['pembiasaan']['mulai']) }} - {{ $formatWaktu($ringkasanHari['pembiasaan']['selesai']) }})</span>
                        </p>
                        <p class="text-xs text-slate-600">{{ $ringkasanHari['pembiasaan']['keterangan'] }}</p>
                    @else
                        <p class="text-xs text-slate-500">Belum ada pembiasaan pagi yang diatur untuk hari ini.</p>
                    @endif
                </div>
            </div>

            @if($adaRotasi)
                <div class="flex items-center space-x-1.5 bg-white p-1.5 rounded-xl border border-yellow-200 self-start lg:self-auto">
                    @foreach([1 => 'Sesi 1 (Minggu Ganjil)', 2 => 'Sesi 2 (Minggu Genap)'] as $nomorSesi => $namaSesi)
                        <a href="{{ route('admin.jadwal.index', ['hari' => $hariTerpilih, 'sesi' => $nomorSesi]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition whitespace-nowrap {{ $sesiTerpilih === $nomorSesi ? 'bg-amber-500 text-white shadow' : 'text-slate-600 hover:bg-yellow-100' }}">
                            {{ $namaSesi }}
                            @if($sesiMingguIni === $nomorSesi)
                                <span class="ml-1 text-[9px] uppercase">Minggu Ini</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if($adaRotasi)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($aturanSesiAktif as $aturan)
                    <div class="flex items-center space-x-2 bg-white border border-yellow-200 rounded-xl px-3 py-2 text-xs">
                        <i class="fa-solid {{ stripos($aturan->kegiatan, 'upacara') !== false ? 'fa-flag text-sky-600' : 'fa-people-roof text-amber-600' }}"></i>
                        <span class="font-bold text-slate-800">{{ $kelompokLabel[$aturan->kelompok] ?? 'Kelompok ' . $aturan->kelompok }}</span>
                        <span class="text-slate-500">&rarr;</span>
                        <span class="font-semibold text-slate-700">{{ $aturan->kegiatan }}{{ $aturan->lokasi ? ' di ' . $aturan->lokasi : '' }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 pb-2 border-b border-slate-100">
            <div class="flex items-start sm:items-center space-x-3.5">
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 border border-emerald-100 shadow-xs">
                    <i class="fa-solid fa-table-cells text-lg"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">
                            Matriks Jadwal Pelajaran - Hari {{ $hariTerpilih }}
                        </h3>
                        <span class="inline-flex items-center text-[11px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full whitespace-nowrap shadow-xs">
                            {{ $ringkasanHari['maks_jam'] }} Jam Pelajaran
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Klik sel jadwal untuk mengedit, atau klik sel kosong di matriks ruangan untuk menjadwalkan
                    </p>
                </div>
            </div>

            <div class="w-full sm:w-auto grid grid-cols-2 sm:flex sm:items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                <button type="button" id="tabBtnKelas" onclick="switchMatriksView('kelas')" class="w-full sm:w-auto px-4 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-2 bg-emerald-700 text-white shadow-sm">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Matriks Kelas</span>
                </button>
                <button type="button" id="tabBtnRuang" onclick="switchMatriksView('ruang')" class="w-full sm:w-auto px-4 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-2 text-slate-600 hover:text-slate-900 hover:bg-white/60">
                    <i class="fa-solid fa-door-open"></i>
                    <span>Matriks Ruangan (PDF)</span>
                </button>
                <div class="hidden sm:flex items-center space-x-1 border-l border-slate-300 pl-2">
                    <button type="button" onclick="scrollMatriksHorizontal(-350)" title="Geser Kiri" class="p-1.5 rounded-lg text-slate-600 hover:bg-slate-200 text-xs">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" onclick="scrollMatriksHorizontal(350)" title="Geser Kanan" class="p-1.5 rounded-lg text-slate-600 hover:bg-slate-200 text-xs">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="topScrollContainer" class="bg-slate-50 border border-slate-200 rounded-2xl p-2.5 mb-3 shadow-xs">
            <div class="flex items-center justify-between text-[11px] font-bold text-slate-600 mb-1.5 px-1">
                <span class="flex items-center space-x-1.5 text-emerald-800">
                    <i class="fa-solid fa-arrows-left-right text-xs"></i>
                    <span>Scrollbar Horizontal (Bagian Atas)</span>
                </span>
                <span class="text-slate-400 font-normal hidden sm:inline text-[10px]">Geser scrollbar ini untuk menggeser matriks jadwal secara langsung</span>
            </div>
            <div id="topScrollWrapper" class="overflow-x-auto overflow-y-hidden custom-scrollbar-x bg-white border border-slate-200 rounded-xl p-0.5">
                <div id="topScrollDummy" class="h-2" style="min-width: {{ 340 + $jumlahKolom * 125 }}px; width: {{ 340 + $jumlahKolom * 125 }}px;"></div>
            </div>
        </div>

        <div id="viewMatriksKelas" class="overflow-x-auto border border-slate-200 rounded-2xl custom-scrollbar-x shadow-xs">
            <table class="w-full text-center text-xs border-collapse" style="min-width: {{ 260 + $jumlahKolom * 125 }}px">
                <thead>
                    <tr>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 min-w-[110px] w-28">KELAS</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 min-w-[80px] w-20">JAM KE</th>
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Pembiasaan')
                                <th class="p-2.5 bg-yellow-300 text-slate-900 font-black border border-yellow-400 min-w-[130px] w-32" title="{{ $slot->keterangan }}">{{ strtoupper($slot->nama) }}</th>
                            @elseif($slot->jenis === 'Istirahat')
                                <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 min-w-[105px] w-28" title="{{ $slot->nama }}">ISTIRAHAT</th>
                            @else
                                <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 min-w-[125px] w-32">JAM {{ $slot->jam_ke }}</th>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="text-[11px] text-slate-600 bg-slate-50">
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Istirahat')
                                <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700 min-w-[105px]">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</th>
                            @else
                                <th class="p-1 border border-slate-200 font-medium {{ $slot->jenis === 'Pembiasaan' ? 'min-w-[130px]' : 'min-w-[125px]' }}">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($daftarKelas as $k)
                        @php
                            $jadwalKelas = $daftarJadwal->where('id_kelas', $k->id);
                            $label = $labelPembiasaan[$k->id];
                            $kelasWarnaPembiasaan = $label['rotasi'] && stripos($label['kegiatan'], 'upacara') !== false
                                ? 'bg-sky-100 text-sky-900'
                                : 'bg-yellow-100/70 text-amber-900';
                        @endphp
                        @foreach(['mapel', 'ruang', 'guru'] as $indeksBaris => $baris)
                            <tr>
                                @if($indeksBaris === 0)
                                    <td rowspan="3" class="p-3 font-black text-slate-900 bg-slate-50 border border-slate-200 whitespace-nowrap">
                                        {{ $k->nama }}
                                    </td>
                                @endif
                                <td class="p-2 font-bold text-slate-500 bg-slate-100/70 border border-slate-200 text-[10px]">
                                    {{ strtoupper($baris) }}
                                </td>

                                @php $lewatiSampai = 0; @endphp
                                @foreach($slotHari as $slot)
                                    @if($slot->jenis === 'Pembiasaan')
                                        @if($indeksBaris === 0)
                                            <td rowspan="3" class="p-2 {{ $kelasWarnaPembiasaan }} font-bold border border-slate-200 text-xs">
                                                {{ strtoupper($label['kegiatan']) }}
                                                @if($label['lokasi'])
                                                    <span class="block text-[9px] font-medium opacity-80 mt-0.5">{{ $label['lokasi'] }}</span>
                                                @endif
                                            </td>
                                        @endif
                                        @continue
                                    @endif

                                    @if($slot->jenis === 'Istirahat')
                                        @if($indeksBaris === 0)
                                            <td rowspan="3" class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]" title="{{ $slot->nama }}">
                                                ISTIRAHAT
                                            </td>
                                        @endif
                                        @continue
                                    @endif

                                    @if($slot->jam_ke <= $lewatiSampai)
                                        @continue
                                    @endif

                                    @php
                                        $jdw = $jadwalKelas->first(fn ($i) => $i->jam_ke_mulai == $slot->jam_ke);
                                    @endphp
                                    @if($jdw)
                                        @php
                                            $span = $jdw->jam_ke_selesai - $jdw->jam_ke_mulai + 1;
                                            $lewatiSampai = $jdw->jam_ke_selesai;
                                            $namaGuru = $jdw->guru ? explode(',', $jdw->guru->nama)[0] : '-';
                                        @endphp
                                        @if($baris === 'mapel')
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
                                        @elseif($baris === 'ruang')
                                            <td colspan="{{ $span }}" class="p-1 font-semibold text-slate-600 bg-slate-50 border border-slate-200 text-[11px]">
                                                {{ $jdw->ruangan ? $jdw->ruangan->kode : '-' }}
                                            </td>
                                        @else
                                            <td colspan="{{ $span }}" class="p-1 text-slate-700 bg-slate-50 border border-slate-200 text-[11px]">
                                                {{ $namaGuru }}
                                            </td>
                                        @endif
                                    @else
                                        <td class="p-{{ $baris === 'mapel' ? '2' : '1' }} {{ $baris === 'mapel' ? 'text-slate-400' : 'text-slate-300' }} border border-slate-200">-</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="{{ $jumlahKolom + 2 }}" class="p-8 text-center text-slate-400">Belum ada data kelas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="viewMatriksRuangan" class="hidden overflow-x-auto border border-slate-200 rounded-2xl custom-scrollbar-x shadow-xs">
            <table class="w-full text-center text-xs border-collapse" style="min-width: {{ 340 + $jumlahKolom * 125 }}px">
                <thead>
                    <tr>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 min-w-[50px] w-12">NO</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 min-w-[200px] w-48 text-left">NAMA RUANG</th>
                        <th rowspan="2" class="p-3 bg-slate-100 text-slate-800 font-extrabold border border-slate-200 min-w-[90px] w-24">KODE</th>
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Pembiasaan')
                                <th class="p-2.5 bg-yellow-300 text-slate-900 font-black border border-yellow-400 min-w-[130px] w-32" title="{{ $slot->keterangan }}">{{ strtoupper($slot->nama) }}</th>
                            @elseif($slot->jenis === 'Istirahat')
                                <th class="p-2.5 bg-pink-400 text-white font-black border border-pink-500 min-w-[105px] w-28" title="{{ $slot->nama }}">ISTIRAHAT</th>
                            @else
                                <th class="p-2.5 bg-emerald-700 text-white font-black border border-emerald-800 min-w-[125px] w-32">JAM {{ $slot->jam_ke }}</th>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="text-[11px] text-slate-600 bg-slate-50">
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Istirahat')
                                <th class="p-1 border border-pink-200 bg-pink-50 font-medium text-pink-700 min-w-[105px]">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</th>
                            @else
                                <th class="p-1 border border-slate-200 font-medium {{ $slot->jenis === 'Pembiasaan' ? 'min-w-[130px]' : 'min-w-[125px]' }}">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $rekapTerpakai = array_fill_keys($slotPelajaran->pluck('jam_ke')->all(), 0);
                        $rekapKosong = $rekapTerpakai;
                    @endphp
                    @forelse($daftarRuangan as $index => $r)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="p-2.5 font-bold text-slate-500 border border-slate-200 bg-slate-50/50">{{ $index + 1 }}</td>
                            <td class="p-2.5 font-bold text-slate-900 border border-slate-200 text-left whitespace-nowrap">{{ $r->nama }}</td>
                            <td class="p-2.5 font-mono font-bold text-slate-600 border border-slate-200 bg-slate-50/30">{{ $r->kode }}</td>

                            @foreach($slotHari as $slot)
                                @if($slot->jenis === 'Pembiasaan')
                                    <td class="p-2 bg-yellow-50 text-amber-900 font-medium border border-slate-200 text-[11px]">-</td>
                                    @continue
                                @endif

                                @if($slot->jenis === 'Istirahat')
                                    <td class="p-2 bg-pink-100 text-pink-700 font-bold border border-slate-200 text-[11px]">ISTIRAHAT</td>
                                    @continue
                                @endif

                                @php
                                    $jam = $slot->jam_ke;
                                    $pakai = $daftarJadwal->first(fn ($j) => $j->id_ruangan == $r->id && $jam >= $j->jam_ke_mulai && $jam <= $j->jam_ke_selesai);
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
                            <td colspan="{{ $jumlahKolom + 3 }}" class="p-8 text-center text-slate-400">Belum ada data ruangan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-extrabold text-xs">
                    <tr class="bg-rose-50 text-rose-800 border-t-2 border-rose-300">
                        <td colspan="3" class="p-2.5 text-left font-black tracking-wider border border-slate-200">JUMLAH RUANGAN TERPAKAI</td>
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Pelajaran')
                                <td class="p-2 border border-slate-200 font-mono">{{ $rekapTerpakai[$slot->jam_ke] }}</td>
                            @elseif($slot->jenis === 'Istirahat')
                                <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                            @else
                                <td class="p-2 border border-slate-200">-</td>
                            @endif
                        @endforeach
                    </tr>
                    <tr class="bg-emerald-50 text-emerald-800 border-b-2 border-emerald-300">
                        <td colspan="3" class="p-2.5 text-left font-black tracking-wider border border-slate-200">JUMLAH RUANGAN KOSONG</td>
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Pelajaran')
                                <td class="p-2 border border-slate-200 font-mono">{{ $rekapKosong[$slot->jam_ke] }}</td>
                            @elseif($slot->jenis === 'Istirahat')
                                <td class="p-2 bg-pink-100 border border-slate-200">-</td>
                            @else
                                <td class="p-2 border border-slate-200">-</td>
                            @endif
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-600">
            <div class="flex items-center space-x-2">
                <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-arrows-left-right text-xs"></i>
                </span>
                <span class="font-medium text-slate-700">Geser horizontal scroll bar di atas atau gunakan tombol kontrol untuk melihat seluruh jam pelajaran:</span>
            </div>
            <div class="flex items-center space-x-1.5 flex-shrink-0">
                <button type="button" onclick="scrollMatriksHorizontal('start')" title="Ke Jam Pertama" class="px-2.5 py-1.5 bg-white hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 rounded-xl font-bold text-slate-700 flex items-center space-x-1 transition shadow-xs">
                    <i class="fa-solid fa-backward-step text-[10px]"></i>
                    <span>Awal</span>
                </button>
                <button type="button" onclick="scrollMatriksHorizontal(-350)" title="Geser Kiri" class="px-3 py-1.5 bg-white hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 rounded-xl font-bold text-slate-700 flex items-center space-x-1.5 transition shadow-xs">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Geser Kiri</span>
                </button>
                <button type="button" onclick="scrollMatriksHorizontal(350)" title="Geser Kanan" class="px-3 py-1.5 bg-white hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 rounded-xl font-bold text-slate-700 flex items-center space-x-1.5 transition shadow-xs">
                    <span>Geser Kanan</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
                <button type="button" onclick="scrollMatriksHorizontal('end')" title="Ke Jam Terakhir" class="px-2.5 py-1.5 bg-white hover:bg-emerald-50 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 rounded-xl font-bold text-slate-700 flex items-center space-x-1 transition shadow-xs">
                    <span>Akhir</span>
                    <i class="fa-solid fa-forward-step text-[10px]"></i>
                </button>
            </div>
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
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm min-w-[820px]">
                    <thead class="bg-slate-100 text-slate-700 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-3.5 rounded-tl-xl whitespace-nowrap min-w-[120px]">Kelas</th>
                            <th class="p-3.5 whitespace-nowrap min-w-[160px]">Mata Pelajaran</th>
                            <th class="p-3.5 whitespace-nowrap min-w-[180px]">Guru Pengajar</th>
                            <th class="p-3.5 whitespace-nowrap min-w-[140px]">Ruangan</th>
                            <th class="p-3.5 whitespace-nowrap min-w-[150px]">Jam Ke</th>
                            <th class="p-3.5 rounded-tr-xl text-right whitespace-nowrap min-w-[120px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarJadwal->sortBy('jam_ke_mulai') as $j)
                            @php
                                $waktuMulai = $jamKeMap[$j->jam_ke_mulai]->jam_mulai ?? null;
                                $waktuSelesai = $jamKeMap[$j->jam_ke_selesai]->jam_selesai ?? null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 whitespace-nowrap shadow-xs">
                                        {{ $j->kelas ? $j->kelas->nama : '-' }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">{{ $j->mapel ? $j->mapel->nama : '-' }}</td>
                                <td class="p-3.5 text-slate-700 text-xs font-semibold whitespace-nowrap">
                                    @if($j->guru)
                                        {{ $j->guru->nama }}
                                    @else
                                        <span class="text-slate-400 italic font-normal">Tanpa Guru / -</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-slate-600 text-xs whitespace-nowrap">{{ $j->ruangan ? $j->ruangan->nama : '-' }}</td>
                                <td class="p-3.5 font-mono whitespace-nowrap">
                                    <div class="text-xs font-bold text-emerald-700 whitespace-nowrap">
                                        Jam {{ $j->jam_ke_mulai }} s/d {{ $j->jam_ke_selesai }}
                                    </div>
                                    @if($waktuMulai && $waktuSelesai)
                                        <div class="text-[11px] text-slate-400 font-medium whitespace-nowrap mt-0.5">
                                            {{ $formatWaktu($waktuMulai) }} - {{ $formatWaktu($waktuSelesai) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button"
                                            data-id="{{ $j->id }}"
                                            data-hari="{{ $j->hari }}"
                                            data-kelas="{{ $j->id_kelas }}"
                                            data-mapel="{{ $j->id_mapel }}"
                                            data-ruang="{{ $j->id_ruangan }}"
                                            data-guru="{{ $j->id_guru ?? '' }}"
                                            data-mulai="{{ $j->jam_ke_mulai }}"
                                            data-selesai="{{ $j->jam_ke_selesai }}"
                                            onclick="openEditModalFromCell(this)"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}" data-confirm="Apakah Anda yakin ingin menghapus jadwal ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
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
                        <select name="hari" id="tambahHari" onchange="onTambahHariChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($daftarHari as $h)
                                <option value="{{ $h }}" {{ $hariTerpilih === $h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Mulai</label>
                        <select name="jam_ke_mulai" id="tambahMulai" onchange="onJamMulaiChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($ringkasanHari['jam'] as $slot)
                                <option value="{{ $slot['jam_ke'] }}">Jam {{ $slot['jam_ke'] }} ({{ $formatWaktu($slot['jam_mulai']) }} - {{ $formatWaktu($slot['jam_selesai']) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Selesai</label>
                        <select name="jam_ke_selesai" id="tambahSelesai" onchange="evaluasiFormJadwal()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($ringkasanHari['jam'] as $slot)
                                <option value="{{ $slot['jam_ke'] }}">Jam {{ $slot['jam_ke'] }} ({{ $formatWaktu($slot['jam_mulai']) }} - {{ $formatWaktu($slot['jam_selesai']) }})</option>
                            @endforeach
                        </select>
                    </div>
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                <select name="id_guru" id="tambahGuru" onchange="evaluasiFormJadwal()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Tanpa Guru / Belum Ditentukan --</option>
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
                        <select id="editHari" name="hari" onchange="onEditHariChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($daftarHari as $h)
                                <option value="{{ $h }}">{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Mulai</label>
                        <select id="editMulai" name="jam_ke_mulai" onchange="onEditJamMulaiChange()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($ringkasanHari['jam'] as $slot)
                                <option value="{{ $slot['jam_ke'] }}">Jam {{ $slot['jam_ke'] }} ({{ $formatWaktu($slot['jam_mulai']) }} - {{ $formatWaktu($slot['jam_selesai']) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jam Ke Selesai</label>
                        <select id="editSelesai" name="jam_ke_selesai" onchange="evaluasiEditForm()" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                            @foreach($ringkasanHari['jam'] as $slot)
                                <option value="{{ $slot['jam_ke'] }}">Jam {{ $slot['jam_ke'] }} ({{ $formatWaktu($slot['jam_mulai']) }} - {{ $formatWaktu($slot['jam_selesai']) }})</option>
                            @endforeach
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
                <label class="block text-sm font-semibold text-slate-700 mb-1">Guru Pengajar <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                <select id="editGuru" name="id_guru" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                    <option value="">-- Tanpa Guru / Belum Ditentukan --</option>
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
const URL_JAM_HARI = @json(route('admin.jadwal.jam', ['hari' => '__HARI__']));
const CACHE_JAM = {};
CACHE_JAM[@json($hariTerpilih)] = @json($ringkasanHari);
let currentEditId = null;

function byId(id) {
    return document.getElementById(id);
}

function formatWaktu(waktu) {
    return waktu ? String(waktu).replace(':', '.') : '';
}

async function ambilJamHari(hari) {
    if (CACHE_JAM[hari]) {
        return CACHE_JAM[hari];
    }

    const respons = await fetch(URL_JAM_HARI.replace('__HARI__', encodeURIComponent(hari)), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });

    if (!respons.ok) {
        throw new Error('Gagal memuat daftar jam pelajaran');
    }

    CACHE_JAM[hari] = await respons.json();
    return CACHE_JAM[hari];
}

function isiOpsiJam(idSelect, data, nilai) {
    const select = byId(idSelect);
    select.innerHTML = '';

    data.jam.forEach(j => {
        const opt = document.createElement('option');
        opt.value = j.jam_ke;
        opt.textContent = `Jam ${j.jam_ke} (${formatWaktu(j.jam_mulai)} - ${formatWaktu(j.jam_selesai)})`;
        select.appendChild(opt);
    });

    const pilihan = parseInt(nilai);
    const tersedia = data.jam.some(j => j.jam_ke === pilihan);
    select.value = String(tersedia ? pilihan : data.jam[0].jam_ke);
}

function rentangJam(dari, sampai) {
    return dari === sampai ? `${dari}` : `${dari}-${sampai}`;
}

function renderKeterangan(sufiks, data) {
}

function melewatiIstirahat(data, mulai, selesai) {
    return data.istirahat.some(i => i.setelah_jam >= mulai && i.setelah_jam < selesai);
}

function kumpulkanKesalahanJam(data, hari, mulai, selesai) {
    const errors = [];

    if (selesai < mulai) {
        errors.push('Jam selesai tidak boleh lebih awal dari jam mulai.');
    }

    if (data) {
        if (mulai > data.maks_jam || selesai > data.maks_jam) {
            errors.push(`Hari ${hari} hanya memiliki Jam 1 sampai Jam ${data.maks_jam}.`);
        }

        if (melewatiIstirahat(data, mulai, selesai)) {
            errors.push('Jadwal tidak boleh melewati jam istirahat. Harap pecah menjadi dua jadwal terpisah sebelum dan sesudah istirahat.');
        }
    }

    return errors;
}

function switchMatriksView(view) {
    const vKelas = byId('viewMatriksKelas');
    const vRuang = byId('viewMatriksRuangan');
    const btnKelas = byId('tabBtnKelas');
    const btnRuang = byId('tabBtnRuang');

    const aktifClass = 'w-full sm:w-auto px-4 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-2 bg-emerald-700 text-white shadow-sm';
    const nonAktifClass = 'w-full sm:w-auto px-4 py-2 rounded-xl text-xs font-bold transition flex items-center justify-center space-x-2 text-slate-600 hover:text-slate-900 hover:bg-white/60';

    if (view === 'ruang') {
        vKelas.classList.add('hidden');
        vRuang.classList.remove('hidden');
        btnRuang.className = aktifClass;
        btnKelas.className = nonAktifClass;
    } else {
        vRuang.classList.add('hidden');
        vKelas.classList.remove('hidden');
        btnKelas.className = aktifClass;
        btnRuang.className = nonAktifClass;
    }
    syncTopScrollDimension();
}

function openTambahModal() {
    byId('tambahModal').classList.remove('hidden');
    onTambahHariChange();
}

function closeTambahModal() {
    byId('tambahModal').classList.add('hidden');
}

async function onTambahHariChange() {
    const hari = byId('tambahHari').value;

    try {
        const data = await ambilJamHari(hari);
        if (byId('tambahHari').value !== hari) {
            return;
        }

        isiOpsiJam('tambahMulai', data, byId('tambahMulai').value);
        isiOpsiJam('tambahSelesai', data, byId('tambahSelesai').value);
        renderKeterangan('Tambah', data);
        onJamMulaiChange();
    } catch (e) {
        showModalMsg('Gagal Memuat Data', 'Gagal memuat daftar jam pelajaran. Muat ulang halaman lalu coba lagi.', 'error');
    }
}

function onJamMulaiChange() {
    const mulai = parseInt(byId('tambahMulai').value) || 1;
    const selesaiSelect = byId('tambahSelesai');
    if (parseInt(selesaiSelect.value) < mulai) {
        selesaiSelect.value = mulai;
    }
    evaluasiFormJadwal();
}

function onKelasChange() {
    const kSelect = byId('tambahKelas');
    const opt = kSelect.options[kSelect.selectedIndex];
    const ruangId = opt ? opt.getAttribute('data-ruangan') : null;
    if (ruangId) {
        byId('tambahRuangan').value = ruangId;
    }
    evaluasiFormJadwal();
}

async function tambahJadwalUntukRuangan(hari, idRuangan, jam) {
    byId('tambahHari').value = hari;

    try {
        const data = await ambilJamHari(hari);
        isiOpsiJam('tambahMulai', data, jam);
        isiOpsiJam('tambahSelesai', data, jam);
        renderKeterangan('Tambah', data);
    } catch (e) {
        showModalMsg('Gagal Memuat Data', 'Gagal memuat daftar jam pelajaran. Muat ulang halaman lalu coba lagi.', 'error');
        return;
    }

    byId('tambahModal').classList.remove('hidden');
    renderDropdownRuangan();
    byId('tambahRuangan').value = idRuangan;
    evaluasiFormJadwal();
}

function renderDropdownRuangan() {
    const hari = byId('tambahHari').value;
    const mulai = parseInt(byId('tambahMulai').value) || 1;
    const selesai = parseInt(byId('tambahSelesai').value) || mulai;
    const filterKosong = byId('checkFilterRuangKosong').checked;
    const selectRuangan = byId('tambahRuangan');
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

    const statusEl = byId('statusRuangText');
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
    const hari = byId('tambahHari').value;
    const mulai = parseInt(byId('tambahMulai').value) || 1;
    const selesai = parseInt(byId('tambahSelesai').value) || mulai;
    const selectGuru = byId('tambahGuru');
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
    defaultOpt.textContent = '-- Tanpa Guru / Belum Ditentukan --';
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

    const statusGuruEl = byId('statusGuruText');
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

function tampilkanHasilEvaluasi(alertBox, btnSimpan, errors) {
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

function kumpulkanBentrok(hari, mulai, selesai, idKelas, idRuang, idGuru, kecualiId) {
    const errors = [];
    const sama = j => (kecualiId === null || String(j.id) !== String(kecualiId)) &&
        j.hari === hari &&
        j.jam_ke_mulai <= selesai &&
        j.jam_ke_selesai >= mulai;

    if (idKelas) {
        const bentrokKelas = SEMUA_JADWAL.find(j => sama(j) && j.id_kelas === idKelas);
        if (bentrokKelas) {
            const namaMapel = bentrokKelas.mapel ? bentrokKelas.mapel.nama : 'Mapel Lain';
            errors.push(`Kelas ini sudah memiliki jadwal '${namaMapel}' pada jam tersebut.`);
        }
    }

    if (idRuang) {
        const bentrokRuang = SEMUA_JADWAL.find(j => sama(j) && j.id_ruangan === idRuang);
        if (bentrokRuang) {
            const namaKelas = bentrokRuang.kelas ? bentrokRuang.kelas.nama : 'Kelas Lain';
            errors.push(`Ruangan sudah digunakan oleh kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    if (idGuru) {
        const bentrokGuru = SEMUA_JADWAL.find(j => sama(j) && j.id_guru === idGuru);
        if (bentrokGuru) {
            const namaKelas = bentrokGuru.kelas ? bentrokGuru.kelas.nama : 'Kelas Lain';
            errors.push(`Guru yang dipilih sudah memiliki jadwal mengajar di kelas '${namaKelas}' pada jam tersebut.`);
        }
    }

    return errors;
}

function evaluasiFormJadwal() {
    renderDropdownRuangan();
    renderDropdownGuru();

    const hari = byId('tambahHari').value;
    const mulai = parseInt(byId('tambahMulai').value) || 1;
    const selesai = parseInt(byId('tambahSelesai').value) || mulai;
    const idKelas = parseInt(byId('tambahKelas').value);
    const idRuang = parseInt(byId('tambahRuangan').value);
    const idGuru = parseInt(byId('tambahGuru').value) || null;

    const errors = kumpulkanKesalahanJam(CACHE_JAM[hari], hari, mulai, selesai)
        .concat(kumpulkanBentrok(hari, mulai, selesai, idKelas, idRuang, idGuru, null));

    tampilkanHasilEvaluasi(byId('alertBentrokJadwal'), byId('btnSimpanJadwal'), errors);
}

async function openEditModalFromCell(sel) {
    currentEditId = sel.getAttribute('data-id');
    const hari = sel.getAttribute('data-hari');
    const kelas = sel.getAttribute('data-kelas');
    const mapel = sel.getAttribute('data-mapel');
    const ruang = sel.getAttribute('data-ruang');
    const guru = sel.getAttribute('data-guru');
    const mulai = sel.getAttribute('data-mulai');
    const selesai = sel.getAttribute('data-selesai');

    try {
        const data = await ambilJamHari(hari);
        isiOpsiJam('editMulai', data, mulai);
        isiOpsiJam('editSelesai', data, selesai);
        renderKeterangan('Edit', data);
    } catch (e) {
        showModalMsg('Gagal Memuat Data', 'Gagal memuat daftar jam pelajaran. Muat ulang halaman lalu coba lagi.', 'error');
        return;
    }

    byId('editForm').action = '/admin/jadwal/' + currentEditId;
    byId('deleteForm').action = '/admin/jadwal/' + currentEditId;
    byId('editHari').value = hari;
    byId('editKelas').value = kelas;
    byId('editMapel').value = mapel;
    byId('editRuang').value = ruang;
    byId('editGuru').value = guru || '';

    byId('editModal').classList.remove('hidden');
    evaluasiEditForm();
}

function closeEditModal() {
    byId('editModal').classList.add('hidden');
}

async function onEditHariChange() {
    const hari = byId('editHari').value;

    try {
        const data = await ambilJamHari(hari);
        if (byId('editHari').value !== hari) {
            return;
        }

        isiOpsiJam('editMulai', data, byId('editMulai').value);
        isiOpsiJam('editSelesai', data, byId('editSelesai').value);
        renderKeterangan('Edit', data);
        onEditJamMulaiChange();
    } catch (e) {
        showModalMsg('Gagal Memuat Data', 'Gagal memuat daftar jam pelajaran. Muat ulang halaman lalu coba lagi.', 'error');
    }
}

function onEditJamMulaiChange() {
    const mulai = parseInt(byId('editMulai').value) || 1;
    const selesaiSelect = byId('editSelesai');
    if (parseInt(selesaiSelect.value) < mulai) {
        selesaiSelect.value = mulai;
    }
    evaluasiEditForm();
}

function evaluasiEditForm() {
    const hari = byId('editHari').value;
    const mulai = parseInt(byId('editMulai').value) || 1;
    const selesai = parseInt(byId('editSelesai').value) || mulai;
    const idKelas = parseInt(byId('editKelas').value);
    const idRuang = parseInt(byId('editRuang').value);
    const idGuru = parseInt(byId('editGuru').value) || null;

    const errors = kumpulkanKesalahanJam(CACHE_JAM[hari], hari, mulai, selesai)
        .concat(kumpulkanBentrok(hari, mulai, selesai, idKelas, idRuang, idGuru, currentEditId));

    tampilkanHasilEvaluasi(byId('editAlertBentrok'), byId('btnSimpanEditJadwal'), errors);
}

function hapusJadwalAktif() {
    bukaKonfirmasi('Apakah Anda yakin ingin menghapus jadwal ini?', function() {
        byId('deleteForm').submit();
    }, { warna: 'rose', tombolTeks: 'Ya, Hapus' });
}

function getActiveMatriksElement() {
    const vKelas = byId('viewMatriksKelas');
    const vRuang = byId('viewMatriksRuangan');
    return (!vKelas || vKelas.classList.contains('hidden')) ? vRuang : vKelas;
}

function scrollMatriksHorizontal(direction) {
    const target = getActiveMatriksElement();
    if (!target) return;
    if (direction === 'start') {
        target.scrollTo({ left: 0, behavior: 'smooth' });
    } else if (direction === 'end') {
        target.scrollTo({ left: target.scrollWidth, behavior: 'smooth' });
    } else {
        target.scrollBy({ left: direction, behavior: 'smooth' });
    }
}

const topScrollWrapper = byId('topScrollWrapper');
const topScrollDummy = byId('topScrollDummy');
let isSyncingScroll = false;

function syncTopScrollDimension() {
    const active = getActiveMatriksElement();
    if (!active || !topScrollDummy) return;
    const targetWidth = active.scrollWidth;
    topScrollDummy.style.width = targetWidth + 'px';
    topScrollDummy.style.minWidth = targetWidth + 'px';
    if (topScrollWrapper) {
        topScrollWrapper.scrollLeft = active.scrollLeft;
    }
}

if (topScrollWrapper) {
    topScrollWrapper.addEventListener('scroll', function() {
        if (isSyncingScroll) return;
        isSyncingScroll = true;
        const active = getActiveMatriksElement();
        if (active) {
            active.scrollLeft = topScrollWrapper.scrollLeft;
        }
        isSyncingScroll = false;
    });
}

function attachMatriksScrollListener(el) {
    if (!el) return;
    el.addEventListener('scroll', function() {
        if (isSyncingScroll) return;
        isSyncingScroll = true;
        if (topScrollWrapper) {
            topScrollWrapper.scrollLeft = el.scrollLeft;
        }
        isSyncingScroll = false;
    });
}

attachMatriksScrollListener(byId('viewMatriksKelas'));
attachMatriksScrollListener(byId('viewMatriksRuangan'));

window.addEventListener('resize', syncTopScrollDimension);
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(syncTopScrollDimension, 60);
});
setTimeout(syncTopScrollDimension, 120);
</script>
@endsection

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
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Jadwal Pelajaran</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola jadwal belajar mengajar sesuai waktu operasional harian.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.jadwal.template') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-download text-emerald-700"></i>
                <span>Download Template</span>
            </a>
            <button type="button" onclick="openImportModal()" class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-600 text-white font-bold text-xs shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-upload"></i>
                <span>Import Excel</span>
            </button>
            <button type="button" onclick="triggerFixTeacherIds()" class="px-4 py-2.5 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold text-xs shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-wrench text-amber-600"></i>
                <span>Fix Teacher IDs</span>
            </button>
            <button type="button" onclick="openTambahModal()" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-plus text-emerald-400"></i>
                <span>Tambah Jadwal</span>
            </button>
        </div>
    </div>

    <div class="flex items-center space-x-2 bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200 overflow-x-auto w-fit">
        @foreach($daftarHari as $h)
            <a href="{{ route('admin.jadwal.index', array_merge(['hari' => $h], $parameterSesi)) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center space-x-2 whitespace-nowrap {{ $hariTerpilih === $h ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                <i class="fa-solid fa-calendar-day {{ $hariTerpilih === $h ? 'text-emerald-700' : 'text-slate-400' }}"></i>
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
                        <span class="font-semibold text-slate-700">{{ strtoupper($aturan->kegiatan) }}{{ $aturan->lokasi ? ' di ' . $aturan->lokasi : '' }}</span>
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
                        Klik tanda (+) pada sel kosong untuk menjadwalkan, atau klik sel jadwal untuk mengubah/menghapus.
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
                    <span>Matriks Ruangan</span>
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

<<<<<<< HEAD
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
=======
        <div id="viewMatriksKelas" class="overflow-x-auto border border-slate-200 rounded-2xl custom-scrollbar-x shadow-xs bg-white">
            <table class="w-full text-center text-xs border-collapse" style="min-width: {{ 220 + $jumlahKolom * 180 }}px">
>>>>>>> 2798e98 (feat: auto-split jadwal melewati jam istirahat, modul jadwal flutter reference, dan update landing page)
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="p-3.5 bg-slate-100 text-slate-800 font-extrabold border-r border-slate-200 min-w-[150px] w-40 text-center">
                            KELAS / WAKTU
                        </th>
                        @foreach($slotHari as $slot)
                            @if($slot->jenis === 'Pembiasaan')
                                <th class="p-3 bg-yellow-300 text-slate-900 font-black border-r border-yellow-400 min-w-[170px] w-44" title="{{ $slot->keterangan }}">
                                    <div class="text-xs font-black uppercase">{{ $slot->nama }}</div>
                                    <div class="text-[11px] font-semibold text-slate-700 mt-0.5">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</div>
                                </th>
                            @elseif($slot->jenis === 'Istirahat')
                                <th class="p-3 bg-pink-100 text-pink-900 font-black border-r border-pink-200 min-w-[150px] w-40" title="{{ $slot->nama }}">
                                    <div class="text-xs font-black text-pink-700 uppercase">ISTIRAHAT</div>
                                    <div class="text-[11px] font-medium text-pink-600 mt-0.5">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</div>
                                </th>
                            @else
                                <th class="p-3 bg-slate-50 text-slate-800 font-extrabold border-r border-slate-200 min-w-[180px] w-48">
                                    <div class="text-xs font-black text-emerald-800">Jam {{ $slot->jam_ke }}</div>
                                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $formatWaktu($slot->jam_mulai) }} - {{ $formatWaktu($slot->jam_selesai) }}</div>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($daftarKelas as $k)
                        @php
                            $jadwalKelas = $daftarJadwal->where('id_kelas', $k->id);
                            $label = $labelPembiasaan[$k->id];
                            $kelasWarnaPembiasaan = $label['rotasi'] && stripos($label['kegiatan'], 'upacara') !== false
                                ? 'bg-sky-50 text-sky-900 border-sky-200'
                                : 'bg-yellow-50 text-amber-900 border-yellow-200';
                            $lewatiSampai = 0;
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-black text-emerald-800 bg-slate-50/70 border-r border-slate-200 whitespace-nowrap text-center text-sm">
                                {{ $k->nama }}
                            </td>

                            @foreach($slotHari as $slot)
                                @if($slot->jenis === 'Pembiasaan')
                                    <td class="p-3 {{ $kelasWarnaPembiasaan }} font-bold border-r border-slate-200 text-xs">
                                        <div class="uppercase tracking-wide font-black text-[11px]">{{ strtoupper($label['kegiatan']) }}</div>
                                        @if($label['lokasi'])
                                            <div class="text-[10px] font-medium opacity-80 mt-1 flex items-center justify-center space-x-1">
                                                <i class="fa-solid fa-location-dot text-[9px]"></i>
                                                <span>{{ $label['lokasi'] }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    @continue
                                @endif

                                @if($slot->jenis === 'Istirahat')
                                    <td class="p-3 bg-pink-50/50 text-pink-700 font-bold border-r border-slate-200 text-xs text-center" title="{{ $slot->nama }}">
                                        <span class="tracking-widest font-black text-[11px]">ISTIRAHAT</span>
                                    </td>
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
                                        $namaGuru = $jdw->guru ? $jdw->guru->nama : '-';
                                        $namaRuangan = $jdw->ruangan ? $jdw->ruangan->nama : ($jdw->ruangan ? $jdw->ruangan->kode : '-');
                                    @endphp

                                    @if($jdw->is_kegiatan)
                                        <td colspan="{{ $span }}" class="p-3 border-r border-slate-200 bg-emerald-50/30 text-left align-top">
                                            <div class="bg-emerald-100/70 border border-emerald-200 rounded-xl p-2.5 text-center flex flex-col justify-between h-full min-h-[92px]">
                                                <div>
                                                    <span class="text-[10px] uppercase tracking-wider font-extrabold text-emerald-800 bg-emerald-200/60 px-2 py-0.5 rounded-md inline-block mb-1">
                                                        Kegiatan
                                                    </span>
                                                    <h4 class="font-black text-slate-900 text-xs leading-tight">
                                                        {{ $jdw->nama_kegiatan ?: 'Kegiatan' }}
                                                    </h4>
                                                </div>
                                                <div class="flex items-center justify-center space-x-3 pt-2 border-t border-emerald-200/60 text-[10px]">
                                                    <button type="button" onclick="openEditModalFromDirect('{{ $jdw->id }}', '{{ $jdw->hari }}', '{{ $jdw->id_kelas }}', '', '', '', '{{ $jdw->jam_ke_mulai }}', '{{ $jdw->jam_ke_selesai }}', true, '{{ addslashes($jdw->nama_kegiatan) }}')" class="font-bold text-emerald-800 hover:underline">
                                                        Edit
                                                    </button>
                                                    <span class="text-slate-300">|</span>
                                                    <button type="button" onclick="hapusJadwalDirect({{ $jdw->id }}, event)" class="font-bold text-rose-600 hover:text-rose-800">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @else
                                        <td colspan="{{ $span }}" class="p-3 border-r border-slate-200 bg-white text-left align-top group hover:bg-slate-50 transition">
                                            <div class="bg-indigo-50/50 hover:bg-indigo-50/80 border border-indigo-100 rounded-xl p-2.5 flex flex-col justify-between h-full min-h-[96px] transition cursor-pointer" onclick="openEditModalFromDirect('{{ $jdw->id }}', '{{ $jdw->hari }}', '{{ $jdw->id_kelas }}', '{{ $jdw->id_mapel }}', '{{ $jdw->id_ruangan }}', '{{ $jdw->id_guru }}', '{{ $jdw->jam_ke_mulai }}', '{{ $jdw->jam_ke_selesai }}', false, '')">
                                                <div>
                                                    <div class="bg-indigo-600 text-white font-bold text-[11px] px-2 py-1 rounded-lg truncate text-center shadow-2xs mb-1.5" title="{{ $jdw->mapel ? $jdw->mapel->nama : '-' }}">
                                                        {{ $jdw->mapel ? $jdw->mapel->nama : '-' }}
                                                    </div>
                                                    <div class="flex items-center space-x-1.5 text-[11px] text-slate-600 mb-1">
                                                        <i class="fa-solid fa-door-open text-slate-400 text-[10px] w-3.5 text-center"></i>
                                                        <span class="font-semibold truncate">{{ $namaRuangan }}</span>
                                                    </div>
                                                    <div class="flex items-center space-x-1.5 text-[11px] text-slate-600">
                                                        <i class="fa-solid fa-user-tie text-slate-400 text-[10px] w-3.5 text-center"></i>
                                                        <span class="truncate" title="{{ $namaGuru }}">{{ $namaGuru }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between pt-2 border-t border-indigo-100 text-[10px] mt-2">
                                                    <span class="text-indigo-600 font-bold">Jam {{ $jdw->jam_ke_mulai }}-{{ $jdw->jam_ke_selesai }}</span>
                                                    <button type="button" onclick="hapusJadwalDirect({{ $jdw->id }}, event)" class="font-bold text-rose-600 hover:text-rose-800 transition">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                @else
                                    <td class="p-3 border-r border-slate-200 bg-white text-center align-middle hover:bg-emerald-50/30 transition cursor-pointer" onclick="openTambahModalForCell({{ $k->id }}, {{ $slot->jam_ke }}, '{{ $hariTerpilih }}')" title="Tambah Jadwal {{ $k->nama }} - Jam {{ $slot->jam_ke }}">
                                        <div class="w-full h-24 border border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center text-slate-300 hover:border-emerald-400 hover:text-emerald-600 hover:bg-white transition group/btn">
                                            <i class="fa-solid fa-plus text-base group-hover/btn:scale-125 transition-transform duration-200"></i>
                                            <span class="text-[9px] font-bold mt-1 text-slate-400 group-hover/btn:text-emerald-700">Atur</span>
                                        </div>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $jumlahKolom + 1 }}" class="p-8 text-center text-slate-400">Belum ada data kelas yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="viewMatriksRuangan" class="hidden overflow-x-auto border border-slate-200 rounded-2xl custom-scrollbar-x shadow-xs bg-white">
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
                                    $pakai = $daftarJadwal->first(fn ($j) => !$j->is_kegiatan && $j->id_ruangan == $r->id && $jam >= $j->jam_ke_mulai && $jam <= $j->jam_ke_selesai);
                                    if ($pakai) {
                                        $rekapTerpakai[$jam]++;
                                    } else {
                                        $rekapKosong[$jam]++;
                                    }
                                @endphp
                                @if($pakai)
                                    <td class="p-2 bg-rose-100 text-rose-900 font-bold border border-rose-200 text-xs cursor-pointer hover:bg-rose-200 transition"
                                        onclick="openEditModalFromDirect('{{ $pakai->id }}', '{{ $pakai->hari }}', '{{ $pakai->id_kelas }}', '{{ $pakai->id_mapel }}', '{{ $pakai->id_ruangan }}', '{{ $pakai->id_guru }}', '{{ $pakai->jam_ke_mulai }}', '{{ $pakai->jam_ke_selesai }}', false, '')"
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
                <span class="font-medium text-slate-700">Geser horizontal scroll bar di atas atau gunakan tombol navigasi jam:</span>
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
                            <th class="p-3.5 whitespace-nowrap min-w-[160px]">Mata Pelajaran / Kegiatan</th>
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
                                <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">
                                    @if($j->is_kegiatan)
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-lg text-xs font-extrabold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-flag text-[10px]"></i>
                                            <span>{{ $j->nama_kegiatan ?: 'Kegiatan' }}</span>
                                        </span>
                                    @else
                                        {{ $j->mapel ? $j->mapel->nama : '-' }}
                                    @endif
                                </td>
                                <td class="p-3.5 text-slate-700 text-xs font-semibold whitespace-nowrap">
                                    @if($j->is_kegiatan)
                                        <span class="text-slate-400 italic font-normal">-</span>
                                    @elseif($j->guru)
                                        {{ $j->guru->nama }}
                                    @else
                                        <span class="text-slate-400 italic font-normal">Tanpa Guru / -</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-slate-600 text-xs whitespace-nowrap">
                                    {{ $j->ruangan ? $j->ruangan->nama : '-' }}
                                </td>
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
                                            onclick="openEditModalFromDirect('{{ $j->id }}', '{{ $j->hari }}', '{{ $j->id_kelas }}', '{{ $j->id_mapel }}', '{{ $j->id_ruangan }}', '{{ $j->id_guru }}', '{{ $j->jam_ke_mulai }}', '{{ $j->jam_ke_selesai }}', {{ $j->is_kegiatan ? 'true' : 'false' }}, '{{ addslashes($j->nama_kegiatan ?? '') }}')"
                                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                                            <i class="fa-solid fa-pen mr-1"></i> Edit
                                        </button>
                                        <button type="button" onclick="hapusJadwalDirect({{ $j->id }}, event)" class="bg-rose-50 hover:bg-rose-100 text-rose-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer">
                                            <i class="fa-solid fa-trash mr-1"></i> Hapus
                                        </button>
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
    <div class="bg-white rounded-3xl p-7 max-w-xl w-full shadow-2xl relative max-h-[92vh] overflow-y-auto">
        <button type="button" onclick="closeTambahModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center justify-between mb-4 pr-8">
            <div>
                <h3 id="tambahModalTitle" class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-emerald-700"></i>
                    <span>Atur Jadwal</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Atur jadwal belajar mengajar atau kegiatan operasional sekolah.</p>
            </div>
            <label class="flex items-center space-x-2 bg-slate-100 px-3 py-1.5 rounded-xl cursor-pointer select-none border border-slate-200">
                <input type="checkbox" id="tambahIsKegiatan" name="is_kegiatan" value="1" onchange="toggleKegiatanMode('tambah', this.checked)" class="rounded text-emerald-600 focus:ring-emerald-500">
                <span class="text-xs font-bold text-slate-700">Kegiatan</span>
            </label>
        </div>

        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" id="tambahIsKegiatanHidden" name="is_kegiatan" value="0">

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

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Kelas</label>
                <select name="id_kelas" id="tambahKelas" onchange="onKelasChange()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" data-ruangan="{{ $k->id_ruangan }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="tambahKegiatanSection" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" id="tambahNamaKegiatan" value="Istirahat" placeholder="Misal: Istirahat, Rapat, Upacara, Literasi, dll" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>

            <div id="tambahPelajaranSection" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru Pengajar <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                    <select name="id_guru" id="tambahGuru" onchange="onGuruChange('tambah'); evaluasiFormJadwal();" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                        <option value="">-- Pilih Guru / Belum Ditentukan --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?: ($g->mapel_utama ?? 'Guru') }})</option>
                        @endforeach
                    </select>
                    <div id="statusGuruText" class="text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Mata Pelajaran</label>
                    <select name="id_mapel" id="tambahMapel" onchange="onMapelChange('tambah'); evaluasiFormJadwal();" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->kode }} - {{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-slate-700">Pilih Ruangan</label>
                        <label class="flex items-center space-x-1.5 text-xs text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" id="checkFilterRuangKosong" onchange="renderDropdownRuangan()" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="font-medium">Sembunyikan terpakai</span>
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
            </div>

            <div id="alertBentrokJadwal" class="p-3.5 rounded-2xl text-xs font-semibold hidden"></div>

            <div class="flex justify-end space-x-3 pt-3">
                <button type="button" onclick="closeTambahModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                <button type="submit" id="btnSimpanJadwal" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl shadow transition text-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-7 max-w-xl w-full shadow-2xl relative max-h-[92vh] overflow-y-auto">
        <button type="button" onclick="closeEditModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="flex items-center justify-between mb-4 pr-8">
            <div>
                <h3 id="editModalTitle" class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-700"></i>
                    <span>Edit Jadwal</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi mata pelajaran, guru, ruangan, atau jam pelajaran.</p>
            </div>
            <label class="flex items-center space-x-2 bg-slate-100 px-3 py-1.5 rounded-xl cursor-pointer select-none border border-slate-200">
                <input type="checkbox" id="editIsKegiatan" name="is_kegiatan" value="1" onchange="toggleKegiatanMode('edit', this.checked)" class="rounded text-emerald-600 focus:ring-emerald-500">
                <span class="text-xs font-bold text-slate-700">Kegiatan</span>
            </label>
        </div>

        <form id="editForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editIsKegiatanHidden" name="is_kegiatan" value="0">

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

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Kelas</label>
                <select id="editKelas" name="id_kelas" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm font-semibold" required>
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="editKegiatanSection" class="hidden space-y-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" id="editNamaKegiatan" placeholder="Misal: Istirahat, Rapat, Upacara, Literasi, dll" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
            </div>

            <div id="editPelajaranSection" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru Pengajar <span class="text-xs text-slate-400 font-normal">(Opsional)</span></label>
                    <select id="editGuru" name="id_guru" onchange="onGuruChange('edit'); evaluasiEditForm();" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm">
                        <option value="">-- Tanpa Guru / Belum Ditentukan --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }} ({{ $g->nip ?: ($g->mapel_utama ?? 'Guru') }})</option>
                        @endforeach
                    </select>
                    <div id="editStatusGuruText" class="text-xs mt-1"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Mata Pelajaran</label>
                    <select id="editMapel" name="id_mapel" onchange="onMapelChange('edit'); evaluasiEditForm();" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}">{{ $m->kode }} - {{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Ruangan</label>
                    <select id="editRuang" name="id_ruangan" onchange="evaluasiEditForm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        @foreach($daftarRuangan as $r)
                            <option value="{{ $r->id }}">{{ $r->kode }} - {{ $r->nama }}</option>
                        @endforeach
                    </select>
                    <div id="editStatusRuangText" class="text-xs mt-1"></div>
                </div>
            </div>

            <div id="editAlertBentrok" class="p-3.5 rounded-2xl text-xs font-semibold hidden"></div>

            <div class="flex items-center justify-between pt-3">
                <button type="button" id="btnHapusJadwal" onclick="hapusJadwalAktif()" class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold transition cursor-pointer">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Jadwal
                </button>
                <div class="flex space-x-2">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50">Batal</button>
                    <button type="submit" id="btnSimpanEditJadwal" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-5 py-2.5 rounded-xl shadow transition text-sm">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="importModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-7 max-w-lg w-full shadow-2xl relative">
        <button type="button" onclick="closeImportModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="mb-5">
            <h3 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-file-excel text-emerald-700"></i>
                <span>Import Jadwal Excel</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1">Unggah file jadwal berformat .xlsx atau .csv sesuai template sistem.</p>
        </div>

        <form method="POST" action="{{ route('admin.jadwal.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-emerald-600 transition bg-slate-50/50">
                <i class="fa-solid fa-cloud-arrow-up text-3xl text-emerald-700 mb-2"></i>
                <p class="text-xs font-semibold text-slate-700">Pilih berkas Excel (.xlsx) atau CSV (.csv)</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Maksimal 10 MB per berkas</p>
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="mt-4 block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-600 cursor-pointer">
            </div>

            <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-3 text-xs text-emerald-900">
                <p class="font-bold flex items-center space-x-1 mb-1">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Struktur Kolom Template:</span>
                </p>
                <p class="text-[11px] text-emerald-800 font-mono">Hari, Slot, Kelas, Mapel, Ruangan, Guru</p>
            </div>

            <div class="flex justify-end space-x-2.5 pt-2">
                <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50">Batal</button>
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-5 py-2.5 rounded-xl shadow transition text-xs flex items-center space-x-2">
                    <i class="fa-solid fa-upload"></i>
                    <span>Unggah & Impor</span>
                </button>
            </div>
        </form>
    </div>
</div>

<form id="directDeleteForm" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>

<form id="fixTeacherIdsForm" method="POST" action="{{ route('admin.jadwal.fix-teacher-ids') }}" class="hidden">
    @csrf
</form>

<script>
const SEMUA_JADWAL = @json($semuaJadwal);
const DAFTAR_RUANGAN = @json($daftarRuangan);
const DAFTAR_GURU = @json($daftarGuru);
const DAFTAR_KELAS = @json($daftarKelas);
const DAFTAR_MAPEL = @json($daftarMapel);
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

function toggleKegiatanMode(prefix, isKegiatan) {
    const kegiatanSection = byId(prefix + 'KegiatanSection');
    const pelajaranSection = byId(prefix + 'PelajaranSection');
    const checkbox = byId(prefix + 'IsKegiatan');
    const hiddenInput = byId(prefix + 'IsKegiatanHidden');

    if (checkbox) checkbox.checked = isKegiatan;
    if (hiddenInput) hiddenInput.value = isKegiatan ? '1' : '0';

    if (isKegiatan) {
        kegiatanSection.classList.remove('hidden');
        pelajaranSection.classList.add('hidden');
        byId(prefix + 'NamaKegiatan').required = true;
        byId(prefix + 'Mapel').required = false;
        byId(prefix + 'Ruangan').required = false;
    } else {
        kegiatanSection.classList.add('hidden');
        pelajaranSection.classList.remove('hidden');
        byId(prefix + 'NamaKegiatan').required = false;
        byId(prefix + 'Mapel').required = true;
        byId(prefix + 'Ruangan').required = true;
    }

    if (prefix === 'tambah') evaluasiFormJadwal();
    if (prefix === 'edit') evaluasiEditForm();
}

function onGuruChange(prefix) {
    const guruSelect = byId(prefix + 'Guru');
    const mapelSelect = byId(prefix + 'Mapel');
    const guruId = guruSelect.value ? parseInt(guruSelect.value) : null;
    const currentMapelId = mapelSelect.value ? parseInt(mapelSelect.value) : null;

    if (!guruId) {
        Array.from(mapelSelect.options).forEach(opt => {
            opt.hidden = false;
        });
        return;
    }

    const guru = DAFTAR_GURU.find(g => g.id === guruId);
    const allowedMapelIds = (guru && Array.isArray(guru.mapel_ids)) ? guru.mapel_ids : [];

    let hasSelectedValid = false;
    Array.from(mapelSelect.options).forEach(opt => {
        if (!opt.value) {
            opt.hidden = false;
            return;
        }
        const mId = parseInt(opt.value);
        if (allowedMapelIds.length === 0 || allowedMapelIds.includes(mId)) {
            opt.hidden = false;
            if (mId === currentMapelId) hasSelectedValid = true;
        } else {
            opt.hidden = true;
        }
    });

    if (currentMapelId && !hasSelectedValid && allowedMapelIds.length > 0) {
        mapelSelect.value = '';
    }
}

function onMapelChange(prefix) {
    const mapelSelect = byId(prefix + 'Mapel');
    const guruSelect = byId(prefix + 'Guru');
    const mapelId = mapelSelect.value ? parseInt(mapelSelect.value) : null;

    if (!mapelId) return;

    const currentGuruId = guruSelect.value ? parseInt(guruSelect.value) : null;
    if (currentGuruId) {
        const guru = DAFTAR_GURU.find(g => g.id === currentGuruId);
        if (guru && Array.isArray(guru.mapel_ids) && guru.mapel_ids.length > 0 && !guru.mapel_ids.includes(mapelId)) {
            guruSelect.value = '';
        }
    }
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
    toggleKegiatanMode('tambah', false);
    byId('tambahModalTitle').innerHTML = '<i class="fa-solid fa-plus text-emerald-700"></i><span>Atur Jadwal</span>';
    byId('tambahModal').classList.remove('hidden');
    onTambahHariChange();
}

function closeTambahModal() {
    byId('tambahModal').classList.add('hidden');
}

function openTambahModalForCell(kelasId, jamKe, hari) {
    byId('tambahHari').value = hari || @json($hariTerpilih);
    byId('tambahKelas').value = kelasId;
    toggleKegiatanMode('tambah', false);
    byId('tambahModalTitle').innerHTML = `<i class="fa-solid fa-plus text-emerald-700"></i><span>Atur Jadwal - Jam ${jamKe}</span>`;

    ambilJamHari(byId('tambahHari').value).then(data => {
        isiOpsiJam('tambahMulai', data, jamKe);
        isiOpsiJam('tambahSelesai', data, jamKe);
        byId('tambahModal').classList.remove('hidden');
        onKelasChange();
        evaluasiFormJadwal();
    }).catch(() => {
        byId('tambahModal').classList.remove('hidden');
    });
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
    toggleKegiatanMode('tambah', false);

    try {
        const data = await ambilJamHari(hari);
        isiOpsiJam('tambahMulai', data, jam);
        isiOpsiJam('tambahSelesai', data, jam);
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
            !j.is_kegiatan &&
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
            !j.is_kegiatan &&
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
    defaultOpt.textContent = '-- Pilih Guru / Belum Ditentukan --';
    selectGuru.appendChild(defaultOpt);

    if (guruTersedia.length > 0) {
        const groupBebas = document.createElement('optgroup');
        groupBebas.label = 'Guru Tersedia (Bebas Jam Mengajar)';
        guruTersedia.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = `${g.nama} (${g.nip || g.mapel_utama || 'Guru'})`;
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

function pecahRentangJamJs(dataJam, mulai, selesai) {
    if (!dataJam || !Array.isArray(dataJam.istirahat)) {
        return [{ mulai: mulai, selesai: selesai }];
    }
    const titikIstirahat = [];
    dataJam.istirahat.forEach(ist => {
        const setelah = parseInt(ist.setelah_jam);
        if (setelah >= mulai && setelah < selesai) {
            titikIstirahat.push(setelah);
        }
    });
    titikIstirahat.sort((a, b) => a - b);
    if (titikIstirahat.length === 0) {
        return [{ mulai: mulai, selesai: selesai }];
    }
    const segmen = [];
    let curMulai = mulai;
    titikIstirahat.forEach(titik => {
        segmen.push({ mulai: curMulai, selesai: titik });
        curMulai = titik + 1;
    });
    if (curMulai <= selesai) {
        segmen.push({ mulai: curMulai, selesai: selesai });
    }
    return segmen;
}

function tampilkanHasilEvaluasi(alertBox, btnSimpan, errors, segmen = []) {
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
    } else if (segmen && segmen.length > 1) {
        const detailSesi = segmen.map(s => s.mulai === s.selesai ? `Jam ${s.mulai}` : `Jam ${s.mulai}-${s.selesai}`).join(' dan ');
        alertBox.className = 'p-3.5 rounded-2xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-2';
        alertBox.innerHTML = `
            <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-sm"></i>
            <div>
                <p class="font-bold text-emerald-900">Otomatis Terpisah Melewati Istirahat</p>
                <p class="text-emerald-700 mt-0.5">Jadwal melewati waktu istirahat dan akan otomatis disimpan menjadi ${segmen.length} sesi terpisah (${detailSesi}).</p>
            </div>
        `;
        alertBox.classList.remove('hidden');
        btnSimpan.disabled = false;
        btnSimpan.classList.remove('opacity-50', 'cursor-not-allowed');
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
    const sama = j => !j.is_kegiatan && (kecualiId === null || String(j.id) !== String(kecualiId)) &&
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

    const isKegiatan = byId('tambahIsKegiatan').checked;
    const hari = byId('tambahHari').value;
    const mulai = parseInt(byId('tambahMulai').value) || 1;
    const selesai = parseInt(byId('tambahSelesai').value) || mulai;
    const idKelas = parseInt(byId('tambahKelas').value);
    const idRuang = isKegiatan ? null : parseInt(byId('tambahRuangan').value);
    const idGuru = isKegiatan ? null : (parseInt(byId('tambahGuru').value) || null);
    const dataJam = CACHE_JAM[hari];
    const segmen = pecahRentangJamJs(dataJam, mulai, selesai);

    let errors = [];
    if (selesai < mulai) {
        errors.push('Jam selesai tidak boleh lebih awal dari jam mulai.');
    }

    if (dataJam && (mulai > dataJam.maks_jam || selesai > dataJam.maks_jam)) {
        errors.push(`Hari ${hari} hanya memiliki Jam 1 sampai Jam ${dataJam.maks_jam}.`);
    }

    if (!isKegiatan) {
        segmen.forEach(s => {
            errors = errors.concat(kumpulkanBentrok(hari, s.mulai, s.selesai, idKelas, idRuang, idGuru, null));
        });
        errors = Array.from(new Set(errors));
    }

    tampilkanHasilEvaluasi(byId('alertBentrokJadwal'), byId('btnSimpanJadwal'), errors, segmen);
}

function openEditModalFromDirect(id, hari, kelasId, mapelId, ruangId, guruId, mulai, selesai, isKegiatan, namaKegiatan) {
    currentEditId = id;
    byId('editForm').action = '/admin/jadwal/' + id;
    byId('editHari').value = hari;
    byId('editKelas').value = kelasId;

    toggleKegiatanMode('edit', isKegiatan);

    if (isKegiatan) {
        byId('editNamaKegiatan').value = namaKegiatan || 'Kegiatan';
    } else {
        byId('editMapel').value = mapelId;
        byId('editRuang').value = ruangId;
        byId('editGuru').value = guruId || '';
        onGuruChange('edit');
    }

    byId('editModalTitle').innerHTML = `<i class="fa-solid fa-pen-to-square text-emerald-700"></i><span>Edit Jadwal - Jam ${mulai}</span>`;

    ambilJamHari(hari).then(data => {
        isiOpsiJam('editMulai', data, mulai);
        isiOpsiJam('editSelesai', data, selesai);
        byId('editModal').classList.remove('hidden');
        evaluasiEditForm();
    }).catch(() => {
        byId('editModal').classList.remove('hidden');
    });
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
    const isKegiatan = byId('editIsKegiatan').checked;
    const hari = byId('editHari').value;
    const mulai = parseInt(byId('editMulai').value) || 1;
    const selesai = parseInt(byId('editSelesai').value) || mulai;
    const idKelas = parseInt(byId('editKelas').value);
    const idRuang = isKegiatan ? null : parseInt(byId('editRuang').value);
    const idGuru = isKegiatan ? null : (parseInt(byId('editGuru').value) || null);
    const dataJam = CACHE_JAM[hari];
    const segmen = pecahRentangJamJs(dataJam, mulai, selesai);

    let errors = [];
    if (selesai < mulai) {
        errors.push('Jam selesai tidak boleh lebih awal dari jam mulai.');
    }

    if (dataJam && (mulai > dataJam.maks_jam || selesai > dataJam.maks_jam)) {
        errors.push(`Hari ${hari} hanya memiliki Jam 1 sampai Jam ${dataJam.maks_jam}.`);
    }

    if (!isKegiatan) {
        segmen.forEach((s, idx) => {
            const kecId = (idx === 0) ? currentEditId : null;
            errors = errors.concat(kumpulkanBentrok(hari, s.mulai, s.selesai, idKelas, idRuang, idGuru, kecId));
        });
        errors = Array.from(new Set(errors));
    }

    tampilkanHasilEvaluasi(byId('editAlertBentrok'), byId('btnSimpanEditJadwal'), errors, segmen);
}

function hapusJadwalAktif() {
    if (!currentEditId) return;
    bukaKonfirmasi('Apakah Anda yakin ingin menghapus jadwal ini?', function() {
        const form = byId('directDeleteForm');
        form.action = '/admin/jadwal/' + currentEditId;
        form.submit();
    }, { warna: 'rose', tombolTeks: 'Ya, Hapus' });
}

function hapusJadwalDirect(id, event) {
    if (event) event.stopPropagation();
    bukaKonfirmasi('Apakah Anda yakin ingin menghapus jadwal ini?', function() {
        const form = byId('directDeleteForm');
        form.action = '/admin/jadwal/' + id;
        form.submit();
    }, { warna: 'rose', tombolTeks: 'Ya, Hapus' });
}

function triggerFixTeacherIds() {
    bukaKonfirmasi('Ini akan memperbaiki jadwal yang memiliki teacher ID berupa nama guru menjadi ID yang valid. Lanjutkan?', function() {
        byId('fixTeacherIdsForm').submit();
    }, { warna: 'emerald', tombolTeks: 'Ya, Perbaiki' });
}

function openImportModal() {
    byId('importModal').classList.remove('hidden');
}

function closeImportModal() {
    byId('importModal').classList.add('hidden');
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

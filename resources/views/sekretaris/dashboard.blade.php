@extends('layouts.sekretaris', ['title' => 'Portal Sekretaris - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex space-x-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto">
        <button type="button" onclick="switchSekreTab('pengganti')" id="btnSekreTab_pengganti" class="sekre-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'pengganti' ? 'bg-amber-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-user-pen"></i>
            <span>A. Pengganti Absensi Guru</span>
        </button>
        <button type="button" onclick="switchSekreTab('jadwal')" id="btnSekreTab_jadwal" class="sekre-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'jadwal' ? 'bg-amber-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-calendar-days"></i>
            <span>B. Jadwal Kelas</span>
        </button>
        <button type="button" onclick="switchSekreTab('laporan')" id="btnSekreTab_laporan" class="sekre-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'laporan' ? 'bg-amber-600 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-clipboard-user"></i>
            <span>C. Laporan Absensi</span>
        </button>
    </div>

    <div id="sekreTab_pengganti" class="sekre-tab-content space-y-6 {{ $tabAktif === 'pengganti' ? '' : 'hidden' }}">
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="text-center max-w-xl mx-auto">
                <div class="w-14 h-14 bg-amber-100 text-amber-800 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900">Jadwal Kelas & Pengganti Absensi Guru</h3>
                <p class="text-xs text-slate-500 mt-1">Jika guru berhalangan hadir, sekretaris dapat menggantikan pencatatan absensi beserta alasannya.</p>
            </div>

            <form method="POST" action="{{ route('sekretaris.absensi-pengganti') }}" id="formPengganti" class="space-y-4 max-w-2xl mx-auto">
                @csrf
                <div>
                    <label for="pilihGuru" class="block text-xs font-bold text-slate-700 mb-1">Pilih Guru Berhalangan</label>
                    <select id="pilihGuru" name="id_guru" required onchange="tampilkanJadwalGuru(this.value)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none bg-white text-xs focus:border-amber-600">
                        <option value="">-- Pilih Guru Pengajar --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ old('id_guru') == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} ({{ $g->mapel_utama ?? 'Guru Pengajar' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="infoJadwalBox" class="hidden p-4 bg-amber-50/70 border border-amber-200/80 rounded-xl space-y-2">
                    <div class="flex items-center space-x-2 text-amber-900 font-bold text-xs">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Jadwal Mengajar Hari Ini ({{ $namaHariIni }}):</span>
                    </div>
                    <div id="daftarJadwalTerdampak" class="text-xs text-slate-700 space-y-1">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="statusKehadiran" class="block text-xs font-bold text-slate-700 mb-1">Status Kehadiran</label>
                        <select id="statusKehadiran" name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none bg-white text-xs focus:border-amber-600">
                            <option value="Sakit" {{ old('status') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin" {{ old('status') === 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Alpa" {{ old('status') === 'Alpa' ? 'selected' : '' }}>Alpa / Tanpa Keterangan</option>
                            <option value="Hadir" {{ old('status') === 'Hadir' ? 'selected' : '' }}>Hadir (Lupa Scan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($tanggalHariIni)->locale('id')->isoFormat('dddd, D MMMM Y') }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-600" readonly>
                    </div>
                </div>

                <div>
                    <label for="alasanInput" class="block text-xs font-bold text-slate-700 mb-1">Alasan Ketidakhadiran</label>
                    <textarea id="alasanInput" name="alasan" rows="3" required placeholder="Tuliskan keterangan surat dokter, keperluan dinas, atau alasan izin..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-xs focus:border-amber-600">{{ old('alasan') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 rounded-xl shadow transition text-xs flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Absensi Pengganti Sekretaris</span>
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                        <i class="fa-solid fa-user-clock text-amber-600"></i>
                        <span>Daftar Guru Belum Presensi Hari Ini</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Guru yang belum melakukan scan kehadiran mandiri hari ini.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    {{ $guruBelumAbsen->count() }} Guru
                </span>
            </div>

            @if($guruBelumAbsen->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($guruBelumAbsen as $gba)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between">
                            <div class="min-w-0 pr-2">
                                <h4 class="font-bold text-slate-900 text-xs truncate">{{ $gba->nama }}</h4>
                                <span class="text-[10px] text-slate-400 block font-mono">{{ $gba->nip ?? 'NIP: -' }}</span>
                                <span class="text-[10px] text-slate-500 truncate block">{{ $gba->mapel_utama ?? 'Guru Pengajar' }}</span>
                            </div>
                            <button type="button" onclick="pilihGuruCepat('{{ $gba->id }}')" class="px-2.5 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-[11px] font-bold rounded-lg transition whitespace-nowrap shadow-sm">
                                Pilih
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 bg-emerald-50 text-emerald-800 rounded-xl text-center text-xs font-semibold">
                    <i class="fa-solid fa-circle-check mr-1.5 text-emerald-600"></i>
                    Seluruh guru telah melakukan presensi kehadiran untuk hari ini.
                </div>
            @endif
        </div>
    </div>

    <div id="sekreTab_jadwal" class="sekre-tab-content space-y-6 {{ $tabAktif === 'jadwal' ? '' : 'hidden' }}">
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-calendar-days text-amber-600"></i>
                        <span>B. Jadwal Pelajaran (Hanya-Baca)</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Matriks jadwal seluruh kelas dan rombongan belajar SMKN 13 Bandung.</p>
                </div>
                <div class="flex items-center space-x-1.5 bg-slate-100 p-1 rounded-xl overflow-x-auto">
                    @foreach($daftarHari as $dh)
                        <a href="{{ route('sekretaris.dashboard', ['tab' => 'jadwal', 'hari' => $dh]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold transition whitespace-nowrap {{ $hariMatriks === $dh ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-200' }}">
                            {{ $dh }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                <table class="w-full text-center text-xs min-w-[900px] border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-slate-700 font-bold">
                            <th rowspan="2" class="p-3 bg-slate-200 border-r border-slate-300 w-28">KELAS</th>
                            <th rowspan="2" class="p-3 bg-slate-200 border-r border-slate-300 w-20">JAM KE</th>
                            @foreach($slotJam as $slot)
                                @if($slot->jenis === 'Carabika')
                                    <th class="p-2 bg-yellow-300 text-slate-900 font-black border-r border-slate-300">{{ $slot->nama }}</th>
                                @elseif($slot->jenis === 'Istirahat')
                                    <th class="p-2 bg-pink-400 text-white font-black border-r border-slate-300">{{ $slot->nama }}</th>
                                @else
                                    <th class="p-2 bg-emerald-700 text-white font-black border-r border-slate-300">Jam {{ $slot->jam_ke }}</th>
                                @endif
                            @endforeach
                        </tr>
                        <tr class="border-b border-slate-300 text-[10px] text-slate-600">
                            @foreach($slotJam as $slot)
                                <th class="p-1.5 bg-slate-100 border-r border-slate-300 font-mono whitespace-nowrap">
                                    {{ \Illuminate\Support\Str::substr($slot->jam_mulai, 0, 5) }}-{{ \Illuminate\Support\Str::substr($slot->jam_selesai, 0, 5) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y-2 divide-slate-300">
                        @forelse($daftarKelas as $kelas)
                            @php
                                $jadwalKelasHari = $jadwalMatriks->where('id_kelas', $kelas->id);
                            @endphp
                            <tr class="border-t-2 border-slate-300 bg-white">
                                <td rowspan="3" class="p-3 bg-slate-50 border-r-2 border-slate-300 font-black text-slate-900 text-sm align-middle">
                                    {{ $kelas->nama }}
                                </td>
                                <td class="p-2 bg-slate-100 border-r border-slate-300 font-bold text-slate-700 text-[11px]">MAPEL</td>
                                @foreach($slotJam as $slot)
                                    @if($slot->jenis === 'Carabika')
                                        <td rowspan="3" class="p-2 bg-yellow-100/70 border-r border-slate-300 font-bold text-slate-800 text-[11px] align-middle">
                                            {{ $hariMatriks === 'Senin' ? 'UPACARA' : 'PERWALIAN' }}
                                        </td>
                                    @elseif($slot->jenis === 'Istirahat')
                                        <td rowspan="3" class="p-2 bg-pink-100/70 text-pink-900 border-r border-slate-300 font-black text-[10px] uppercase tracking-wider align-middle">
                                            ISTIRAHAT
                                        </td>
                                    @else
                                        @php
                                            $jadwalSlot = $jadwalKelasHari->first(function ($j) use ($slot) {
                                                return $slot->jam_ke >= $j->jam_ke_mulai && $slot->jam_ke <= $j->jam_ke_selesai;
                                            });
                                        @endphp
                                        <td class="p-2 border-r border-slate-200 {{ $jadwalSlot ? 'bg-emerald-100 font-bold text-emerald-950' : 'text-slate-300' }}">
                                            {{ $jadwalSlot ? $jadwalSlot->mapel->nama : '-' }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <tr class="bg-white">
                                <td class="p-2 bg-slate-100 border-r border-slate-300 font-bold text-slate-700 text-[11px]">RUANG</td>
                                @foreach($slotJam as $slot)
                                    @if($slot->jenis === 'Pelajaran')
                                        @php
                                            $jadwalSlot = $jadwalKelasHari->first(function ($j) use ($slot) {
                                                return $slot->jam_ke >= $j->jam_ke_mulai && $slot->jam_ke <= $j->jam_ke_selesai;
                                            });
                                        @endphp
                                        <td class="p-2 border-r border-slate-200 text-slate-600 font-medium">
                                            {{ $jadwalSlot && $jadwalSlot->ruangan ? $jadwalSlot->ruangan->nama : '-' }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>

                            <tr class="bg-white">
                                <td class="p-2 bg-slate-100 border-r border-slate-300 font-bold text-slate-700 text-[11px]">GURU</td>
                                @foreach($slotJam as $slot)
                                    @if($slot->jenis === 'Pelajaran')
                                        @php
                                            $jadwalSlot = $jadwalKelasHari->first(function ($j) use ($slot) {
                                                return $slot->jam_ke >= $j->jam_ke_mulai && $slot->jam_ke <= $j->jam_ke_selesai;
                                            });
                                            $namaGuruTampil = '-';
                                            if ($jadwalSlot && $jadwalSlot->guru) {
                                                $namaGuruTampil = explode(',', $jadwalSlot->guru->nama)[0];
                                            }
                                        @endphp
                                        <td class="p-2 border-r border-slate-200 text-slate-700 font-semibold text-[11px]">
                                            {{ $namaGuruTampil }}
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="py-8 text-center text-slate-400">Belum ada data kelas yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="sekreTab_laporan" class="sekre-tab-content space-y-6 {{ $tabAktif === 'laporan' ? '' : 'hidden' }}">
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-clipboard-user text-amber-600"></i>
                        <span>C. Laporan & Rekapitulasi Absensi</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Pratinjau rekapitulasi absensi guru dan siswa SMKN 13 Bandung.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.laporan.guru.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Laporan</span>
                    </a>
                    <a href="{{ route('admin.laporan.guru.csv', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-3.5 py-1.5 bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-csv"></i>
                        <span>Ekspor CSV</span>
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('sekretaris.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                <input type="hidden" name="tab" value="laporan">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-amber-600 focus:outline-none">
                        @foreach($daftarBulan as $num => $namaBulan)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-amber-600 focus:outline-none">
                        @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-filter"></i>
                        <span>Terapkan Periode</span>
                    </button>
                </div>
            </form>

            <div class="space-y-3">
                <h4 class="font-bold text-sm text-slate-900">Ringkasan Kehadiran Guru Bulan {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}</h4>
                <div class="overflow-x-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3">Nama Guru</th>
                                <th class="py-2.5 px-3 w-16 text-center">Hadir</th>
                                <th class="py-2.5 px-3 w-20 text-center">Terlambat</th>
                                <th class="py-2.5 px-3 w-16 text-center">Sakit</th>
                                <th class="py-2.5 px-3 w-16 text-center">Izin</th>
                                <th class="py-2.5 px-3 w-16 text-center">Alpa</th>
                                <th class="py-2.5 px-3 w-20 text-center font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rekapGuru as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-2 px-3 font-semibold text-slate-900">{{ $item['guru']->nama }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-emerald-700">{{ $item['hadir'] }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-amber-600">{{ $item['terlambat'] }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-sky-600">{{ $item['sakit'] }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-yellow-600">{{ $item['izin'] }}</td>
                                    <td class="py-2 px-3 text-center font-bold text-rose-600">{{ $item['alpa'] }}</td>
                                    <td class="py-2 px-3 text-center font-black text-slate-900 bg-slate-50/50">{{ $item['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-400">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const jadwalPerGuru = @json($jadwalHariIniSemua);

function switchSekreTab(tabName) {
    document.querySelectorAll('.sekre-tab-content').forEach(el => el.classList.add('hidden'));
    const target = document.getElementById('sekreTab_' + tabName);
    if (target) target.classList.remove('hidden');

    document.querySelectorAll('.sekre-tab-btn').forEach(btn => {
        btn.className = "sekre-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-slate-600 hover:bg-slate-100 whitespace-nowrap";
    });

    const activeBtn = document.getElementById('btnSekreTab_' + tabName);
    if (activeBtn) {
        activeBtn.className = "sekre-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-amber-600 text-white shadow whitespace-nowrap";
    }
}

function tampilkanJadwalGuru(guruId) {
    const box = document.getElementById('infoJadwalBox');
    const container = document.getElementById('daftarJadwalTerdampak');
    if (!box || !container) return;

    if (!guruId || !jadwalPerGuru[guruId] || jadwalPerGuru[guruId].length === 0) {
        box.classList.add('hidden');
        container.innerHTML = '';
        return;
    }

    const list = jadwalPerGuru[guruId];
    let html = '<ul class="list-disc pl-5 space-y-1">';
    list.forEach(j => {
        const kelasNama = j.kelas ? j.kelas.nama : '-';
        const mapelNama = j.mapel ? j.mapel.nama : '-';
        const ruangNama = j.ruangan ? j.ruangan.nama : '-';
        html += `<li><strong>${kelasNama}</strong> - ${mapelNama} (Jam ke ${j.jam_ke_mulai}-${j.jam_ke_selesai}, Ruang ${ruangNama})</li>`;
    });
    html += '</ul>';

    container.innerHTML = html;
    box.classList.remove('hidden');
}

function pilihGuruCepat(guruId) {
    const select = document.getElementById('pilihGuru');
    if (select) {
        select.value = guruId;
        tampilkanJadwalGuru(guruId);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}
</script>
@endsection

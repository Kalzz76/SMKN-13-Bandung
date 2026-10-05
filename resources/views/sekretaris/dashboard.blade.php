@extends('layouts.sekretaris', ['title' => 'Portal Siswa & Presensi - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 sm:space-y-8">
    @if(($tabAktif ?? 'jadwal') === 'jadwal')
        <div class="rounded-3xl p-6 sm:p-8 bg-[#059669] text-white shadow-sm flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10 max-w-xl">
                <span class="text-[11px] uppercase tracking-widest text-emerald-200 font-bold block mb-1">Dashboard Sekretaris</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Halo, {{ strtoupper(auth()->user()->name) }}!</h2>
                <p class="text-emerald-100 text-sm sm:text-base font-semibold mt-2 flex items-center space-x-2">
                    <span>Kelas {{ $kelasAktif->nama ?? 'Siswa' }}</span>
                    <span>•</span>
                    <span>Sekretaris Kelas</span>
                </p>
            </div>
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white text-emerald-700 font-black text-2xl sm:text-3xl flex items-center justify-center shadow-md flex-shrink-0 relative z-10">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>
        </div>

        <div class="space-y-3">
            <button type="button" onclick="toggleNotifikasi()" class="flex items-center space-x-2 text-slate-800 font-bold text-sm sm:text-base hover:text-slate-900 transition">
                <span>Notifikasi Terbaru</span>
                <i id="notifChevron" class="fa-solid fa-chevron-up text-slate-400 text-xs transition-transform duration-200"></i>
            </button>
            <div id="kontainerNotifikasi" class="bg-white rounded-2xl p-8 border border-slate-100 shadow-xs text-center transition-all duration-300">
                <i class="fa-regular fa-bell-slash text-2xl text-slate-300 mb-2 block mx-auto"></i>
                <p class="text-xs text-slate-400 italic">Tidak ada notifikasi baru</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">Jadwal Kelas Hari Ini ({{ $namaHariIni }})</h3>
                    @if($daftarKelas->count() > 1)
                        <form method="GET" action="{{ route('sekretaris.dashboard') }}" class="inline-block">
                            <input type="hidden" name="tab" value="jadwal">
                            <select name="kelas_id" onchange="this.form.submit()" class="text-xs font-semibold bg-white border border-slate-200 text-slate-700 rounded-xl px-3 py-1.5 outline-none shadow-2xs focus:ring-2 focus:ring-emerald-500">
                                @foreach($daftarKelas as $k)
                                    <option value="{{ $k->id }}" {{ ($kelasAktif->id ?? null) == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold self-start sm:self-auto">
                    <button type="button" onclick="pilihTampilanJadwal('harian')" id="btnTabHarian" class="px-3.5 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition">Hari Ini</button>
                    <button type="button" onclick="pilihTampilanJadwal('mingguan')" id="btnTabMingguan" class="px-3.5 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition">Mingguan</button>
                </div>
            </div>

            <div id="viewJadwalHarian" class="space-y-4">
                @if($jadwalKelasHariIni->isEmpty())
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-100 shadow-xs text-slate-400 space-y-2">
                        <i class="fa-regular fa-calendar-xmark text-3xl text-slate-300"></i>
                        <p class="text-sm">Tidak ada jadwal hari ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($jadwalKelasHariIni as $j)
                            <div class="bg-white p-5 rounded-2xl border {{ ($statusAbsensiSiswa[$j->id] ?? false) ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-100' }} shadow-xs flex flex-col justify-between space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-1">
                                        <div class="inline-flex items-center space-x-2 text-[11px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg">
                                            <i class="fa-regular fa-clock text-slate-400"></i>
                                            <span>Jam Ke {{ $j->jam_ke_mulai }} - {{ $j->jam_ke_selesai }}</span>
                                        </div>
                                        <h4 class="text-base font-bold text-slate-900 mt-1">{{ $j->mapel->nama }}</h4>
                                        <p class="text-xs text-slate-500 flex items-center space-x-2">
                                            <i class="fa-solid fa-chalkboard-user text-slate-400"></i>
                                            <span>{{ $j->guru->nama }}</span>
                                        </p>
                                        <p class="text-xs text-slate-400 flex items-center space-x-2">
                                            <i class="fa-solid fa-door-open text-slate-400"></i>
                                            <span>Ruang: {{ $j->ruangan->nama ?? '-' }}</span>
                                        </p>
                                    </div>

                                    <div>
                                        @if($statusAbsensiSiswa[$j->id] ?? false)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-check text-[10px] mr-1"></i> Sudah Diabsen
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-clock text-[10px] mr-1"></i> Belum Diabsen
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($statusAbsensiSiswa[$j->id] ?? false)
                                    <div class="grid grid-cols-4 gap-2 pt-2 border-t border-slate-100 text-center text-xs">
                                        <div class="p-1.5 bg-emerald-50 rounded-lg">
                                            <span class="text-[10px] text-emerald-700 font-bold block">Hadir</span>
                                            <span class="font-black text-emerald-900">{{ $rekapAbsensiHariIni[$j->id]['hadir'] ?? 0 }}</span>
                                        </div>
                                        <div class="p-1.5 bg-sky-50 rounded-lg">
                                            <span class="text-[10px] text-sky-700 font-bold block">Sakit</span>
                                            <span class="font-black text-sky-900">{{ $rekapAbsensiHariIni[$j->id]['sakit'] ?? 0 }}</span>
                                        </div>
                                        <div class="p-1.5 bg-amber-50 rounded-lg">
                                            <span class="text-[10px] text-amber-700 font-bold block">Izin</span>
                                            <span class="font-black text-amber-900">{{ $rekapAbsensiHariIni[$j->id]['izin'] ?? 0 }}</span>
                                        </div>
                                        <div class="p-1.5 bg-rose-50 rounded-lg">
                                            <span class="text-[10px] text-rose-700 font-bold block">Alpa</span>
                                            <span class="font-black text-rose-900">{{ $rekapAbsensiHariIni[$j->id]['alpa'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="pt-2">
                                    <button type="button" onclick="bukaModalAbsensi({{ $j->id }})" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-2 shadow-xs">
                                        <i class="fa-solid fa-clipboard-user"></i>
                                        <span>{{ ($statusAbsensiSiswa[$j->id] ?? false) ? 'Ubah Presensi Siswa' : 'Absen Siswa' }}</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div id="viewJadwalMingguan" class="hidden space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <span class="font-bold text-sm text-slate-800">{{ $hari }}</span>
                                <span class="text-xs text-slate-400 font-semibold">{{ isset($jadwalKelasMingguan[$hari]) ? $jadwalKelasMingguan[$hari]->count() : 0 }} Mata Pelajaran</span>
                            </div>

                            @if(!isset($jadwalKelasMingguan[$hari]) || $jadwalKelasMingguan[$hari]->isEmpty())
                                <p class="text-xs text-slate-400 italic py-4 text-center">Tidak ada jadwal.</p>
                            @else
                                <div class="space-y-2">
                                    @foreach($jadwalKelasMingguan[$hari] as $jm)
                                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-1">
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-slate-800 truncate">{{ $jm->mapel->nama }}</span>
                                                <span class="text-[10px] font-bold text-slate-500">Jam {{ $jm->jam_ke_mulai }}-{{ $jm->jam_ke_selesai }}</span>
                                            </div>
                                            <p class="text-[11px] text-slate-500">{{ $jm->guru->nama }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @foreach($jadwalKelasHariIni as $j)
            <div id="modalAbsensi{{ $j->id }}" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
                <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-start justify-between bg-slate-50/50">
                        <div>
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider">Presensi Siswa</span>
                            <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $j->mapel->nama }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Kelas {{ $kelasAktif->nama }} • Jam Ke {{ $j->jam_ke_mulai }}-{{ $j->jam_ke_selesai }} • Pengajar: {{ $j->guru->nama }}
                            </p>
                        </div>
                        <button type="button" onclick="tutupModalAbsensi({{ $j->id }})" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('sekretaris.absensi-siswa.simpan', $j->id) }}" class="flex-1 flex flex-col overflow-hidden">
                        @csrf
                        <div class="px-6 py-3 bg-emerald-50/40 border-b border-emerald-100 flex items-center justify-between text-xs">
                            <span class="text-emerald-950 font-bold">Total: {{ $daftarSiswaKelas->count() }} Siswa</span>
                            <button type="button" onclick="setSemuaHadir({{ $j->id }})" class="bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-100 font-bold px-3 py-1 rounded-lg transition shadow-2xs flex items-center space-x-1">
                                <i class="fa-solid fa-check-double text-emerald-600"></i>
                                <span>Pilih Semua Hadir</span>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6 divide-y divide-slate-100">
                            @foreach($daftarSiswaKelas as $idx => $s)
                                @php
                                    $record = $detailAbsensiTersimpan[$j->id][$s->id] ?? null;
                                    $currentStatus = $record ? $record->status : 'Hadir';
                                    $currentKet = $record ? $record->keterangan : '';
                                @endphp
                                <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold flex items-center justify-center flex-shrink-0">{{ $idx + 1 }}</span>
                                            <span class="font-bold text-sm text-slate-900 truncate">{{ $s->nama }}</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 ml-7 block">NIS: {{ $s->nis }}</span>
                                    </div>

                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                        <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold">
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status[{{ $s->id }}]" value="Hadir" {{ $currentStatus === 'Hadir' ? 'checked' : '' }} class="sr-only peer status-radio-{{ $j->id }}">
                                                <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-emerald-600 peer-checked:text-white text-slate-600 transition">Hadir</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status[{{ $s->id }}]" value="Sakit" {{ $currentStatus === 'Sakit' ? 'checked' : '' }} class="sr-only peer">
                                                <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-sky-600 peer-checked:text-white text-slate-600 transition">Sakit</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status[{{ $s->id }}]" value="Izin" {{ $currentStatus === 'Izin' ? 'checked' : '' }} class="sr-only peer">
                                                <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-amber-600 peer-checked:text-white text-slate-600 transition">Izin</span>
                                            </label>
                                            <label class="cursor-pointer">
                                                <input type="radio" name="status[{{ $s->id }}]" value="Alpa" {{ $currentStatus === 'Alpa' ? 'checked' : '' }} class="sr-only peer">
                                                <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-rose-600 peer-checked:text-white text-slate-600 transition">Alpa</span>
                                            </label>
                                        </div>

                                        <input type="text" name="keterangan[{{ $s->id }}]" value="{{ $currentKet }}" placeholder="Keterangan..." class="w-full sm:w-32 px-2.5 py-1 text-xs border border-slate-200 rounded-lg outline-none focus:ring-1 focus:ring-emerald-500">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-4 border-t border-slate-100 flex items-center justify-end space-x-3 bg-slate-50/50">
                            <button type="button" onclick="tutupModalAbsensi({{ $j->id }})" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition">Batal</button>
                            <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl shadow-xs transition flex items-center space-x-1.5">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan Presensi Siswa</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    @elseif(($tabAktif ?? 'jadwal') === 'laporan')
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Rekapitulasi Presensi Siswa</h3>
                    <p class="text-xs text-slate-500 mt-1">Laporan kehadiran siswa per kelas dan mata pelajaran.</p>
                </div>

                <form method="GET" action="{{ route('sekretaris.dashboard') }}" class="flex flex-wrap items-center gap-2">
                    <input type="hidden" name="tab" value="laporan">
                    <select name="kelas_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" {{ ($kelasId ?? null) == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama }}</option>
                        @endforeach
                    </select>

                    <select name="bulan" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600">
                        @foreach($daftarBulan as $bKey => $bVal)
                            <option value="{{ $bKey }}" {{ $bulan == $bKey ? 'selected' : '' }}>{{ $bVal }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if(empty($rekapSiswa))
                <div class="text-center py-12 text-slate-400">
                    <p class="text-sm">Belum ada data kehadiran siswa pada periode ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[10px]">
                            <tr>
                                <th class="p-3 rounded-l-xl">No</th>
                                <th class="p-3">Nama Siswa</th>
                                <th class="p-3 text-center">Hadir</th>
                                <th class="p-3 text-center">Sakit</th>
                                <th class="p-3 text-center">Izin</th>
                                <th class="p-3 text-center">Alpa</th>
                                <th class="p-3 text-center">Total</th>
                                <th class="p-3 rounded-r-xl text-center">% Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rekapSiswa as $idx => $r)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3 font-semibold">{{ $idx + 1 }}</td>
                                    <td class="p-3 font-bold text-slate-900">{{ $r['siswa']->nama }}</td>
                                    <td class="p-3 text-center font-bold text-emerald-600">{{ $r['hadir'] }}</td>
                                    <td class="p-3 text-center font-bold text-sky-600">{{ $r['sakit'] }}</td>
                                    <td class="p-3 text-center font-bold text-amber-600">{{ $r['izin'] }}</td>
                                    <td class="p-3 text-center font-bold text-rose-600">{{ $r['alpa'] }}</td>
                                    <td class="p-3 text-center font-bold text-slate-800">{{ $r['total'] }}</td>
                                    <td class="p-3 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded-full font-bold {{ $r['persentase'] >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $r['persentase'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    @elseif(($tabAktif ?? 'jadwal') === 'pengganti')
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-900">Pencatatan Presensi Pengganti Guru</h3>
                <p class="text-xs text-slate-500 mt-1">Gunakan formulir ini jika guru berhalangan scan barcode mandiri (izin, sakit, dinas luar).</p>
            </div>

            <form method="POST" action="{{ route('sekretaris.absensi-pengganti') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Guru</label>
                    <select name="id_guru" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kehadiran</label>
                    <select name="status" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alpa">Alpa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan / Keterangan</label>
                    <input type="text" name="alasan" required placeholder="Contoh: Sakit, Surat dokter terlampir" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs outline-none focus:ring-2 focus:ring-emerald-600">
                </div>

                <div class="sm:col-span-3 flex justify-end">
                    <button type="submit" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl text-xs shadow-xs transition">
                        Simpan Presensi Pengganti
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    function toggleNotifikasi() {
        const box = document.getElementById('kontainerNotifikasi');
        const chevron = document.getElementById('notifChevron');
        if (box && chevron) {
            box.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }
    }

    function pilihTampilanJadwal(mode) {
        const vHarian = document.getElementById('viewJadwalHarian');
        const vMingguan = document.getElementById('viewJadwalMingguan');
        const btnHarian = document.getElementById('btnTabHarian');
        const btnMingguan = document.getElementById('btnTabMingguan');

        if (mode === 'harian') {
            vHarian.classList.remove('hidden');
            vMingguan.classList.add('hidden');
            btnHarian.className = 'px-3.5 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition font-bold';
            btnMingguan.className = 'px-3.5 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition font-bold';
        } else {
            vHarian.classList.add('hidden');
            vMingguan.classList.remove('hidden');
            btnHarian.className = 'px-3.5 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition font-bold';
            btnMingguan.className = 'px-3.5 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition font-bold';
        }
    }

    function bukaModalAbsensi(id) {
        const m = document.getElementById('modalAbsensi' + id);
        if (m) m.classList.remove('hidden');
    }

    function tutupModalAbsensi(id) {
        const m = document.getElementById('modalAbsensi' + id);
        if (m) m.classList.add('hidden');
    }

    function setSemuaHadir(id) {
        const radios = document.querySelectorAll('.status-radio-' + id);
        radios.forEach(r => {
            if (r.value === 'Hadir') {
                r.checked = true;
            }
        });
    }
</script>
@endsection

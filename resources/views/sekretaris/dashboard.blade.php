@extends('layouts.sekretaris', ['title' => 'Jadwal & Presensi Siswa - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 sm:space-y-8">
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

    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight" id="judulTampilanJadwal">Jadwal Kelas Hari Ini ({{ $namaHariIni }})</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Kelas {{ $kelasAktif->nama ?? '-' }}
                </span>
            </div>

            <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold self-start sm:self-auto border border-slate-200/50">
                <button type="button" onclick="pilihTampilanJadwal('harian')" id="btnTabHarian" class="px-4 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition">Hari Ini</button>
                <button type="button" onclick="pilihTampilanJadwal('mingguan')" id="btnTabMingguan" class="px-4 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition">Mingguan</button>
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
                        @php
                            $info = $infoAbsensiSiswa[$j->id] ?? [
                                'ada' => false,
                                'diisi_guru' => false,
                                'diisi_sekre' => false,
                                'status_validasi' => null,
                            ];
                            $isUpcoming = ($j->status_waktu === 'segera');
                        @endphp
                        <div class="bg-white p-4 sm:p-5 rounded-2xl border {{ $info['ada'] ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-100' }} shadow-xs flex flex-col justify-between space-y-4">
                            <div class="space-y-3">
                                {{-- Header Slot Jam & Status Badge (Responsif di Layar Mobile) --}}
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="inline-flex items-center space-x-2 text-[11px] font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl self-start">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        <span>{{ $j->jam_ke_label }} ({{ $j->rentang_waktu ?? '' }})</span>
                                    </div>

                                    <div class="self-start sm:self-auto">
                                        @if($info['diisi_guru'])
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800" title="Absensi sudah dicatat langsung oleh guru mapel">
                                                <i class="fa-solid fa-circle-check text-[10px] mr-1"></i> Absensi sudah tercatat oleh Guru
                                            </span>
                                        @elseif($info['diisi_sekre'])
                                            @if($info['status_validasi'] === 'disetujui')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                    <i class="fa-solid fa-check-double text-[10px] mr-1"></i> Disetujui Guru
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                                    <i class="fa-solid fa-clock text-[10px] mr-1"></i> Menunggu Validasi Guru
                                                </span>
                                            @endif
                                        @elseif($isUpcoming)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                                <i class="fa-solid fa-lock text-[10px] mr-1"></i> Belum Waktunya
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                                <i class="fa-solid fa-clock text-[10px] mr-1"></i> Belum Diabsen
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Identitas Mapel, Guru & Ruangan --}}
                                <div>
                                    <h4 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">{{ $j->mapel->nama }}</h4>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-xs text-slate-600 flex items-center space-x-2">
                                            <i class="fa-solid fa-chalkboard-user text-slate-400 w-4 text-center"></i>
                                            <span>{{ $j->guru->nama ?? 'Guru' }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 flex items-center space-x-2">
                                            <i class="fa-solid fa-door-open text-slate-400 w-4 text-center"></i>
                                            <span>Ruang: {{ $j->ruangan->nama ?? '-' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if($info['ada'])
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
                                @if($isUpcoming)
                                    <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 border border-slate-200 cursor-not-allowed" title="Jam KBM belum dimulai (mulai pukul {{ $j->jam_mulai_formatted }})">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                        <span>Belum Waktunya (Mulai {{ $j->jam_mulai_formatted }})</span>
                                    </button>
                                @elseif($info['diisi_guru'])
                                    <button type="button" disabled class="w-full bg-slate-100 text-slate-400 font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 border border-slate-200 cursor-not-allowed">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                        <span>Absensi sudah tercatat oleh Guru</span>
                                    </button>
                                @elseif($info['diisi_sekre'])
                                    @if($info['status_validasi'] === 'disetujui')
                                        <span class="w-full bg-emerald-50 text-emerald-700 font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 border border-emerald-200">
                                            <i class="fa-solid fa-check-double text-xs"></i>
                                            <span>Selesai & Divalidasi Guru</span>
                                        </span>
                                    @else
                                        <a href="{{ route('sekretaris.absensi-siswa', $j->id) }}" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-2 shadow-xs">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span>Perbarui Presensi (Menunggu Validasi)</span>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('sekretaris.absensi-siswa', $j->id) }}" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-2 shadow-xs">
                                        <i class="fa-solid fa-clipboard-user"></i>
                                        <span>Absen Siswa (Guru Tidak Hadir)</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="viewJadwalMingguan" class="hidden space-y-6">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                @php
                    $jadwalHari = $jadwalKelasMingguan[$hari] ?? collect();
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                        <div class="flex items-center space-x-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                            <h4 class="font-bold text-base text-slate-900 tracking-tight">{{ $hari }}</h4>
                        </div>
                        <span class="text-xs text-slate-500 font-bold bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200/60">
                            {{ $jadwalHari->count() }} Mata Pelajaran
                        </span>
                    </div>

                    @if($jadwalHari->isEmpty())
                        <div class="py-3 px-4 rounded-xl bg-slate-50/80 border border-dashed border-slate-200 text-xs text-slate-400 italic">
                            Tidak ada jadwal pelajaran pada hari {{ $hari }}.
                        </div>
                    @else
                        <div class="space-y-2.5">
                            @foreach($jadwalHari as $jm)
                                <div class="bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200/80 shadow-xs hover:border-emerald-200 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="space-y-1">
                                        <h5 class="font-bold text-slate-900 text-sm leading-snug">{{ $jm->mapel->nama }}</h5>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                            <span class="flex items-center space-x-1.5">
                                                <i class="fa-solid fa-chalkboard-user text-slate-400 text-[11px]"></i>
                                                <span>{{ $jm->guru->nama ?? 'Guru' }}</span>
                                            </span>
                                            @if($jm->ruangan)
                                                <span class="flex items-center space-x-1.5">
                                                    <i class="fa-solid fa-door-open text-slate-400 text-[11px]"></i>
                                                    <span>{{ $jm->ruangan->nama }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0 self-start sm:self-center">
                                        <span class="inline-flex items-center text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/80 px-3 py-1.5 rounded-lg">
                                            <i class="fa-regular fa-clock text-[11px] mr-1.5 text-emerald-600"></i>
                                            {{ $jm->jam_ke_label_singkat }} ({{ $jm->rentang_waktu ?? '' }})
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function pilihTampilanJadwal(mode) {
        const vHarian = document.getElementById('viewJadwalHarian');
        const vMingguan = document.getElementById('viewJadwalMingguan');
        const btnHarian = document.getElementById('btnTabHarian');
        const btnMingguan = document.getElementById('btnTabMingguan');
        const judul = document.getElementById('judulTampilanJadwal');

        if (mode === 'harian') {
            vHarian.classList.remove('hidden');
            vMingguan.classList.add('hidden');
            btnHarian.className = 'px-4 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition font-bold';
            btnMingguan.className = 'px-4 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition font-bold';
            if (judul) judul.textContent = 'Jadwal Kelas Hari Ini ({{ $namaHariIni }})';
        } else {
            vHarian.classList.add('hidden');
            vMingguan.classList.remove('hidden');
            btnHarian.className = 'px-4 py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition font-bold';
            btnMingguan.className = 'px-4 py-1.5 rounded-lg bg-white text-emerald-800 shadow-xs transition font-bold';
            if (judul) judul.textContent = 'Jadwal Pelajaran Mingguan';
        }
    }
</script>
@endsection

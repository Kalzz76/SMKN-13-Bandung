@extends('layouts.guru', ['title' => 'Dashboard Guru - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    {{-- 1. Greeting Banner --}}
    @php
        $hour = (int) now('Asia/Jakarta')->format('H');
        if ($hour < 11) {
            $sapaan = 'Selamat Pagi';
            $sapaanIcon = 'fa-sun text-amber-300';
        } elseif ($hour < 15) {
            $sapaan = 'Selamat Siang';
            $sapaanIcon = 'fa-sun text-amber-300';
        } elseif ($hour < 18) {
            $sapaan = 'Selamat Sore';
            $sapaanIcon = 'fa-cloud-sun text-amber-200';
        } else {
            $sapaan = 'Selamat Malam';
            $sapaanIcon = 'fa-moon text-indigo-200';
        }
    @endphp

    <div class="rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white shadow-md relative overflow-hidden" style="background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);">
        <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
            <i class="fa-solid fa-graduation-cap text-8xl sm:text-9xl"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-800/60 backdrop-blur-xs px-3 py-1 rounded-full text-[11px] sm:text-xs font-semibold text-emerald-100 border border-emerald-600/40">
                    <i class="fa-solid {{ $sapaanIcon }}"></i>
                    <span>{{ $sapaan }}</span>
                </div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-white tracking-tight leading-snug">
                    {{ $sapaan }}, {{ $guru->nama ?? auth()->user()->name }}!
                </h2>
                <p class="text-emerald-100 text-xs sm:text-sm max-w-xl leading-relaxed">
                    Tetap semangat mengajar demi masa depan bangsa di SMKN 13 Bandung. Pantau jadwal mengajar dan kelola absensi siswa secara real-time.
                </p>
            </div>
            <div class="flex flex-wrap md:flex-col items-start md:items-end gap-2 text-xs">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-xs px-3.5 py-1.5 rounded-xl border border-white/15 text-emerald-50 text-[11px] sm:text-xs font-medium">
                    <i class="fa-regular fa-calendar-check text-emerald-300"></i>
                    <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                </div>
                @if($guru && $guru->nip)
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-xs px-3.5 py-1.5 rounded-xl border border-white/15 text-emerald-50 text-[11px] sm:text-xs font-mono">
                        <i class="fa-solid fa-id-badge text-emerald-300"></i>
                        <span>NIP: {{ $guru->nip }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. Kontrol Tampilan (Hari Ini vs Mingguan) --}}
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3.5">
            <div>
                <div class="flex items-center justify-between sm:justify-start gap-2.5">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900" id="jadwalTitle">
                        Jadwal Mengajar Hari Ini ({{ $hariIni }})
                    </h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 shrink-0" id="jadwalBadge">
                        {{ $jumlahSesiHariIni }} Sesi
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Daftar kelas penugasan, rentang jam KBM, dan status validasi absensi.</p>
            </div>
            <div class="w-full sm:w-auto grid grid-cols-2 sm:inline-flex rounded-xl bg-slate-100 p-1 text-xs font-semibold border border-slate-200 shadow-2xs">
                <button type="button" onclick="setModeJadwal('hari_ini')" id="btnJadwalHariIni" class="px-4 py-2 sm:py-1.5 rounded-lg font-bold bg-white text-emerald-700 shadow-xs transition flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-calendar-day mr-1.5"></i><span>Hari Ini</span>
                </button>
                <button type="button" onclick="setModeJadwal('mingguan')" id="btnJadwalMingguan" class="px-4 py-2 sm:py-1.5 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-calendar-week mr-1.5"></i><span>Mingguan</span>
                </button>
            </div>
        </div>

        {{-- 4. Tampilan Hari Ini (Grid Sesi) --}}
        <div id="wrapperJadwalHariIni">
            @if($jadwalHariIni->isEmpty())
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-8 sm:p-14 text-center text-slate-400 shadow-xs space-y-3">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl mx-auto shadow-2xs">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base">Tidak Ada Jadwal Mengajar Hari {{ $hariIni }}</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">Anda tidak memiliki jam KBM yang dijadwalkan hari ini. Gunakan tombol 'Mingguan' untuk melihat penugasan di hari lainnya.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @foreach($jadwalHariIni as $j)
                        @php
                            $isOngoing = ($j->status_waktu === 'berlangsung');
                            $isPassed = ($j->status_waktu === 'selesai');
                            $isUpcoming = ($j->status_waktu === 'segera');
                            $isFilled = $j->sudah_diisi;
                        @endphp
                        <div class="bg-white rounded-2xl sm:rounded-3xl border {{ $isOngoing ? 'border-emerald-300 ring-2 ring-emerald-500/20 shadow-md' : 'border-slate-200 shadow-xs' }} p-4 sm:p-6 flex flex-col justify-between relative overflow-hidden transition hover:shadow-md">
                            {{-- Ribbon Sedang Berjalan --}}
                            @if($isOngoing)
                                <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider px-3 py-1 rounded-bl-xl shadow-xs flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                                    <span>Sedang Berjalan</span>
                                </div>
                            @endif

                            <div class="space-y-3.5">
                                {{-- Header Card: Slot & Waktu --}}
                                <div class="flex items-center justify-between pr-2">
                                    <span class="px-2.5 py-1 rounded-xl text-xs font-bold {{ $isOngoing ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                        Jam {{ $j->jam_ke_mulai }} - {{ $j->jam_ke_selesai }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-500 flex items-center space-x-1">
                                        <i class="fa-regular fa-clock text-slate-400"></i>
                                        <span>{{ $j->rentang_waktu }}</span>
                                    </span>
                                </div>

                                {{-- Identitas Kelas & Mapel --}}
                                <div>
                                    <h4 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">
                                        {{ $j->kelas ? $j->kelas->nama : 'Kelas' }}
                                    </h4>
                                    <p class="text-xs sm:text-sm font-semibold text-emerald-700 mt-0.5">
                                        {{ $j->mapel ? $j->mapel->nama : 'Mata Pelajaran' }}
                                    </p>
                                </div>

                                {{-- Ruangan Box --}}
                                <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 flex items-center space-x-2 text-xs text-slate-700 font-medium">
                                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                                    <span class="truncate">Ruangan: <strong>{{ $j->ruangan ? $j->ruangan->nama : 'Ruang Kelas' }}</strong></span>
                                </div>
                            </div>

                            {{-- Footer Card: Status Absensi & Action Button --}}
                            <div class="pt-3.5 mt-3.5 border-t border-slate-100 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Validasi</span>
                                    @if($isFilled)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-lg">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Tervalidasi</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-lg">
                                            <i class="fa-solid fa-clock"></i>
                                            <span>Belum Diisi</span>
                                        </span>
                                    @endif
                                </div>

                                @if($isOngoing)
                                    <a href="{{ route('guru.absensi-siswa', $j->id) }}" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold transition shadow-xs flex items-center justify-center space-x-2 {{ $isFilled ? 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200' : 'bg-emerald-700 hover:bg-emerald-800 text-white' }}">
                                        <i class="fa-solid {{ $isFilled ? 'fa-pen-to-square' : 'fa-clipboard-check' }}"></i>
                                        <span>{{ $isFilled ? 'Ubah Absensi Kelas' : 'Isi Absensi Kelas' }}</span>
                                    </a>
                                @elseif($isUpcoming)
                                    <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed flex items-center justify-center space-x-2">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                        <span>Belum Waktunya (Mulai {{ $j->jam_mulai_formatted }})</span>
                                    </button>
                                @else
                                    @if($isFilled)
                                        <span class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center space-x-2">
                                            <i class="fa-solid fa-circle-check text-xs"></i>
                                            <span>Selesai & Tercatat</span>
                                        </span>
                                    @else
                                        <button type="button" disabled class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-rose-50 text-rose-500 border border-rose-200 cursor-not-allowed flex items-center justify-center space-x-2" title="Waktu KBM telah usai. Jika Anda berhalangan hadir, sekretaris kelas yang mengisikan.">
                                            <i class="fa-solid fa-ban text-xs"></i>
                                            <span>Waktu Berakhir (Selesai {{ $j->jam_selesai_formatted }})</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 5. Tampilan Mingguan (Weekly Grouped View) --}}
        <div id="wrapperJadwalMingguan" class="hidden space-y-4">
            @php
                $adaJadwalMingguan = $semuaJadwal->isNotEmpty();
            @endphp

            @if(!$adaJadwalMingguan)
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-8 sm:p-12 text-center text-slate-400 shadow-xs">
                    <p class="text-sm font-medium">Belum ada penugasan jadwal mengajar mingguan.</p>
                </div>
            @else
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                    @php
                        $jadwalHariTersebut = $jadwalMingguanPerHari[$hari] ?? collect();
                    @endphp
                    @if($jadwalHariTersebut->isNotEmpty())
                        <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 p-4 sm:p-6 shadow-xs space-y-3.5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">
                                        <i class="fa-solid fa-calendar-day"></i>
                                    </div>
                                    <h4 class="text-sm sm:text-base font-bold text-slate-900">{{ $hari }}</h4>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $jadwalHariTersebut->count() }} Sesi
                                </span>
                            </div>

                            <div class="divide-y divide-slate-100">
                                @foreach($jadwalHariTersebut as $jm)
                                    <div class="py-3 flex items-center justify-between gap-3">
                                        <div class="flex items-start sm:items-center space-x-3 min-w-0 flex-1">
                                            <div class="w-20 sm:w-24 text-[11px] sm:text-xs font-mono font-bold text-slate-700 flex-shrink-0 bg-slate-50 px-2 py-1.5 rounded-lg border border-slate-200 text-center">
                                                {{ $jm->rentang_waktu }}
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-1.5">
                                                    <span class="font-bold text-xs sm:text-sm text-slate-900">{{ $jm->kelas ? $jm->kelas->nama : '-' }}</span>
                                                    <span class="text-slate-300 hidden sm:inline">•</span>
                                                    <span class="text-xs font-semibold text-emerald-700">{{ $jm->mapel ? $jm->mapel->nama : '-' }}</span>
                                                </div>
                                                <div class="text-[11px] sm:text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-2">
                                                    <span><i class="fa-solid fa-door-open mr-1 text-slate-400"></i>{{ $jm->ruangan ? $jm->ruangan->nama : 'Ruang Kelas' }}</span>
                                                    <span>•</span>
                                                    <span>Jam {{ $jm->jam_ke_mulai }}-{{ $jm->jam_ke_selesai }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
function setModeJadwal(mode) {
    const btnHariIni = document.getElementById('btnJadwalHariIni');
    const btnMingguan = document.getElementById('btnJadwalMingguan');
    const wrapHariIni = document.getElementById('wrapperJadwalHariIni');
    const wrapMingguan = document.getElementById('wrapperJadwalMingguan');
    const title = document.getElementById('jadwalTitle');
    const badge = document.getElementById('jadwalBadge');

    if (mode === 'hari_ini') {
        if (wrapHariIni) wrapHariIni.classList.remove('hidden');
        if (wrapMingguan) wrapMingguan.classList.add('hidden');
        if (btnHariIni) {
            btnHariIni.className = 'px-4 py-2 sm:py-1.5 rounded-lg font-bold bg-white text-emerald-700 shadow-xs transition flex items-center justify-center cursor-pointer';
        }
        if (btnMingguan) {
            btnMingguan.className = 'px-4 py-2 sm:py-1.5 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition flex items-center justify-center cursor-pointer';
        }
        if (title) title.innerText = 'Jadwal Mengajar Hari Ini ({{ $hariIni }})';
        if (badge) badge.innerText = '{{ $jumlahSesiHariIni }} Sesi';
    } else {
        if (wrapHariIni) wrapHariIni.classList.add('hidden');
        if (wrapMingguan) wrapMingguan.classList.remove('hidden');
        if (btnMingguan) {
            btnMingguan.className = 'px-4 py-2 sm:py-1.5 rounded-lg font-bold bg-white text-emerald-700 shadow-xs transition flex items-center justify-center cursor-pointer';
        }
        if (btnHariIni) {
            btnHariIni.className = 'px-4 py-2 sm:py-1.5 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition flex items-center justify-center cursor-pointer';
        }
        if (title) title.innerText = 'Jadwal Mengajar Mingguan';
        if (badge) badge.innerText = '{{ $semuaJadwal->count() }} Sesi';
    }
}
</script>
@endsection

@extends('layouts.guru', ['title' => 'Dashboard Guru - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex space-x-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100 overflow-x-auto">
        <button type="button" onclick="switchGuruTab('validasi')" id="btnTab_validasi" class="guru-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'validasi' ? 'bg-emerald-700 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-camera"></i>
            <span>B. Validasi Absensi (Scan)</span>
        </button>
        <button type="button" onclick="switchGuruTab('jadwal')" id="btnTab_jadwal" class="guru-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'jadwal' ? 'bg-emerald-700 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-calendar-week"></i>
            <span>A. Jadwal Mengajar</span>
        </button>
        <button type="button" onclick="switchGuruTab('rekap')" id="btnTab_rekap" class="guru-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center space-x-2 whitespace-nowrap {{ $tabAktif === 'rekap' ? 'bg-emerald-700 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-clipboard-list"></i>
            <span>C. Rekap Absensi</span>
        </button>
    </div>

    <div id="guruTab_validasi" class="guru-tab-content space-y-6 {{ $tabAktif === 'validasi' ? '' : 'hidden' }}">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-7 bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-slate-900 text-lg flex items-center space-x-2">
                            <i class="fa-solid fa-qrcode text-emerald-700"></i>
                            <span>Scan Barcode Kehadiran</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Arahkan kamera ke kartu barcode Anda di area sekolah</p>
                    </div>
                    <div>
                        @if($absensiHariIni)
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $absensiHariIni->status === 'Hadir' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                <i class="fa-solid fa-check mr-1"></i> {{ $absensiHariIni->status }} Jam {{ \Illuminate\Support\Str::substr($absensiHariIni->jam_masuk, 0, 5) }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                <i class="fa-solid fa-clock mr-1"></i> Belum Absen Hari Ini
                            </span>
                        @endif
                    </div>
                </div>

                @if($absensiHariIni)
                    <div class="bg-emerald-50 border-2 border-emerald-500/40 rounded-2xl p-6 text-center space-y-3">
                        <div class="w-14 h-14 bg-emerald-700 text-white rounded-full flex items-center justify-center text-2xl mx-auto shadow-md">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h4 class="text-xl font-black text-emerald-950">Presensi Hari Ini Berhasil!</h4>
                        <p class="text-xs text-slate-600 max-w-md mx-auto">
                            Kehadiran Anda telah dicatat oleh sistem pada {{ \Carbon\Carbon::parse($absensiHariIni->tanggal)->isoFormat('dddd, D MMMM Y') }}.
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 text-xs border-t border-emerald-200/60 max-w-lg mx-auto">
                            <div class="p-2.5 bg-white rounded-xl border border-emerald-100">
                                <span class="text-[10px] text-slate-400 block font-semibold">STATUS</span>
                                <span class="font-bold text-emerald-800 text-sm">{{ $absensiHariIni->status }}</span>
                            </div>
                            <div class="p-2.5 bg-white rounded-xl border border-emerald-100">
                                <span class="text-[10px] text-slate-400 block font-semibold">WAKTU</span>
                                <span class="font-bold text-slate-900 text-sm">{{ \Illuminate\Support\Str::substr($absensiHariIni->jam_masuk, 0, 5) }} WIB</span>
                            </div>
                            <div class="p-2.5 bg-white rounded-xl border border-emerald-100">
                                <span class="text-[10px] text-slate-400 block font-semibold">METODE</span>
                                <span class="font-bold text-slate-900 text-sm">{{ $absensiHariIni->metode }}</span>
                            </div>
                            <div class="p-2.5 bg-white rounded-xl border border-emerald-100">
                                <span class="text-[10px] text-slate-400 block font-semibold">JARAK</span>
                                <span class="font-bold text-slate-900 text-sm">{{ $absensiHariIni->jarak_meter ?? 0 }} m</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="gpsStatusBox" class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-xs flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-slate-600">
                            <i class="fa-solid fa-location-crosshairs text-emerald-700"></i>
                            <span id="gpsText">Mendeteksi koordinat lokasi GPS Anda...</span>
                        </div>
                        <button type="button" onclick="ambilLokasiUlang()" class="text-emerald-700 font-bold hover:underline">
                            Perbarui GPS
                        </button>
                    </div>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-950 p-2">
                        <div id="reader" class="w-full"></div>
                    </div>

                    <form id="scanForm" method="POST" action="{{ route('guru.absensi-scan') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="kode_barcode" id="formKodeBarcode">
                        <input type="hidden" name="latitude" id="formLatitude">
                        <input type="hidden" name="longitude" id="formLongitude">

                        <div class="pt-2 text-center text-xs text-slate-400">
                            Pastikan Anda memberikan izin kamera dan lokasi (GPS) pada peramban Anda.
                        </div>
                    </form>
                @endif
            </div>

            <div class="lg:col-span-5 bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
                <div class="pb-3 border-b border-slate-100">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest">{{ $hariIni }}</span>
                    <h3 class="font-bold text-slate-900 text-lg mt-0.5">Jadwal Mengajar Hari Ini</h3>
                    <p class="text-xs text-slate-400">Isi absensi siswa dan catatan materi per kelas</p>
                </div>

                @if($jadwalHariIni->isEmpty())
                    <div class="p-8 text-center text-slate-400">
                        <i class="fa-solid fa-mug-hot text-3xl mb-2 text-slate-300"></i>
                        <p class="text-sm">Tidak ada jadwal mengajar pada hari {{ $hariIni }}.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($jadwalHariIni as $j)
                            <div class="p-4 rounded-2xl border {{ $statusAbsensiSiswa[$j->id] ?? false ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-200 bg-white' }} space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="bg-emerald-700 text-white font-bold text-xs px-2.5 py-1 rounded-lg">
                                        {{ $j->kelas ? $j->kelas->nama : '-' }}
                                    </span>
                                    <span class="text-xs text-slate-500 font-mono">
                                        Jam {{ $j->jam_ke_mulai }} - {{ $j->jam_ke_selesai }}
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-sm">{{ $j->mapel ? $j->mapel->nama : '-' }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">
                                        <i class="fa-solid fa-door-open mr-1"></i> {{ $j->ruangan ? $j->ruangan->nama : '-' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                                    @if($statusAbsensiSiswa[$j->id] ?? false)
                                        <span class="text-xs font-bold text-emerald-700 flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-check"></i>
                                            <span>Sudah Diisi</span>
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold text-amber-600 flex items-center space-x-1">
                                            <i class="fa-solid fa-circle-exclamation"></i>
                                            <span>Belum Diisi</span>
                                        </span>
                                    @endif

                                    <a href="{{ route('guru.absensi-siswa', $j->id) }}" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-3 py-1.5 rounded-xl text-xs transition flex items-center space-x-1.5 shadow-sm">
                                        <i class="fa-solid fa-user-check"></i>
                                        <span>{{ $statusAbsensiSiswa[$j->id] ?? false ? 'Ubah Absensi' : 'Isi Absensi Siswa' }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="guruTab_jadwal" class="guru-tab-content space-y-6 {{ $tabAktif === 'jadwal' ? '' : 'hidden' }}">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-4">
            <div>
                <h3 class="font-bold text-slate-900 text-lg flex items-center space-x-2">
                    <i class="fa-solid fa-calendar-week text-emerald-700"></i>
                    <span>A. Jadwal Mengajar Anda</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar lengkap penugasan jam pelajaran Anda dari hari Senin sampai Jumat.</p>
            </div>

            @if($semuaJadwal->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm">Belum ada jadwal mengajar yang ditentukan untuk Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                            <tr>
                                <th class="p-3.5 rounded-l-xl">Hari</th>
                                <th class="p-3.5">Kelas</th>
                                <th class="p-3.5">Mata Pelajaran</th>
                                <th class="p-3.5">Jam Mengajar</th>
                                <th class="p-3.5">Ruangan</th>
                                <th class="p-3.5 rounded-r-xl">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($semuaJadwal as $j)
                                <tr class="{{ $j->hari === $hariIni ? 'bg-emerald-50/70 font-semibold' : 'hover:bg-slate-50/50' }} transition">
                                    <td class="p-3.5">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $j->hari === $hariIni ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-700' }}">
                                            {{ $j->hari }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 font-bold text-slate-900">{{ $j->kelas ? $j->kelas->nama : '-' }}</td>
                                    <td class="p-3.5">{{ $j->mapel ? $j->mapel->nama : '-' }}</td>
                                    <td class="p-3.5 font-mono text-emerald-800 text-xs">
                                        Jam {{ $j->jam_ke_mulai }} s/d {{ $j->jam_ke_selesai }}
                                    </td>
                                    <td class="p-3.5 text-xs text-slate-600">{{ $j->ruangan ? $j->ruangan->nama : '-' }}</td>
                                    <td class="p-3.5">
                                        @if($j->hari === $hariIni)
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                                Hari Ini
                                            </span>
                                        @else
                                            <span class="bg-slate-100 text-slate-500 text-xs font-medium px-2.5 py-1 rounded-full">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div id="guruTab_rekap" class="guru-tab-content space-y-6 {{ $tabAktif === 'rekap' ? '' : 'hidden' }}">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg flex items-center space-x-2">
                        <i class="fa-solid fa-clipboard-user text-emerald-700"></i>
                        <span>C. Rekap Riwayat Presensi Anda</span>
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Catatan riwayat scan kehadiran mandiri dan absensi proxy sekretaris.</p>
                </div>

                <form method="GET" action="{{ route('guru.dashboard') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="tab" value="rekap">
                    <select name="bulan" class="px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-xs">
                        <option value="">-- Semua Bulan --</option>
                        @for($b = 1; $b <= 12; $b++)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(2026, $b, 1)->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                        Filter
                    </button>
                    @if(request('bulan'))
                        <a href="{{ route('guru.dashboard', ['tab' => 'rekap']) }}" class="bg-slate-100 text-slate-600 px-3 py-2 rounded-xl text-xs font-semibold hover:bg-slate-200">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            @if(!$rekapAbsensi || $rekapAbsensi->isEmpty())
                <div class="p-12 text-center text-slate-400">
                    <i class="fa-solid fa-clipboard-question text-4xl mb-3 text-slate-300"></i>
                    <p class="text-sm">Belum ada riwayat absensi.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                            <tr>
                                <th class="p-3.5 rounded-l-xl">No</th>
                                <th class="p-3.5">Waktu Kehadiran</th>
                                <th class="p-3.5">Metode</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 rounded-r-xl">Keterangan / Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($rekapAbsensi as $index => $ra)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-3.5 font-medium text-slate-400">{{ $rekapAbsensi->firstItem() + $index }}</td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($ra->tanggal)->isoFormat('dddd, D MMMM Y') }}</div>
                                        <div class="text-xs text-slate-400 font-mono">{{ $ra->jam_masuk ? 'Pukul ' . \Illuminate\Support\Str::substr($ra->jam_masuk, 0, 5) . ' WIB' : '-' }}</div>
                                    </td>
                                    <td class="p-3.5">
                                        <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-semibold">
                                            {{ $ra->metode }}
                                        </span>
                                    </td>
                                    <td class="p-3.5">
                                        @if($ra->status === 'Hadir')
                                            <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Hadir</span>
                                        @elseif($ra->status === 'Terlambat')
                                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Terlambat</span>
                                        @elseif($ra->status === 'Izin')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Izin</span>
                                        @elseif($ra->status === 'Sakit')
                                            <span class="bg-sky-100 text-sky-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Sakit</span>
                                        @else
                                            <span class="bg-rose-100 text-rose-800 text-xs font-bold px-2.5 py-1 rounded-full uppercase">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-xs text-slate-600">
                                        @if($ra->metode === 'Scan')
                                            <span>Jarak: {{ $ra->jarak_meter ?? 0 }} m dari kampus</span>
                                            @if($ra->latitude)
                                                <span class="text-slate-400 block font-mono text-[10px]">Lat: {{ $ra->latitude }}, Long: {{ $ra->longitude }}</span>
                                            @endif
                                        @else
                                            <span class="italic text-slate-700">"{{ $ra->alasan ?? 'Dicatat Sekretaris' }}"</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $rekapAbsensi->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function switchGuruTab(tabName) {
    document.querySelectorAll('.guru-tab-content').forEach(function(el) {
        el.classList.add('hidden');
    });
    document.querySelectorAll('.guru-tab-btn').forEach(function(btn) {
        btn.classList.remove('bg-emerald-700', 'text-white', 'shadow');
        btn.classList.add('text-slate-600');
    });

    const activeContent = document.getElementById('guruTab_' + tabName);
    const activeBtn = document.getElementById('btnTab_' + tabName);
    if (activeContent) activeContent.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.add('bg-emerald-700', 'text-white', 'shadow');
        activeBtn.classList.remove('text-slate-600');
    }
}

let userLatitude = null;
let userLongitude = null;

function dapatkanLokasi() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                const gpsEl = document.getElementById('gpsText');
                if (gpsEl) {
                    gpsEl.innerText = 'Lokasi GPS terdeteksi (Lat: ' + userLatitude.toFixed(5) + ', Long: ' + userLongitude.toFixed(5) + ')';
                    gpsEl.parentElement.classList.remove('text-slate-600');
                    gpsEl.parentElement.classList.add('text-emerald-800', 'font-semibold');
                }
            },
            function(error) {
                const gpsEl = document.getElementById('gpsText');
                if (gpsEl) {
                    gpsEl.innerText = 'GPS ditolak / tidak aktif. Harap izinkan akses lokasi di browser untuk absensi.';
                    gpsEl.parentElement.classList.add('text-rose-600', 'font-semibold');
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }
}

function ambilLokasiUlang() {
    dapatkanLokasi();
}

document.addEventListener('DOMContentLoaded', function() {
    dapatkanLokasi();

    const readerEl = document.getElementById('reader');
    if (readerEl) {
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: { width: 220, height: 220 } },
            false
        );

        html5QrcodeScanner.render(function(decodedText) {
            if (!userLatitude || !userLongitude) {
                alert('Menunggu koordinat lokasi GPS Anda. Pastikan lokasi aktif dan diizinkan di peramban.');
                dapatkanLokasi();
                return;
            }

            document.getElementById('formKodeBarcode').value = decodedText;
            document.getElementById('formLatitude').value = userLatitude;
            document.getElementById('formLongitude').value = userLongitude;

            html5QrcodeScanner.clear();
            document.getElementById('scanForm').submit();
        });
    }
});
</script>
@endsection

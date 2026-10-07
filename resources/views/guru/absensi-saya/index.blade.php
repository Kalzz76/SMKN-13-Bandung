@extends('layouts.guru', ['title' => 'Absensi Saya - Portal Guru SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Absensi Saya (Scan Barcode)</h2>
            <p class="text-sm text-slate-500 mt-1">Arahkan kamera ke kartu barcode kehadiran Anda di area sekolah SMKN 13 Bandung.</p>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Scanner -->
        <div class="lg:col-span-7 bg-white p-4 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm space-y-5 sm:space-y-6">
            
            <!-- Scanner Card Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 sm:pb-5 border-b border-slate-100">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center text-lg sm:text-xl shrink-0 shadow-2xs">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-tight">Pemindai Barcode Kehadiran</h4>
                        <p class="text-xs text-slate-400 mt-0.5 leading-snug">Pastikan barcode berada tepat di dalam kotak fokus kamera.</p>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="shrink-0 self-start sm:self-center">
                    @if($absensiHariIni)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Sudah Absen ({{ $absensiHariIni->status }})</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold whitespace-nowrap bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>Belum Absen Hari Ini</span>
                        </span>
                    @endif
                </div>
            </div>

            @if($absensiHariIni)
                <!-- Success State -->
                <div class="bg-gradient-to-b from-emerald-50/80 to-white border border-emerald-200/90 rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-center space-y-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl sm:text-3xl mx-auto shadow-md ring-4 ring-emerald-100">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <h4 class="text-lg sm:text-xl font-bold text-slate-900">Presensi Hari Ini Berhasil!</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
                            Kehadiran Anda telah tercatat otomatis pada <span class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($absensiHariIni->tanggal)->isoFormat('dddd, D MMMM Y') }}</span> pukul <span class="font-semibold text-slate-700">{{ \Illuminate\Support\Str::substr($absensiHariIni->jam_masuk, 0, 5) }} WIB</span>.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3 pt-4 border-t border-emerald-100 max-w-lg mx-auto">
                        <div class="p-3 bg-white rounded-2xl border border-slate-200/70 shadow-2xs">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Status</span>
                            <span class="font-bold text-emerald-700 text-xs sm:text-sm mt-0.5 block">{{ $absensiHariIni->status }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-slate-200/70 shadow-2xs">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Waktu</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm mt-0.5 block">{{ \Illuminate\Support\Str::substr($absensiHariIni->jam_masuk, 0, 5) }} WIB</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-slate-200/70 shadow-2xs">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Metode</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm mt-0.5 block">{{ $absensiHariIni->metode }}</span>
                        </div>
                        <div class="p-3 bg-white rounded-2xl border border-slate-200/70 shadow-2xs">
                            <span class="text-[10px] text-slate-400 block font-bold uppercase tracking-wider">Jarak GPS</span>
                            <span class="font-bold text-slate-800 text-xs sm:text-sm mt-0.5 block">{{ $absensiHariIni->jarak_meter ?? 0 }} m</span>
                        </div>
                    </div>
                </div>
            @else
                <!-- GPS Indicator Box -->
                <div id="gpsStatusBox" class="p-3 sm:p-3.5 bg-slate-50/80 border border-slate-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs transition">
                    <div class="flex items-center gap-2.5 text-slate-600 min-w-0">
                        <span id="gpsIndicator" class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping shrink-0"></span>
                        <span id="gpsText" class="truncate font-medium">Mendeteksi koordinat lokasi GPS Anda...</span>
                    </div>
                    <button type="button" onclick="ambilLokasiUlang()" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-3.5 py-2 sm:py-1.5 rounded-xl bg-white border border-slate-200 text-emerald-700 font-bold hover:bg-emerald-50 hover:border-emerald-200 transition shadow-2xs shrink-0 cursor-pointer">
                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                        <span>Perbarui GPS</span>
                    </button>
                </div>

                <!-- Camera Scanner Container -->
                <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-slate-200 bg-slate-900 p-2 sm:p-3 shadow-inner min-h-[260px] sm:min-h-[320px] flex flex-col items-center justify-center">
                    <div id="reader" class="w-full overflow-hidden rounded-xl sm:rounded-2xl"></div>
                </div>

                <!-- Hidden Scan Submission Form -->
                <form id="scanForm" method="POST" action="{{ route('guru.absensi-scan') }}">
                    @csrf
                    <input type="hidden" name="kode_barcode" id="formKodeBarcode">
                    <input type="hidden" name="latitude" id="formLatitude">
                    <input type="hidden" name="longitude" id="formLongitude">
                </form>
            @endif
        </div>

        <!-- Right Column: Instructions & Tips -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Instructions Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-lg shadow-2xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base">Panduan Scan Absensi</h4>
                </div>

                <div class="space-y-3.5">
                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-xs">1</span>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            Izinkan akses <strong class="text-slate-900">Kamera</strong> dan <strong class="text-slate-900">Lokasi (GPS)</strong> saat browser meminta izin.
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-xs">2</span>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            Pastikan Anda berada di area sekolah <strong class="text-slate-900">SMKN 13 Bandung</strong> (radius GPS maksimal 100 meter).
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-xs">3</span>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            Arahkan kamera tepat ke kartu barcode identitas guru Anda hingga terbaca jelas di kotak fokus.
                        </div>
                    </div>

                    <div class="flex items-start gap-3.5 p-3 rounded-2xl bg-slate-50/70 border border-slate-100">
                        <span class="w-7 h-7 rounded-xl bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-xs">4</span>
                        <div class="text-xs text-slate-600 leading-relaxed">
                            Sistem otomatis memverifikasi kode barcode, mencatat jam kehadiran, dan menyimpan status Anda.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pro Tip Box -->
            <div class="p-5 rounded-3xl bg-amber-50/80 border border-amber-200/80 flex items-start gap-3.5 shadow-2xs">
                <i class="fa-solid fa-lightbulb text-amber-500 text-lg mt-0.5 shrink-0"></i>
                <div class="text-xs text-amber-900 leading-relaxed">
                    <span class="font-bold block text-amber-950 mb-0.5">Tips Pencahayaan</span>
                    Hindari pantulan cahaya berlebih atau bayangan gelap pada barcode kartu agar kamera dapat mendeteksi barcode dengan instan.
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Custom Scanner Styling -->
<style>
#reader {
    border: none !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    width: 100% !important;
}
#reader > div {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    text-align: center !important;
}
#reader video {
    border-radius: 1rem;
    object-fit: cover;
    margin: 0 auto !important;
    max-width: 100% !important;
}
#reader__scan_region {
    border-radius: 1rem;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto !important;
    text-align: center !important;
    width: 100% !important;
}
#reader__scan_region img,
#reader img {
    margin: 1.5rem auto !important;
    display: block !important;
    filter: invert(1) hue-rotate(180deg) opacity(0.85) !important;
}
#reader__dashboard_section {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    text-align: center !important;
    width: 100% !important;
}
#reader__dashboard_section_csr {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.75rem !important;
    text-align: center !important;
}
#reader__dashboard_section_csr > div {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.5rem !important;
    text-align: center !important;
}
#reader__camera_selection {
    background-color: #1e293b !important;
    color: #f8fafc !important;
    border: 1px solid #475569 !important;
    border-radius: 0.75rem !important;
    padding: 0.5rem 1rem !important;
    font-size: 0.8125rem !important;
    outline: none !important;
}
#reader button {
    border: none !important;
    padding: 0.625rem 1.5rem !important;
    border-radius: 0.75rem !important;
    font-size: 0.875rem !important;
    font-weight: 700 !important;
    cursor: pointer !important;
    transition: background-color 0.2s !important;
    margin: 0.5rem auto !important;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2) !important;
}
/* Style Start Button */
#html5-qrcode-button-camera-start,
#reader__dashboard_section_csr button:first-of-type {
    background-color: #4f46e5 !important;
    color: white !important;
}
#html5-qrcode-button-camera-start:hover,
#reader__dashboard_section_csr button:first-of-type:hover {
    background-color: #4338ca !important;
}
/* Style Stop Button */
#html5-qrcode-button-camera-stop,
#reader__dashboard_section_csr button:last-of-type {
    background-color: #e11d48 !important;
    color: white !important;
}
#html5-qrcode-button-camera-stop:hover,
#reader__dashboard_section_csr button:last-of-type:hover {
    background-color: #be123c !important;
}
/* Hide disabled buttons so only the active one appears */
#reader button:disabled {
    display: none !important;
}
#reader a {
    color: #94a3b8 !important;
    font-size: 0.75rem !important;
    text-decoration: underline !important;
    display: inline-block !important;
    margin-top: 0.25rem !important;
}
#reader a:hover {
    color: #e2e8f0 !important;
}
#reader__dashboard_section_csr span {
    color: #cbd5e1 !important;
    font-size: 0.8125rem !important;
}
#reader__status_span {
    color: #94a3b8 !important;
    font-size: 0.75rem !important;
    text-align: center !important;
}
#reader__header_message {
    text-align: center !important;
    color: #94a3b8 !important;
    font-size: 0.75rem !important;
}
</style>

<!-- HTML5 QR Code CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
let userLatitude = null;
let userLongitude = null;

function dapatkanLokasi() {
    const gpsEl = document.getElementById('gpsText');
    const indicatorEl = document.getElementById('gpsIndicator');

    if ("geolocation" in navigator) {
        if (indicatorEl) {
            indicatorEl.className = 'w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping shrink-0';
        }
        if (gpsEl) {
            gpsEl.innerText = 'Mendeteksi koordinat lokasi GPS Anda...';
            gpsEl.parentElement.className = 'flex items-center gap-2.5 text-slate-600 min-w-0';
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                userLatitude = position.coords.latitude;
                userLongitude = position.coords.longitude;
                if (gpsEl) {
                    gpsEl.innerText = 'GPS siap (Lat: ' + userLatitude.toFixed(5) + ', Long: ' + userLongitude.toFixed(5) + ')';
                    gpsEl.parentElement.className = 'flex items-center gap-2.5 text-emerald-800 font-semibold min-w-0';
                }
                if (indicatorEl) {
                    indicatorEl.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0';
                }
            },
            function(error) {
                if (gpsEl) {
                    gpsEl.innerText = 'GPS belum aktif / izin ditolak. Harap izinkan lokasi di browser.';
                    gpsEl.parentElement.className = 'flex items-center gap-2.5 text-rose-600 font-semibold min-w-0';
                }
                if (indicatorEl) {
                    indicatorEl.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0';
                }
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        if (gpsEl) {
            gpsEl.innerText = 'Browser Anda tidak mendukung Geolocation.';
            gpsEl.parentElement.className = 'flex items-center gap-2.5 text-rose-600 font-semibold min-w-0';
        }
    }
}

function ambilLokasiUlang() {
    dapatkanLokasi();
}

function rapikanTombolScanner() {
    const buttons = document.querySelectorAll('#reader button');
    if (buttons.length >= 2) {
        // buttons[0] = Start Scanning, buttons[1] = Stop Scanning
        const startBtn = buttons[0];
        const stopBtn = buttons[1];

        // Jika startBtn disabled atau kamera lagi jalan -> sembunyikan startBtn, tampilkan stopBtn
        if (startBtn.disabled || startBtn.style.display === 'none') {
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-block';
        } else if (stopBtn.disabled) {
            startBtn.style.display = 'inline-block';
            stopBtn.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    dapatkanLokasi();

    const readerEl = document.getElementById('reader');
    if (readerEl) {
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { 
                fps: 10, 
                qrbox: { width: 220, height: 220 },
                aspectRatio: 1.333334,
                videoConstraints: {
                    facingMode: { ideal: "environment" }
                }
            },
            false
        );

        html5QrcodeScanner.render(function(decodedText) {
            if (!userLatitude || !userLongitude) {
                showModalMsg('Perhatian', 'Menunggu koordinat lokasi GPS Anda. Pastikan lokasi aktif dan diizinkan di peramban.', 'error');
                dapatkanLokasi();
                return;
            }

            document.getElementById('formKodeBarcode').value = decodedText;
            document.getElementById('formLatitude').value = userLatitude;
            document.getElementById('formLongitude').value = userLongitude;

            html5QrcodeScanner.clear();
            document.getElementById('scanForm').submit();
        });

        // Pantau perubahan tombol scanner
        const observer = new MutationObserver(rapikanTombolScanner);
        observer.observe(readerEl, { childList: true, subtree: true, attributes: true });
        setTimeout(rapikanTombolScanner, 300);
        setTimeout(rapikanTombolScanner, 800);
    }
});
</script>
@endsection

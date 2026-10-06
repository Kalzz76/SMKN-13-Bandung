<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Tanda Guru - SMKN 13 Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .print-wrapper {
                padding: 0 !important;
                max-width: 100% !important;
            }
            .card-guru-cetak {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin-bottom: 24px !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm 10mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 min-h-screen text-slate-900">
    <div class="max-w-6xl mx-auto space-y-6 print-wrapper">
        <div class="no-print bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-extrabold text-slate-800 text-lg sm:text-xl flex items-center space-x-2">
                    <i class="fa-solid fa-id-card text-emerald-700"></i>
                    <span>Cetak Kartu Tanda Guru (Barcode Presensi)</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Jumlah kartu: <strong class="text-slate-800">{{ $daftarGuru->count() }} Guru</strong> | Format Kartu Tanda Guru SMKN 13 Bandung</p>
            </div>
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <button type="button" onclick="window.print()" class="flex-1 sm:flex-none bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow transition flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Sekarang</span>
                </button>
                <button type="button" onclick="window.close()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-sm transition">
                    Tutup
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 justify-items-center">
            @foreach($daftarGuru as $g)
                <div class="card-guru-cetak w-full max-w-[550px] bg-[#0f783e] text-white rounded-[26px] p-5 pb-0 shadow-lg border-[3px] border-[#0c6233] flex flex-col justify-between relative overflow-hidden" style="-webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: #0f783e;">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="inline-block bg-white text-[#0f783e] font-black text-lg sm:text-xl tracking-wider uppercase px-5 py-1.5 rounded-full shadow-sm">
                                KARTU TANDA GURU
                            </div>

                            <div class="mt-5 space-y-2.5">
                                <div>
                                    <h2 class="text-white font-black text-xl sm:text-2xl uppercase tracking-wide leading-snug">
                                        {{ $g->nama }}
                                    </h2>
                                    <p class="text-emerald-50 text-xs sm:text-sm font-semibold tracking-wide font-mono mt-0.5">
                                        NUPTK/NIP {{ $g->nip ?? '-' }}
                                    </p>
                                </div>

                                <div class="pt-1">
                                    <p class="text-emerald-100 text-[11px] sm:text-xs font-bold tracking-widest uppercase">
                                        GURU MATA PELAJARAN
                                    </p>
                                    <p class="text-white font-black text-sm sm:text-base tracking-wide uppercase mt-0.5">
                                        {{ $g->mapel->nama ?? $g->mapel_utama ?? ($g->jabatan ? $g->jabatan : 'GURU PENGAJAR') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-32 sm:w-36 flex flex-col items-center flex-shrink-0 space-y-2">
                            <div class="w-full bg-white rounded-2xl p-2 shadow flex flex-col items-center justify-center text-center">
                                <img src="{{ $pengaturan->logo_url ?? asset('images/logo-smkn13.png') }}" alt="Logo SMKN 13 Bandung" class="h-12 w-auto object-contain">
                                <span class="text-[10px] font-black text-slate-900 tracking-wider block mt-1 uppercase leading-tight">SMKN 13</span>
                                <span class="text-[10px] font-black text-slate-900 tracking-wider block uppercase leading-tight">BANDUNG</span>
                            </div>

                            <div class="w-full bg-white rounded-2xl p-2 shadow flex items-center justify-center">
                                <div id="qr_{{ $g->id }}" data-code="{{ $g->kode_barcode }}" class="qrcode-item flex items-center justify-center"></div>
                            </div>

                            <div class="w-full text-[9px] text-emerald-50 space-y-0.5 font-medium text-right leading-tight pr-1">
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="fa-solid fa-globe text-[9px]"></i>
                                    <span class="truncate">{{ $pengaturan->social_media ?? 'https://smkn13bandung.sch.id' }}</span>
                                </div>
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="fa-regular fa-envelope text-[9px]"></i>
                                    <span class="truncate">{{ $pengaturan->email ?? 'info@smkn13bandung.sch.id' }}</span>
                                </div>
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="fa-solid fa-phone text-[9px]"></i>
                                    <span class="truncate">{{ $pengaturan->telepon ?? '(022) 7311546' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#09572c] text-emerald-100 text-[8.5px] sm:text-[9.5px] font-normal px-4 py-1.5 -mx-5 mt-4 rounded-b-[22px] flex items-center justify-center text-center leading-normal" style="-webkit-print-color-adjust: exact; print-color-adjust: exact; background-color: #09572c;">
                        <span>Alamat: {{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta No.KM. 10, Jatisari, Kec. Buahbatu, Kota Bandung, Jawa Barat 40286' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.qrcode-item').forEach(function(el) {
            const code = el.getAttribute('data-code');
            if (code) {
                new QRCode(el, {
                    text: code,
                    width: 96,
                    height: 96,
                    colorDark : "#052e16",
                    colorLight : "#ffffff",
                    correctLevel : QRCode.CorrectLevel.M
                });
            }
        });
    });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Absensi Guru - SMKN 13 Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .card-cetak { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-slate-100 p-8 min-h-screen text-slate-900">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 flex items-center justify-between">
            <div>
                <h1 class="font-bold text-slate-800 text-base">Cetak Kartu Absensi Guru</h1>
                <p class="text-xs text-slate-500 mt-0.5">Jumlah kartu: {{ $daftarGuru->count() }} guru</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="window.print()" class="bg-[#0b6534] hover:bg-[#09572c] text-white font-bold px-5 py-2.5 rounded-xl text-sm shadow-sm transition flex items-center space-x-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Sekarang</span>
                </button>
                <button type="button" onclick="window.close()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-sm shadow-sm transition">
                    Tutup
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($daftarGuru as $g)
                <div class="card-cetak bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-[#0b6534] text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                            13
                        </div>
                        <div>
                            <h2 class="text-[11px] font-black uppercase tracking-wider text-slate-800 leading-none">SMK NEGERI 13 BANDUNG</h2>
                            <p class="text-[9px] text-[#0b6534] font-bold uppercase tracking-wider mt-0.5">KARTU BARCODE KEHADIRAN GURU</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 py-1">
                        <div class="space-y-1">
                            <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $g->nama }}</h3>
                            <p class="text-[10px] text-slate-400 font-mono">{{ $g->nip ?? '-' }}</p>
                            <p class="text-[11px] text-slate-700 font-medium">{{ $g->mapel->nama ?? $g->mapel_utama ?? ($g->jabatan ?? 'Guru Pengajar') }}</p>
                            <span class="inline-block text-[9px] bg-slate-50 text-slate-500 font-mono px-2 py-0.5 rounded border border-slate-200 mt-2">
                                BARCODE-{{ strtoupper(\Illuminate\Support\Str::limit($g->kode_barcode, 10, '...')) }}
                            </span>
                        </div>
                        <div class="flex-shrink-0 p-1.5 bg-white rounded-xl border border-slate-200">
                            <div id="qr_{{ $g->id }}" data-code="{{ $g->kode_barcode }}" class="qrcode-item"></div>
                        </div>
                    </div>

                    <div class="pt-2 flex items-center justify-between text-[9px] text-slate-400">
                        <span>Gunakan saat presensi di area kampus</span>
                        <span>SMKN 13 Bandung</span>
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
                    width: 85,
                    height: 85,
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
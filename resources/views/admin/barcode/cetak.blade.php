@php
    $pengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
    $totalGuru = $daftarGuru->count();
    $isSingle = $totalGuru === 1;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Absensi Guru - {{ $pengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; }

        .card-id {
            width: 428px;
            min-width: 428px;
            max-width: 428px;
            height: 270px;
            min-height: 270px;
            background-image: url('{{ asset('images/desain-card.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            border-radius: 0.875rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
            overflow: hidden;
            position: relative;
            page-break-inside: avoid;
            break-inside: avoid;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: flex;
            box-sizing: border-box;
            flex-shrink: 0;
        }

        @if($isSingle)
        @page {
            size: 85.6mm 54mm landscape;
            margin: 0;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            #printableArea {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            .card-scale-wrapper {
                width: 85.6mm !important;
                height: 54mm !important;
                overflow: hidden !important;
                display: flex !important;
                justify-content: center !important;
            }
            .card-scaler {
                transform: scale(0.755) !important;
                transform-origin: top left !important;
                width: 428px !important;
                height: 270px !important;
            }
            .card-id {
                box-shadow: none !important;
                border: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                width: 428px !important;
                height: 270px !important;
            }
        }
        @else
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            #printableArea {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 6mm 10.8mm !important;
                padding: 0 !important;
                margin: 0 auto !important;
                width: 100% !important;
            }
            .card-scale-wrapper {
                width: 85.6mm !important;
                max-width: 85.6mm !important;
                height: 54mm !important;
                max-height: 54mm !important;
                overflow: hidden !important;
                display: flex !important;
                justify-content: center !important;
                align-items: flex-start !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .card-scaler {
                transform: scale(0.755) !important;
                transform-origin: top left !important;
                width: 428px !important;
                height: 270px !important;
            }
            .card-id {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                width: 428px !important;
                height: 270px !important;
            }
        }
        @endif
    </style>
</head>
<body class="p-4 sm:p-8 min-h-screen text-slate-900">
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Control Header -->
        <div class="no-print bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-bold text-slate-900 text-base sm:text-lg">Cetak Kartu Absensi Guru</h1>
                <p class="text-xs text-slate-500 mt-0.5">
                    Jumlah kartu: <span class="font-semibold text-emerald-700">{{ $totalGuru }} guru</span>
                    @if(!$isSingle)
                        <span class="text-slate-400 ml-1">· Klik <b>"Download PDF"</b> untuk mengunduh dokumen .pdf, atau <b>"Cetak Sekarang"</b> untuk kirim ke printer.</span>
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto">
                <button type="button" id="btnDownloadPdf" onclick="downloadAllPDF()" class="col-span-1 sm:w-auto bg-rose-600 hover:bg-rose-500 text-white font-bold px-3.5 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer whitespace-nowrap">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span id="btnDownloadPdfText">Download PDF</span>
                </button>
                <button type="button" onclick="window.print()" class="col-span-1 sm:w-auto bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-3.5 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-xs transition flex items-center justify-center space-x-2 cursor-pointer whitespace-nowrap">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Sekarang</span>
                </button>
                <button type="button" onclick="window.close()" class="col-span-2 sm:col-span-1 sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition flex items-center justify-center cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>

        <!-- Cards Grid -->
        <div id="printableArea" class="grid grid-cols-1 md:grid-cols-2 gap-6 grid-cetak justify-items-center">
            @foreach($daftarGuru as $g)
                @php
                    $rawNama = trim($g->nama);
                    $parts = explode(' ', $rawNama, 2);
                    $nama1 = $parts[0] ?? $rawNama;
                    $nama2 = $parts[1] ?? '';
                @endphp
                <div class="card-scale-wrapper w-full max-w-[480px] flex justify-center items-start overflow-hidden">
                    <div class="card-scaler origin-top transition-transform duration-150" style="width: 480px; height: 276px; flex-shrink: 0;">
                        <div class="card-id" id="card_{{ $g->id }}">
                            <!-- Left Section: Dark Navy Area -->
                            <div style="width: 46%; height: 100%; display: flex; flex-direction: column; justify-content: space-between; padding: 14px 6px 14px 14px; box-sizing: border-box; text-align: left; position: relative; z-index: 2;">
                                <!-- Top Left: Logo dari Pengaturan Sekolah & School Title -->
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @if(!empty($pengaturan?->logo_url))
                                        <img src="{{ $pengaturan->logo_url }}" alt="Logo" style="width: 35px; height: 35px; object-fit: contain; border-radius: 50%; background: #ffffff; padding: 2px; border: 2px solid #fbbf24; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                    @else
                                        <div style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #fbbf24; background: #0f172a; color: #ffffff; font-weight: 900; font-size: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.2);">
                                            13
                                        </div>
                                    @endif
                                    <div style="min-width: 0; flex: 1;">
                                        <h2 style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: -0.02em; color: #ffffff; line-height: 1.15; margin: 0; font-family: 'Inter', sans-serif;">
                                            {{ $pengaturan->nama_sekolah ?? 'SMK Negeri 13 Bandung' }}
                                        </h2>
                                        <p style="font-size: 7.5px; font-weight: 700; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.1em; line-height: 1; margin: 3px 0 0 0; font-family: 'Inter', sans-serif;">
                                            KARTU PRESENSI GURU
                                        </p>
                                    </div>
                                </div>

                                <!-- Bottom Left: Contact Info from Pengaturan Sekolah -->
                                <div style="max-width: 175px; font-size: 8px; color: #f1f5f9; line-height: 1.35; margin-top: 6px; display: flex; flex-direction: column; gap: 3.5px; font-family: 'Inter', sans-serif;">
                                    <div style="display: flex; align-items: flex-start; gap: 5px;">
                                        <i class="fa-solid fa-house" style="color: #fbbf24; width: 12px; text-align: center; flex-shrink: 0; margin-top: 1.5px; font-size: 8px;"></i>
                                        <span style="color: #f1f5f9; line-height: 1.25; word-break: break-word;">{{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta Km. 10 Gedebage, Bandung' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fa-solid fa-envelope" style="color: #fbbf24; width: 12px; text-align: center; flex-shrink: 0; font-size: 8px;"></i>
                                        <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 155px;">{{ $pengaturan->email ?? 'info@smkn13bandung.sch.id' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fa-solid fa-phone" style="color: #fbbf24; width: 12px; text-align: center; flex-shrink: 0; font-size: 8px;"></i>
                                        <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $pengaturan->telepon ?? '(022) 7801234 / 7805678' }}</span>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <i class="fa-solid fa-globe" style="color: #fbbf24; width: 12px; text-align: center; flex-shrink: 0; font-size: 8px;"></i>
                                        <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $pengaturan->social_media ?? 'smkn13bdg.sch.id' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section: White Area -->
                            <div style="width: 54%; height: 100%; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; text-align: right; padding: 14px 14px 14px 8px; box-sizing: border-box; position: relative; z-index: 2;">
                                <!-- Top Right: Name & Role -->
                                <div style="max-width: 210px; display: flex; flex-direction: column; gap: 2px;">
                                    <h3 style="font-size: 14px; font-weight: 900; letter-spacing: -0.02em; line-height: 1.15; text-transform: uppercase; margin: 0; font-family: 'Inter', sans-serif;">
                                        <span class="sr-only">{{ $g->nama }}</span>
                                        <span style="color: #f59e0b;">{{ $nama1 }}</span>
                                        <span style="color: #0f172a;">{{ $nama2 }}</span>
                                    </h3>
                                    <p style="font-size: 9.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #1e293b; margin: 0; font-family: 'Inter', sans-serif;">
                                        {{ $g->jabatan ?? 'GURU PENGAJAR' }}
                                    </p>
                                    <p style="font-size: 8.5px; color: #64748b; font-family: monospace; margin: 0;">
                                        NIP. {{ $g->nip ?? '-' }}
                                    </p>
                                </div>

                                <!-- Bottom Right: QR Code -->
                                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                    <div style="padding: 4px; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; display: inline-block;">
                                        <div id="qr_{{ $g->id }}" data-code="{{ $g->kode_barcode }}" class="qrcode-item"></div>
                                    </div>
                                    <span style="font-size: 7.5px; font-family: monospace; color: #94a3b8; margin-top: 3px; text-transform: uppercase; letter-spacing: -0.02em;">
                                        {{ Str::substr($g->kode_barcode, 0, 16) }}...
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
    function resizeCardScalers() {
        const wrappers = document.querySelectorAll('.card-scale-wrapper');
        const baseW = 428;
        const baseH = 270;

        wrappers.forEach(function(wrapper) {
            const scaler = wrapper.querySelector('.card-scaler');
            if (!scaler) return;
            const availW = wrapper.clientWidth;

            if (availW > 0 && availW < baseW) {
                const scale = availW / baseW;
                scaler.style.transform = `scale(${scale})`;
                scaler.style.transformOrigin = 'top center';
                wrapper.style.height = `${Math.round(baseH * scale)}px`;
            } else {
                scaler.style.transform = 'none';
                wrapper.style.height = `${baseH}px`;
            }
        });
    }

    window.addEventListener('resize', resizeCardScalers);

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof QRCode !== 'undefined') {
            document.querySelectorAll('.qrcode-item').forEach(function(el) {
                const code = el.getAttribute('data-code');
                if (code) {
                    new QRCode(el, {
                        text: code,
                        width: 68,
                        height: 68,
                        colorDark : "#0f172a",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.M
                    });
                }
            });
        }

        resizeCardScalers();

        // Auto download PDF jika query param download=pdf tersedia
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('download') === 'pdf') {
            setTimeout(function() {
                downloadAllPDF();
            }, 600);
        }
    });

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(resizeCardScalers, 50);
    }

    async function downloadAllPDF() {
        const btn = document.getElementById('btnDownloadPdf');
        const originalText = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i><span>Menyiapkan PDF...</span>';
            btn.disabled = true;
        }

        try {
            // Pastikan seluruh font web dan glyph icon sudah selesai termuat
            if (document.fonts && document.fonts.ready) {
                await document.fonts.ready;
            }

            const cards = document.querySelectorAll('.card-id');
            const totalCards = cards.length;

            if (totalCards === 0) {
                showModalMsg('Pemberitahuan', 'Tidak ada kartu barcode untuk diunduh.', 'error');
                return;
            }

            const { jsPDF } = window.jspdf;
            const isSingle = totalCards === 1;

            if (isSingle) {
                // Single card PDF: format persis standar ISO ID-1 (85.6 mm x 54 mm)
                const pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: [85.6, 54]
                });

                const card = cards[0];
                const canvas = await html2canvas(card, {
                    scale: 3,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                    logging: false
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.98);
                pdf.addImage(imgData, 'JPEG', 0, 0, 85.6, 54);

                const guruNama = '{{ Str::slug($daftarGuru->first()->nama ?? "Guru") }}';
                pdf.save('Kartu-Barcode-' + guruNama + '.pdf');
            } else {
                // Multi-card PDF: format A4 Portrait dengan 8 kartu berukuran persis 85.6 x 54 mm (2 kolom x 4 baris)
                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });

                const cardW = 85.6;
                const cardH = 54;
                const startX = 14;
                const startY = 12;
                const gapX = 10.8;
                const gapY = 6;
                const cardsPerPage = 8;

                for (let i = 0; i < totalCards; i++) {
                    if (btn) {
                        btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-1"></i><span>Membuat PDF (${i + 1}/${totalCards})...</span>`;
                    }

                    const card = cards[i];
                    const canvas = await html2canvas(card, {
                        scale: 2.5,
                        useCORS: true,
                        backgroundColor: '#ffffff',
                        logging: false
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.95);

                    const pageIndex = i % cardsPerPage;
                    if (i > 0 && pageIndex === 0) {
                        pdf.addPage();
                    }

                    const col = pageIndex % 2;
                    const row = Math.floor(pageIndex / 2);
                    const x = startX + col * (cardW + gapX);
                    const y = startY + row * (cardH + gapY);

                    pdf.addImage(imgData, 'JPEG', x, y, cardW, cardH);

                    // Jeda sejenak setiap 4 kartu agar browser tidak freeze
                    if (i % 4 === 0) {
                        await new Promise(r => setTimeout(r, 20));
                    }
                }

                if (btn) {
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i><span>Menyimpan file PDF...</span>';
                }

                pdf.save('Kumpulan-Kartu-Barcode-Guru.pdf');
            }
        } catch (err) {
            console.error('Error generating PDF:', err);
            showModalMsg('Gagal Memproses PDF', 'Terjadi kesalahan saat memproses file PDF: ' + err.message, 'error');
        } finally {
            if (btn) {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    }
    </script>

    @include('partials.modal-pesan')
</body>
</html>
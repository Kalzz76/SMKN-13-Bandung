@extends('layouts.admin', ['title' => 'Barcode Absensi - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Barcode Absensi Guru</h1>
            <p class="text-sm text-slate-500 mt-1">Pembuatan QR Code acak 32-karakter dan cetak Kartu Tanda Guru SMKN 13 Bandung.</p>
        </div>
        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto shrink-0">
            <a href="{{ route('admin.barcode.cetak', ['download' => 'pdf']) }}" target="_blank" class="col-span-1 sm:w-auto bg-rose-600 hover:bg-rose-500 text-white font-semibold px-3.5 py-2.5 rounded-xl shadow-xs transition flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Download PDF</span>
            </a>
            <a href="{{ route('admin.barcode.cetak') }}" target="_blank" class="col-span-1 sm:w-auto bg-slate-800 hover:bg-slate-700 text-white font-semibold px-3.5 py-2.5 rounded-xl shadow-xs transition flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Barcode</span>
            </a>
            <form method="POST" action="{{ route('admin.barcode.generate-semua') }}" data-confirm="Apakah Anda yakin ingin membuat ulang semua barcode guru? Barcode lama tidak akan berlaku." class="col-span-2 sm:col-span-1 m-0 w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto bg-amber-600 hover:bg-amber-500 text-white font-semibold px-3.5 py-2.5 rounded-xl shadow-xs transition flex items-center justify-center space-x-2 text-xs sm:text-sm whitespace-nowrap cursor-pointer">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Generate Ulang</span>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg mb-2 flex items-center space-x-2">
                    <i class="fa-solid fa-qrcode text-[#0b6534]"></i>
                    <span>Generator QR Code Guru</span>
                </h3>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Pilih guru pengajar untuk membuat atau memperbarui kode barcode unik yang akan digunakan untuk scan validasi kehadiran mandiri.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.barcode.generate') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru Pengajar</label>
                    <select name="id_guru" id="selectGuru" onchange="window.location.href='{{ route('admin.barcode.index') }}?guru_id=' + this.value" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-[#0b6534] text-sm" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ $guruTerpilih && $guruTerpilih->id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} ({{ $g->nip ?? 'No NIP' }}) - {{ $g->kode_barcode ? 'Aktif' : 'Belum Ada Barcode' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-[#0b6534] hover:bg-[#09572c] text-white font-bold py-3 rounded-xl shadow transition flex items-center justify-center space-x-2 text-sm">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>{{ $guruTerpilih && $guruTerpilih->kode_barcode ? 'Generate Ulang Barcode' : 'Buat Barcode Absensi' }}</span>
                </button>
            </form>
        </div>

        <div class="lg:col-span-7 bg-white p-4 sm:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center space-y-4">
            @if($guruTerpilih && $guruTerpilih->kode_barcode)
                @php
                    $pengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
                    $rawNama = trim($guruTerpilih->nama);
                    $parts = explode(' ', $rawNama, 2);
                    $nama1 = $parts[0] ?? $rawNama;
                    $nama2 = $parts[1] ?? '';
                @endphp
                <div class="w-full flex flex-col items-center justify-center overflow-hidden">
                    <div id="cardScaleContainer" class="w-full max-w-[428px] flex justify-center items-start overflow-hidden">
                        <div id="cardBarcodeScaler" class="origin-top transition-transform duration-150" style="width: 428px; height: 270px; flex-shrink: 0;">
                            <div id="cardBarcodePreview" style="background-image: url('{{ asset('images/desain-card.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; width: 428px; height: 270px; min-width: 428px; min-height: 270px; border-radius: 14px; border: 1px solid #cbd5e1; position: relative; overflow: hidden; display: flex; box-sizing: border-box; text-align: left; box-shadow: 0 8px 20px rgba(0,0,0,0.08);">
                                <div style="width: 46%; height: 100%; display: flex; flex-direction: column; justify-content: flex-start; gap: 7px; padding: 12px 6px 12px 12px; box-sizing: border-box; position: relative; z-index: 2;">
                                    <div style="display: flex; align-items: center; gap: 7px;">
                                        @if(!empty($pengaturan?->logo_url))
                                            <img src="{{ $pengaturan->logo_url }}" alt="Logo" style="width: 32px; height: 32px; object-fit: contain; border-radius: 50%; background: #ffffff; padding: 2px; border: 2px solid #fbbf24; flex-shrink: 0;">
                                        @else
                                            <div style="width: 32px; height: 32px; border-radius: 50%; border: 2px solid #fbbf24; background: #0f172a; color: #ffffff; font-weight: 900; font-size: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                13
                                            </div>
                                        @endif
                                        <div style="min-width: 0; flex: 1;">
                                            <h2 style="font-size: 9.5px; font-weight: 900; text-transform: uppercase; letter-spacing: -0.02em; color: #ffffff; line-height: 1.15; margin: 0; font-family: 'Inter', sans-serif;">
                                                {{ $pengaturan->nama_sekolah ?? 'SMK Negeri 13 Bandung' }}
                                            </h2>
                                            <p style="font-size: 7px; font-weight: 700; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.1em; line-height: 1; margin: 2px 0 0 0; font-family: 'Inter', sans-serif;">
                                                KARTU PRESENSI GURU
                                            </p>
                                        </div>
                                    </div>

                                    <div style="width: 100%; max-width: 168px; font-size: 7px; color: #f1f5f9; line-height: 1.25; display: flex; flex-direction: column; gap: 3px; font-family: 'Inter', sans-serif; background: rgba(11, 34, 62, 0.78); padding: 6px 7px; border-radius: 8px; border: 1px solid rgba(251, 191, 36, 0.25); box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        <div style="display: flex; align-items: flex-start; gap: 5px;">
                                            <i class="fa-solid fa-house" style="color: #fbbf24; width: 11px; text-align: center; flex-shrink: 0; margin-top: 1px; font-size: 7px;"></i>
                                            <span style="color: #f1f5f9; line-height: 1.2; word-break: break-word;">{{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta Km. 10 Gedebage, Bandung' }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-envelope" style="color: #fbbf24; width: 11px; text-align: center; flex-shrink: 0; font-size: 7px;"></i>
                                            <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">{{ $pengaturan->email ?? 'info@smkn13bandung.sch.id' }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-phone" style="color: #fbbf24; width: 11px; text-align: center; flex-shrink: 0; font-size: 7px;"></i>
                                            <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $pengaturan->telepon ?? '(022) 7801234 / 7805678' }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-globe" style="color: #fbbf24; width: 11px; text-align: center; flex-shrink: 0; font-size: 7px;"></i>
                                            <span style="color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $pengaturan->social_media ?? 'smkn13bdg.sch.id' }}</span>
                                        </div>
                                    </div>

                                    <div style="margin-top: auto; display: flex; align-items: center; gap: 4px; padding-bottom: 2px;">
                                        <span style="font-size: 6px; font-weight: 700; color: #fbbf24; text-transform: uppercase; letter-spacing: 0.1em; font-family: 'Inter', sans-serif; opacity: 0.9;">
                                            Pusat Keunggulan · SMKN 13
                                        </span>
                                    </div>
                                </div>

                                <div style="width: 54%; height: 100%; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; text-align: right; padding: 12px 12px 12px 6px; box-sizing: border-box; position: relative; z-index: 2;">
                                    <div style="max-width: 200px; display: flex; flex-direction: column; gap: 2px;">
                                        <h3 style="font-size: 13px; font-weight: 900; letter-spacing: -0.02em; line-height: 1.15; text-transform: uppercase; margin: 0; font-family: 'Inter', sans-serif;">
                                            <span style="color: #f59e0b;">{{ $nama1 }}</span>
                                            <span style="color: #0f172a;">{{ $nama2 }}</span>
                                        </h3>
                                        <p style="font-size: 9px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #1e293b; margin: 0; font-family: 'Inter', sans-serif;">
                                            {{ $guruTerpilih->jabatan ?? 'GURU PENGAJAR' }}
                                        </p>
                                        <p style="font-size: 8px; color: #64748b; font-family: monospace; margin: 0;">
                                            NIP. {{ $guruTerpilih->nip ?? '-' }}
                                        </p>
                                    </div>

                                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                        <div style="padding: 3px; background: #ffffff; border-radius: 6px; border: 1px solid #e2e8f0; display: inline-block;">
                                            <div id="qrcode"></div>
                                        </div>
                                        <span style="font-size: 7px; font-family: monospace; color: #94a3b8; margin-top: 2px; text-transform: uppercase; letter-spacing: -0.02em;">
                                            {{ Str::substr($guruTerpilih->kode_barcode, 0, 16) }}...
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 pt-2 w-full max-w-[428px]">
                    <button type="button" id="btnDownloadCardPdf" onclick="downloadCardPDF('{{ Str::slug($guruTerpilih->nama) }}')" class="bg-rose-600 hover:bg-rose-500 text-white font-semibold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition flex items-center justify-center space-x-2 shadow cursor-pointer">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Download PDF</span>
                    </button>
                    <a href="{{ route('admin.barcode.cetak', ['id' => $guruTerpilih->id]) }}" target="_blank" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl text-xs sm:text-sm transition flex items-center justify-center space-x-2 shadow">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Kartu Guru Ini</span>
                    </a>
                </div>
            @else
                <div class="w-36 h-36 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                    <i class="fa-solid fa-id-card text-4xl"></i>
                </div>
                <p class="text-sm text-slate-400 mt-3">Pilih guru pengajar di sebelah kiri untuk melihat kartu.</p>
            @endif
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <h3 class="font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-table-list text-[#0b6534]"></i>
                <span>Daftar Status Barcode Guru Pengajar</span>
            </h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $daftarGuru->count() }} Guru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[680px]">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                    <tr>
                        <th class="p-3.5 rounded-l-xl whitespace-nowrap">No</th>
                        <th class="p-3.5 whitespace-nowrap">Nama Guru</th>
                        <th class="p-3.5 whitespace-nowrap">NIP</th>
                        <th class="p-3.5 whitespace-nowrap">Mata Pelajaran</th>
                        <th class="p-3.5 whitespace-nowrap">Status Barcode</th>
                        <th class="p-3.5 rounded-r-xl text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($daftarGuru as $index => $g)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3.5 font-medium text-slate-400 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">{{ $g->nama }}</td>
                            <td class="p-3.5 text-slate-500 text-xs font-mono whitespace-nowrap">{{ $g->nip ?? '-' }}</td>
                            <td class="p-3.5 text-slate-600 text-xs whitespace-nowrap">{{ $g->mapel_utama ?? '-' }}</td>
                            <td class="p-3.5 whitespace-nowrap">
                                @if($g->kode_barcode)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 border border-slate-200 whitespace-nowrap">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Belum Ada</span>
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($g->kode_barcode)
                                        <a href="{{ route('admin.barcode.cetak', ['id' => $g->id, 'download' => 'pdf']) }}" target="_blank" class="bg-rose-50 hover:bg-rose-100 text-rose-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center space-x-1" title="Download Kartu PDF">
                                            <i class="fa-solid fa-file-pdf"></i>
                                            <span>PDF</span>
                                        </a>
                                        <a href="{{ route('admin.barcode.cetak', ['id' => $g->id]) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center space-x-1" title="Cetak / Preview">
                                            <i class="fa-solid fa-print"></i>
                                            <span>Cetak</span>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('admin.barcode.generate') }}">
                                        @csrf
                                        <input type="hidden" name="id_guru" value="{{ $g->id }}">
                                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-[#0b6534] px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                            <i class="fa-solid fa-rotate mr-1"></i> {{ $g->kode_barcode ? 'Generate Ulang' : 'Buat Barcode' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
@if($guruTerpilih && $guruTerpilih->kode_barcode)
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function resizeCardPreview() {
    const scaler = document.getElementById('cardBarcodeScaler');
    const container = document.getElementById('cardScaleContainer');
    if (!scaler || !container) return;
    const baseW = 428;
    const baseH = 270;
    const containerW = container.clientWidth;

    if (containerW > 0 && containerW < baseW) {
        const scale = containerW / baseW;
        scaler.style.transform = `scale(${scale})`;
        scaler.style.transformOrigin = 'top center';
        container.style.height = `${Math.round(baseH * scale)}px`;
    } else {
        scaler.style.transform = 'none';
        container.style.height = `${baseH}px`;
    }
}

window.addEventListener('resize', resizeCardPreview);
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById("qrcode"), {
        text: "{{ $guruTerpilih->kode_barcode }}",
        width: 68,
        height: 68,
        colorDark : "#0f172a",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.M
    });
    resizeCardPreview();
});

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    setTimeout(resizeCardPreview, 50);
}

async function downloadCardPDF(namaSlug) {
    const cardEl = document.getElementById('cardBarcodePreview');
    const btn = document.getElementById('btnDownloadCardPdf');
    if (!cardEl) return;

    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i><span>Membuat PDF...</span>';
    btn.disabled = true;

    try {
        if (document.fonts && document.fonts.ready) {
            await document.fonts.ready;
        }

        const canvas = await html2canvas(cardEl, {
            scale: 3,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        });

        const { jsPDF } = window.jspdf;
        // Ukuran standar ISO/IEC 7810 ID-1: 85.6 mm x 54 mm
        const pdf = new jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: [85.6, 54]
        });

        const imgData = canvas.toDataURL('image/jpeg', 0.98);
        pdf.addImage(imgData, 'JPEG', 0, 0, 85.6, 54);
        pdf.save('Kartu-Barcode-' + (namaSlug || 'Guru') + '.pdf');
    } catch (err) {
        console.error('Error generating PDF:', err);
        showModalMsg('Gagal Cetak PDF', 'Terjadi kesalahan saat membuat file PDF: ' + err.message, 'error');
    } finally {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}
</script>
@endif
@endsection
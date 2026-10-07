@extends('layouts.guru', ['title' => 'Pengajuan Izin - Portal Guru SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Izin Guru</h2>
            <p class="text-sm text-slate-500 mt-1">Ajukan permohonan ketidakhadiran, dispensasi keterlambatan, atau penugasan luar secara resmi.</p>
        </div>
    </div>

    <!-- Grid: Form Pengajuan & Riwayat -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Kolom Kiri: Form Permohonan Izin -->
        <div class="lg:col-span-5 bg-white p-5 sm:p-7 rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xs space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg font-bold">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Formulir Pengajuan Izin</h3>
                    <p class="text-xs text-slate-400">Lengkapi data izin di bawah ini</p>
                </div>
            </div>

            <form action="{{ route('guru.izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="formPengajuanIzin">
                @csrf

                <!-- Jenis Izin -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Izin <span class="text-rose-500">*</span></label>
                    <select name="jenis_izin" id="selectJenisIzin" onchange="toggleFormIzin(this.value)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition" required>
                        <option value="">-- Pilih Jenis Izin --</option>
                        <option value="Sakit" {{ old('jenis_izin') === 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ old('jenis_izin') === 'Izin' ? 'selected' : '' }}>Izin / Keperluan Penting</option>
                        <option value="Terlambat" {{ old('jenis_izin') === 'Terlambat' ? 'selected' : '' }}>Terlambat Hadir (Dispensasi Jam)</option>
                        <option value="Cuti" {{ old('jenis_izin') === 'Cuti' ? 'selected' : '' }}>Cuti Tahunan / Melahirkan</option>
                        <option value="Tugas Luar" {{ old('jenis_izin') === 'Tugas Luar' ? 'selected' : '' }}>Tugas Luar / Dinas / Pelatihan</option>
                    </select>
                </div>

                <!-- Section Tanggal Rentang (Untuk Sakit, Izin, Cuti, Tugas Luar) -->
                <div id="sectionRentangTanggal" class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_mulai" id="inputTanggalMulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition" required>
                        </div>
                        <div id="wrapperTanggalSelesai">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_selesai" id="inputTanggalSelesai" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Section Jam Estimasi (Khusus Terlambat) -->
                <div id="sectionTerlambat" class="hidden p-3.5 bg-amber-50/70 border border-amber-200 rounded-2xl space-y-2">
                    <label class="block text-xs font-bold text-amber-900">Estimasi Sampai di Sekolah Pukul <span class="text-rose-500">*</span></label>
                    <div class="flex items-center space-x-2">
                        <input type="time" name="jam_estimasi" id="inputJamEstimasi" value="{{ old('jam_estimasi', '08:30') }}" class="w-full bg-white border border-amber-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 outline-none focus:ring-2 focus:ring-amber-500">
                        <span class="text-xs font-bold text-amber-800 whitespace-nowrap">WIB</span>
                    </div>
                    <p class="text-[11px] text-amber-700 leading-snug">Berikan perkiraan waktu kehadiran Anda di kampus SMKN 13 Bandung.</p>
                </div>

                <!-- Alasan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Alasan / Keterangan <span class="text-rose-500">*</span></label>
                    <textarea name="alasan" rows="3" placeholder="Tuliskan keterangan lengkap alasan izin Anda..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition" required>{{ old('alasan') }}</textarea>
                </div>

                <!-- Upload Bukti (Opsional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah Bukti Dokumen / Surat <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-emerald-50 hover:file:text-emerald-700 border border-slate-200 rounded-xl p-1.5 bg-slate-50 cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG, atau PDF (maks. 3MB). Surat dokter/surat dinas tugas.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-2 shadow-xs cursor-pointer">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        <span>Kirim Pengajuan Izin</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Riwayat Pengajuan Izin Guru -->
        <div class="lg:col-span-7 bg-white p-5 sm:p-7 rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-lg font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Riwayat Pengajuan Izin Saya</h3>
                        <p class="text-xs text-slate-400">Status verifikasi oleh admin/tata usaha</p>
                    </div>
                </div>
            </div>

            @if($daftarPengajuan->isEmpty())
                <div class="py-12 text-center text-slate-400 space-y-2">
                    <i class="fa-regular fa-folder-open text-3xl text-slate-300 block"></i>
                    <p class="text-xs">Anda belum pernah mengajukan permohonan izin.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3">Jenis Izin</th>
                                <th class="p-3">Periode / Jam</th>
                                <th class="p-3">Alasan</th>
                                <th class="p-3 text-center">Bukti</th>
                                <th class="p-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($daftarPengajuan as $p)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="p-3 font-bold text-slate-900 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold
                                            @if($p->jenis_izin === 'Sakit') bg-blue-50 text-blue-700 border border-blue-200
                                            @elseif($p->jenis_izin === 'Izin') bg-amber-50 text-amber-700 border border-amber-200
                                            @elseif($p->jenis_izin === 'Terlambat') bg-purple-50 text-purple-700 border border-purple-200
                                            @elseif($p->jenis_izin === 'Cuti') bg-teal-50 text-teal-700 border border-teal-200
                                            @else bg-emerald-50 text-emerald-700 border border-emerald-200
                                            @endif">
                                            {{ $p->jenis_izin }}
                                        </span>
                                    </td>
                                    <td class="p-3 whitespace-nowrap">
                                        @if($p->jenis_izin === 'Terlambat')
                                            <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id')->isoFormat('D MMM Y') }}</div>
                                            <div class="text-[11px] text-purple-700 font-mono mt-0.5"><i class="fa-regular fa-clock mr-1"></i>Tiba ± {{ substr($p->jam_estimasi, 0, 5) }} WIB</div>
                                        @else
                                            <div class="font-semibold text-slate-800">
                                                {{ \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3 max-w-xs">
                                        <p class="text-slate-700 line-clamp-2 leading-relaxed">{{ $p->alasan }}</p>
                                        @if($p->catatan_admin)
                                            <p class="text-[10px] text-rose-600 mt-1 italic bg-rose-50 p-1.5 rounded-lg border border-rose-100">
                                                <strong>Catatan Admin:</strong> {{ $p->catatan_admin }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if($p->bukti)
                                            <a href="{{ asset('storage/' . $p->bukti) }}" target="_blank" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition shadow-2xs">
                                                <i class="fa-solid fa-paperclip text-[10px]"></i>
                                                <span>Lihat</span>
                                            </a>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center whitespace-nowrap">
                                        @if($p->status === 'Disetujui')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                                <i class="fa-solid fa-check text-[10px] mr-1"></i> Disetujui
                                            </span>
                                        @elseif($p->status === 'Ditolak')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                                <i class="fa-solid fa-xmark text-[10px] mr-1"></i> Ditolak
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                                <i class="fa-solid fa-clock text-[10px] mr-1"></i> Menunggu
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-3">
                    {{ $daftarPengajuan->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleFormIzin(jenis) {
    const sectionTerlambat = document.getElementById('sectionTerlambat');
    const wrapperTanggalSelesai = document.getElementById('wrapperTanggalSelesai');
    const inputJamEstimasi = document.getElementById('inputJamEstimasi');
    const inputTanggalSelesai = document.getElementById('inputTanggalSelesai');

    if (jenis === 'Terlambat') {
        sectionTerlambat.classList.remove('hidden');
        if (wrapperTanggalSelesai) wrapperTanggalSelesai.classList.add('hidden');
        if (inputJamEstimasi) inputJamEstimasi.setAttribute('required', 'required');
        if (inputTanggalSelesai) inputTanggalSelesai.removeAttribute('required');
    } else {
        sectionTerlambat.classList.add('hidden');
        if (wrapperTanggalSelesai) wrapperTanggalSelesai.classList.remove('hidden');
        if (inputJamEstimasi) inputJamEstimasi.removeAttribute('required');
        if (inputTanggalSelesai) inputTanggalSelesai.setAttribute('required', 'required');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const val = document.getElementById('selectJenisIzin').value;
    if (val) toggleFormIzin(val);
});
</script>
@endsection

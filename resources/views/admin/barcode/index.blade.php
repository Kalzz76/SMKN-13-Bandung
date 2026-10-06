@extends('layouts.admin', ['title' => 'Barcode Absensi - Admin SMKN 13 Bandung'])

@section('content')
<div class="space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Barcode Absensi Guru</h1>
            <p class="text-sm text-slate-500 mt-1">Pembuatan QR Code acak 32-karakter dan cetak Kartu Tanda Guru SMKN 13 Bandung.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.barcode.cetak', ['filter' => 'belum']) }}" target="_blank" class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
                <i class="fa-solid fa-user-clock"></i>
                <span>Cetak Guru Belum Barcode</span>
            </a>
            <a href="{{ route('admin.barcode.cetak') }}" target="_blank" class="bg-emerald-700 hover:bg-emerald-600 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Semua Guru</span>
            </a>
            <form method="POST" action="{{ route('admin.barcode.generate-semua') }}" onsubmit="return confirm('Apakah Anda yakin ingin membuat ulang semua barcode guru? Barcode lama tidak akan berlaku.')">
                @csrf
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Generate Ulang Semua</span>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg mb-2 flex items-center space-x-2">
                    <i class="fa-solid fa-qrcode text-emerald-700"></i>
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
                    <select name="id_guru" id="selectGuru" onchange="window.location.href='{{ route('admin.barcode.index') }}?guru_id=' + this.value" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($daftarGuru as $g)
                            <option value="{{ $g->id }}" {{ $guruTerpilih && $guruTerpilih->id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} ({{ $g->nip ?? 'No NIP' }}) - {{ $g->kode_barcode ? 'Aktif' : 'Belum Ada Barcode' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow transition flex items-center justify-center space-x-2 text-sm">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>{{ $guruTerpilih && $guruTerpilih->kode_barcode ? 'Generate Ulang Barcode' : 'Buat Barcode Absensi' }}</span>
                </button>
            </form>
        </div>

        <div class="lg:col-span-7 bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center">
            @if($guruTerpilih)
                <div class="w-full max-w-[480px] bg-[#0f783e] text-white rounded-[24px] p-5 pb-0 shadow-lg border-[3px] border-[#0c6233] flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <div class="inline-block bg-white text-[#0f783e] font-black text-xs sm:text-sm tracking-wider uppercase px-4 py-1 rounded-full shadow-sm">
                                KARTU TANDA GURU
                            </div>

                            <div class="mt-4 space-y-2">
                                <div>
                                    <h4 class="text-white font-black text-base sm:text-lg uppercase tracking-wide leading-tight">
                                        {{ $guruTerpilih->nama }}
                                    </h4>
                                    <p class="text-emerald-100 text-xs font-semibold tracking-wide font-mono mt-0.5">
                                        NUPTK/NIP {{ $guruTerpilih->nip ?? '-' }}
                                    </p>
                                </div>

                                <div class="pt-0.5">
                                    <p class="text-emerald-100 text-[10px] font-bold tracking-widest uppercase">
                                        GURU MATA PELAJARAN
                                    </p>
                                    <p class="text-white font-black text-xs sm:text-sm tracking-wide uppercase mt-0.5">
                                        {{ $guruTerpilih->mapel->nama ?? $guruTerpilih->mapel_utama ?? ($guruTerpilih->jabatan ? $guruTerpilih->jabatan : 'GURU PENGAJAR') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="w-28 flex flex-col items-center flex-shrink-0 space-y-1.5">
                            <div class="w-full bg-white rounded-xl p-1.5 shadow flex flex-col items-center justify-center text-center">
                                <img src="{{ $pengaturan->logo_url ?? asset('images/logo-smkn13.png') }}" alt="Logo SMKN 13" class="h-9 w-auto object-contain">
                                <span class="text-[8px] font-black text-slate-900 tracking-wider block mt-0.5 uppercase leading-none">SMKN 13</span>
                                <span class="text-[8px] font-black text-slate-900 tracking-wider block uppercase leading-none mt-0.5">BANDUNG</span>
                            </div>

                            <div class="w-full bg-white rounded-xl p-1.5 shadow flex items-center justify-center min-h-[78px]">
                                @if($guruTerpilih->kode_barcode)
                                    <div id="qrcodePreview" class="flex items-center justify-center"></div>
                                @else
                                    <div class="text-center p-1">
                                        <i class="fa-solid fa-qrcode text-slate-300 text-xl block"></i>
                                        <span class="text-[8px] text-slate-400 block mt-0.5">Belum Dibuat</span>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full text-[8px] text-emerald-100 space-y-0.5 font-medium text-right leading-tight pr-0.5">
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="fa-solid fa-globe text-[8px]"></i>
                                    <span class="truncate max-w-[85px]">{{ $pengaturan->social_media ?? 'smkn13bandung.sch.id' }}</span>
                                </div>
                                <div class="flex items-center justify-end space-x-1">
                                    <i class="fa-regular fa-envelope text-[8px]"></i>
                                    <span class="truncate max-w-[85px]">{{ $pengaturan->email ?? 'info@smkn13bandung.sch.id' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-[#09572c] text-emerald-100 text-[8px] font-normal px-3 py-1 -mx-5 mt-3 rounded-b-[20px] flex items-center justify-center text-center truncate">
                        <span>Alamat: {{ $pengaturan->alamat ?? 'Jl. Soekarno-Hatta No.KM. 10, Kota Bandung' }}</span>
                    </div>
                </div>

                <div class="flex items-center space-x-3 mt-4">
                    <a href="{{ route('admin.barcode.cetak', ['id' => $guruTerpilih->id]) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-5 py-2 rounded-xl text-xs transition flex items-center space-x-2">
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
                <i class="fa-solid fa-table-list text-emerald-700"></i>
                <span>Daftar Status Barcode Guru Pengajar</span>
            </h3>
            <span class="text-xs text-slate-500 font-medium">Total: {{ $daftarGuru->count() }} Guru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-100 text-slate-700 uppercase text-xs">
                    <tr>
                        <th class="p-3.5 rounded-l-xl">No</th>
                        <th class="p-3.5">Nama Guru</th>
                        <th class="p-3.5">NIP</th>
                        <th class="p-3.5">Mata Pelajaran</th>
                        <th class="p-3.5">Status Barcode</th>
                        <th class="p-3.5 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($daftarGuru as $index => $g)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3.5 font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="p-3.5 font-bold text-slate-900">{{ $g->nama }}</td>
                            <td class="p-3.5 text-slate-500 text-xs font-mono">{{ $g->nip ?? '-' }}</td>
                            <td class="p-3.5 text-slate-600 text-xs font-medium">{{ $g->mapel->nama ?? $g->mapel_utama ?? '-' }}</td>
                            <td class="p-3.5">
                                @if($g->kode_barcode)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-check mr-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fa-solid fa-clock mr-1"></i> Belum Dibuat
                                    </span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.barcode.cetak', ['id' => $g->id]) }}" target="_blank" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition flex items-center space-x-1">
                                        <i class="fa-solid fa-print"></i>
                                        <span>Cetak</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.barcode.generate') }}">
                                        @csrf
                                        <input type="hidden" name="id_guru" value="{{ $g->id }}">
                                        <button type="submit" class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
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

@if($guruTerpilih && $guruTerpilih->kode_barcode)
<script>
document.addEventListener('DOMContentLoaded', function() {
    new QRCode(document.getElementById("qrcodePreview"), {
        text: "{{ $guruTerpilih->kode_barcode }}",
        width: 70,
        height: 70,
        colorDark : "#052e16",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.M
    });
});
</script>
@endif
@endsection
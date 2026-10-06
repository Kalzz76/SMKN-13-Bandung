@extends('layouts.admin', ['title' => 'Barcode Absensi - Admin SMKN 13 Bandung'])

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Barcode Absensi Guru</h1>
                <p class="text-sm text-slate-500 mt-1">Pembuatan QR Code acak 32-karakter dan kartu cetak kehadiran guru.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.barcode.cetak') }}" target="_blank"
                    class="bg-slate-800 hover:bg-slate-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Semua Barcode</span>
                </a>
                <form method="POST" action="{{ route('admin.barcode.generate-semua') }}"
                    onsubmit="return confirm('Apakah Anda yakin ingin membuat ulang semua barcode guru? Barcode lama tidak akan berlaku.')">
                    @csrf
                    <button type="submit"
                        class="bg-amber-600 hover:bg-amber-500 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition flex items-center space-x-2 text-sm">
                        <i class="fa-solid fa-arrows-rotate"></i>
                        <span>Generate Ulang Semua</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between space-y-6">
                <div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2 flex items-center space-x-2">
                        <i class="fa-solid fa-qrcode text-emerald-700"></i>
                        <span>Generator QR Code Guru</span>
                    </h3>
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Pilih guru pengajar untuk membuat atau memperbarui kode barcode unik yang akan digunakan untuk scan
                        validasi kehadiran mandiri.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.barcode.generate') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Guru Pengajar</label>
                        <select name="id_guru" id="selectGuru"
                            onchange="window.location.href='{{ route('admin.barcode.index') }}?guru_id=' + this.value"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600 text-sm"
                            required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($daftarGuru as $g)
                                <option value="{{ $g->id }}" {{ $guruTerpilih && $guruTerpilih->id == $g->id ? 'selected' : '' }}>
                                    {{ $g->nama }} ({{ $g->nip ?? 'No NIP' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow transition flex items-center justify-center space-x-2 text-sm">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Buat Barcode Absensi</span>
                    </button>
                </form>
            </div>

            <div
                class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col items-center justify-center text-center space-y-4">
                @if($guruTerpilih && $guruTerpilih->kode_barcode)
                    <div
                        class="p-4 bg-white rounded-2xl border-2 border-emerald-600/30 shadow-sm flex items-center justify-center">
                        <div id="qrcode" class="flex items-center justify-center"></div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block">Barcode Aktif</span>
                        <h4 class="text-lg font-bold text-slate-900 mt-0.5">Barcode: {{ $guruTerpilih->nama }}</h4>
                        <p class="text-xs text-slate-400 font-mono mt-1">Kode: {{ $guruTerpilih->kode_barcode }}</p>
                    </div>
                    <div class="flex items-center space-x-3 pt-2">
                        <a href="{{ route('admin.barcode.cetak', ['id' => $guruTerpilih->id]) }}" target="_blank"
                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl text-xs transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Kartu</span>
                        </a>
                    </div>
                @elseif($guruTerpilih)
                    <div
                        class="w-36 h-36 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 p-4">
                        <i class="fa-solid fa-qrcode text-3xl mb-2 text-slate-300"></i>
                        <span class="text-xs">Belum ada barcode</span>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-900">{{ $guruTerpilih->nama }}</h4>
                        <p class="text-xs text-slate-500 mt-1">Klik tombol "Buat Barcode Absensi" untuk membuat kode QR.</p>
                    </div>
                @else
                    <div
                        class="w-36 h-36 rounded-2xl border-2 border-dashed border-slate-200 flex items-center justify-center text-slate-300">
                        <i class="fa-solid fa-qrcode text-4xl"></i>
                    </div>
                    <p class="text-sm text-slate-400">Pilih guru pengajar di sebelah kiri.</p>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center space-x-2">
                <i class="fa-solid fa-table-list text-emerald-700"></i>
                <span>Daftar Status Barcode Guru Pengajar</span>
            </h3>

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
                                <td class="p-3.5 text-slate-600 text-xs">{{ $g->mapel_utama ?? '-' }}</td>
                                <td class="p-3.5">
                                    @if($g->kode_barcode)
                                        <span
                                            class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            <i class="fa-solid fa-check mr-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-xs font-semibold px-2.5 py-1 rounded-full">
                                            Belum Ada
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        @if($g->kode_barcode)
                                            <a href="{{ route('admin.barcode.cetak', ['id' => $g->id]) }}" target="_blank"
                                                class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-print mr-1"></i> Cetak
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.barcode.generate') }}">
                                            @csrf
                                            <input type="hidden" name="id_guru" value="{{ $g->id }}">
                                            <button type="submit"
                                                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                                <i class="fa-solid fa-rotate mr-1"></i>
                                                {{ $g->kode_barcode ? 'Generate Ulang' : 'Buat Barcode' }}
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
            document.addEventListener('DOMContentLoaded', function () {
                new QRCode(document.getElementById("qrcode"), {
                    text: "{{ $guruTerpilih->kode_barcode }}",
                    width: 140,
                    height: 140,
                    colorDark: "#065f46",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        </script>
    @endif
@endsection
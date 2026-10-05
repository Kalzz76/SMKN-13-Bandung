@extends('layouts.admin', ['title' => 'Laporan Rekapitulasi Absensi - CMS SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-xl font-black text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-clipboard-user text-emerald-700"></i>
                <span>Laporan & Rekapitulasi Absensi</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Laporan komprehensif kehadiran pendidik dan peserta didik dengan opsi cetak dan ekspor CSV.
            </p>
        </div>
        <div class="flex space-x-2 bg-slate-100 p-1.5 rounded-xl">
            <a href="{{ route('admin.laporan.index', ['tab' => 'guru', 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'guru' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-chalkboard-user mr-1.5"></i>
                <span>Absensi Guru</span>
            </a>
            <a href="{{ route('admin.laporan.index', ['tab' => 'siswa', 'bulan' => $bulan, 'tahun' => $tahun, 'kelas_id' => $kelasId]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'siswa' ? 'bg-white text-emerald-800 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-user-graduate mr-1.5"></i>
                <span>Absensi Siswa</span>
            </a>
        </div>
    </div>

    @if($tab === 'guru')
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <input type="hidden" name="tab" value="guru">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        @foreach($daftarBulan as $num => $namaBulan)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        @foreach($daftarTahun as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Filter Guru</label>
                    <select name="guru_id" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        <option value="">Semua Guru</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id }}" {{ request('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-filter"></i>
                        <span>Terapkan</span>
                    </button>
                </div>
            </form>

            <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-600">
                    Periode Laporan: {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}
                </span>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.laporan.guru.cetak', ['bulan' => $bulan, 'tahun' => $tahun]) }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-print"></i>
                        <span>Cetak Laporan</span>
                    </a>
                    <a href="{{ route('admin.laporan.guru.csv', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="px-4 py-2 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-csv"></i>
                        <span>Ekspor CSV</span>
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-chart-pie text-emerald-700"></i>
                    <span>Ringkasan Kehadiran Seluruh Guru</span>
                </h3>
                <div class="overflow-x-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-2.5 px-3">Nama Guru</th>
                                <th class="py-2.5 px-3 w-28">NIP</th>
                                <th class="py-2.5 px-3 w-16 text-center">Hadir</th>
                                <th class="py-2.5 px-3 w-20 text-center">Terlambat</th>
                                <th class="py-2.5 px-3 w-16 text-center">Sakit</th>
                                <th class="py-2.5 px-3 w-16 text-center">Izin</th>
                                <th class="py-2.5 px-3 w-16 text-center">Alpa</th>
                                <th class="py-2.5 px-3 w-20 text-center font-bold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rekapGuru as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $item['guru']->nama }}</td>
                                    <td class="py-2.5 px-3 font-mono text-slate-500">{{ $item['guru']->nip ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-emerald-700">{{ $item['hadir'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-amber-600">{{ $item['terlambat'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-sky-600">{{ $item['sakit'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-yellow-600">{{ $item['izin'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-rose-600">{{ $item['alpa'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-black text-slate-900 bg-slate-50/50">{{ $item['total'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 text-center text-slate-400">Belum ada data guru terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-slate-100">
                <h3 class="font-bold text-sm text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-emerald-700"></i>
                    <span>Log Detail Absensi Guru ({{ $riwayatGuru->total() }} Catatan)</span>
                </h3>
                <div class="overflow-x-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-3 w-28">Tanggal</th>
                                <th class="py-3 px-3 w-24">Waktu</th>
                                <th class="py-3 px-3">Nama Guru</th>
                                <th class="py-3 px-3 w-28 text-center">Status</th>
                                <th class="py-3 px-3 w-28 text-center">Metode</th>
                                <th class="py-3 px-3">Lokasi / Keterangan</th>
                                <th class="py-3 px-3 w-40">Pencatat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($riwayatGuru as $row)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-2.5 px-3 font-mono text-slate-600 whitespace-nowrap">{{ $row->tanggal }}</td>
                                    <td class="py-2.5 px-3 font-mono text-slate-600 whitespace-nowrap">
                                        {{ $row->jam_masuk ? \Illuminate\Support\Str::substr($row->jam_masuk, 0, 5) . ' WIB' : '-' }}
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $row->guru ? $row->guru->nama : '-' }}</td>
                                    <td class="py-2.5 px-3 text-center">
                                        @if($row->status === 'Hadir')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Hadir</span>
                                        @elseif($row->status === 'Terlambat')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Terlambat</span>
                                        @elseif($row->status === 'Sakit')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-100 text-sky-800">Sakit</span>
                                        @elseif($row->status === 'Izin')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800">Izin</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Alpa</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $row->metode === 'Scan' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                            {{ $row->metode }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600">
                                        @if($row->metode === 'Scan')
                                            <span class="font-mono text-[11px] text-slate-500">
                                                Jarak {{ $row->jarak_meter ?? 0 }}m
                                                ({{ round($row->latitude, 4) }}, {{ round($row->longitude, 4) }})
                                            </span>
                                        @else
                                            <span>{{ $row->alasan ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600 font-medium">
                                        @if($row->metode === 'Scan')
                                            <span class="text-slate-400">Scan Barcode Mandiri</span>
                                        @else
                                            <span>{{ $row->userInput ? $row->userInput->name : 'Sekretaris' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        Belum ada riwayat absensi guru pada periode yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($riwayatGuru->hasPages())
                    <div class="pt-3">
                        {{ $riwayatGuru->links() }}
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
                <input type="hidden" name="tab" value="siswa">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Kelas</label>
                    <select name="kelas_id" required class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        @foreach($daftarKelas as $k)
                            <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Mata Pelajaran (Opsional)</label>
                    <select name="mapel_id" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        <option value="">Semua Mata Pelajaran</option>
                        @foreach($daftarMapel as $m)
                            <option value="{{ $m->id }}" {{ $mapelId == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full text-xs p-2.5 border border-slate-200 rounded-xl focus:border-emerald-600 focus:outline-none">
                        @foreach($daftarBulan as $num => $namaBulan)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center space-x-1.5 shadow-sm">
                        <i class="fa-solid fa-filter"></i>
                        <span>Filter Rekap</span>
                    </button>
                </div>
            </form>

            @if($kelasTerpilih)
                <div class="flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-slate-100">
                    <div>
                        <span class="text-xs font-bold text-slate-800">
                            Kelas: {{ $kelasTerpilih->nama }}
                        </span>
                        <span class="text-xs text-slate-500 ml-2">
                            Periode: {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.laporan.siswa.cetak', ['kelas_id' => $kelasTerpilih->id, 'bulan' => $bulan, 'tahun' => $tahun, 'mapel_id' => $mapelId]) }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-print"></i>
                            <span>Cetak Rekap</span>
                        </a>
                        <a href="{{ route('admin.laporan.siswa.csv', ['kelas_id' => $kelasTerpilih->id, 'bulan' => $bulan, 'tahun' => $tahun, 'mapel_id' => $mapelId]) }}" class="px-4 py-2 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 font-bold rounded-xl text-xs transition flex items-center space-x-1.5">
                            <i class="fa-solid fa-file-csv"></i>
                            <span>Ekspor CSV</span>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto border border-slate-100 rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-3 w-10 text-center">No</th>
                                <th class="py-3 px-3 w-28">NIS</th>
                                <th class="py-3 px-3">Nama Siswa</th>
                                <th class="py-3 px-3 w-16 text-center">L/P</th>
                                <th class="py-3 px-3 w-16 text-center">Hadir</th>
                                <th class="py-3 px-3 w-16 text-center">Sakit</th>
                                <th class="py-3 px-3 w-16 text-center">Izin</th>
                                <th class="py-3 px-3 w-16 text-center">Alpa</th>
                                <th class="py-3 px-3 w-20 text-center">Pertemuan</th>
                                <th class="py-3 px-3 w-36 text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($rekapSiswa as $index => $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-2.5 px-3 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                                    <td class="py-2.5 px-3 font-mono text-slate-600">{{ $item['siswa']->nis }}</td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $item['siswa']->nama }}</td>
                                    <td class="py-2.5 px-3 text-center text-slate-500">{{ $item['siswa']->jenis_kelamin ?? '-' }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-emerald-700">{{ $item['hadir'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-sky-600">{{ $item['sakit'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-yellow-600">{{ $item['izin'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-rose-600">{{ $item['alpa'] }}</td>
                                    <td class="py-2.5 px-3 text-center font-bold text-slate-700 bg-slate-50/50">{{ $item['total'] }}</td>
                                    <td class="py-2.5 px-3 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <div class="w-16 bg-slate-200 rounded-full h-2 overflow-hidden">
                                                <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $item['persentase'] }}%"></div>
                                            </div>
                                            <span class="font-mono font-bold text-[11px] text-slate-800">{{ $item['persentase'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-8 text-center text-slate-400">
                                        Belum ada siswa yang terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center text-slate-400">
                    Silakan tambahkan data kelas terlebih dahulu untuk melihat rekapitulasi siswa.
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

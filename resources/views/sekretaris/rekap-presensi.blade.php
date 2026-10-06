@extends('layouts.sekretaris', ['title' => 'Rekap Presensi Siswa - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Rekapitulasi Presensi Siswa</h3>
                <p class="text-xs text-slate-500 mt-1">Laporan kehadiran siswa per kelas dan mata pelajaran.</p>
            </div>

            <form method="GET" action="{{ route('sekretaris.rekap-presensi') }}" class="flex flex-wrap items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                    Kelas {{ $kelasAktif->nama ?? '-' }}
                </span>

                <select name="bulan" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    @foreach($daftarBulan as $bKey => $bVal)
                        <option value="{{ $bKey }}" {{ $bulan == $bKey ? 'selected' : '' }}>{{ $bVal }}</option>
                    @endforeach
                </select>

                <select name="tahun" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold outline-none focus:ring-2 focus:ring-emerald-600 bg-white">
                    @foreach($daftarTahun as $tVal)
                        <option value="{{ $tVal }}" {{ $tahun == $tVal ? 'selected' : '' }}>{{ $tVal }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(empty($rekapSiswa))
            <div class="text-center py-12 text-slate-400">
                <i class="fa-regular fa-folder-open text-3xl text-slate-300 mb-2 block"></i>
                <p class="text-sm">Belum ada data kehadiran siswa pada periode ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-[10px]">
                        <tr>
                            <th class="p-3 rounded-l-xl">No</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3 text-center">Hadir</th>
                            <th class="p-3 text-center">Sakit</th>
                            <th class="p-3 text-center">Izin</th>
                            <th class="p-3 text-center">Alpa</th>
                            <th class="p-3 text-center">Total</th>
                            <th class="p-3 rounded-r-xl text-center">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rekapSiswa as $idx => $r)
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3 font-semibold">{{ $idx + 1 }}</td>
                                <td class="p-3 font-bold text-slate-900">{{ $r['siswa']->nama }}</td>
                                <td class="p-3 text-center font-bold text-emerald-600">{{ $r['hadir'] }}</td>
                                <td class="p-3 text-center font-bold text-sky-600">{{ $r['sakit'] }}</td>
                                <td class="p-3 text-center font-bold text-amber-600">{{ $r['izin'] }}</td>
                                <td class="p-3 text-center font-bold text-rose-600">{{ $r['alpa'] }}</td>
                                <td class="p-3 text-center font-bold text-slate-800">{{ $r['total'] }}</td>
                                <td class="p-3 text-center">
                                    <span class="inline-block px-2 py-0.5 rounded-full font-bold {{ $r['persentase'] >= 80 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $r['persentase'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

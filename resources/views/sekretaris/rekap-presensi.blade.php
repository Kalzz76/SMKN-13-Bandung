@extends('layouts.sekretaris', ['title' => 'Rekap Presensi Siswa - SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 sm:space-y-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Rekapitulasi Presensi Siswa</h3>
                <p class="text-xs text-slate-500 mt-1">Laporan kehadiran siswa per kelas dan mata pelajaran.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('sekretaris.rekap-presensi', array_merge(request()->query(), ['export' => 'excel'])) }}"
                   class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-xs transition">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Unduh Excel</span>
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('sekretaris.rekap-presensi') }}" class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs items-end">
            <div>
                <label class="block font-bold text-slate-700 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                    @foreach($daftarKelas as $k)
                        <option value="{{ $k->id }}" {{ ($kelasAktif && $kelasAktif->id == $k->id) ? 'selected' : '' }}>{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Mata Pelajaran</label>
                <select name="id_mapel" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($daftarMapel as $m)
                        <option value="{{ $m->id }}" {{ ($idMapelPilihan ?? '') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl transition text-center shadow-xs cursor-pointer">
                    <i class="fa-solid fa-filter text-[11px] mr-1"></i> Filter
                </button>
                <a href="{{ route('sekretaris.rekap-presensi') }}" class="py-2 px-3 bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 font-bold rounded-xl transition text-center">
                    Reset
                </a>
            </div>
        </form>

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

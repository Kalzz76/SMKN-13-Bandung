@extends('layouts.admin', ['title' => 'Pengajuan Izin Guru - Administrator SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pengajuan Izin & Presensi Guru</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola permohonan izin ketidakhadiran guru dan catat status presensi (termasuk Alpa).</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="bukaModalCatat()" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-user-pen text-xs"></i>
                <span>Tandai Kehadiran Manual / Alpa</span>
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-inbox"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengajuan</span>
                <span class="text-2xl font-black text-slate-900 block mt-0.5">{{ $totalPengajuan }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Menunggu Review</span>
                <span class="text-2xl font-black text-amber-600 block mt-0.5">{{ $totalMenunggu }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Disetujui</span>
                <span class="text-2xl font-black text-emerald-600 block mt-0.5">{{ $totalDisetujui }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Ditolak</span>
                <span class="text-2xl font-black text-rose-600 block mt-0.5">{{ $totalDitolak }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3.5">
        <!-- Search Bar di Atas -->
        <form method="GET" action="{{ route('admin.izin.index') }}" class="flex items-center gap-2 w-full">
            @if($statusFilter)
                <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama guru / NIP..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shrink-0 cursor-pointer shadow-xs">
                Cari
            </button>
        </form>

        <!-- 4 Filter Menyamping di Bawah Search Bar -->
        <div class="flex items-center gap-2 overflow-x-auto pb-0.5" style="-webkit-overflow-scrolling: touch; scrollbar-width: none;">
            <a href="{{ route('admin.izin.index', array_filter(['search' => $search])) }}" class="whitespace-nowrap shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ !$statusFilter ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.izin.index', array_filter(['status' => 'Menunggu', 'search' => $search])) }}" class="whitespace-nowrap shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'Menunggu' ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu ({{ $totalMenunggu }})
            </a>
            <a href="{{ route('admin.izin.index', array_filter(['status' => 'Disetujui', 'search' => $search])) }}" class="whitespace-nowrap shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'Disetujui' ? 'bg-emerald-700 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Disetujui ({{ $totalDisetujui }})
            </a>
            <a href="{{ route('admin.izin.index', array_filter(['status' => 'Ditolak', 'search' => $search])) }}" class="whitespace-nowrap shrink-0 px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ $statusFilter === 'Ditolak' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Ditolak ({{ $totalDitolak }})
            </a>
        </div>
    </div>

    <!-- Tabel Daftar Pengajuan Izin -->
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
        @if($daftarPengajuan->isEmpty())
            <div class="py-16 text-center text-slate-400 space-y-2">
                <i class="fa-regular fa-folder-open text-3xl text-slate-300 block"></i>
                <p class="text-sm font-semibold text-slate-600">Tidak ada data pengajuan izin.</p>
                <p class="text-xs text-slate-400">Belum ada permohonan yang sesuai dengan filter saat ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-bold border-b border-slate-200">
                        <tr>
                            <th class="p-4">Guru</th>
                            <th class="p-4">Jenis Izin</th>
                            <th class="p-4">Periode / Estimasi Jam</th>
                            <th class="p-4">Alasan</th>
                            <th class="p-4 text-center">Bukti</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarPengajuan as $p)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-900 text-sm">{{ $p->guru->nama ?? 'Guru' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">NIP: {{ $p->guru->nip ?? '-' }}</div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold
                                        @if($p->jenis_izin === 'Sakit') bg-blue-50 text-blue-700 border border-blue-200
                                        @elseif($p->jenis_izin === 'Izin') bg-amber-50 text-amber-700 border border-amber-200
                                        @elseif($p->jenis_izin === 'Terlambat') bg-purple-50 text-purple-700 border border-purple-200
                                        @elseif($p->jenis_izin === 'Cuti') bg-teal-50 text-teal-700 border border-teal-200
                                        @else bg-emerald-50 text-emerald-700 border border-emerald-200
                                        @endif">
                                        {{ $p->jenis_izin }}
                                    </span>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    @if($p->jenis_izin === 'Terlambat')
                                        <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id')->isoFormat('dddd, D MMM Y') }}</div>
                                        <div class="text-[11px] text-purple-700 font-mono mt-0.5"><i class="fa-regular fa-clock mr-1"></i>Tiba ± {{ substr($p->jam_estimasi, 0, 5) }} WIB</div>
                                    @else
                                        <div class="font-bold text-slate-800">
                                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->locale('id')->isoFormat('D MMM') }} s/d {{ \Carbon\Carbon::parse($p->tanggal_selesai)->locale('id')->isoFormat('D MMM Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 max-w-xs">
                                    <p class="text-slate-700 leading-relaxed">{{ $p->alasan }}</p>
                                    @if($p->catatan_admin)
                                        <p class="text-[10px] text-slate-500 mt-1 italic">
                                            <strong>Catatan:</strong> {{ $p->catatan_admin }}
                                        </p>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($p->bukti)
                                        <a href="{{ asset('storage/' . $p->bukti) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] transition shadow-2xs">
                                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                            <span>Lihat Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($p->status === 'Disetujui')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="fa-solid fa-check text-[10px] mr-1"></i> Disetujui
                                        </span>
                                    @elseif($p->status === 'Ditolak')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                            <i class="fa-solid fa-xmark text-[10px] mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <i class="fa-solid fa-clock text-[10px] mr-1"></i> Menunggu
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right whitespace-nowrap">
                                    @if($p->status === 'Menunggu')
                                        <div class="flex items-center justify-end gap-2">
                                            <form method="POST" action="{{ route('admin.izin.setujui', $p->id) }}" data-confirm="Setujui pengajuan izin ini? Sistem otomatis mencatat ke rekap absensi guru.">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition shadow-2xs flex items-center gap-1 cursor-pointer">
                                                    <i class="fa-solid fa-check text-[10px]"></i>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>
                                            <button type="button" onclick="bukaModalTolak({{ $p->id }}, '{{ addslashes($p->guru->nama ?? 'Guru') }}', '{{ addslashes($p->jenis_izin) }}')" class="px-3 py-1.5 rounded-xl border border-rose-300 text-rose-600 hover:bg-rose-50 font-bold text-xs transition flex items-center gap-1 cursor-pointer">
                                                <i class="fa-solid fa-xmark text-[10px]"></i>
                                                <span>Tolak</span>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-slate-400 italic">Selesai diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $daftarPengajuan->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tolak Pengajuan Izin -->
    <div id="modalTolakPengajuan" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div id="modalTolakCard" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-ban"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Tolak Pengajuan Izin</h3>
                </div>
                <button type="button" onclick="tutupModalTolak()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">
                Anda akan menolak pengajuan izin <strong class="text-slate-800" id="tolakJenisIzin"></strong> untuk <strong class="text-slate-800" id="tolakNamaGuru"></strong>.
            </p>

            <form id="formTolakPengajuan" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Alasan Penolakan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea name="catatan_admin" rows="3" placeholder="Sertakan alasan penolakan untuk guru..." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-rose-500 focus:bg-white"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="tutupModalTolak()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Catat Kehadiran Guru Manual (Termasuk Alpa) -->
    <div id="modalCatatKehadiran" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
        <div id="modalCatatCard" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Catat Kehadiran / Tandai Alpa</h3>
                        <p class="text-[11px] text-slate-400">Pencatatan langsung oleh Tata Usaha / Admin</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalCatat()" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.izin.catat-manual') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Guru <span class="text-rose-500">*</span></label>
                    <select name="id_guru" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white" required>
                        <option value="">-- Pilih Nama Guru --</option>
                        @foreach($semuaGuru as $g)
                            <option value="{{ $g->id }}">{{ $g->nama }} (NIP: {{ $g->nip ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Kehadiran <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-semibold outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white" required>
                            <option value="Alpa" class="text-rose-600 font-bold">Alpa (Tanpa Keterangan)</option>
                            <option value="Hadir">Hadir</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Tugas Luar">Tugas Luar</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan / Alasan</label>
                    <input type="text" name="alasan" placeholder="Misal: Tanpa kabar, izin via telepon, dinas rapat, dll." class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="tutupModalCatat()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs cursor-pointer">
                        Simpan Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function bukaModalCatat() {
    const m = document.getElementById('modalCatatKehadiran');
    if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
}

function tutupModalCatat() {
    const m = document.getElementById('modalCatatKehadiran');
    if (m) {
        m.classList.remove('flex');
        m.classList.add('hidden');
    }
}

function bukaModalTolak(id, nama, jenis) {
    const m = document.getElementById('modalTolakPengajuan');
    const form = document.getElementById('formTolakPengajuan');
    const namaEl = document.getElementById('tolakNamaGuru');
    const jenisEl = document.getElementById('tolakJenisIzin');

    if (form) form.action = '{{ url("/admin/izin") }}/' + id + '/tolak';
    if (namaEl) namaEl.textContent = nama;
    if (jenisEl) jenisEl.textContent = jenis;

    if (m) {
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
}

function tutupModalTolak() {
    const m = document.getElementById('modalTolakPengajuan');
    if (m) {
        m.classList.remove('flex');
        m.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modalCatat = document.getElementById('modalCatatKehadiran');
    if (modalCatat) {
        modalCatat.addEventListener('click', function(e) {
            if (e.target === modalCatat) {
                tutupModalCatat();
            }
        });
    }

    const modalTolak = document.getElementById('modalTolakPengajuan');
    if (modalTolak) {
        modalTolak.addEventListener('click', function(e) {
            if (e.target === modalTolak) {
                tutupModalTolak();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalCatat();
            tutupModalTolak();
        }
    });
});
</script>
@endsection

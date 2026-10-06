@extends('layouts.guru', ['title' => 'Validasi Absensi - Portal Guru SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Validasi Absensi</h2>
        <p class="text-sm text-slate-500 mt-1">Periksa dan konfirmasi laporan absensi kelas yang diisi oleh sekretaris.</p>
    </div>

    @if(isset($daftarValidasi) && count($daftarValidasi) > 0)
        {{-- Jika ada laporan absensi yang menunggu validasi dari sekretaris --}}
        <div x-data="{
            detailMode: false,
            selectedSession: null,
            confirmAction(type) {
                this.detailMode = false;
            }
        }" class="space-y-6">

            <!-- List Mode -->
            <div x-show="!detailMode" class="space-y-4">
                @foreach($daftarValidasi as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs hover:border-slate-300 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">{{ $item['kelas'] }} — {{ $item['mapel'] }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span>{{ $item['tanggal'] }}</span>
                                    <span>•</span>
                                    <span>{{ $item['jam'] }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button @click="selectedSession = {{ json_encode($item) }}; detailMode = true"
                                    class="px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100 font-semibold text-xs tracking-wide flex items-center gap-2 transition">
                                <span>Verifikasi</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Detail Mode (Tanpa Pilih Jam Pelajaran, hanya Tolak & Konfirmasi) -->
            <div x-show="detailMode" x-cloak class="space-y-6">
                <div class="flex items-center gap-3">
                    <button @click="detailMode = false" class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-700 shadow-2xs transition">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Validasi Sesi</h3>
                        <p class="text-xs text-slate-500" x-text="selectedSession ? selectedSession.kelas + ' • ' + selectedSession.mapel : ''"></p>
                    </div>
                </div>

                <!-- Status Kehadiran Guru Banner -->
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-2xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-wider text-slate-400 uppercase">STATUS KEHADIRAN GURU</p>
                            <h4 class="text-sm font-bold text-slate-900 mt-0.5">Anda diabsenkan HADIR</h4>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs">
                        Hadir
                    </span>
                </div>

                <!-- Student Attendance Table -->
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
                    <div class="px-6 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <span>NAMA SISWA</span>
                        <span class="w-24 text-center">STATUS</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        <template x-if="selectedSession && selectedSession.students">
                            <template x-for="student in selectedSession.students" :key="student.nis">
                                <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition">
                                    <div>
                                        <p class="font-bold text-sm text-slate-900" x-text="student.name"></p>
                                        <p class="text-xs text-slate-400 font-mono mt-0.5" x-text="student.nis"></p>
                                    </div>
                                    <div class="w-24 flex justify-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                              x-text="student.status">
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>

                <!-- Action Buttons: Tolak & Konfirmasi Saja -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <button @click="confirmAction('tolak')"
                            class="w-full py-3 px-5 rounded-xl border border-rose-400 text-rose-600 font-bold text-sm hover:bg-rose-50 transition text-center shadow-xs">
                        Tolak
                    </button>
                    <button @click="confirmAction('konfirmasi')"
                            class="w-full py-3 px-5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm transition text-center shadow-xs">
                        Konfirmasi
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- State Kosong --}}
        <div class="flex items-center justify-center py-16 sm:py-24">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 max-w-md w-full shadow-xs text-center space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mx-auto shadow-2xs">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-base">Semua Absensi Terkonfirmasi</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Tidak ada laporan absensi baru yang memerlukan verifikasi manual saat ini.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

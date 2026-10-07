@extends('layouts.admin', ['title' => 'Chronos (Waktu Virtual) - CMS SMKN 13 Bandung'])

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="p-3.5 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl shadow-md text-white flex items-center justify-center">
                <i class="fa-solid fa-flask-vial text-2xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Chronos</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pengatur waktu virtual untuk pengujian fitur real-time</p>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            @if(!empty($status['enabled']))
                <form method="POST" action="{{ route('admin.chronos.reset') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition flex items-center space-x-1.5 cursor-pointer">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Kembalikan Waktu Nyata</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ !empty($status['enabled']) ? 'bg-indigo-50 text-indigo-600 border border-indigo-100' : 'bg-slate-100 text-slate-400' }}">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <h4 class="text-base font-bold {{ !empty($status['enabled']) ? 'text-indigo-600' : 'text-slate-900' }}">
                        {{ !empty($status['enabled']) ? 'Chronos Aktif' : 'Chronos Tidak Aktif' }}
                    </h4>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ !empty($status['enabled'])
                            ? "Sistem membaca waktu virtual: {$status['day_name']}, " . sprintf('%02d:%02d', $status['hour'], $status['minute'])
                            : 'Aktifkan untuk menguji fitur jadwal dan absensi' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.chronos.toggle') }}">
                @csrf
                <input type="hidden" name="enabled" value="{{ !empty($status['enabled']) ? '0' : '1' }}">
                <button type="submit" class="relative inline-flex h-7 w-14 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ !empty($status['enabled']) ? 'bg-indigo-600' : 'bg-slate-300' }}">
                    <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out {{ !empty($status['enabled']) ? 'translate-x-7' : 'translate-x-0' }}"></span>
                </button>
            </form>
        </div>
    </div>

    @if(!empty($status['enabled']))
        <form method="POST" action="{{ route('admin.chronos.update') }}" id="chronosForm" class="space-y-6">
            @csrf

            <div class="space-y-2.5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pilih Tanggal</h3>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-bold uppercase text-slate-400 block">Tanggal Virtual Saat Ini</span>
                            <span id="displayTanggal" class="text-lg font-black text-slate-900 block mt-1">
                                {{ $status['day_name'] }}, {{ \Carbon\Carbon::parse($status['date'])->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-3 w-full sm:w-auto">
                            <input type="date" name="date" id="inputDate" value="{{ $status['date'] }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 outline-none focus:ring-2 focus:ring-indigo-600 bg-slate-50">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Pilih Jam</h3>
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 space-y-8">
                    <div class="flex items-center justify-center space-x-3 sm:space-x-5">
                        <div class="flex flex-col items-center">
                            <div class="px-5 sm:px-8 py-3.5 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl text-white shadow-md">
                                <span id="displayHour" class="font-mono text-3xl sm:text-5xl font-black tracking-tight">
                                    {{ sprintf('%02d', $status['hour']) }}
                                </span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 mt-2 uppercase">Jam</span>
                        </div>

                        <span class="text-3xl sm:text-5xl font-black text-indigo-600 pb-5">:</span>

                        <div class="flex flex-col items-center">
                            <div class="px-5 sm:px-8 py-3.5 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl text-white shadow-md">
                                <span id="displayMinute" class="font-mono text-3xl sm:text-5xl font-black tracking-tight">
                                    {{ sprintf('%02d', $status['minute']) }}
                                </span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 mt-2 uppercase">Menit</span>
                        </div>

                        <span class="text-3xl sm:text-5xl font-black text-indigo-600 pb-5">:</span>

                        <div class="flex flex-col items-center">
                            <div class="px-5 sm:px-8 py-3.5 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl text-white shadow-md">
                                <span id="displaySecond" class="font-mono text-3xl sm:text-5xl font-black tracking-tight">
                                    {{ sprintf('%02d', $status['second'] ?? 0) }}
                                </span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400 mt-2 uppercase">Detik</span>
                        </div>
                    </div>

                    <div class="space-y-6 pt-2">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-mono text-slate-400">06:00</span>
                                <span class="font-bold text-slate-700">Geser Jam: <span id="sliderHourVal" class="text-indigo-600 font-mono">{{ sprintf('%02d', $status['hour']) }}:00</span></span>
                                <span class="font-mono text-slate-400">18:00</span>
                            </div>
                            <input type="range" name="hour" id="sliderHour" min="6" max="18" value="{{ max(6, min(18, $status['hour'])) }}" class="w-full h-2 bg-indigo-100 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-mono text-slate-400">00</span>
                                <span class="font-bold text-slate-700">Geser Menit: <span id="sliderMinuteVal" class="text-purple-600 font-mono">{{ sprintf('%02d', $status['minute']) }} menit</span></span>
                                <span class="font-mono text-slate-400">59</span>
                            </div>
                            <input type="range" name="minute" id="sliderMinute" min="0" max="59" value="{{ $status['minute'] }}" class="w-full h-2 bg-purple-100 rounded-lg appearance-none cursor-pointer accent-purple-600">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center justify-center space-x-2 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            <span>Terapkan Waktu Virtual</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="space-y-2.5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Preset Cepat</h3>
            <div class="flex flex-wrap gap-2.5">
                @php
                    $presets = [
                        ['label' => 'Senin Jam 1', 'day' => 1, 'hour' => 6, 'minute' => 30],
                        ['label' => 'Senin Jam 5', 'day' => 1, 'hour' => 9, 'minute' => 30],
                        ['label' => 'Selasa Pagi', 'day' => 2, 'hour' => 7, 'minute' => 0],
                        ['label' => 'Rabu Siang', 'day' => 3, 'hour' => 12, 'minute' => 0],
                        ['label' => 'Kamis Jam 3', 'day' => 4, 'hour' => 8, 'minute' => 15],
                        ['label' => 'Jumat Jam 1', 'day' => 5, 'hour' => 6, 'minute' => 30],
                    ];
                @endphp

                @foreach($presets as $p)
                    <form method="POST" action="{{ route('admin.chronos.preset') }}">
                        @csrf
                        <input type="hidden" name="day" value="{{ $p['day'] }}">
                        <input type="hidden" name="hour" value="{{ $p['hour'] }}">
                        <input type="hidden" name="minute" value="{{ $p['minute'] }}">
                        <input type="hidden" name="label" value="{{ $p['label'] }}">
                        <button type="submit" class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 text-slate-700 text-xs font-bold shadow-2xs transition flex items-center space-x-1.5 cursor-pointer">
                            <i class="fa-regular fa-clock text-indigo-500"></i>
                            <span>{{ $p['label'] }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">({{ sprintf('%02d:%02d', $p['hour'], $p['minute']) }})</span>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-start space-x-3 text-amber-900 text-xs">
            <i class="fa-solid fa-triangle-exclamation text-amber-600 text-base mt-0.5 shrink-0"></i>
            <div>
                <p class="font-bold">Chronos aktif: sistem membaca Hari = {{ $status['day_name'] }}, Jam = {{ sprintf('%02d:%02d', $status['hour'], $status['minute']) }} WIB.</p>
                <p class="mt-0.5 text-amber-800 leading-relaxed">
                    Jam absensi, jadwal aktif di dashboard guru/siswa, dan monitor harian saat ini menggunakan waktu virtual ini.
                </p>
            </div>
        </div>

        <div class="space-y-3 pt-2">
            <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-calendar-check text-indigo-600"></i>
                <span>Simulasi Dampak Jadwal Pelajaran (Hari {{ $status['day_name'] }})</span>
            </h3>

            @if(empty($activeJadwal))
                <div class="bg-white p-8 rounded-2xl border border-slate-100 text-center text-slate-400 text-xs shadow-sm">
                    Tidak ada jadwal pelajaran yang tercatat pada hari {{ $status['day_name'] }} atau sesi jam telah selesai.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($activeJadwal as $item)
                        <div class="bg-white p-5 rounded-2xl border {{ $item['is_active'] ? 'border-indigo-300 ring-2 ring-indigo-100' : 'border-slate-100' }} shadow-sm space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item['is_active'] ? 'bg-indigo-100 text-indigo-800 animate-pulse' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $item['is_active'] ? 'Sedang Berlangsung' : 'Segera Masuk' }}
                                </span>
                                <span class="text-xs font-mono font-bold text-slate-600">
                                    {{ $item['mulai'] }} - {{ $item['selesai'] }} WIB
                                </span>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">{{ $item['jadwal']->mapel->nama ?? '-' }}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    Kelas: <span class="font-bold text-slate-700">{{ $item['jadwal']->kelas->nama ?? '-' }}</span> •
                                    Guru: <span class="font-semibold text-slate-700">{{ $item['jadwal']->guru->nama ?? '-' }}</span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sliderHour = document.getElementById('sliderHour');
    const sliderMinute = document.getElementById('sliderMinute');
    const displayHour = document.getElementById('displayHour');
    const displayMinute = document.getElementById('displayMinute');
    const sliderHourVal = document.getElementById('sliderHourVal');
    const sliderMinuteVal = document.getElementById('sliderMinuteVal');
    const inputDate = document.getElementById('inputDate');
    const displayTanggal = document.getElementById('displayTanggal');

    if (sliderHour && displayHour) {
        sliderHour.addEventListener('input', function() {
            const h = String(this.value).padStart(2, '0');
            displayHour.textContent = h;
            if (sliderHourVal) sliderHourVal.textContent = h + ':00';
        });
    }

    if (sliderMinute && displayMinute) {
        sliderMinute.addEventListener('input', function() {
            const m = String(this.value).padStart(2, '0');
            displayMinute.textContent = m;
            if (sliderMinuteVal) sliderMinuteVal.textContent = m + ' menit';
        });
    }

    if (inputDate && displayTanggal) {
        inputDate.addEventListener('change', function() {
            if (this.value) {
                const parts = this.value.split('-');
                if (parts.length === 3) {
                    displayTanggal.textContent = parts[2] + '/' + parts[1] + '/' + parts[0];
                }
            }
        });
    }
});
</script>
@endsection

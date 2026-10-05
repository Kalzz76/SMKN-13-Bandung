@extends('layouts.admin', ['title' => 'Log Aktivitas Sistem - CMS SMKN 13 Bandung'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div>
            <h2 class="text-xl font-black text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-clock-rotate-left text-emerald-700"></i>
                <span>Log Aktivitas Sistem</span>
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Riwayat jejak audit pencatatan perubahan data, autentikasi, dan aktivitas penting pada sistem CMS.
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-3.5 py-1.5 rounded-xl font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                Total: {{ $daftarLog->total() }} Aktivitas
            </span>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 space-y-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <form method="GET" action="{{ route('admin.log.index') }}" class="w-full sm:w-80">
                <div class="relative">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari aksi, user, keterangan..." class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-xs focus:border-emerald-600 focus:outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </form>
            @if(request('cari'))
                <a href="{{ route('admin.log.index') }}" class="text-xs text-rose-600 hover:underline font-bold flex items-center space-x-1">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Reset Pencarian</span>
                </a>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-3 w-12 text-center">No</th>
                        <th class="py-3 px-3 w-40">Waktu</th>
                        <th class="py-3 px-3 w-48">Pengguna</th>
                        <th class="py-3 px-3 w-48">Aksi</th>
                        <th class="py-3 px-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarLog as $index => $log)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-3 text-center text-slate-400 font-mono">{{ $daftarLog->firstItem() + $index }}</td>
                            <td class="py-3 px-3 text-slate-600 whitespace-nowrap font-mono text-[11px]">
                                {{ \Carbon\Carbon::parse($log->created_at)->locale('id')->isoFormat('D MMM Y, HH:mm:ss') }}
                            </td>
                            <td class="py-3 px-3">
                                @if($log->user)
                                    <div class="font-bold text-slate-900">{{ $log->user->name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">{{ $log->user->role }}</div>
                                @else
                                    <span class="text-slate-400 italic">Sistem / Pengunjung</span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-800 border border-slate-200 inline-block">
                                    {{ $log->aksi }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-slate-700 leading-relaxed">
                                {{ $log->keterangan }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400">
                                @if(request('cari'))
                                    Tidak ada catatan log aktivitas yang cocok dengan kata kunci "{{ request('cari') }}".
                                @else
                                    Belum ada catatan aktivitas di dalam sistem.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($daftarLog->hasPages())
            <div class="pt-4 border-t border-slate-100">
                {{ $daftarLog->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

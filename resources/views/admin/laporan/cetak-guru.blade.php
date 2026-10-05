<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi Guru - SMKN 13 Bandung</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 min-h-screen text-slate-800 text-xs">
    <div class="max-w-4xl mx-auto mb-4 no-print flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <div>
            <h1 class="font-bold text-slate-900 text-sm">Pratinjau Cetak Laporan Kehadiran Guru</h1>
            <p class="text-[11px] text-slate-500">Klik tombol di samping untuk mencetak atau simpan ke PDF.</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="window.print()" class="bg-emerald-700 hover:bg-emerald-600 text-white font-bold px-4 py-2 rounded-lg text-xs shadow transition">
                Cetak Dokumen
            </button>
            <button onclick="window.close()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2 rounded-lg text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200 space-y-6">
        <div class="text-center border-b-2 border-slate-800 pb-4">
            <h2 class="text-base font-bold uppercase tracking-wider text-slate-900">Pemerintah Daerah Provinsi Jawa Barat</h2>
            <h3 class="text-lg font-black uppercase text-slate-900">SMK Negeri 13 Bandung</h3>
            <p class="text-[11px] text-slate-600 mt-0.5">Jl. Soekarno-Hatta Km. 10, Kota Bandung, Jawa Barat | Telp: (022) 7318960</p>
            <h4 class="text-sm font-extrabold uppercase mt-3 text-emerald-900 underline">
                Laporan Rekapitulasi Presensi Kehadiran Guru Pengajar
            </h4>
            <p class="text-[11px] text-slate-600 mt-0.5 font-semibold">
                Periode: {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}
            </p>
        </div>

        <div class="space-y-2">
            <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wide">A. Ringkasan Kehadiran Guru</h5>
            <table class="w-full text-left border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">
                    <tr class="border-b border-slate-300">
                        <th class="p-2 border-r border-slate-300">Nama Guru</th>
                        <th class="p-2 border-r border-slate-300 w-28">NIP</th>
                        <th class="p-2 border-r border-slate-300 w-14 text-center">Hadir</th>
                        <th class="p-2 border-r border-slate-300 w-16 text-center">Terlambat</th>
                        <th class="p-2 border-r border-slate-300 w-14 text-center">Sakit</th>
                        <th class="p-2 border-r border-slate-300 w-14 text-center">Izin</th>
                        <th class="p-2 border-r border-slate-300 w-14 text-center">Alpa</th>
                        <th class="p-2 text-center w-16">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($rekapGuru as $item)
                        <tr>
                            <td class="p-2 border-r border-slate-200 font-medium">{{ $item['guru']->nama }}</td>
                            <td class="p-2 border-r border-slate-200 font-mono text-[10px]">{{ $item['guru']->nip ?? '-' }}</td>
                            <td class="p-2 border-r border-slate-200 text-center font-bold text-emerald-800">{{ $item['hadir'] }}</td>
                            <td class="p-2 border-r border-slate-200 text-center font-bold text-amber-700">{{ $item['terlambat'] }}</td>
                            <td class="p-2 border-r border-slate-200 text-center">{{ $item['sakit'] }}</td>
                            <td class="p-2 border-r border-slate-200 text-center">{{ $item['izin'] }}</td>
                            <td class="p-2 border-r border-slate-200 text-center">{{ $item['alpa'] }}</td>
                            <td class="p-2 text-center font-bold">{{ $item['total'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="space-y-2 pt-2">
            <h5 class="font-bold text-slate-900 text-xs uppercase tracking-wide">B. Rincian Log Presensi Harian</h5>
            <table class="w-full text-left border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">
                    <tr class="border-b border-slate-300">
                        <th class="p-2 border-r border-slate-300 w-24">Tanggal</th>
                        <th class="p-2 border-r border-slate-300 w-20">Waktu</th>
                        <th class="p-2 border-r border-slate-300">Nama Guru</th>
                        <th class="p-2 border-r border-slate-300 w-24 text-center">Status</th>
                        <th class="p-2 border-r border-slate-300 w-24 text-center">Metode</th>
                        <th class="p-2 border-r border-slate-300">Keterangan / Lokasi</th>
                        <th class="p-2 w-32">Pencatat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($daftarAbsensi as $row)
                        <tr>
                            <td class="p-2 border-r border-slate-200 font-mono">{{ $row->tanggal }}</td>
                            <td class="p-2 border-r border-slate-200 font-mono">
                                {{ $row->jam_masuk ? \Illuminate\Support\Str::substr($row->jam_masuk, 0, 5) . ' WIB' : '-' }}
                            </td>
                            <td class="p-2 border-r border-slate-200 font-medium">{{ $row->guru ? $row->guru->nama : '-' }}</td>
                            <td class="p-2 border-r border-slate-200 text-center font-bold">{{ $row->status }}</td>
                            <td class="p-2 border-r border-slate-200 text-center">{{ $row->metode }}</td>
                            <td class="p-2 border-r border-slate-200">
                                @if($row->metode === 'Scan')
                                    Jarak {{ $row->jarak_meter ?? 0 }}m
                                @else
                                    {{ $row->alasan ?? '-' }}
                                @endif
                            </td>
                            <td class="p-2">{{ $row->metode === 'Scan' ? 'Scan Mandiri' : ($row->userInput ? $row->userInput->name : 'Sekretaris') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-slate-400">Tidak ada riwayat presensi guru pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-8 flex justify-end">
            <div class="text-center w-56">
                <p class="text-[11px] text-slate-600">Bandung, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                <p class="text-[11px] font-bold text-slate-800 mt-1">Kepala SMKN 13 Bandung</p>
                <div class="h-16"></div>
                <p class="text-xs font-bold text-slate-900 underline">Drs. H. Ino Rohyana, M.M.Pd.</p>
                <p class="text-[10px] text-slate-500 font-mono">NIP. 196803151994121002</p>
            </div>
        </div>
    </div>
</body>
</html>

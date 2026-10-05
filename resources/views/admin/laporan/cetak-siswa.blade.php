<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Absensi Siswa {{ $kelas->nama }} - SMKN 13 Bandung</title>
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
            <h1 class="font-bold text-slate-900 text-sm">Pratinjau Cetak Rekapitulasi Absensi Siswa</h1>
            <p class="text-[11px] text-slate-500">Kelas {{ $kelas->nama }} - Periode {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}</p>
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
                Laporan Rekapitulasi Presensi Kehadiran Siswa
            </h4>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div>
                <p><span class="font-bold text-slate-700">Rombongan Belajar / Kelas:</span> {{ $kelas->nama }}</p>
                <p class="mt-1"><span class="font-bold text-slate-700">Wali Kelas:</span> {{ $kelas->waliKelas ? $kelas->waliKelas->nama : '-' }}</p>
            </div>
            <div class="text-right">
                <p><span class="font-bold text-slate-700">Periode:</span> {{ $daftarBulan[$bulan] ?? $bulan }} {{ $tahun }}</p>
                <p class="mt-1"><span class="font-bold text-slate-700">Mata Pelajaran:</span> {{ $mapel ? $mapel->nama : 'Semua Mata Pelajaran' }}</p>
            </div>
        </div>

        <table class="w-full text-left border border-slate-300">
            <thead class="bg-slate-100 text-slate-700 font-bold text-[10px] uppercase">
                <tr class="border-b border-slate-300">
                    <th class="p-2 border-r border-slate-300 w-10 text-center">No</th>
                    <th class="p-2 border-r border-slate-300 w-28">NIS</th>
                    <th class="p-2 border-r border-slate-300">Nama Siswa</th>
                    <th class="p-2 border-r border-slate-300 w-12 text-center">L/P</th>
                    <th class="p-2 border-r border-slate-300 w-14 text-center">Hadir</th>
                    <th class="p-2 border-r border-slate-300 w-14 text-center">Sakit</th>
                    <th class="p-2 border-r border-slate-300 w-14 text-center">Izin</th>
                    <th class="p-2 border-r border-slate-300 w-14 text-center">Alpa</th>
                    <th class="p-2 border-r border-slate-300 w-16 text-center">Pertemuan</th>
                    <th class="p-2 w-20 text-center">% Hadir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($rekapSiswa as $index => $item)
                    <tr>
                        <td class="p-2 border-r border-slate-200 text-center font-mono text-[10px]">{{ $index + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-mono text-[10px]">{{ $item['siswa']->nis }}</td>
                        <td class="p-2 border-r border-slate-200 font-medium">{{ $item['siswa']->nama }}</td>
                        <td class="p-2 border-r border-slate-200 text-center text-slate-500">{{ $item['siswa']->jenis_kelamin ?? '-' }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-bold text-emerald-800">{{ $item['hadir'] }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ $item['sakit'] }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ $item['izin'] }}</td>
                        <td class="p-2 border-r border-slate-200 text-center">{{ $item['alpa'] }}</td>
                        <td class="p-2 border-r border-slate-200 text-center font-bold">{{ $item['total'] }}</td>
                        <td class="p-2 text-center font-mono font-bold">{{ $item['persentase'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="p-4 text-center text-slate-400">Tidak ada data siswa terdaftar di kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pt-8 flex justify-between items-end">
            <div class="text-center w-56">
                <p class="text-[11px] text-slate-600">Mengetahui,</p>
                <p class="text-[11px] font-bold text-slate-800 mt-1">Wali Kelas</p>
                <div class="h-16"></div>
                <p class="text-xs font-bold text-slate-900 underline">{{ $kelas->waliKelas ? $kelas->waliKelas->nama : '( ........................................ )' }}</p>
                <p class="text-[10px] text-slate-500 font-mono">{{ $kelas->waliKelas ? 'NIP. ' . ($kelas->waliKelas->nip ?? '-') : '' }}</p>
            </div>

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

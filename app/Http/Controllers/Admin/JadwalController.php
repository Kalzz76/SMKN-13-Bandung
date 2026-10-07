<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\JadwalPembiasaan;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Mapel;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Csv as CsvReader;

class JadwalController extends Controller
{
    private const DAFTAR_HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    public function index(Request $request)
    {
        $daftarHari = self::DAFTAR_HARI;

        $hariIniInggris = Carbon::now('Asia/Jakarta')->format('l');
        $petaHari = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
        ];

        $hariDefault = $petaHari[$hariIniInggris] ?? 'Senin';
        $hariTerpilih = $request->query('hari', $hariDefault);

        if (!in_array($hariTerpilih, $daftarHari)) {
            $hariTerpilih = 'Senin';
        }

        $daftarKelas = Kelas::with(['ruangan', 'waliKelas'])->orderBy('nama')->get();
        $daftarJadwalRaw = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])
            ->where('hari', $hariTerpilih)
            ->get();
        $daftarJadwal = $this->gabungkanJadwalSama($daftarJadwalRaw);
        $semuaJadwal = Jadwal::with(['kelas', 'mapel', 'guru', 'ruangan'])->get();

        $slotHari = JamPelajaran::slotHari($hariTerpilih);
        $ringkasanHari = JamPelajaran::ringkasan($slotHari, $hariTerpilih);
        $jamKeMap = $slotHari->where('jenis', JamPelajaran::JENIS_PELAJARAN)->keyBy('jam_ke');

        $sesiMingguIni = JadwalPembiasaan::sesiMingguIni();
        $sesiTerpilih = (int) $request->query('sesi', $sesiMingguIni);
        if (!in_array($sesiTerpilih, [1, 2], true)) {
            $sesiTerpilih = $sesiMingguIni;
        }
        $parameterSesi = $request->has('sesi') ? ['sesi' => $sesiTerpilih] : [];

        $aturanRotasi = JadwalPembiasaan::aturanHari($hariTerpilih);
        $adaRotasi = $aturanRotasi->isNotEmpty();
        $namaPembiasaanHari = $ringkasanHari['pembiasaan']['nama'] ?? 'Pembiasaan';

        $labelPembiasaan = [];
        foreach ($daftarKelas as $k) {
            $aturan = $aturanRotasi->first(function ($a) use ($sesiTerpilih, $k) {
                return $a->sesi === $sesiTerpilih && $a->kelompok === $k->kelompok_pembiasaan;
            });

            $labelPembiasaan[$k->id] = $aturan
                ? ['kegiatan' => $aturan->kegiatan, 'lokasi' => $aturan->lokasi, 'rotasi' => true]
                : ['kegiatan' => $namaPembiasaanHari, 'lokasi' => null, 'rotasi' => false];
        }

        $daftarMapel = Mapel::orderBy('nama')->get();
        $daftarGuru = Guru::with('mapels')->where('jenis', 'Guru')->orderBy('nama')->get()->map(function ($guru) {
            $mapelIds = $guru->mapels->pluck('id')->all();
            if ($guru->id_mapel && !in_array($guru->id_mapel, $mapelIds)) {
                $mapelIds[] = $guru->id_mapel;
            }
            $guru->mapel_ids = $mapelIds;
            return $guru;
        });
        $daftarRuangan = Ruangan::orderBy('kode')->get();

        return view('admin.jadwal.index', compact(
            'daftarHari',
            'hariTerpilih',
            'daftarKelas',
            'daftarJadwal',
            'semuaJadwal',
            'slotHari',
            'ringkasanHari',
            'jamKeMap',
            'sesiTerpilih',
            'sesiMingguIni',
            'parameterSesi',
            'aturanRotasi',
            'adaRotasi',
            'labelPembiasaan',
            'daftarMapel',
            'daftarGuru',
            'daftarRuangan'
        ));
    }

    public function jamHari(string $hari)
    {
        abort_unless(in_array($hari, self::DAFTAR_HARI, true), 404);

        return response()->json(JamPelajaran::ringkasanHari($hari));
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jadwal');

        $headers = ['Hari', 'Slot', 'Kelas', 'Mapel', 'Ruangan', 'Guru'];
        foreach ($headers as $index => $header) {
            $colLetter = chr(65 + $index);
            $sheet->setCellValue($colLetter . '1', $header);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '047857']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(26);

        $sampleRows = [
            ['Senin', 'Jam 1', 'XI RPL 1', 'Matematika', 'R.01', 'Budi Santoso'],
            ['Senin', 'Jam 2', 'XI RPL 1', 'B. Indonesia', 'R.01', 'Siti Aminah'],
            ['Senin', 'Jam 3', 'XI RPL 1', 'Istirahat', 'R.01', ''],
            ['Selasa', 'Jam 1', 'X TKJ 1', 'Dasar Pemrograman', 'Lab Komputer', 'Ahmad Dani'],
        ];

        foreach ($sampleRows as $rIdx => $row) {
            $rowNum = $rIdx + 2;
            foreach ($row as $cIdx => $val) {
                $colLetter = chr(65 + $cIdx);
                $sheet->setCellValueExplicit($colLetter . $rowNum, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
            $sheet->getRowDimension($rowNum)->setRowHeight(20);
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Jadwal_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ], [
            'file.required' => 'Silakan pilih berkas Excel atau CSV untuk diimpor.',
            'file.max' => 'Ukuran berkas maksimal 10MB.',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['xlsx', 'xls', 'csv', 'txt'])) {
            return redirect()->back()->with('error', 'Format berkas tidak didukung. Harap unggah file .xlsx atau .csv');
        }

        try {
            if (in_array($ext, ['csv', 'txt'])) {
                $reader = new CsvReader();
                $firstLine = fgets(fopen($file->getRealPath(), 'r'));
                if ($firstLine && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                    $reader->setDelimiter(';');
                }
            } else {
                $reader = new XlsxReader();
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca berkas Excel: ' . $e->getMessage());
        }

        if (count($rows) < 2) {
            return redirect()->back()->with('error', 'Berkas kosong atau hanya berisi baris judul.');
        }

        $importedCount = 0;
        $daftarKelas = Kelas::all();
        $daftarMapel = Mapel::all();
        $daftarGuru = Guru::all();
        $daftarRuangan = Ruangan::all();

        $firstRow = true;
        foreach ($rows as $row) {
            if ($firstRow) {
                $firstRow = false;
                continue;
            }

            $hari = trim((string) ($row['A'] ?? ''));
            $slot = trim((string) ($row['B'] ?? ''));
            $className = trim((string) ($row['C'] ?? ''));
            $subjectName = trim((string) ($row['D'] ?? ''));
            $roomName = trim((string) ($row['E'] ?? ''));
            $teacherName = trim((string) ($row['F'] ?? ''));

            if (empty($hari) || empty($slot) || empty($className)) {
                continue;
            }

            if (!in_array($hari, self::DAFTAR_HARI, true)) {
                continue;
            }

            $cls = $daftarKelas->first(function ($c) use ($className) {
                return strcasecmp($c->nama, $className) === 0 || (string) $c->id === $className;
            });
            if (!$cls) {
                continue;
            }

            preg_match_all('/\d+/', $slot, $matches);
            $numbers = $matches[0] ?? [];
            if (empty($numbers)) {
                continue;
            }
            $mulai = (int) $numbers[0];
            $selesai = isset($numbers[1]) ? (int) $numbers[1] : $mulai;

            $isKegiatan = false;
            $namaKegiatan = null;
            if (in_array(strtolower($subjectName), ['istirahat', 'upacara', 'literasi', 'rapat'], true) || stripos($subjectName, 'kegiatan') !== false) {
                $isKegiatan = true;
                $namaKegiatan = $subjectName ?: 'Kegiatan';
            }

            $subject = null;
            $room = null;
            $teacher = null;

            if (!$isKegiatan) {
                $subject = $daftarMapel->first(function ($m) use ($subjectName) {
                    return strcasecmp($m->nama, $subjectName) === 0 || strcasecmp($m->kode, $subjectName) === 0 || (string) $m->id === $subjectName;
                });
                if (!$subject) {
                    continue;
                }

                $room = $daftarRuangan->first(function ($r) use ($roomName) {
                    return strcasecmp($r->kode, $roomName) === 0 || strcasecmp($r->nama, $roomName) === 0 || (string) $r->id === $roomName;
                });
                if (!$room) {
                    $room = $cls->id_ruangan ? $daftarRuangan->firstWhere('id', $cls->id_ruangan) : $daftarRuangan->first();
                }

                if (!empty($teacherName)) {
                    $teacher = $daftarGuru->first(function ($g) use ($teacherName) {
                        return strcasecmp($g->nama, $teacherName) === 0 || (string) $g->nip === $teacherName || (string) $g->id === $teacherName;
                    });
                    if (!$teacher) {
                        continue;
                    }
                }
            }

            $ringkasan = JamPelajaran::ringkasanHari($hari);
            $segmen = JamPelajaran::pecahRentangJam($ringkasan, $mulai, $selesai);

            foreach ($segmen as $s) {
                Jadwal::updateOrCreate(
                    [
                        'hari' => $hari,
                        'id_kelas' => $cls->id,
                        'jam_ke_mulai' => $s['mulai'],
                    ],
                    [
                        'jam_ke_selesai' => $s['selesai'],
                        'is_kegiatan' => $isKegiatan,
                        'nama_kegiatan' => $namaKegiatan,
                        'id_mapel' => $subject ? $subject->id : null,
                        'id_ruangan' => $room ? $room->id : null,
                        'id_guru' => $teacher ? $teacher->id : null,
                    ]
                );
                $importedCount++;
            }
        }

        LogAktivitas::catat('Import Jadwal', "Mengimpor {$importedCount} jadwal dari file Excel ({$file->getClientOriginalName()})");

        return redirect()->route('admin.jadwal.index')->with('sukses', "Berhasil mengimpor {$importedCount} jadwal pelajaran.");
    }

    public function fixTeacherIds()
    {
        $allSchedules = Jadwal::where('is_kegiatan', false)->get();
        $allTeachers = Guru::with('mapels')->get();
        $fixedCount = 0;

        foreach ($allSchedules as $jadwal) {
            $needsFix = false;
            if (!$jadwal->id_guru) {
                $needsFix = true;
            } else {
                $found = $allTeachers->firstWhere('id', $jadwal->id_guru);
                if (!$found) {
                    $needsFix = true;
                }
            }

            if ($needsFix && $jadwal->id_mapel) {
                $matchedTeacher = $allTeachers->first(function ($t) use ($jadwal) {
                    return $t->mapels->contains('id', $jadwal->id_mapel) || $t->id_mapel == $jadwal->id_mapel;
                });

                if (!$matchedTeacher) {
                    $mapel = Mapel::find($jadwal->id_mapel);
                    if ($mapel) {
                        $matchedTeacher = $allTeachers->first(function ($t) use ($mapel) {
                            return stripos($t->mapel_utama ?? '', $mapel->nama) !== false;
                        });
                    }
                }

                if ($matchedTeacher) {
                    $jadwal->id_guru = $matchedTeacher->id;
                    $jadwal->save();
                    $fixedCount++;
                }
            }
        }

        LogAktivitas::catat('Fix Teacher IDs', "Memperbaiki {$fixedCount} ID guru pada jadwal pelajaran");

        return redirect()->route('admin.jadwal.index')->with('sukses', "Berhasil memeriksa dan memperbaiki {$fixedCount} jadwal guru.");
    }

    public function store(Request $request)
    {
        $isKegiatan = $request->boolean('is_kegiatan');

        if ($isKegiatan) {
            $request->validate([
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'id_kelas' => 'required|exists:kelas,id',
                'nama_kegiatan' => 'required|string|max:100',
                'jam_ke_mulai' => 'required|integer|min:1',
                'jam_ke_selesai' => 'required|integer|min:1',
            ]);
        } else {
            $request->validate($this->aturanValidasi());
        }

        if ($kesalahan = $this->periksaStrukturJam($request)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        $ringkasan = JamPelajaran::ringkasanHari($request->hari);
        $segmen = JamPelajaran::pecahRentangJam($ringkasan, (int) $request->jam_ke_mulai, (int) $request->jam_ke_selesai);

        if (!$isKegiatan) {
            foreach ($segmen as $s) {
                $reqSegmen = clone $request;
                $reqSegmen->merge([
                    'jam_ke_mulai' => $s['mulai'],
                    'jam_ke_selesai' => $s['selesai'],
                ]);
                if ($kesalahan = $this->periksaBentrok($reqSegmen)) {
                    return redirect()->back()->withInput()->with('error', $kesalahan);
                }
            }
        }

        foreach ($segmen as $s) {
            $data = $this->dataJadwal($request);
            $data['jam_ke_mulai'] = $s['mulai'];
            $data['jam_ke_selesai'] = $s['selesai'];
            Jadwal::create($data);
        }

        $kelas = Kelas::find($request->id_kelas);
        $deskripsi = $isKegiatan
            ? "Menambahkan jadwal kegiatan {$kelas->nama} - {$request->nama_kegiatan} ({$request->hari})"
            : "Menambahkan jadwal pelajaran {$kelas->nama} - " . (Mapel::find($request->id_mapel)->nama ?? '') . " ({$request->hari})";
        LogAktivitas::catat('Tambah Jadwal', $deskripsi);

        $pesan = count($segmen) > 1
            ? "Jadwal melewati jam istirahat dan otomatis dipecah menjadi " . count($segmen) . " sesi terpisah."
            : "Jadwal pelajaran berhasil ditambahkan.";

        return redirect()->route('admin.jadwal.index', ['hari' => $request->hari])->with('sukses', $pesan);
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $isKegiatan = $request->boolean('is_kegiatan');

        if ($isKegiatan) {
            $request->validate([
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
                'id_kelas' => 'required|exists:kelas,id',
                'nama_kegiatan' => 'required|string|max:100',
                'jam_ke_mulai' => 'required|integer|min:1',
                'jam_ke_selesai' => 'required|integer|min:1',
            ]);
        } else {
            $request->validate($this->aturanValidasi());
        }

        if ($kesalahan = $this->periksaStrukturJam($request)) {
            return redirect()->back()->withInput()->with('error', $kesalahan);
        }

        $ringkasan = JamPelajaran::ringkasanHari($request->hari);
        $segmen = JamPelajaran::pecahRentangJam($ringkasan, (int) $request->jam_ke_mulai, (int) $request->jam_ke_selesai);

        $idsTerkait = array_values(array_filter(array_map('intval', explode(',', (string) $request->input('ids_terkait', $id)))));
        if (empty($idsTerkait)) {
            $idsTerkait = [(int) $id];
        }

        if (!$isKegiatan) {
            foreach ($segmen as $s) {
                $reqSegmen = clone $request;
                $reqSegmen->merge([
                    'jam_ke_mulai' => $s['mulai'],
                    'jam_ke_selesai' => $s['selesai'],
                ]);
                if ($kesalahan = $this->periksaBentrok($reqSegmen, $idsTerkait)) {
                    return redirect()->back()->withInput()->with('error', $kesalahan);
                }
            }
        }

        foreach ($segmen as $idx => $s) {
            $data = $this->dataJadwal($request);
            $data['jam_ke_mulai'] = $s['mulai'];
            $data['jam_ke_selesai'] = $s['selesai'];
            if ($idx === 0) {
                $jadwal->update($data);
            } else {
                Jadwal::create($data);
            }
        }

        $hapusLain = array_diff($idsTerkait, [(int) $jadwal->id]);
        if (!empty($hapusLain)) {
            Jadwal::whereIn('id', $hapusLain)->delete();
        }

        $kelas = Kelas::find($request->id_kelas);
        $deskripsi = $isKegiatan
            ? "Memperbarui jadwal kegiatan {$kelas->nama} - {$request->nama_kegiatan} ({$request->hari})"
            : "Memperbarui jadwal pelajaran {$kelas->nama} - " . (Mapel::find($request->id_mapel)->nama ?? '') . " ({$request->hari})";
        LogAktivitas::catat('Ubah Jadwal', $deskripsi);

        $pesan = count($segmen) > 1
            ? "Jadwal melewati jam istirahat dan otomatis dipecah menjadi " . count($segmen) . " sesi terpisah."
            : "Jadwal pelajaran berhasil diperbarui.";

        return redirect()->route('admin.jadwal.index', ['hari' => $request->hari])->with('sukses', $pesan);
    }

    public function destroy($id)
    {
        $ids = array_values(array_filter(array_map('intval', explode(',', (string) $id))));
        if (empty($ids)) {
            $ids = [(int) $id];
        }

        $jadwals = Jadwal::whereIn('id', $ids)->get();
        if ($jadwals->isEmpty()) {
            abort(404);
        }

        foreach ($jadwals as $j) {
            if ($j->absensiSiswa()->count() > 0 || $j->jurnalKelas()->count() > 0) {
                return redirect()->back()->with('error', 'Jadwal tidak dapat dihapus karena sudah memiliki riwayat absensi siswa atau jurnal kelas.');
            }
        }

        $pertama = $jadwals->first();
        $hari = $pertama->hari;
        $kelas = $pertama->kelas ? $pertama->kelas->nama : 'Kelas';
        $namaItem = $pertama->is_kegiatan ? ($pertama->nama_kegiatan ?? 'Kegiatan') : ($pertama->mapel ? $pertama->mapel->nama : 'Mapel');

        Jadwal::whereIn('id', $ids)->delete();

        LogAktivitas::catat('Hapus Jadwal', "Menghapus jadwal {$kelas} - {$namaItem} ({$hari})");

        return redirect()->route('admin.jadwal.index', ['hari' => $hari])->with('sukses', 'Jadwal pelajaran berhasil dihapus.');
    }

    private function aturanValidasi(): array
    {
        return [
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_kelas' => 'required|exists:kelas,id',
            'id_mapel' => 'required|exists:mapel,id',
            'id_guru' => 'nullable|exists:guru,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'jam_ke_mulai' => 'required|integer|min:1',
            'jam_ke_selesai' => 'required|integer|min:1',
        ];
    }

    private function dataJadwal(Request $request): array
    {
        $isKegiatan = $request->boolean('is_kegiatan');

        if ($isKegiatan) {
            return [
                'hari' => $request->hari,
                'id_kelas' => $request->id_kelas,
                'is_kegiatan' => true,
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_mapel' => null,
                'id_guru' => null,
                'id_ruangan' => $request->id_ruangan ?: null,
                'jam_ke_mulai' => $request->jam_ke_mulai,
                'jam_ke_selesai' => $request->jam_ke_selesai,
            ];
        }

        return [
            'hari' => $request->hari,
            'id_kelas' => $request->id_kelas,
            'is_kegiatan' => false,
            'nama_kegiatan' => null,
            'id_mapel' => $request->id_mapel,
            'id_guru' => $request->id_guru ?: null,
            'id_ruangan' => $request->id_ruangan,
            'jam_ke_mulai' => $request->jam_ke_mulai,
            'jam_ke_selesai' => $request->jam_ke_selesai,
        ];
    }

    private function periksaStrukturJam(Request $request): ?string
    {
        $mulai = (int) $request->jam_ke_mulai;
        $selesai = (int) $request->jam_ke_selesai;

        if ($selesai < $mulai) {
            return 'Jam selesai tidak boleh lebih awal dari jam mulai.';
        }

        $ringkasan = JamPelajaran::ringkasanHari($request->hari);
        $maksimal = $ringkasan['maks_jam'];

        if ($mulai > $maksimal || $selesai > $maksimal) {
            return "Hari {$request->hari} hanya memiliki Jam 1 sampai Jam {$maksimal}. Jam yang dipilih tidak tersedia.";
        }

        return null;
    }

    private function periksaBentrok(Request $request, $kecualiId = null): ?string
    {
        if ($request->id_guru) {
            $bentrokGuru = $this->cariBentrok($request, 'id_guru', $kecualiId);
            if ($bentrokGuru) {
                $guru = Guru::find($request->id_guru);
                $kelas = Kelas::find($bentrokGuru->id_kelas);
                return "Guru {$guru->nama} sudah memiliki jadwal mengajar di kelas {$kelas->nama} pada jam tersebut.";
            }
        }

        $bentrokKelas = $this->cariBentrok($request, 'id_kelas', $kecualiId);
        if ($bentrokKelas) {
            $kelas = Kelas::find($request->id_kelas);
            return "Kelas {$kelas->nama} sudah memiliki jadwal pelajaran lain pada jam tersebut.";
        }

        $bentrokRuangan = $this->cariBentrok($request, 'id_ruangan', $kecualiId);
        if ($bentrokRuangan) {
            $ruangan = Ruangan::find($request->id_ruangan);
            $kelas = Kelas::find($bentrokRuangan->id_kelas);
            return "Ruangan {$ruangan->nama} sudah digunakan oleh kelas {$kelas->nama} pada jam tersebut.";
        }

        return null;
    }

    private function cariBentrok(Request $request, string $kolom, $kecualiId = null): ?Jadwal
    {
        $nilai = $request->input($kolom);
        if (!$nilai) {
            return null;
        }

        $kecualiArray = is_array($kecualiId) ? $kecualiId : ($kecualiId ? [(int) $kecualiId] : []);

        return Jadwal::where('hari', $request->hari)
            ->where('is_kegiatan', false)
            ->where($kolom, $nilai)
            ->when(!empty($kecualiArray), fn ($q) => $q->whereNotIn('id', $kecualiArray))
            ->where('jam_ke_mulai', '<=', $request->jam_ke_selesai)
            ->where('jam_ke_selesai', '>=', $request->jam_ke_mulai)
            ->first();
    }

    private function gabungkanJadwalSama($daftarJadwal)
    {
        $hasil = collect();
        $perKelas = $daftarJadwal->groupBy('id_kelas');

        foreach ($perKelas as $kelasId => $items) {
            $urut = $items->sortBy('jam_ke_mulai')->values();
            $cur = null;

            foreach ($urut as $item) {
                if (!$cur) {
                    $cur = clone $item;
                    $cur->ids_terkait = [(int) $item->id];
                    continue;
                }

                $samaTipe = (bool) $cur->is_kegiatan === (bool) $item->is_kegiatan;
                $samaKonten = $samaTipe && (
                    $cur->is_kegiatan
                        ? ($cur->nama_kegiatan === $item->nama_kegiatan)
                        : ($cur->id_mapel === $item->id_mapel && $cur->id_guru === $item->id_guru)
                );
                $samaRuang = ($cur->id_ruangan === $item->id_ruangan);
                $berurutan = ((int) $item->jam_ke_mulai <= (int) $cur->jam_ke_selesai + 1);

                if ($samaKonten && $samaRuang && $berurutan) {
                    $cur->jam_ke_selesai = max((int) $cur->jam_ke_selesai, (int) $item->jam_ke_selesai);
                    $terkait = $cur->ids_terkait ?? [];
                    $terkait[] = (int) $item->id;
                    $cur->ids_terkait = $terkait;
                } else {
                    $hasil->push($cur);
                    $cur = clone $item;
                    $cur->ids_terkait = [(int) $item->id];
                }
            }

            if ($cur) {
                $hasil->push($cur);
            }
        }

        return $hasil->sortBy(function ($j) {
            return sprintf('%03d_%s', $j->jam_ke_mulai, $j->kelas ? $j->kelas->nama : '');
        })->values();
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query()->with('kelas');

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nis', 'like', "%{$cari}%")
                  ->orWhere('nisn', 'like', "%{$cari}%")
                  ->orWhere('nama', 'like', "%{$cari}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$cari}%")
                  ->orWhereHas('kelas', function ($kq) use ($cari) {
                      $kq->where('nama', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarSiswa = $query->paginate(10)->withQueryString();
        $daftarKelas = Kelas::withCount('siswa')->orderBy('nama')->get();
        $totalSiswa = Siswa::count();

        return view('admin.siswa.index', compact('daftarSiswa', 'daftarKelas', 'totalSiswa'));
    }

    public function template(Request $request)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Data Siswa');

        $headers = [
            'A1' => 'NIS',
            'B1' => 'NISN',
            'C1' => 'Nama Lengkap',
            'D1' => 'Jenis Kelamin (L/P)',
            'E1' => 'Nama Kelas',
            'F1' => 'Tahun Ajaran',
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E293B'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sampleKelas1 = Kelas::first()?->nama ?? 'X RPL 1';
        $sampleKelas2 = Kelas::skip(1)->first()?->nama ?? $sampleKelas1;

        $sampleData = [
            ['100101', '0081122331', 'Ahmad Rizky Pratama', 'L', $sampleKelas1, '2026/2027'],
            ['100102', '0081122332', 'Nabila Putri Cahyani', 'P', $sampleKelas1, '2026/2027'],
            ['100103', '0081122333', 'Dimas Arya Pamungkas', 'L', $sampleKelas2, '2026/2027'],
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            $sheet->setCellValueExplicit('A' . $rowNum, $row[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $rowNum, $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $rowNum, $row[2]);
            $sheet->setCellValue('D' . $rowNum, $row[3]);
            $sheet->setCellValue('E' . $rowNum, $row[4]);
            $sheet->setCellValue('F' . $rowNum, $row[5]);
            $sheet->getRowDimension($rowNum)->setRowHeight(22);
            $rowNum++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        if ($request->query('format') === 'csv') {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
            $filename = 'template_siswa_smkn13.csv';
            $contentType = 'text/csv';
        } else {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'template_siswa_smkn13.xlsx';
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        }

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => $contentType,
        ]);
    }

    public function import(Request $request)
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
            return redirect()->back()->with('error', 'Format berkas tidak didukung. Harap unggah file .xlsx, .xls, atau .csv');
        }

        try {
            if (in_array($ext, ['csv', 'txt'])) {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                $firstLine = fgets(fopen($file->getRealPath(), 'r'));
                if ($firstLine && substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
                    $reader->setDelimiter(';');
                }
            } elseif ($ext === 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            } else {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal membaca berkas: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return redirect()->back()->with('error', 'Berkas Excel atau CSV kosong.');
        }

        $headerMap = [];
        $dataStartRow = 2;
        $ignoredCols = [];

        foreach ($rows as $rowIndex => $row) {
            $normalizedCells = [];
            foreach ($row as $colLetter => $cellVal) {
                $norm = preg_replace('/[^a-z0-9]/', '', strtolower((string)$cellVal));
                if ($norm) {
                    $normalizedCells[$colLetter] = $norm;
                }
            }

            $hasNis = false;
            $hasNama = false;
            foreach ($normalizedCells as $colLetter => $val) {
                if (in_array($val, ['no', 'nomor', 'nourut', 'number'])) {
                    $ignoredCols[] = $colLetter;
                } elseif (in_array($val, ['nisnisn', 'nisdannisn', 'nisnis', 'nisdanisn', 'nisnomorinduknisn'])) {
                    $headerMap['nis_nisn'] = $colLetter;
                    $headerMap['nis'] = $colLetter;
                    $headerMap['nisn'] = $colLetter;
                    $hasNis = true;
                } elseif (in_array($val, ['nis', 'noinduk', 'nomorinduk', 'nomorinduksiswa'])) {
                    $headerMap['nis'] = $colLetter;
                    $hasNis = true;
                } elseif (in_array($val, ['nisn', 'nomorinduksiswanasional'])) {
                    $headerMap['nisn'] = $colLetter;
                } elseif (in_array($val, ['nama', 'namalengkap', 'namasiswa', 'pesertadidik', 'namapesertadidik'])) {
                    $headerMap['nama'] = $colLetter;
                    $hasNama = true;
                } elseif (in_array($val, ['jeniskelamin', 'jk', 'gender', 'lp', 'jeniskelaminlp'])) {
                    $headerMap['jenis_kelamin'] = $colLetter;
                } elseif (in_array($val, ['kelas', 'namakelas', 'rombel', 'idkelas'])) {
                    $headerMap['kelas'] = $colLetter;
                } elseif (in_array($val, ['tahunajaran', 'tahun', 'tp', 'tahunpelajaran'])) {
                    $headerMap['tahun_ajaran'] = $colLetter;
                } elseif (in_array($val, ['jabatan', 'peran', 'posisi'])) {
                    $headerMap['jabatan'] = $colLetter;
                }
            }

            if ($hasNis || $hasNama) {
                $dataStartRow = $rowIndex + 1;
                break;
            }
        }

        $availableCols = array_values(array_diff(['A', 'B', 'C', 'D', 'E', 'F', 'G'], $ignoredCols));

        if (!isset($headerMap['nis'])) $headerMap['nis'] = $availableCols[0] ?? 'A';
        if (!isset($headerMap['nisn'])) $headerMap['nisn'] = $availableCols[1] ?? 'B';
        if (!isset($headerMap['nama'])) $headerMap['nama'] = $availableCols[2] ?? 'C';
        if (!isset($headerMap['jenis_kelamin'])) $headerMap['jenis_kelamin'] = $availableCols[3] ?? 'D';
        if (!isset($headerMap['kelas'])) $headerMap['kelas'] = $availableCols[4] ?? 'E';
        if (!isset($headerMap['tahun_ajaran'])) $headerMap['tahun_ajaran'] = $availableCols[5] ?? 'F';
        if (!isset($headerMap['jabatan'])) $headerMap['jabatan'] = $availableCols[6] ?? 'G';

        $fileDetectedKelasName = null;
        $originalFileName = $file->getClientOriginalName();
        if (preg_match('/(?:^|[^a-zA-Z0-9])(X|XI|XII)[_\s-]+([A-Za-z]+)[_\s-]+([0-9]+)/i', $originalFileName, $m)) {
            $fileDetectedKelasName = strtoupper($m[1]) . ' ' . strtoupper($m[2]) . ' ' . $m[3];
        } elseif (preg_match('/(?:^|[^a-zA-Z0-9])(X|XI|XII)[_\s-]+([A-Za-z0-9\s]+)/i', $originalFileName, $m)) {
            $fileDetectedKelasName = trim(strtoupper($m[1]) . ' ' . strtoupper($m[2]));
        }

        if (!$fileDetectedKelasName && isset($worksheet)) {
            $sheetTitle = $worksheet->getTitle();
            if (preg_match('/(?:^|[^a-zA-Z0-9])(X|XI|XII)[_\s-]+([A-Za-z]+)[_\s-]+([0-9]+)/i', $sheetTitle, $m)) {
                $fileDetectedKelasName = strtoupper($m[1]) . ' ' . strtoupper($m[2]) . ' ' . $m[3];
            }
        }

        $allKelas = Kelas::all();
        $kelasMap = [];
        foreach ($allKelas as $k) {
            $keyExact = strtolower(trim($k->nama));
            $keyClean = preg_replace('/[^a-z0-9]/', '', strtolower($k->nama));
            $kelasMap[$keyExact] = $k->id;
            $kelasMap[$keyClean] = $k->id;
        }

        $formKelasId = $request->input('id_kelas');
        $defaultKelasId = $formKelasId ?: null;
        if (!$defaultKelasId && $fileDetectedKelasName) {
            $cleanDetected = preg_replace('/[^a-z0-9]/', '', strtolower($fileDetectedKelasName));
            if (isset($kelasMap[$cleanDetected])) {
                $defaultKelasId = $kelasMap[$cleanDetected];
            } else {
                $newKelas = Kelas::create(['nama' => $fileDetectedKelasName]);
                $defaultKelasId = $newKelas->id;
                $kelasMap[strtolower(trim($newKelas->nama))] = $newKelas->id;
                $kelasMap[$cleanDetected] = $newKelas->id;
            }
        }
        if (!$defaultKelasId) {
            $defaultKelasId = $allKelas->first()?->id;
        }

        $ditambahkan = 0;
        $diperbarui = 0;
        $dilewati = 0;

        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < $dataStartRow) {
                continue;
            }

            $rawNisVal = (string)($row[$headerMap['nis']] ?? '');
            $rawNisnVal = isset($headerMap['nisn']) ? (string)($row[$headerMap['nisn']] ?? '') : '';

            $nis = '';
            $nisn = '';

            if (isset($headerMap['nis_nisn']) || str_contains($rawNisVal, '/')) {
                $parts = explode('/', $rawNisVal);
                $nis = preg_replace('/[^0-9]/', '', $parts[0] ?? '');
                $nisn = preg_replace('/[^0-9]/', '', $parts[1] ?? '');
            } else {
                if (is_numeric($rawNisVal)) {
                    $rawNisVal = sprintf('%.0f', (float)$rawNisVal);
                }
                $nis = preg_replace('/[^0-9]/', '', $rawNisVal);

                if (is_numeric($rawNisnVal)) {
                    $rawNisnVal = sprintf('%.0f', (float)$rawNisnVal);
                }
                $nisn = preg_replace('/[^0-9]/', '', $rawNisnVal);
            }

            $nama = trim((string)($row[$headerMap['nama']] ?? ''));

            if (empty($nis) && empty($nama)) {
                continue;
            }

            if (empty($nis) || empty($nama)) {
                $dilewati++;
                continue;
            }

            if (empty($nisn)) {
                $nisn = '00' . $nis;
            }

            $rawJk = strtoupper(trim((string)($row[$headerMap['jenis_kelamin']] ?? 'L')));
            $jk = in_array($rawJk, ['P', 'PEREMPUAN', 'WANITA', 'FEMALE', 'F']) ? 'P' : 'L';

            $rawKelas = isset($headerMap['kelas']) ? trim((string)($row[$headerMap['kelas']] ?? '')) : '';
            if (!empty($rawKelas)) {
                $cleanKelas = preg_replace('/[^a-z0-9]/', '', strtolower($rawKelas));
                if (isset($kelasMap[$cleanKelas])) {
                    $kelasId = $kelasMap[$cleanKelas];
                } elseif (isset($kelasMap[strtolower($rawKelas)])) {
                    $kelasId = $kelasMap[strtolower($rawKelas)];
                } else {
                    $newK = Kelas::create(['nama' => $rawKelas]);
                    $kelasId = $newK->id;
                    $kelasMap[strtolower(trim($rawKelas))] = $newK->id;
                    $kelasMap[$cleanKelas] = $newK->id;
                }
            } else {
                $kelasId = $defaultKelasId;
            }

            $tahunAjaran = trim((string)($row[$headerMap['tahun_ajaran']] ?? ''));
            if (empty($tahunAjaran)) {
                $tahunAjaran = '2026/2027';
            }

            $rawJabatan = isset($headerMap['jabatan']) ? strtolower(trim((string)($row[$headerMap['jabatan']] ?? ''))) : '';
            $jabatan = str_contains($rawJabatan, 'sekre') ? 'Sekretaris' : 'Anggota';

            $siswa = Siswa::where('nis', $nis)
                ->orWhere(function ($q) use ($nisn) {
                    if (!empty($nisn)) {
                        $q->where('nisn', $nisn);
                    }
                })
                ->orWhereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($nama))])
                ->first();

            if ($siswa) {
                $updateData = [
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'id_kelas' => $kelasId,
                    'tahun_ajaran' => $tahunAjaran,
                ];
                if (!empty($rawJabatan)) {
                    $updateData['jabatan'] = $jabatan;
                }
                $siswa->update($updateData);
                $diperbarui++;
            } else {
                $existingNisn = Siswa::where('nisn', $nisn)->first();
                if ($existingNisn) {
                    $nisn = $nisn . rand(10, 99);
                }
                Siswa::create([
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'nama' => $nama,
                    'jenis_kelamin' => $jk,
                    'id_kelas' => $kelasId,
                    'tahun_ajaran' => $tahunAjaran,
                    'jabatan' => $jabatan,
                ]);
                $ditambahkan++;
            }
        }

        LogAktivitas::catat('Import Siswa', "Mengimpor data siswa: {$ditambahkan} baru, {$diperbarui} diperbarui, {$dilewati} dilewati.");

        $pesan = "Import data siswa berhasil diselesaikan! {$ditambahkan} siswa baru ditambahkan";
        if ($diperbarui > 0) {
            $pesan .= ", {$diperbarui} data siswa berhasil diperbarui/ditimpa (duplikasi nama/NIS otomatis disatukan)";
        }
        $pesan .= ".";
        if ($dilewati > 0) {
            $pesan .= " ({$dilewati} baris dilewati karena data tidak lengkap).";
        }

        return redirect()->route('admin.siswa.index')->with('sukses', $pesan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nis'],
            'nisn' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nisn'],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
        ]);

        $duplikatNama = Siswa::whereRaw('LOWER(TRIM(nama)) = ?', [strtolower(trim($request->nama))])
            ->where('id_kelas', $request->id_kelas)
            ->first();
        if ($duplikatNama) {
            return redirect()->back()->withInput()->with('error', "Siswa dengan nama '{$request->nama}' sudah ada di kelas tersebut (NIS: {$duplikatNama->nis}). Silakan gunakan menu Edit jika ingin mengubah data.");
        }

        $siswa = Siswa::create([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
            'jabatan' => 'Anggota',
        ]);

        LogAktivitas::catat('Tambah Siswa', "Menambahkan siswa '{$siswa->nama}' (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nis' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nis,' . $id],
            'nisn' => ['required', 'regex:/^[0-9]+$/', 'max:50', 'unique:siswa,nisn,' . $id],
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'id_kelas' => 'required|exists:kelas,id',
            'tahun_ajaran' => 'required|string|max:20',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'nisn.regex' => 'NISN hanya boleh berisi angka.',
        ]);

        $siswa->update([
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_kelas' => $request->id_kelas,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        LogAktivitas::catat('Ubah Siswa', "Memperbarui data siswa '{$siswa->nama}' (NIS: {$siswa->nis})");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        if ($siswa->absensi()->count() > 0) {
            return redirect()->route('admin.siswa.index')->with('error', 'Data siswa tidak dapat dihapus karena memiliki riwayat absensi.');
        }

        $nama = $siswa->nama;
        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        LogAktivitas::catat('Hapus Siswa', "Menghapus siswa '{$nama}'");

        return redirect()->route('admin.siswa.index')->with('sukses', 'Data siswa berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'scope' => 'required|in:per_kelas,semua',
            'id_kelas' => 'nullable|required_if:scope,per_kelas|exists:kelas,id',
            'konfirmasi' => 'required|accepted',
        ], [
            'konfirmasi.accepted' => 'Harap centang konfirmasi bahwa Anda memahami penghapusan data ini bersifat permanen.',
            'id_kelas.required_if' => 'Silakan pilih kelas yang ingin dihapus datanya.',
        ]);

        $scope = $request->scope;
        $hapusAbsensi = $request->boolean('hapus_absensi', true);

        if ($scope === 'per_kelas') {
            $kelas = Kelas::findOrFail($request->id_kelas);
            $siswaQuery = Siswa::where('id_kelas', $kelas->id);
            $namaScope = "kelas '{$kelas->nama}'";
        } else {
            $siswaQuery = Siswa::query();
            $namaScope = "semua kelas";
        }

        $siswaList = $siswaQuery->get();
        $total = $siswaList->count();

        if ($total === 0) {
            return redirect()->route('admin.siswa.index')->with('error', "Tidak ada data siswa yang ditemukan untuk dihapus pada {$namaScope}.");
        }

        $userIds = $siswaList->pluck('user_id')->filter()->unique()->toArray();
        $siswaIds = $siswaList->pluck('id')->toArray();

        if ($hapusAbsensi) {
            \App\Models\AbsensiSiswa::whereIn('id_siswa', $siswaIds)->delete();
        } else {
            $hasAbsensi = \App\Models\AbsensiSiswa::whereIn('id_siswa', $siswaIds)->exists();
            if ($hasAbsensi) {
                return redirect()->route('admin.siswa.index')->with('error', "Terdapat siswa yang memiliki riwayat absensi. Centang opsi hapus riwayat absensi untuk melanjutkan.");
            }
        }

        if (!empty($userIds)) {
            User::whereIn('id', $userIds)->delete();
        }

        Siswa::whereIn('id', $siswaIds)->delete();

        LogAktivitas::catat('Hapus Massal Siswa', "Menghapus {$total} siswa pada {$namaScope}");

        return redirect()->route('admin.siswa.index')->with('sukses', "Berhasil menghapus {$total} data siswa pada {$namaScope}.");
    }
}

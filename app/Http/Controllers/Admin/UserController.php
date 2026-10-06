<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\LogAktivitas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private function getSekretarisBelumPunyaAkun()
    {
        $sekreIds = Siswa::where('jabatan', 'like', '%sekretaris%')
            ->whereNull('user_id')
            ->pluck('id')
            ->toArray();

        if (\Illuminate\Support\Facades\Schema::hasColumn('kelas', 'struktur')) {
            $kelasList = Kelas::whereNotNull('struktur')->get();
            foreach ($kelasList as $k) {
                $struktur = (array) $k->struktur;
                $namesOrIds = array_filter([
                    $struktur['sekretaris_1'] ?? null,
                    $struktur['sekretaris_2'] ?? null,
                ]);
                if (!empty($namesOrIds)) {
                    $matched = Siswa::where('id_kelas', $k->id)
                        ->whereNull('user_id')
                        ->where(function ($sq) use ($namesOrIds) {
                            $sq->whereIn('nama', $namesOrIds)
                               ->orWhereIn('id', $namesOrIds);
                        })
                        ->pluck('id')
                        ->toArray();
                    $sekreIds = array_merge($sekreIds, $matched);
                }
            }
        }

        $sekreIds = array_unique($sekreIds);

        return Siswa::whereIn('id', $sekreIds)->with('kelas')->orderBy('nama')->get();
    }

    public function index(Request $request)
    {
        $query = User::query()->with(['guru', 'siswa.kelas']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('username', 'like', "%{$cari}%")
                  ->orWhere('role', 'like', "%{$cari}%")
                  ->orWhereHas('guru', function ($gq) use ($cari) {
                      $gq->where('nama', 'like', "%{$cari}%");
                  })
                  ->orWhereHas('siswa', function ($sq) use ($cari) {
                      $sq->where('nama', 'like', "%{$cari}%");
                  });
            });
        }

        $daftarUser = $query->paginate(10)->withQueryString();
        $guruBelumPunyaAkun = Guru::whereNull('user_id')->orderBy('nama')->get();
        $sekreBelumPunyaAkun = $this->getSekretarisBelumPunyaAkun();
        $guruTanpaAkunCount = $guruBelumPunyaAkun->count();
        $sekreTanpaAkunCount = $sekreBelumPunyaAkun->count();

        return view('admin.user.index', compact('daftarUser', 'guruBelumPunyaAkun', 'sekreBelumPunyaAkun', 'guruTanpaAkunCount', 'sekreTanpaAkunCount'));
    }

    public function generateGuru(Request $request)
    {
        $guruList = Guru::whereNull('user_id')->get();

        if ($guruList->isEmpty()) {
            return redirect()->route('admin.user.index')->with('info', 'Semua data guru sudah memiliki akun pengguna.');
        }

        $berhasil = 0;
        foreach ($guruList as $guru) {
            $namaClean = preg_replace('/^(drs?|dra|ir|prof|h|hj)\.?\s+/i', '', trim($guru->nama));
            $words = preg_split('/[\s,]+/', $namaClean);
            $firstWord = $words[0] ?? 'guru';
            $namaDepan = Str::slug($firstWord, '');
            if (empty($namaDepan)) {
                $namaDepan = 'guru';
            }

            $nipClean = preg_replace('/[^0-9]/', '', (string)$guru->nip);
            if (strlen($nipClean) >= 4) {
                $empatDigit = substr($nipClean, 0, 4);
            } elseif (strlen($nipClean) > 0) {
                $empatDigit = str_pad($nipClean, 4, '0', STR_PAD_RIGHT);
            } else {
                $empatDigit = sprintf('%04d', $guru->id);
            }

            $baseUsername = strtolower($namaDepan . $empatDigit);
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user = User::create([
                'name' => $guru->nama,
                'username' => $username,
                'role' => 'guru',
                'password' => Hash::make('guru123'),
                'status' => 'Aktif',
            ]);

            $guru->update(['user_id' => $user->id]);
            $berhasil++;
        }

        LogAktivitas::catat('Generate Akun Guru', "Berhasil men-generate {$berhasil} akun guru secara otomatis");

        return redirect()->route('admin.user.index')->with('sukses', "Berhasil men-generate {$berhasil} akun guru. Password default: guru123");
    }

    public function generateSiswa(Request $request)
    {
        $sekreList = $this->getSekretarisBelumPunyaAkun();

        if ($sekreList->isEmpty()) {
            return redirect()->route('admin.user.index')->with('info', 'Tidak ada siswa sekretaris kelas yang belum memiliki akun.');
        }

        $berhasil = 0;
        foreach ($sekreList as $siswa) {
            $words = preg_split('/[\s,]+/', trim($siswa->nama));
            $firstWord = $words[0] ?? 'sekre';
            $namaDepan = Str::slug($firstWord, '');
            if (empty($namaDepan)) {
                $namaDepan = 'sekre';
            }

            $nisnClean = preg_replace('/[^0-9]/', '', (string)$siswa->nisn);
            if (strlen($nisnClean) >= 2) {
                $duaDigit = substr($nisnClean, -2);
            } else {
                $nisClean = preg_replace('/[^0-9]/', '', (string)$siswa->nis);
                $duaDigit = strlen($nisClean) >= 2 ? substr($nisClean, -2) : sprintf('%02d', $siswa->id % 100);
            }

            $baseUsername = strtolower($namaDepan . $duaDigit);
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            $user = User::create([
                'name' => $siswa->nama,
                'username' => $username,
                'role' => 'sekretaris',
                'password' => Hash::make('sekretaris123'),
                'status' => 'Aktif',
            ]);

            $siswa->update(['user_id' => $user->id]);
            $berhasil++;
        }

        LogAktivitas::catat('Generate Akun Siswa', "Berhasil men-generate {$berhasil} akun sekretaris siswa secara otomatis");

        return redirect()->route('admin.user.index')->with('sukses', "Berhasil men-generate {$berhasil} akun siswa (sekretaris). Password default: sekretaris123");
    }

    public function store(Request $request)
    {
        if ($request->role === 'guru') {
            if ($request->filled('guru_id')) {
                $request->validate([
                    'guru_id' => 'required|exists:guru,id',
                    'username' => 'required|string|max:50|unique:users,username|alpha_dash',
                    'role' => 'required|in:admin,guru,sekretaris',
                    'password' => 'required|string|min:6',
                    'status' => 'required|in:Aktif,Nonaktif',
                ], [
                    'guru_id.required' => 'Silakan pilih guru dari daftar.',
                    'guru_id.exists' => 'Guru yang dipilih tidak valid.',
                    'username.required' => 'Username wajib diisi.',
                    'username.unique' => 'Username sudah digunakan.',
                    'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
                    'password.required' => 'Password wajib diisi.',
                    'password.min' => 'Password minimal 6 karakter.',
                ]);

                $guru = Guru::where('id', $request->guru_id)->whereNull('user_id')->first();
                if (!$guru) {
                    return redirect()->back()->withInput()->with('error', 'Guru yang dipilih sudah memiliki akun atau tidak ditemukan.');
                }
                $nama = $guru->nama;
            } else {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'username' => 'required|string|max:50|unique:users,username|alpha_dash',
                    'role' => 'required|in:admin,guru,sekretaris',
                    'password' => 'required|string|min:6',
                    'status' => 'required|in:Aktif,Nonaktif',
                ], [
                    'name.required' => 'Nama lengkap wajib diisi.',
                    'username.required' => 'Username wajib diisi.',
                    'username.unique' => 'Username sudah digunakan.',
                    'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
                    'password.required' => 'Password wajib diisi.',
                    'password.min' => 'Password minimal 6 karakter.',
                ]);
                $nama = $request->name;
                $guru = null;
            }

            $user = User::create([
                'name' => $nama,
                'username' => Str::lower($request->username),
                'role' => 'guru',
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);

            if ($guru) {
                $guru->update(['user_id' => $user->id]);
            }
        } elseif ($request->role === 'sekretaris' && $request->filled('siswa_id')) {
            $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'username' => 'required|string|max:50|unique:users,username|alpha_dash',
                'role' => 'required|in:admin,guru,sekretaris',
                'password' => 'required|string|min:6',
                'status' => 'required|in:Aktif,Nonaktif',
            ], [
                'siswa_id.required' => 'Silakan pilih siswa sekretaris dari daftar.',
                'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
            ]);

            $siswa = Siswa::where('id', $request->siswa_id)->whereNull('user_id')->first();
            if (!$siswa) {
                return redirect()->back()->withInput()->with('error', 'Siswa yang dipilih sudah memiliki akun atau tidak ditemukan.');
            }

            $user = User::create([
                'name' => $siswa->nama,
                'username' => Str::lower($request->username),
                'role' => 'sekretaris',
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);

            $siswa->update(['user_id' => $user->id]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:50|unique:users,username|alpha_dash',
                'role' => 'required|in:admin,guru,sekretaris',
                'password' => 'required|string|min:6',
                'status' => 'required|in:Aktif,Nonaktif',
            ], [
                'name.required' => 'Nama lengkap wajib diisi.',
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, strip, dan garis bawah.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => Str::lower($request->username),
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'status' => $request->status,
            ]);
        }

        LogAktivitas::catat('Tambah Akun', "Menambahkan akun pengguna '{$user->username}' (Role: {$user->role})");

        return redirect()->route('admin.user.index')->with('sukses', 'Akun pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $id,
            'role' => 'required|in:admin,guru,sekretaris',
            'status' => 'required|in:Aktif,Nonaktif',
            'password' => 'nullable|string|min:6',
        ]);

        $user->name = $request->name;
        $user->username = Str::lower($request->username);
        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        LogAktivitas::catat('Ubah Akun', "Memperbarui akun pengguna '{$user->username}'");

        return redirect()->route('admin.user.index')->with('sukses', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin.user.index')->with('error', 'Akun yang sedang Anda gunakan tidak dapat dihapus!');
        }

        $user = User::findOrFail($id);
        $username = $user->username;

        if ($user->guru) {
            $user->guru->update(['user_id' => null]);
        }

        if ($user->siswa) {
            $user->siswa->update(['user_id' => null]);
        }

        $user->delete();

        LogAktivitas::catat('Hapus Akun', "Menghapus akun pengguna '{$username}'");

        return redirect()->route('admin.user.index')->with('sukses', 'Akun pengguna berhasil dihapus.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $defaultPassword = match ($user->role) {
            'guru' => 'guru123',
            'sekretaris' => 'sekretaris123',
            default => 'admin123',
        };

        $user->password = Hash::make($defaultPassword);
        $user->save();

        LogAktivitas::catat('Reset Password', "Mereset password akun '{$user->username}' ({$user->role}) ke default");

        return redirect()->route('admin.user.index')->with('sukses', "Password akun '{$user->name}' ({$user->username}) berhasil di-reset ke default: {$defaultPassword}");
    }
}

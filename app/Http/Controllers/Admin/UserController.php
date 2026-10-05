<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('guru');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('name', 'like', "%{$cari}%")
                  ->orWhere('username', 'like', "%{$cari}%")
                  ->orWhere('role', 'like', "%{$cari}%");
            });
        }

        $daftarUser = $query->paginate(10)->withQueryString();

        return view('admin.user.index', compact('daftarUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username|alpha_dash',
            'role' => 'required|in:admin,guru,sekretaris',
            'password' => 'required|string|min:6',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => Str::lower($request->username),
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

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

        $user->delete();

        LogAktivitas::catat('Hapus Akun', "Menghapus akun pengguna '{$username}'");

        return redirect()->route('admin.user.index')->with('sukses', 'Akun pengguna berhasil dihapus.');
    }
}

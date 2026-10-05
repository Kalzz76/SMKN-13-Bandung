<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $layout = match ($user->role) {
            'guru' => 'layouts.guru',
            'sekretaris' => 'layouts.sekretaris',
            default => 'layouts.admin',
        };

        $backUrl = match ($user->role) {
            'guru' => route('guru.dashboard'),
            'sekretaris' => route('sekretaris.dashboard'),
            default => route('admin.dashboard'),
        };

        return view('profil.index', compact('user', 'layout', 'backUrl'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'alamat' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->alamat = $request->alamat;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        LogAktivitas::catat('Ubah Profil', "Memperbarui profil pengguna '{$user->name}'");

        return redirect()->route('profil.index')->with('sukses', 'Profil berhasil diperbarui.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan akun lain.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'foto.max' => 'Ukuran gambar maksimal 3MB.',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->alamat = $request->alamat;

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('profil', 'public');
            $user->foto = $fotoPath;
            if ($user->guru) {
                $user->guru->foto = $fotoPath;
                $user->guru->save();
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        LogAktivitas::catat('Ubah Profil', "Memperbarui profil pengguna '{$user->name}'");

        return redirect()->route('profil.index')->with('sukses', 'Profil berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp,gif|max:3072',
        ], [
            'foto.required' => 'Pilih foto profil terlebih dahulu.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'foto.max' => 'Ukuran gambar maksimal 3MB.',
        ]);

        $user = auth()->user();
        $fotoPath = $request->file('foto')->store('profil', 'public');
        $user->foto = $fotoPath;
        $user->save();

        if ($user->guru) {
            $user->guru->foto = $fotoPath;
            $user->guru->save();
        }

        LogAktivitas::catat('Ubah Foto Profil', "Memperbarui foto profil pengguna '{$user->name}'");

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui.',
            'foto_url' => asset('storage/' . $fotoPath),
        ]);
    }

    public function hapusFoto(Request $request)
    {
        $user = auth()->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->foto = null;
        $user->save();

        if ($user->guru) {
            $user->guru->foto = null;
            $user->guru->save();
        }

        LogAktivitas::catat('Hapus Foto Profil', "Menghapus foto profil pengguna '{$user->name}'");

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus.',
                'initial' => strtoupper(substr($user->name ?? 'U', 0, 1)),
            ]);
        }

        return redirect()->route('profil.index')->with('sukses', 'Foto profil berhasil dihapus.');
    }
}

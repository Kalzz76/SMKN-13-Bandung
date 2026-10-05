<?php

namespace App\Http\Controllers;

use App\Models\PengaturanSekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'guru') {
                return redirect()->route('guru.dashboard');
            }
            if ($user->role === 'sekretaris') {
                return redirect()->route('sekretaris.dashboard');
            }
            return redirect('/');
        }

        $pengaturan = PengaturanSekolah::first();

        return view('auth.login', compact('pengaturan'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:3|max:50|unique:users,username|regex:/^[a-zA-Z0-9_-]+$/',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'username.unique' => 'Username sudah digunakan, coba yang lain.',
            'username.regex' => 'Username hanya boleh huruf, angka, tanda hubung, dan garis bawah.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => strtolower(trim($request->username)),
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'status' => 'Aktif',
        ]);

        return redirect()->route('login')->with('sukses', 'Akun berhasil dibuat! Silakan masuk dengan akun Anda.');
    }

    public function checkUsername(Request $request)
    {
        $username = strtolower(trim($request->query('username', '')));
        if (empty($username)) {
            return response()->json(['available' => false, 'message' => 'Username wajib diisi.']);
        }
        if (strlen($username) < 3) {
            return response()->json(['available' => false, 'message' => 'Username minimal 3 karakter.']);
        }
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
            return response()->json(['available' => false, 'message' => 'Username hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.']);
        }

        $exists = User::where('username', $username)->exists();
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Username sudah digunakan, coba yang lain.' : 'Username tersedia'
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status !== 'Aktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.');
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('sukses', 'Selamat datang, Administrator.');
            }

            if ($user->role === 'guru') {
                return redirect()->route('guru.dashboard')->with('sukses', 'Selamat datang, ' . $user->name . '.');
            }

            if ($user->role === 'sekretaris') {
                return redirect()->route('sekretaris.dashboard')->with('sukses', 'Selamat datang di Portal Sekretaris.');
            }

            return redirect('/');
        }

        return redirect()->back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('sukses', 'Anda telah berhasil keluar ke portal.');
    }
}

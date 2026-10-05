<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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

@extends('layouts.admin', ['title' => 'Profil Saya - CMS SMKN 13 Bandung'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Profil Pengguna</h1>
            <p class="text-sm text-slate-500">Kelola informasi data diri dan kata sandi akun Anda.</p>
        </div>
        <div class="inline-flex items-center space-x-2 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
            <i class="fa-solid fa-shield-halved"></i>
            <span class="capitalize">Role: {{ $user->role ?? 'Admin' }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.profil.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex items-center space-x-5 pb-6 border-b border-slate-100">
                <div class="w-20 h-20 rounded-2xl bg-emerald-700 text-white font-black text-3xl flex items-center justify-center shadow-lg shadow-emerald-700/20">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 font-mono">@<span>{{ $user->username }}</span></p>
                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                        {{ $user->role ?? 'Administrator' }}
                    </span>
                </div>
            </div>

            <div class="space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Data Diri & Akun</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Role / Hak Akses
                        </label>
                        <input type="text" value="{{ ucfirst($user->role ?? 'Admin') }}" readonly
                            class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 cursor-not-allowed font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Lengkap
                        </label>
                        <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}" placeholder="Contoh: Jl. Soekarno-Hatta No. KM 10, Bandung"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400">Ganti Kata Sandi</h3>
                <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengubah kata sandi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="inputPassword" placeholder="Minimal 6 karakter"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition pr-10">
                        <button type="button" onclick="togglePasswordVisibility('inputPassword', 'iconTogglePassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i class="fa-regular fa-eye" id="iconTogglePassword"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="inputPasswordConfirmation" placeholder="Ulangi password baru"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition pr-10">
                        <button type="button" onclick="togglePasswordVisibility('inputPasswordConfirmation', 'iconToggleConfirmPassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i class="fa-regular fa-eye" id="iconToggleConfirmPassword"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold transition">
                Kembali
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-bold shadow-md shadow-emerald-700/20 transition flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection

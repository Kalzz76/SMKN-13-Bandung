@extends('layouts.admin', ['title' => 'Profil Saya - CMS SMKN 13 Bandung'])

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <!-- Header Banner / Cover -->
    <div class="relative h-64 sm:h-72 rounded-3xl overflow-hidden shadow-xs border border-blue-900/10 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-600">
        <!-- Geometric SVG Pattern Overlay -->
        <svg class="absolute inset-0 w-full h-full opacity-15 object-cover pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" preserveAspectRatio="none">
            <path d="M0 0 L400 200 L0 400 Z" fill="#ffffff" />
            <path d="M400 0 L800 200 L400 400 Z" fill="#ffffff" />
            <path d="M200 0 L600 0 L400 200 Z" fill="#ffffff" />
            <path d="M200 400 L600 400 L400 200 Z" fill="#ffffff" />
        </svg>

        <!-- Accent Floating Shapes -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 -top-10 w-48 h-48 bg-cyan-400/20 rounded-full blur-xl pointer-events-none"></div>

        <!-- Tombol Pojok Kanan Atas (Change Cover) -->
        <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-10">
            <button type="button" onclick="showModalMsg('Informasi', 'Fitur ganti sampul akan segera hadir!', 'info')" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-slate-900/40 hover:bg-slate-900/60 text-white backdrop-blur-md text-xs font-semibold shadow-xs transition border border-white/10 cursor-pointer">
                <i class="fa-solid fa-camera text-xs"></i>
                <span>Change Cover</span>
            </button>
        </div>
    </div>

    <!-- Main Content Overlap Grid (Naik ke Atas Menindih Cover) -->
    <div class="relative -mt-36 sm:-mt-44 px-3 sm:px-6 z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- KOLOM KIRI: Card Identitas & Ringkasan Info (lg:col-span-4) -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 flex flex-col items-center text-center space-y-5">
            <!-- Avatar Bulat Besar dengan Tombol Kamera -->
            <div class="relative">
                <div class="w-28 h-28 rounded-full ring-4 ring-white shadow-md bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black text-3xl flex items-center justify-center select-none overflow-hidden">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                </div>
                <button type="button" onclick="document.getElementById('inputNamaLengkapAdmin').focus()" class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xs shadow-md transition cursor-pointer ring-2 ring-white" title="Perbarui Profil">
                    <i class="fa-solid fa-camera"></i>
                </button>
            </div>

            <!-- Nama & Instansi -->
            <div class="space-y-1 w-full">
                <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500 font-medium">SMK Negeri 13 Bandung</p>
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100">
                    {{ ucfirst($user->role ?? 'Administrator') }}
                </span>
            </div>

            <div class="w-full border-t border-slate-100"></div>

            <!-- Ringkasan Statistik / Info Akun -->
            <div class="w-full space-y-3 text-xs">
                <div class="flex items-center justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600 font-medium">Status Akun</span>
                    <span class="font-bold text-amber-500">{{ $user->status ?? 'Aktif' }}</span>
                </div>
                <div class="flex items-center justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-600 font-medium">Hak Akses</span>
                    <span class="font-bold text-emerald-600">{{ ucfirst($user->role ?? 'Admin') }}</span>
                </div>
                <div class="flex items-center justify-between py-1">
                    <span class="text-slate-600 font-medium">Terdaftar Sejak</span>
                    <span class="font-bold text-slate-700">{{ $user->created_at ? $user->created_at->format('M Y') : '2026' }}</span>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: Card Pengaturan Akun & Tabs Form (lg:col-span-8) -->
        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 sm:p-8 space-y-6">
            <!-- Tabs Navigasi (Hanya: Account Settings, Security & Password, Institusi SMKN 13) -->
            <div class="flex items-center space-x-6 border-b border-slate-200 text-xs sm:text-sm font-semibold overflow-x-auto scrollbar-none pb-px">
                <button type="button" onclick="switchProfileTab('tabAccount')" id="btnTabAccount" class="pb-3 border-b-2 border-blue-600 text-blue-600 font-bold transition whitespace-nowrap cursor-pointer">
                    Account Settings
                </button>
                <button type="button" onclick="switchProfileTab('tabSecurity')" id="btnTabSecurity" class="pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition whitespace-nowrap cursor-pointer">
                    Security & Password
                </button>
                <button type="button" onclick="switchProfileTab('tabInstitusi')" id="btnTabInstitusi" class="pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition whitespace-nowrap cursor-pointer">
                    Institusi SMKN 13
                </button>
            </div>

            <!-- Form Edit Profile -->
            <form method="POST" action="{{ route('admin.profil.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- TAB 1: Account Settings (Default Aktif) -->
                <div id="contentTabAccount" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="inputNamaLengkapAdmin" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Username / ID Pengguna <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Phone Number / Kontak
                            </label>
                            <input type="text" value="+62 22 7318960" readonly
                                class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-600 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email address
                            </label>
                            <input type="text" value="{{ $user->username }}@smkn13bdg.sch.id" readonly
                                class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-600 outline-none font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Domisili
                            </label>
                            <input type="text" name="alamat" value="{{ old('alamat', $user->alamat) }}" placeholder="Contoh: Jl. Soekarno-Hatta KM 10, Bandung"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                State / Provinsi
                            </label>
                            <input type="text" value="Jawa Barat" readonly
                                class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-600 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Postcode / Kode Pos
                            </label>
                            <input type="text" value="40286" readonly
                                class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-600 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Country
                            </label>
                            <input type="text" value="Indonesia" readonly
                                class="w-full bg-slate-50/70 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-600 outline-none">
                        </div>
                    </div>
                </div>

                <!-- TAB 2: Security & Password -->
                <div id="contentTabSecurity" class="space-y-5 hidden">
                    <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100 text-blue-900 text-xs">
                        <p class="font-bold mb-0.5"><i class="fa-solid fa-shield-halved mr-1.5"></i> Keamanan Akun</p>
                        <p class="text-blue-700">Kosongkan kolom di bawah jika Anda tidak ingin mengganti kata sandi.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="inputPasswordAdmin" placeholder="Minimal 6 karakter"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition pr-10">
                                <button type="button" onclick="togglePasswordVisibility('inputPasswordAdmin', 'iconTogglePasswordAdmin')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-regular fa-eye" id="iconTogglePasswordAdmin"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="inputPasswordConfirmationAdmin" placeholder="Ulangi password baru"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition pr-10">
                                <button type="button" onclick="togglePasswordVisibility('inputPasswordConfirmationAdmin', 'iconToggleConfirmPasswordAdmin')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-regular fa-eye" id="iconToggleConfirmPasswordAdmin"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: Institusi SMKN 13 -->
                <div id="contentTabInstitusi" class="space-y-4 hidden text-xs">
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <h4 class="font-bold text-slate-900 text-sm">Informasi Sekolah</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-600">
                            <div><strong>Nama Instansi:</strong> SMK Negeri 13 Bandung</div>
                            <div><strong>NPSN:</strong> 20219156</div>
                            <div><strong>Alamat:</strong> Jl. Soekarno-Hatta Km. 10, Kota Bandung</div>
                            <div><strong>Akreditasi:</strong> A (Unggul)</div>
                        </div>
                    </div>
                </div>

                <!-- Action Button di Bawah Persis Gambar (Tombol Biru 'Update') -->
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition">
                        Kembali
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition cursor-pointer">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchProfileTab(tabName) {
    const tabs = ['tabAccount', 'tabSecurity', 'tabInstitusi'];
    
    tabs.forEach(t => {
        const content = document.getElementById('content' + t.charAt(0).toUpperCase() + t.slice(1));
        const btn = document.getElementById('btn' + t.charAt(0).toUpperCase() + t.slice(1));
        
        if (t === tabName) {
            if (content) content.classList.remove('hidden');
            if (btn) {
                btn.classList.add('border-blue-600', 'text-blue-600', 'font-bold');
                btn.classList.remove('border-transparent', 'text-slate-500');
            }
        } else {
            if (content) content.classList.add('hidden');
            if (btn) {
                btn.classList.remove('border-blue-600', 'text-blue-600', 'font-bold');
                btn.classList.add('border-transparent', 'text-slate-500');
            }
        }
    });
}

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

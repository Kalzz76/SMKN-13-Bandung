@extends($layout, ['title' => 'Profil Saya - SMKN 13 Bandung'])

@section('content')
<div class="max-w-6xl mx-auto pb-10">
    <div class="relative h-36 sm:h-44 rounded-3xl overflow-hidden shadow-xs border border-blue-900/10 bg-gradient-to-r from-blue-700 via-indigo-600 to-blue-600">
        <svg class="absolute inset-0 w-full h-full opacity-15 object-cover pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 400" preserveAspectRatio="none">
            <path d="M0 0 L400 200 L0 400 Z" fill="#ffffff" />
            <path d="M400 0 L800 200 L400 400 Z" fill="#ffffff" />
            <path d="M200 0 L600 0 L400 200 Z" fill="#ffffff" />
            <path d="M200 400 L600 400 L400 200 Z" fill="#ffffff" />
        </svg>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 -top-10 w-48 h-48 bg-cyan-400/20 rounded-full blur-xl pointer-events-none"></div>

        <div class="absolute top-3 right-3 sm:top-4 sm:right-5 z-10">
            <button type="button" onclick="showModalMsg('Informasi', 'Fitur ganti sampul akan segera hadir!', 'info')" class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl bg-slate-900/40 hover:bg-slate-900/60 text-white backdrop-blur-md text-xs shadow-xs transition border border-white/10 cursor-pointer" title="Ganti Sampul">
                <i class="fa-solid fa-camera"></i>
            </button>
        </div>
    </div>

    <div class="relative -mt-20 sm:-mt-24 px-3 sm:px-6 z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 sm:p-8 flex flex-col justify-between h-full space-y-6">
            <div class="flex flex-col items-center text-center space-y-4 w-full">
                <div class="relative">
                    <div id="avatarPreviewContainer" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full ring-4 ring-white shadow-md bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black text-3xl flex items-center justify-center select-none overflow-hidden">
                        @if($user->foto_url)
                            <img id="avatarImage" src="{{ $user->foto_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span id="avatarInitial">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</span>
                        @endif
                    </div>
                    <label for="inputFotoProfil" class="absolute bottom-1 right-1 w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center text-xs shadow-md transition cursor-pointer ring-2 ring-white hover:scale-105 active:scale-95" title="Ganti Foto Profil">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                    <input type="file" id="inputFotoProfil" name="foto" form="formProfil" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" onchange="handleFotoProfilChange(this)">
                </div>

                <div class="space-y-1 w-full">
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500 font-medium">SMK Negeri 13 Bandung</p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100">
                        {{ ucfirst($user->role ?? 'Pengguna') }}
                    </span>
                </div>
            </div>

            <div class="w-full space-y-4 pt-6 border-t border-slate-100 mt-auto">
                <div class="w-full space-y-3 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-medium">Status Akun</span>
                        <span class="font-bold text-amber-500">{{ $user->status ?? 'Aktif' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-600 font-medium">Hak Akses</span>
                        <span class="font-bold text-emerald-600">{{ ucfirst($user->role ?? 'User') }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-600 font-medium">Terdaftar Sejak</span>
                        <span class="font-bold text-slate-700">{{ $user->created_at ? $user->created_at->format('M Y') : '2026' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8 bg-white rounded-3xl border border-slate-200/90 shadow-lg p-6 sm:p-8 flex flex-col justify-between h-full space-y-6">
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

            <form id="formProfil" method="POST" action="{{ $user->role === 'admin' ? route('admin.profil.update') : route('profil.update') }}" enctype="multipart/form-data" class="space-y-6 flex flex-col justify-between flex-grow">
                @csrf
                @method('PUT')

                <div id="contentTabAccount" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="inputNamaLengkap" name="name" value="{{ old('name', $user->name) }}" required
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
                            <input type="text" value="{{ $user->guru->no_telp ?? ($user->siswa->no_hp ?? '+62 812-3456-7890') }}" readonly
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
                                <input type="password" name="password" id="inputPassword" placeholder="Minimal 6 karakter"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition pr-10">
                                <button type="button" onclick="togglePasswordVisibility('inputPassword', 'iconTogglePassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-regular fa-eye" id="iconTogglePassword"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="inputPasswordConfirmation" placeholder="Ulangi password baru"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition pr-10">
                                <button type="button" onclick="togglePasswordVisibility('inputPasswordConfirmation', 'iconToggleConfirmPassword')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <i class="fa-regular fa-eye" id="iconToggleConfirmPassword"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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

                <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                    <a href="{{ $backUrl }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs transition">
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

function handleFotoProfilChange(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    
    if (file.size > 3 * 1024 * 1024) {
        showModalMsg('Ukuran Terlalu Besar', 'Maksimal ukuran foto adalah 3MB.', 'warning');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        const container = document.getElementById('avatarPreviewContainer');
        if (container) {
            container.innerHTML = '<img id="avatarImage" src="' + e.target.result + '" alt="Avatar" class="w-full h-full object-cover">';
        }
    };
    reader.readAsDataURL(file);

    const formData = new FormData();
    formData.append('foto', file);
    formData.append('_token', '{{ csrf_token() }}');

    const uploadUrl = "{{ $user->role === 'admin' ? route('admin.profil.foto.update') : route('profil.foto.update') }}";

    fetch(uploadUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => { throw new Error(data.message || 'Gagal mengunggah foto'); });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showModalMsg('Berhasil', data.message || 'Foto profil berhasil diperbarui.', 'success');
            document.querySelectorAll('.header-user-avatar').forEach(el => {
                el.innerHTML = '<img src="' + data.foto_url + '" alt="Avatar" class="w-full h-full object-cover">';
            });
        }
    })
    .catch(err => {
        showModalMsg('Perhatian', err.message || 'Terjadi kesalahan saat mengunggah foto.', 'warning');
    });
}
</script>
@endsection

<div id="modalKonfirmasiLogout" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
    <div id="modalKonfirmasiLogoutCard" class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-4 transform transition-all duration-200 scale-95 opacity-0">
        <div class="w-14 h-14 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-xl font-bold mx-auto ring-8 ring-rose-50 shadow-sm">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>

        <div>
            <h4 class="text-lg font-black text-slate-900">Konfirmasi Keluar</h4>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
                Apakah Anda yakin ingin keluar dari sistem? Anda harus memasukkan kredensial akun kembali untuk mengakses portal ini.
            </p>
        </div>

        @if(auth()->check())
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center space-x-3 text-left">
                <div class="w-9 h-9 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <span class="block text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] font-semibold text-emerald-700 uppercase tracking-wider">Role: {{ auth()->user()->role }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-2 gap-3 pt-2">
            <button type="button" onclick="tutupModalLogout()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                Batal
            </button>
            <button type="button" onclick="kirimFormLogout()" class="w-full px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                <span>Ya, Keluar</span>
            </button>
        </div>
    </div>
</div>

<script>
let formLogoutAktif = null;

function konfirmasiLogout(event, formId) {
    if (event) {
        event.preventDefault();
    }
    const targetId = formId || 'formLogoutUtama';
    formLogoutAktif = document.getElementById(targetId);

    const modal = document.getElementById('modalKonfirmasiLogout');
    const card = document.getElementById('modalKonfirmasiLogoutCard');
    if (modal && card) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(function() {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
}

function tutupModalLogout() {
    const modal = document.getElementById('modalKonfirmasiLogout');
    const card = document.getElementById('modalKonfirmasiLogoutCard');
    if (modal && card) {
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(function() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }, 150);
    }
}

function kirimFormLogout() {
    if (formLogoutAktif) {
        formLogoutAktif.submit();
    } else {
        const defaultForm = document.getElementById('formLogoutUtama');
        if (defaultForm) {
            defaultForm.submit();
        }
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        tutupModalLogout();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalKonfirmasiLogout');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                tutupModalLogout();
            }
        });
    }
});
</script>

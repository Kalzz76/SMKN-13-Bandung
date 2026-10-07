<!-- Custom Modal Notifikasi (Alert / Pesan Sukses / Error) -->
<div id="customModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[9999] flex items-center justify-center p-4 transition-all duration-200 {{ session('sukses') || session('error') || session('info') || $errors->any() ? '' : 'hidden' }}">
    <div id="customModalCard" class="bg-white rounded-3xl p-6 sm:p-7 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-4 transform transition-all duration-200">
        @if(session('error') || $errors->any())
            <div id="customModalIcon" class="w-14 h-14 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 id="customModalTitle" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Perhatian</h4>
            <p id="customModalMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ session('error') ?? $errors->first() }}</p>
        @elseif(session('sukses'))
            <div id="customModalIcon" class="w-14 h-14 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h4 id="customModalTitle" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Berhasil</h4>
            <p id="customModalMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ session('sukses') }}</p>
        @elseif(session('info'))
            <div id="customModalIcon" class="w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <h4 id="customModalTitle" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Informasi</h4>
            <p id="customModalMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ session('info') }}</p>
        @else
            <div id="customModalIcon" class="w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <h4 id="customModalTitle" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Pemberitahuan</h4>
            <p id="customModalMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed"></p>
        @endif
        <div class="pt-2">
            <button type="button" onclick="closeCustomModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-xl transition shadow-xs text-xs cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>
</div>

<!-- Custom Modal Konfirmasi (Pengganti confirm() bawaan browser) -->
<div id="customConfirmModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[10000] hidden items-center justify-center p-4 transition-all duration-200">
    <div id="customConfirmCard" class="bg-white rounded-3xl p-6 sm:p-7 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-4 transform transition-all duration-200">
        <div id="customConfirmIcon" class="w-14 h-14 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h4 id="customConfirmTitle" class="text-base sm:text-lg font-bold text-slate-900 tracking-tight">Konfirmasi Tindakan</h4>
        <p id="customConfirmMessage" class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
        
        <div class="grid grid-cols-2 gap-2.5 pt-2">
            <button type="button" onclick="tutupCustomConfirm()" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition cursor-pointer">
                Batal
            </button>
            <button type="button" id="customConfirmBtnSubmit" onclick="eksekusiConfirm()" class="w-full px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition cursor-pointer">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

<script>
// --- Modal Pesan Biasa (Alert / Flash Message) ---
function closeCustomModal() {
    const modal = document.getElementById('customModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function showModalMsg(title, msg, type = 'info') {
    const modal = document.getElementById('customModal');
    const titleEl = document.getElementById('customModalTitle');
    const msgEl = document.getElementById('customModalMessage');
    const iconEl = document.getElementById('customModalIcon');

    if (titleEl) titleEl.innerText = title || 'Pemberitahuan';
    if (msgEl) msgEl.innerText = msg || '';

    if (iconEl) {
        if (type === 'error' || type === 'danger') {
            iconEl.className = 'w-14 h-14 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
            iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
        } else if (type === 'sukses' || type === 'success') {
            iconEl.className = 'w-14 h-14 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
            iconEl.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        } else if (type === 'warning') {
            iconEl.className = 'w-14 h-14 bg-amber-50 border border-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
            iconEl.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i>';
        } else {
            iconEl.className = 'w-14 h-14 bg-blue-50 border border-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
            iconEl.innerHTML = '<i class="fa-solid fa-circle-info"></i>';
        }
    }

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

// Override alert() bawaan browser agar seluruh notifikasi menggunakan modal buatan sendiri
window.alert = function(message) {
    showModalMsg('Pemberitahuan', String(message), 'info');
};

// --- Modal Konfirmasi Custom (Pengganti confirm() bawaan browser) ---
let targetConfirmForm = null;
let targetConfirmAction = null;

function bukaKonfirmasi(pesan, formOrAction, opsi = {}) {
    const modal = document.getElementById('customConfirmModal');
    const msgEl = document.getElementById('customConfirmMessage');
    const titleEl = document.getElementById('customConfirmTitle');
    const btnSubmit = document.getElementById('customConfirmBtnSubmit');
    const iconEl = document.getElementById('customConfirmIcon');

    if (msgEl) msgEl.innerText = pesan || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
    if (titleEl) titleEl.innerText = opsi.judul || 'Konfirmasi Tindakan';
    
    if (btnSubmit) {
        btnSubmit.innerText = opsi.tombolTeks || 'Ya, Lanjutkan';
        if (opsi.warna === 'emerald') {
            btnSubmit.className = 'w-full px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition cursor-pointer';
            if (iconEl) {
                iconEl.className = 'w-14 h-14 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
                iconEl.innerHTML = '<i class="fa-solid fa-circle-question"></i>';
            }
        } else {
            btnSubmit.className = 'w-full px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition cursor-pointer';
            if (iconEl) {
                iconEl.className = 'w-14 h-14 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto shadow-xs';
                iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
            }
        }
    }

    if (typeof formOrAction === 'function') {
        targetConfirmAction = formOrAction;
        targetConfirmForm = null;
    } else {
        targetConfirmForm = formOrAction;
        targetConfirmAction = null;
    }

    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function eksekusiConfirm() {
    const form = targetConfirmForm;
    const action = targetConfirmAction;
    tutupCustomConfirm();

    if (form) {
        if (form.dataset) form.dataset.confirmed = 'true';
        form.submit();
    } else if (typeof action === 'function') {
        action();
    }
}

function tutupCustomConfirm() {
    const modal = document.getElementById('customConfirmModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    targetConfirmForm = null;
    targetConfirmAction = null;
}

// Global Interceptor: Mencegat semua submit form yang memiliki konfirmasi sebelum native browser dialog muncul
window.addEventListener('submit', function(e) {
    const form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    if (form.dataset && form.dataset.confirmed === 'true') {
        form.dataset.confirmed = 'false';
        return; // Izinkan submit karena sudah disetujui di custom modal
    }

    let confirmMsg = null;
    const onsubmitAttr = form.getAttribute('onsubmit');
    
    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
        // Ambil pesan dari confirm('...')
        const match = onsubmitAttr.match(/confirm\s*\(\s*['"`]([\s\S]*?)['"`]\s*\)/);
        if (match) {
            confirmMsg = match[1].replace(/\\n/g, '\n').replace(/\\'/g, "'").replace(/\\"/g, '"');
        } else {
            confirmMsg = 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        }
    } else if (form.dataset && form.dataset.confirm) {
        confirmMsg = form.dataset.confirm;
    }

    if (confirmMsg) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        let warna = 'rose';
        let tombolTeks = 'Ya, Lanjutkan';
        if (confirmMsg.toLowerCase().includes('hapus') || confirmMsg.toLowerCase().includes('tolak') || confirmMsg.toLowerCase().includes('reset')) {
            warna = 'rose';
            tombolTeks = confirmMsg.toLowerCase().includes('hapus') ? 'Ya, Hapus' : 'Ya, Lanjutkan';
        } else if (confirmMsg.toLowerCase().includes('setujui')) {
            warna = 'emerald';
            tombolTeks = 'Ya, Setujui';
        }

        bukaKonfirmasi(confirmMsg, form, { warna: warna, tombolTeks: tombolTeks });
        return false;
    }
}, true); // Capture phase (true) memastikan event ditangkap sebelum inline onsubmit browser dijalankan

// Override window.confirm agar tidak memunculkan native dialog browser
window.confirm = function(message) {
    console.warn('Native confirm() dipanggil dan dicegat oleh SMKN 13 System:', message);
    return false;
};

document.addEventListener('DOMContentLoaded', function() {
    // Backdrop click close
    const modalMsg = document.getElementById('customModal');
    if (modalMsg) {
        modalMsg.addEventListener('click', function(e) {
            if (e.target === modalMsg) closeCustomModal();
        });
    }

    const modalConfirm = document.getElementById('customConfirmModal');
    if (modalConfirm) {
        modalConfirm.addEventListener('click', function(e) {
            if (e.target === modalConfirm) tutupCustomConfirm();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCustomModal();
            tutupCustomConfirm();
        }
    });
});
</script>

<div id="customModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 {{ session('sukses') || session('error') || session('info') ? '' : 'hidden' }}">
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center space-y-4">
        @if(session('error'))
            <div id="customModalIcon" class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-xl font-bold mx-auto">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 id="customModalTitle" class="text-lg font-bold text-slate-900">Perhatian</h4>
            <p id="customModalMessage" class="text-sm text-slate-600">{{ session('error') }}</p>
        @elseif(session('sukses'))
            <div id="customModalIcon" class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center text-xl font-bold mx-auto">
                <i class="fa-solid fa-check"></i>
            </div>
            <h4 id="customModalTitle" class="text-lg font-bold text-slate-900">Berhasil</h4>
            <p id="customModalMessage" class="text-sm text-slate-600">{{ session('sukses') }}</p>
        @elseif(session('info'))
            <div id="customModalIcon" class="w-12 h-12 bg-sky-100 text-sky-800 rounded-full flex items-center justify-center text-xl font-bold mx-auto">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <h4 id="customModalTitle" class="text-lg font-bold text-slate-900">Informasi</h4>
            <p id="customModalMessage" class="text-sm text-slate-600">{{ session('info') }}</p>
        @else
            <div id="customModalIcon" class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center text-xl font-bold mx-auto">
                <i class="fa-solid fa-check"></i>
            </div>
            <h4 id="customModalTitle" class="text-lg font-bold text-slate-900">Informasi</h4>
            <p id="customModalMessage" class="text-sm text-slate-600"></p>
        @endif
        <button type="button" onclick="closeCustomModal()" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition shadow">Mengerti</button>
    </div>
</div>

<script>
function closeCustomModal() {
    const modal = document.getElementById('customModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function showModalMsg(title, msg, type) {
    const modal = document.getElementById('customModal');
    const titleEl = document.getElementById('customModalTitle');
    const msgEl = document.getElementById('customModalMessage');
    const iconEl = document.getElementById('customModalIcon');

    if (titleEl) titleEl.innerText = title;
    if (msgEl) msgEl.innerText = msg;

    if (iconEl) {
        if (type === 'error') {
            iconEl.className = 'w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center text-xl font-bold mx-auto';
            iconEl.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
        } else {
            iconEl.className = 'w-12 h-12 bg-emerald-100 text-emerald-800 rounded-full flex items-center justify-center text-xl font-bold mx-auto';
            iconEl.innerHTML = '<i class="fa-solid fa-check"></i>';
        }
    }

    if (modal) modal.classList.remove('hidden');
}
</script>

<div id="loginModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
        <button type="button" onclick="closeLoginModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center text-xl font-bold mx-auto mb-3">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900">Login Portal SMKN 13</h3>
            <p class="text-xs text-slate-500 mt-1">Masukkan username dan password akun Anda</p>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Masukkan username" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600" required autofocus>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="Masukkan password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-emerald-600" required>
            </div>
            <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow transition">Masuk Dashboard</button>
        </form>
    </div>
</div>

<script>
function openLoginModal() {
    document.getElementById('loginModal').classList.remove('hidden');
}
function closeLoginModal() {
    document.getElementById('loginModal').classList.add('hidden');
}
</script>

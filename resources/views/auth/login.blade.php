@php
  $sitePengaturan = $pengaturan ?? \App\Models\PengaturanSekolah::first();
  $namaSekolah = $sitePengaturan->nama_sekolah ?? 'SMKN 13 Bandung';
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Masuk — {{ $namaSekolah }}</title>

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <style>
    :root {
      /* Theme SMKN 13 Bandung: Navy & Teal (Harmonis dengan Halaman Publik) */
      --navy-dark: #0F2A47;
      --navy-mid: #17476B;
      --teal-primary: #1D6F6A;
      --teal-light: #3A9189;
      --teal-accent: #9ED6CF;
      --teal-tint: #E1ECEE;
      --brick-red: #B03A2E;
      --brick-tint: #F5E1DE;

      --primary-color: #1D6F6A;
      --primary-dark: #14524E;
      --secondary-color: #0F2A47;
      --accent-color: #9ED6CF;
      --accent-hover: #3A9189;
      --black: #081626;
      --white: #ffffff;
      --bg-dark: #091B2E;
      --card-dark: #102B47;
      --input-bg: #0C233B;
      --border-dark: #1E466E;
      --gray-muted: #9BB3C9;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    @keyframes authPageEntrance {
      0% {
        opacity: 0;
        filter: blur(8px);
        transform: scale(0.985);
      }

      100% {
        opacity: 1;
        filter: blur(0);
        transform: scale(1);
      }
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--bg-dark);
      color: var(--white);
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      position: relative;
      margin: 0;
      padding: 0;
      animation: authPageEntrance 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Background Interactive Canvas */
    #bgCanvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
    }

    /* Toast Notification Container */
    .toast-box {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
      pointer-events: none;
    }

    .toast-item {
      min-width: 290px;
      max-width: 380px;
      background: #ffffff;
      border-radius: 12px;
      padding: 12px 18px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
      display: flex;
      align-items: center;
      gap: 12px;
      border-left: 4px solid var(--primary-color);
      transform: translateY(-20px);
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
      pointer-events: auto;
    }

    .toast-item.muncul {
      transform: translateY(0);
      opacity: 1;
    }

    .toast-item.sukses {
      border-left-color: #1D6F6A;
    }

    .toast-item.sukses i {
      color: #1D6F6A;
      font-size: 1.4rem;
    }

    .toast-item.peringatan {
      border-left-color: #B03A2E;
    }

    .toast-item.peringatan i {
      color: #B03A2E;
      font-size: 1.4rem;
    }

    .toast-item .toast-title {
      font-size: 0.88rem;
      font-weight: 700;
      color: #1e293b;
      display: block;
    }

    .toast-item .toast-desc {
      font-size: 0.78rem;
      color: #64748b;
      margin-top: 2px;
      display: block;
    }

    /* Main Container */
    .container {
      position: relative;
      min-height: 100vh;
      height: 100vh;
      width: 100%;
      overflow: hidden;
      z-index: 2;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      height: 100vh;
      width: 100%;
      position: relative;
      z-index: 7;
    }

    .col {
      width: 50%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow-y: auto;
      overflow-x: hidden;
      scrollbar-width: none;
    }

    .col::-webkit-scrollbar {
      display: none;
    }

    .align-items-center {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .flex-col {
      flex-direction: column;
    }

    .form-wrapper {
      width: 100%;
      max-width: 26rem;
      padding: 0.75rem 1rem;
      z-index: 10;
      margin: auto 0;
    }

    /* Form Container */
    .form {
      padding: 1.75rem 2rem;
      background-color: var(--card-dark);
      backdrop-filter: blur(14px);
      -webkit-backdrop-filter: blur(14px);
      border-radius: 1.25rem;
      width: 100%;
      max-height: 92vh;
      overflow-y: auto;
      box-shadow: rgba(0, 0, 0, 0.45) 0px 15px 35px;
      border: 1px solid rgba(158, 214, 207, 0.15);
      transform: scale(1);
      opacity: 1;
      pointer-events: auto;
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
      transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .form::-webkit-scrollbar {
      width: 5px;
    }

    .form::-webkit-scrollbar-thumb {
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 10px;
    }

    /* Logo Branding */
    .brand-logo-wrap {
      text-align: center;
      margin-bottom: 1.2rem;
    }

    .brand-link {
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      transition: transform 0.25s ease;
    }

    .brand-link:hover {
      transform: scale(1.04);
    }

    .brand-icon {
      width: 42px;
      height: 42px;
      border-radius: 11px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #1D6F6A, #3A9189);
      box-shadow: 0 4px 14px rgba(29, 111, 106, 0.45);
      color: #fff;
      font-size: 1.45rem;
      font-weight: 800;
    }

    .brand-img {
      height: 42px;
      width: auto;
      object-fit: contain;
      filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.35));
    }

    .brand-text {
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      letter-spacing: -0.5px;
    }

    .brand-text span {
      color: var(--accent-color);
    }

    .form-subtitle {
      font-size: 0.82rem;
      color: var(--gray-muted);
      margin-top: 4px;
    }

    /* Input Field Styling */
    .input-group {
      position: relative;
      width: 100%;
      margin: 0.75rem 0;
    }

    .input-group i.field-icon {
      position: absolute;
      top: 50%;
      left: 1rem;
      transform: translateY(-50%);
      font-size: 1.15rem;
      color: #3A9189;
      transition: color 0.3s;
      pointer-events: none;
    }

    .input-group input {
      width: 100%;
      padding: 0.8rem 2.5rem 0.8rem 2.7rem;
      font-size: 0.88rem;
      background-color: var(--input-bg);
      border-radius: 0.7rem;
      border: 1.5px solid var(--border-dark);
      outline: none;
      transition: all 0.25s ease;
      color: #ffffff;
      font-family: inherit;
      font-weight: 500;
    }

    .input-group input::placeholder {
      color: #94a3b8;
    }

    .input-group input:focus {
      border-color: #3A9189;
      background-color: #0E2A47;
      box-shadow: 0 0 0 3px rgba(58, 145, 137, 0.28);
    }

    .input-group input:focus~i.field-icon {
      color: #9ED6CF;
    }

    /* Password Toggle Button */
    .toggle-pass {
      position: absolute;
      top: 50%;
      right: 0.9rem;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #3A9189;
      font-size: 1.15rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      transition: color 0.2s;
    }

    .toggle-pass:hover {
      color: #9ED6CF;
    }

    /* Action Buttons */
    .form button.btn-action {
      cursor: pointer;
      width: 100%;
      padding: 0.8rem 0;
      border-radius: 0.7rem;
      border: none;
      background: linear-gradient(135deg, #1D6F6A 0%, #17476B 100%);
      color: var(--white);
      font-size: 0.95rem;
      font-weight: 600;
      font-family: inherit;
      outline: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 14px rgba(29, 111, 106, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 0.9rem;
    }

    .form button.btn-action:hover {
      background: linear-gradient(135deg, #3A9189 0%, #1D6F6A 100%);
      box-shadow: 0 6px 20px rgba(29, 111, 106, 0.55);
      transform: translateY(-1px);
    }

    .form button.btn-action:active {
      transform: translateY(1px);
    }

    .form button.btn-action:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      pointer-events: none;
      transform: none;
    }

    /* Message Alert Box */
    .form .pesan {
      display: none;
      margin: 0.6rem 0;
      padding: 0.6rem 0.85rem;
      border-radius: 0.6rem;
      font-size: 0.8rem;
      text-align: left;
      line-height: 1.4;
      align-items: center;
      gap: 8px;
    }

    .form .pesan.muncul {
      display: flex;
    }

    .form .pesan.gagal {
      background-color: rgba(176, 58, 46, 0.18);
      color: #F5E1DE;
      border: 1px solid rgba(176, 58, 46, 0.38);
    }

    .form .pesan.berhasil {
      background-color: rgba(29, 111, 106, 0.22);
      color: #9ED6CF;
      border: 1px solid rgba(58, 145, 137, 0.4);
    }

    /* Links */
    .form-footer {
      text-align: center;
      margin-top: 1.1rem;
    }

    .tautan-beranda {
      color: var(--gray-muted);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.82rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .tautan-beranda:hover {
      background-color: rgba(158, 214, 207, 0.12);
      color: #ffffff;
    }

    /* Content Promo Panel (Left Column) */
    .promo-content {
      margin: 2rem 3rem;
      color: var(--white);
      text-align: center;
      max-width: 440px;
      z-index: 10;
    }

    .text-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, 0.12);
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 0.76rem;
      font-weight: 600;
      letter-spacing: 0.4px;
      margin-bottom: 1rem;
      border: 1px solid rgba(158, 214, 207, 0.25);
      backdrop-filter: blur(5px);
    }

    .text-badge i {
      color: var(--accent-color);
    }

    .promo-content h2 {
      font-size: 2.3rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 0.9rem;
    }

    .promo-content p {
      font-weight: 400;
      font-size: 0.92rem;
      line-height: 1.6;
      opacity: 0.92;
    }

    .img-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 1.5rem;
    }

    .img-wrap svg {
      width: 160px;
      height: auto;
      filter: drop-shadow(0 14px 24px rgba(0, 0, 0, 0.35));
    }

    /* Curved Background Panel (Theme SMKN 13 Bandung - Navy & Teal) */
    .container::before {
      content: "";
      position: absolute;
      top: 0;
      right: 50%;
      height: 100vh;
      width: 300vw;
      transform: translate(0, 0);
      background: linear-gradient(-45deg, #0F2A47 0%, #17476B 50%, #1D6F6A 100%);
      z-index: 5;
      box-shadow: rgba(0, 0, 0, 0.45) 0px 5px 35px;
      border-bottom-right-radius: max(50vw, 50vh);
      border-top-left-radius: max(50vw, 50vh);
    }

    /* ================= Responsive For Mobile/Tablets ================= */
    @media only screen and (max-width: 768px) {
      body {
        height: 100%;
        min-height: 100dvh;
        overflow: hidden;
      }

      .container {
        min-height: 100dvh;
        height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
      }

      .container::before {
        position: absolute;
        top: 0;
        right: 0;
        left: auto;
        bottom: auto;
        width: min(85vw, 360px);
        height: min(85vw, 360px);
        border-radius: 50%;
        background: linear-gradient(135deg, #17476B 0%, #1D6F6A 100%);
        box-shadow: 0 18px 45px rgba(23, 71, 107, 0.45);
        transform: translate(25%, -25%);
        z-index: 3;
        pointer-events: none;
      }

      .container::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: min(45vw, 180px);
        height: min(45vw, 180px);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(158, 214, 207, 0.35) 0%, rgba(29, 111, 106, 0.15) 100%);
        filter: blur(10px);
        z-index: 2;
        pointer-events: none;
        transform: translate(-30%, 30%);
      }

      .promo-col {
        display: none !important;
      }

      .row {
        height: 100vh;
        height: 100dvh;
        width: 100%;
        position: relative;
        z-index: 10;
      }

      .col {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
      }

      .form-wrapper {
        width: 100%;
        max-width: 24.5rem;
        padding: 0.5rem;
        margin: auto;
        z-index: 12;
        position: relative;
      }

      .form {
        padding: 1.5rem 1.6rem;
        background-color: var(--card-dark);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 1.4rem;
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.45);
        border: 1.5px solid rgba(255, 255, 255, 0.12);
        max-height: 88vh;
        max-height: 88dvh;
      }

      .brand-text {
        font-size: 20px;
      }

      .input-group {
        margin: 0.65rem 0;
      }

      .input-group input {
        padding: 0.72rem 2.2rem 0.72rem 2.4rem;
        font-size: 0.85rem;
      }

      .form button.btn-action {
        padding: 0.72rem 0;
        font-size: 0.9rem;
      }
    }

    @media (max-height: 680px) {
      .promo-content h2 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
      }

      .promo-content p {
        font-size: 0.82rem;
        line-height: 1.45;
      }

      .img-wrap svg {
        width: 120px;
      }

      .form {
        padding: 1.3rem 1.5rem;
      }

      .input-group {
        margin: 0.55rem 0;
      }

      .input-group input {
        padding: 0.68rem 2.2rem 0.68rem 2.4rem;
        font-size: 0.82rem;
      }

      .brand-logo-wrap {
        margin-bottom: 0.7rem;
      }
    }
  </style>
</head>

<body>

  <!-- Interactive Canvas Background -->
  <canvas id="bgCanvas"></canvas>

  <!-- Toast Notification Box -->
  <div id="toastContainer" class="toast-box"></div>

  <!-- Main Container -->
  <div id="container" class="container">
    <div class="row">

      <!-- ================= PROMO PANEL (LEFT) ================= -->
      <div class="col align-items-center flex-col promo-col">
        <div class="promo-content">
          <div class="text-badge">
            <i class="bx bxs-school"></i> Portal Akademik &amp; Layanan
          </div>
          <h2>Selamat datang kembali!</h2>
          <p>
            Sistem Informasi &amp; Portal Akademik {{ $namaSekolah }}. Akses jadwal pelajaran, presensi digital, jurnal
            kelas, serta layanan sekolah terpadu.
          </p>
          <div class="img-wrap">
            <svg viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M100 45 C75 35 35 38 20 44 V95 C35 89 75 86 100 96 C125 86 165 89 180 95 V44 C165 38 125 35 100 45 Z"
                fill="#ffffff" fill-opacity="0.95" />
              <path d="M100 45 V96" stroke="#1D6F6A" stroke-width="3" stroke-linecap="round" />
              <path d="M100 20 L55 35 L100 50 L145 35 Z" fill="#9ED6CF" />
              <path d="M145 35 V55" stroke="#9ED6CF" stroke-width="3" stroke-linecap="round" />
              <circle cx="145" cy="58" r="4" fill="#9ED6CF" />
            </svg>
          </div>
        </div>
      </div>

      <!-- ================= FORM MASUK (RIGHT) ================= -->
      <div class="col align-items-center flex-col">
        <div class="form-wrapper align-items-center">
          <div class="form">

            <div class="brand-logo-wrap">
              <a href="{{ url('/') }}" class="brand-link">
                @if(!empty($sitePengaturan?->logo_url))
                  <img src="{{ $sitePengaturan->logo_url }}" alt="Logo {{ $namaSekolah }}"
                    class="brand-img">
                @else
                  <div class="brand-icon">
                    <i class="bx bxs-graduation"></i>
                  </div>
                @endif
                <span class="brand-text">{{ $namaSekolah }}<span>.</span></span>
              </a>
              <div class="form-subtitle">Silakan masuk ke akun Anda</div>
            </div>

            <form id="formMasuk" method="POST" action="{{ route('login') }}" autocomplete="on">
              @csrf

              <div class="input-group">
                <i class="bx bx-user field-icon"></i>
                <input type="text" id="usernameMasuk" name="username" placeholder="Username / NIP / NISN"
                  autocomplete="username" required autofocus value="{{ old('username') }}">
              </div>

              <div class="input-group">
                <i class="bx bxs-lock-alt field-icon"></i>
                <input type="password" id="passwordMasuk" name="password" placeholder="Kata sandi"
                  autocomplete="current-password" required>
                <button type="button" class="toggle-pass" onclick="togglePasswordVisibility('passwordMasuk', this)"
                  aria-label="Lihat kata sandi">
                  <i class="bx bx-show"></i>
                </button>
              </div>

              <div class="pesan" id="pesanMasuk">
                <i class="bx bxs-error-circle" style="font-size: 1.15rem;"></i>
                <span id="pesanMasukTeks"></span>
              </div>

              <button type="submit" id="tombolMasuk" class="btn-action">
                <span id="tombolMasukTeks">Masuk ke Akun</span>
                <i id="tombolMasukIcon" class="bx bx-log-in-circle"></i>
              </button>
            </form>

            <div class="form-footer">
              <a class="tautan-beranda" href="{{ url('/') }}">
                <i class="bx bx-home-alt"></i> Kembali ke Beranda
              </a>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- ================= JAVASCRIPT LOGIC ================= -->
  <script>
    const LOGIN_URL = "{{ route('login') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Check server flash messages on load
    window.addEventListener('DOMContentLoaded', () => {
      @if(session('error'))
        showMessage('pesanMasuk', @json(session('error')), 'gagal');
        showToast('Gagal Masuk', @json(session('error')), 'peringatan');
      @endif

      @if(session('sukses'))
        showMessage('pesanMasuk', @json(session('sukses')), 'berhasil');
        showToast('Berhasil', @json(session('sukses')), 'sukses');
      @endif

      @if($errors->any())
        showMessage('pesanMasuk', @json($errors->first()), 'gagal');
        showToast('Peringatan', @json($errors->first()), 'peringatan');
      @endif
    });

    function showMessage(elementId, text, type = 'gagal') {
      const box = document.getElementById(elementId);
      if (!box) return;
      const textSpan = document.getElementById(elementId + 'Teks');
      const icon = box.querySelector('i');

      textSpan.textContent = text;
      box.className = `pesan muncul ${type}`;

      if (type === 'berhasil') {
        icon.className = 'bx bxs-check-circle';
      } else {
        icon.className = 'bx bxs-error-circle';
      }
    }

    function hideMessage(elementId) {
      const box = document.getElementById(elementId);
      if (box) box.className = 'pesan';
    }

    // Toggle Password Visibility
    function togglePasswordVisibility(inputId, btn) {
      const input = document.getElementById(inputId);
      const icon = btn.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bx bx-hide';
      } else {
        input.type = 'password';
        icon.className = 'bx bx-show';
      }
    }

    // Toast Notifications
    function showToast(judul, deskripsi, tipe = 'sukses') {
      const toastContainer = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = `toast-item ${tipe}`;

      const iconClass = tipe === 'sukses' ? 'bx bx-check-circle' : 'bx bx-info-circle';
      toast.innerHTML = `
        <i class="${iconClass}"></i>
        <div>
          <span class="toast-title">${judul}</span>
          <span class="toast-desc">${deskripsi}</span>
        </div>
      `;

      toastContainer.appendChild(toast);

      setTimeout(() => {
        toast.classList.add('muncul');
      }, 30);

      setTimeout(() => {
        toast.classList.remove('muncul');
        setTimeout(() => {
          if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
          }
        }, 350);
      }, 3500);
    }

    // Form Sign-In Submit (AJAX with Fallback)
    const formMasuk = document.getElementById('formMasuk');
    formMasuk.addEventListener('submit', async (e) => {
      e.preventDefault();
      hideMessage('pesanMasuk');

      const username = document.getElementById('usernameMasuk').value.trim();
      const password = document.getElementById('passwordMasuk').value;

      if (!username) {
        showMessage('pesanMasuk', 'Username atau NIP wajib diisi.');
        return;
      }
      if (!password) {
        showMessage('pesanMasuk', 'Kata sandi wajib diisi.');
        return;
      }

      const btn = document.getElementById('tombolMasuk');
      const btnText = document.getElementById('tombolMasukTeks');
      const btnIcon = document.getElementById('tombolMasukIcon');

      btn.disabled = true;
      btnText.textContent = 'Memverifikasi...';
      btnIcon.className = 'bx bx-loader-alt bx-spin';

      try {
        const response = await fetch(LOGIN_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ username, password })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          const errMsg = result.message || 'Login gagal. Periksa kembali username dan kata sandi Anda.';
          showMessage('pesanMasuk', errMsg);
          showToast('Gagal Masuk', errMsg, 'peringatan');
          btn.disabled = false;
          btnText.textContent = 'Masuk ke Akun';
          btnIcon.className = 'bx bx-log-in-circle';
          return;
        }

        showMessage('pesanMasuk', 'Berhasil masuk! Mengalihkan ke dashboard...', 'berhasil');
        showToast('Berhasil Masuk', `Selamat datang, ${result.data?.user?.name || username}!`, 'sukses');

        setTimeout(() => {
          window.location.href = result.data?.redirect || "{{ url('/') }}";
        }, 800);

      } catch (err) {
        // Fallback ke submit form biasa jika AJAX terhalang
        formMasuk.submit();
      }
    });

    // Particle Background Canvas Engine (Emerald Green & Gold)
    (function initParticles() {
      const canvas = document.getElementById('bgCanvas');
      if (!canvas) return;
      const ctx = canvas.getContext('2d');
      let particles = [];
      let mouse = { x: null, y: null };
      let animId;

      function resize() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        createParticles();
      }

      class Particle {
        constructor() {
          this.x = Math.random() * canvas.width;
          this.y = Math.random() * canvas.height;
          this.size = Math.random() * 3 + 1.2;
          this.speedX = (Math.random() - 0.5) * 0.7;
          this.speedY = (Math.random() - 0.5) * 0.7;
          this.color = Math.random() > 0.35 ? 'rgba(58, 145, 137, 0.5)' : 'rgba(158, 214, 207, 0.65)';
        }

        update() {
          this.x += this.speedX;
          this.y += this.speedY;

          if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
          if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;

          if (mouse.x !== null && mouse.y !== null) {
            const dx = mouse.x - this.x;
            const dy = mouse.y - this.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < 120) {
              const force = (120 - dist) / 120;
              const angle = Math.atan2(dy, dx);
              this.x -= Math.cos(angle) * force * 2;
              this.y -= Math.sin(angle) * force * 2;
            }
          }
        }

        draw() {
          ctx.beginPath();
          ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
          ctx.fillStyle = this.color;
          ctx.fill();
        }
      }

      function createParticles() {
        particles = [];
        const count = Math.min(70, Math.floor((canvas.width * canvas.height) / 18000));
        for (let i = 0; i < count; i++) {
          particles.push(new Particle());
        }
      }

      function connect() {
        const maxDist = 125;
        for (let a = 0; a < particles.length; a++) {
          for (let b = a + 1; b < particles.length; b++) {
            const dx = particles[a].x - particles[b].x;
            const dy = particles[a].y - particles[b].y;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < maxDist) {
              const opacity = (1 - dist / maxDist) * 0.22;
              ctx.beginPath();
              ctx.strokeStyle = `rgba(58, 145, 137, ${opacity})`;
              ctx.lineWidth = 0.8;
              ctx.moveTo(particles[a].x, particles[a].y);
              ctx.lineTo(particles[b].x, particles[b].y);
              ctx.stroke();
            }
          }
        }
      }

      function loop() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < particles.length; i++) {
          particles[i].update();
          particles[i].draw();
        }
        connect();
        animId = requestAnimationFrame(loop);
      }

      window.addEventListener('resize', resize);
      window.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
      });
      window.addEventListener('mouseleave', () => {
        mouse.x = null;
        mouse.y = null;
      });
      window.addEventListener('touchstart', (e) => {
        if (e.touches.length > 0) {
          mouse.x = e.touches[0].clientX;
          mouse.y = e.touches[0].clientY;
        }
      }, { passive: true });
      window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
          mouse.x = e.touches[0].clientX;
          mouse.y = e.touches[0].clientY;
        }
      }, { passive: true });
      window.addEventListener('touchend', () => {
        mouse.x = null;
        mouse.y = null;
      });

      resize();
      loop();
    })();
  </script>
</body>

</html>
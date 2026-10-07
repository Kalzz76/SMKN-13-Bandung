# 🏫 Sistem Informasi & Portal Akademik SMKN 13 Bandung

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

Aplikasi web modern berbasis **Laravel 12** dan **Tailwind CSS** yang dirancang sebagai pusat informasi publik sekaligus sistem manajemen data akademik, kepegawaian, kesiswaan, absensi barcode, dan operasional harian **SMK Negeri 13 Bandung**.

---

## 🌟 Fitur Utama

### 🌐 1. Portal Publik & Profil Sekolah
* **Beranda Interaktif**: Sambutan kepala sekolah, statistik sekolah, highlight jurusan, berita terkini, dan galeri kegiatan.
* **Profil Lengkap**: Visi & misi, sejarah sekolah, struktur organisasi, dan tenaga pendidik/kependidikan.
* **Program Keahlian (Jurusan)**: Informasi kurikulum, prospek kerja, dan fasilitas untuk program keahlian:
  * Rekayasa Perangkat Lunak (RPL)
  * Teknik Komputer dan Jaringan (TKJ)
  * Analisis Kimia (Kimia Analis / KA)
* **Berita & Pengumuman**: Publikasi agenda, berita kegiatan, dan informasi penting sekolah.
* **Galeri & Dokumentasi**: Dokumentasi foto & album kegiatan sekolah.
* **Ekstrakurikuler**: Informasi ragam kegiatan ekskul siswa (Banzai, Karawitan, Paskibra, PMR, Pramuka, Paduan Suara, dll.).
* **Kontak & Lokasi**: Informasi alamat resmi, maps, form pesan, dan jam operasional.

---

### 🛡️ 2. Panel Administrasi (Admin)
* **Dashboard Statistik**: Monitoring real-time data guru, siswa, kelas, jadwal, dan rekapitulasi.
* **Manajemen Master Data**:
  * Ruangan & Gedung
  * Mata Pelajaran (Mapel)
  * Guru & Tenaga Kependidikan
  * Kelas & Struktur Organisasi Kelas (Wali Kelas, Ketua Murid, dll.)
  * Data Siswa (Lengkap dengan fitur **Import & Template Excel**)
* **Manajemen Akun (User Management)**:
  * Pembuatan akun otomatis massal untuk Guru & Siswa
  * Pengaturan role (*Admin*, *Guru*, *Sekretaris*, *Siswa*)
  * Reset password & status akun
* **Jadwal Pelajaran**:
  * Penjadwalan mata pelajaran per hari, sesi jam pelajaran, dan ruangan
  * Import & Export Jadwal via Excel
  * Jadwal Pembiasaan & Chronos
* **Sistem Barcode & ID Card**:
  * Generate barcode unik per siswa
  * Cetak kartu siswa (format layout cetak & export PDF)
* **Manajemen Konten Publik**:
  * Berita, Galeri Foto, Prestasi Siswa & Guru, Profil Sekolah, dan Ekstrakurikuler
* **Laporan & Audit**:
  * Laporan absensi & kehadiran
  * Audit log aktivitas sistem (*Activity Log*)

---

### 👨‍🏫 3. Portal Guru & Sekretaris
* **Dashboard Guru**: Rekap jadwal mengajar hari ini, status presensi, dan jadwal pembiasaan.
* **Absensi & Jurnal Mengajar**:
  * Pencatatan kehadiran siswa di kelas (Hadir, Sakit, Izin, Alpa)
  * Input jurnal harian pembelajaran
  * Pengajuan izin/cuti guru secara terintegrasi
* **Dashboard Sekretaris Kelas**:
  * Membantu pencatatan presensi dan absensi harian kelas masing-masing.

---

## 🛠️ Tech Stack & Prasyarat Sistem

* **PHP**: `>= 8.2` (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `gd`, `zip`, `xml`)
* **Composer**: `>= 2.0`
* **Node.js & NPM**: Node.js `>= 18.x` & NPM `>= 9.x`
* **Database**: MySQL `>= 8.0` atau MariaDB `>= 10.4`
* **Web Server**: Apache / Nginx / Laravel Artisan Development Server

---

## 🚀 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan pengembangan lokal:

### 1. Clone Repository
```bash
git clone https://github.com/Kalzz76/SMKN-13-Bandung.git
cd SMKN-13-Bandung
```

### 2. Install Dependensi PHP & JavaScript
```bash
# Dependensi Backend (Composer)
composer install

# Dependensi Frontend (NPM)
npm install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi koneksi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smkn13bandung
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key & Symlink Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Jalankan Migrasi & Seeder Database
Pastikan database MySQL telah dibuat sesuai dengan nama di `.env`, lalu jalankan:
```bash
php artisan migrate --seed
```

### 6. Jalankan Server Pengembangan
Buka dua terminal terpisah:
```bash
# Terminal 1: Asset compiler (Vite)
npm run dev

# Terminal 2: Laravel server
php artisan serve
```
Aplikasi siap diakses melalui peramban di: **`http://localhost:8000`**

---

## 🔑 Akun Bawaan (Default Seeders)

Setelah menjalankan `php artisan db:seed`, akun default berikut dapat langsung digunakan untuk masuk ke sistem:

| Role | Username | Password Default | Akses Menu |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | `/admin` (Semua modul master data, user, & konten) |
| **Sekretaris Kelas** | `sekretaris` | `sekretaris123` | Portal presensi & pencatatan kelas |

> ⚠️ **Catatan Keamanan**: Harap segera mengganti kredensial default di atas pada lingkungan produksi melalui panel profil akun!

---

## 📁 Struktur Direktori Utama

```
SMKN-13-Bandung/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller modul manajemen administrator
│   │   │   ├── Guru/          # Controller modul aktivitas & presensi guru
│   │   │   ├── Sekretaris/    # Controller modul peran sekretaris kelas
│   │   │   └── PublikController.php
│   │   └── Middleware/        # Filter otentikasi & hak akses role
│   └── Models/                # Model Eloquent (User, Siswa, Guru, Jadwal, dll.)
├── database/
│   ├── migrations/            # Skema struktur tabel database
│   └── seeders/               # Data awal & akun bawaan
├── resources/
│   ├── views/
│   │   ├── admin/             # Tampilan Blade panel admin
│   │   ├── guru/              # Tampilan Blade panel guru
│   │   ├── sekretaris/        # Tampilan Blade panel sekretaris
│   │   ├── publik/            # Tampilan Blade portal publik
│   │   └── components/        # Komponen UI Blade reusable
│   ├── css/                   # Konfigurasi Tailwind CSS v4
│   └── js/                    # Script frontend
├── routes/
│   └── web.php                # Definisi routing aplikasi web
├── start-online.bat           # Helper script otomasi server lokal & Ngrok
└── stop-online.bat            # Helper script stop server
```

---

## ⚡ Script Utilitas Windows

Bagi pengembang di lingkungan sistem operasi Windows, disediakan batch script praktis di root direktori:

* **`start-online.bat`**: Otomatis memeriksa port MySQL, menyalakan server Laravel, dan menghubungkan tunnel Ngrok untuk demo online.
* **`stop-online.bat`**: Menghentikan proses server web, tunnel Cloudflare/Ngrok, dan instance PHP yang berjalan.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan internal sistem informasi akademik **SMK Negeri 13 Bandung**. Lisensi framework Laravel mengacu pada [MIT License](https://opensource.org/licenses/MIT).

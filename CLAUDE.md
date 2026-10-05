# CMS Sekolah SMKN 13 Bandung

Website portal sekolah sekaligus sistem informasi internal: halaman publik (beranda, profil, jurusan, berita, galeri, kontak), pengelolaan data sekolah (siswa, kelas, guru, ruangan, mapel, jadwal), absensi guru dengan scan barcode lewat kamera, absensi siswa per kelas, dan laporan absensi. Dibangun dengan Laravel dan MySQL. Ada 3 role: **Admin**, **Guru**, dan **Sekretaris**.

## URUTAN KERJA (BACA DULU SEBELUM MENGERJAKAN APA PUN)

1. Baca `CLAUDE.md` ini sampai selesai.
2. **Sebelum membuat atau mengubah halaman apa pun, buka folder `referensi-desain/`** dan ikuti aturan prioritas di bagian "Referensi Desain" di bawah (halaman publik dari `index.html`, dashboard dari folder Flutter lalu dicek ke `index.html`).
3. Kalau ada folder `referensi/` (kode lama buatan sendiri), baca untuk melihat gaya penulisan kode.
4. Baru tulis kode, mengikuti ATURAN PENULISAN KODE di bawah.

Kalau ada perbedaan antara `index.html`, folder Flutter, dan `CLAUDE.md`, **`CLAUDE.md` yang jadi acuan final**.

## ATURAN PENULISAN KODE (WAJIB DIPATUHI)

Kode di project ini harus sederhana dan bersih, terlihat ditulis manual oleh pelajar, bukan hasil generate.

1. **DILARANG menulis komentar di dalam kode.** Tidak ada `//`, `/* */`, `/** */` (PHP), `{{-- --}}` (Blade), `<!-- -->` (HTML), dan komentar di JavaScript/CSS.
2. **Kode harus sederhana.** Tidak boleh ada Repository, Service layer, Interface per class, Action class, DTO, Trait buatan sendiri, atau design pattern lain. Logika cukup di controller.
3. **Hindari sintaks yang terlihat canggih**: collection chaining panjang, arrow function bertingkat, `match` expression, null-safe operator bertumpuk, ternary bersarang, closure kompleks, scope Eloquent buatan sendiri. Pakai `if`, `foreach`, `for`, dan query Eloquent biasa (`where`, `orderBy`, `find`, `create`, `update`, `delete`).
4. **Yang penting jalan dan tidak error.** Utamakan kode yang lurus dan mudah dibaca daripada kode yang pintar.
5. Nama variabel, method, dan controller boleh campur Indonesia/Inggris (`$daftarBerita`, `$dataGuru`, `simpanAbsensi`, `cekJarak`).
6. Semua teks untuk pengguna (label, pesan sukses, pesan error, validasi) ditulis dalam Bahasa Indonesia. Pesan validasi diberikan lewat parameter pesan di `validate()`.
7. Validasi memakai `$request->validate([...], [...pesan...])` langsung di controller. Tidak perlu Form Request class.
8. **DILARANG mengubah struktur tabel lewat kode di controller atau model.** Struktur tabel hanya lewat file migration. Data awal (akun, pengaturan sekolah, jam pelajaran) lewat seeder.
9. Query memakai Eloquent atau Query Builder. Kalau perlu query mentah, wajib pakai binding parameter, tidak boleh menyambung string dari input pengguna.
10. Tampilan memakai **Blade + Tailwind CSS (CDN) + Font Awesome**, sama seperti `index.html`. Tidak memakai Livewire, Inertia, Vue, React, atau Alpine. JavaScript ditulis sederhana dalam bentuk fungsi biasa (`openModal`, `closeModal`, `switchTab`, dst) seperti di `index.html`.
11. Library tambahan yang diizinkan hanya: `qrcodejs` (membuat QR di browser) dan `html5-qrcode` (scan kamera), keduanya lewat CDN cdnjs seperti di `index.html`. Library lain (termasuk package Composer tambahan) tanya dulu.
12. Fitur yang tidak ada di `CLAUDE.md` ini tidak boleh ditambahkan hanya karena ada di folder referensi. Referensi dipakai untuk **tampilan**, bukan untuk menambah fitur.

Aturan ini berlaku untuk semua perubahan berikutnya, tanpa perlu diingatkan lagi.

## Referensi Desain

Folder `referensi-desain/` berisi dua acuan:

1. **`index.html`**: mockup satu file (HTML + Tailwind + JavaScript) yang memuat halaman publik, login, dashboard Admin, dashboard Guru, dan dashboard Sekretaris. Datanya palsu (objek `dbData` di JavaScript).
2. **`project-aplikasi-pbt-sekolah/`**: aplikasi **Flutter** yang sudah jadi. Dipakai sebagai acuan tampilan dashboard Admin, Guru, dan Siswa. Isinya **tidak lengkap**, jadi untuk bagian yang tidak ada di sana tetap cek `index.html`.

### Aturan prioritas

| Yang dikerjakan                                  | Acuan utama                                   | Pelengkap                                  |
| ------------------------------------------------ | --------------------------------------------- | ------------------------------------------ |
| Beranda dan semua halaman publik                 | `index.html` (satu-satunya acuan)             | Tidak ada                                  |
| Login (modal di portal publik)                   | `index.html`                                  | Tidak ada                                  |
| Dashboard Admin, Guru, Sekretaris                | Folder Flutter (susunan elemen, urutan, kartu) | `index.html` (daftar menu, kolom, form)   |
| Halaman yang tidak ada di folder Flutter         | `index.html`                                  | Tidak ada                                  |

- Folder Flutter dibaca untuk melihat **susunan halaman, urutan elemen, dan alur**. Cari file screen/page dashboard di dalam `lib/`.
- Dashboard **Siswa** di folder Flutter hanya dipakai sebagai gambaran gaya tampilan. **Website ini tidak punya role Siswa** dan tidak ada login siswa.
- **Warna tetap memakai palet di bagian "Palet Warna dan Tampilan"** (diambil dari `index.html`) supaya konsisten dengan beranda, meskipun aplikasi Flutter memakai warna lain.
- **Jangan menyalin kode Dart/Flutter** dan jangan menjadikan project ini Flutter Web. Website ini tetap Laravel + Blade.
- Boleh menyalin kelas Tailwind dan struktur HTML dari `index.html`, tapi dipecah menjadi Blade view dan datanya diambil dari database.
- File di folder `referensi-desain/` tidak ikut di-deploy.

### Pemetaan bagian `index.html` ke halaman Laravel

| Bagian di `index.html`   | Route / View Laravel                       | Judul                       |
| ------------------------ | ------------------------------------------ | --------------------------- |
| `pubPage_home`           | `/` → `publik/beranda`                     | Beranda                     |
| `pubPage_profil`         | `/profil` → `publik/profil`                | Profil & Sejarah            |
| `pubPage_jurusan`        | `/jurusan` → `publik/jurusan`              | Jurusan                     |
| `pubPage_berita`         | `/berita` → `publik/berita`                | Berita & Informasi          |
| `pubPage_galeri`         | `/galeri` → `publik/galeri`                | Galeri & Fasilitas          |
| `pubPage_kontak`         | `/kontak` → `publik/kontak`                | Kontak                      |
| `loginModal`             | modal di navbar publik, kirim ke `POST /login` | Login Portal SMKN 13    |
| `viewAdmin`              | `/admin/...` → `admin/*`                   | Dashboard Admin             |
| `viewGuru`               | `/guru/...` → `guru/*`                     | Portal Guru Pengajar        |
| `viewSekretaris`         | `/sekretaris/...` → `sekretaris/*`         | Portal Sekretaris Sekolah   |
| `customModal`            | partial `partials/modal-pesan` (pesan flash) | Informasi / Berhasil      |

Tambahan di luar mockup (dibuat atas permintaan pemilik project, ikuti deskripsi di bawah): halaman publik **Ekstrakurikuler**, **Prestasi**, **Detail Berita**; menu admin **Dashboard**, **Profil & Sambutan**, **Jurusan**, **Ekstrakurikuler**, **Prestasi**, **Log Aktivitas**; menu mobile (hamburger) di navbar publik.

### Bagian mockup yang sengaja dibedakan dan JANGAN diikuti mentah-mentah

- **Semua data di objek `dbData`** (siswa Rizky Pratama, guru Refky/Uli/Kiki/Nofa, berita contoh, dll) hanya contoh. Di Laravel semua data dari database. Contohnya boleh dijadikan data awal di seeder, bukan ditulis di Blade.
- **Pergantian halaman dengan `display: none/block` (`switchPublicPage`, `switchPortalView`, `switchAdminTab`)** diganti route Laravel biasa. Menu aktif ditandai lewat `request()->routeIs(...)`. Pengecualian: tab di dashboard Guru dan Sekretaris boleh tetap berupa tab JavaScript sederhana kalau isinya ringan.
- **Login di mockup** (dropdown pilih role, dropdown "Pilih Akun Guru", password bawaan `123456`, tulisan "Default password demo") hanya demo. Di Laravel: modal berisi **Username** dan **Password** saja, role mengikuti akun. Tidak ada dropdown role dan tidak ada tulisan password demo.
- **Gambar memakai URL** (`placehold.co`, field "URL Foto Profil", "URL Gambar Header") diganti **upload file**. Kalau foto belum ada, tampilkan gambar pengganti lokal (kotak berwarna dengan inisial atau ikon), bukan link luar.
- **Tombol hapus tanpa konfirmasi** di mockup harus diberi konfirmasi. Mockup juga tidak punya Edit, di Laravel semua CRUD punya **Tambah, Edit, Hapus**.
- **Halaman Manajemen Akun** di mockup hanya 3 kartu statis. Di Laravel jadi CRUD akun sungguhan (lihat bagian Admin).
- **Pengaturan Sekolah** di mockup berisi nilai tetap. Di Laravel diisi dari `pengaturan_sekolah` dan benar-benar tersimpan.
- **Jadwal Mengajar guru** di mockup menampilkan semua jadwal (`myJadwal = dbData.jadwal`). Di Laravel hanya jadwal **milik guru yang login**.
- **Scan barcode di mockup** langsung mencatat Hadir tanpa validasi dan isi QR-nya `SMKN13_ATTENDANCE_{id}_{Date.now()}`. Di Laravel kode disimpan di database, divalidasi di server, dan lokasi dicek.
- **Form "Absensi & Jurnal Kelas" guru di mockup** mengisi status kehadiran **guru** per kelas. Di Laravel yang diisi guru adalah **absensi siswa** per kelas (hadir, sakit, izin, alpa), ditambah catatan materi. Absensi kehadiran guru hanya lewat scan barcode.
- **Sekretaris di mockup** memilih guru dan jadwal berdasarkan teks nama. Di Laravel memakai id, dan pilihan jadwal diganti daftar jadwal guru itu hari ini (hanya sebagai info).
- **Teks sambutan, nama kepala sekolah ("Dr. H. Ahmad Supriyadi, M.Pd."), tanda tangan, dan alamat** di mockup adalah contoh. Isi aslinya diinput admin.
- **Matriks jadwal di mockup** menampilkan satu hari tanpa pemilih hari dan sel-selnya belum akurat. Ikuti bentuk tabelnya, tapi bangun dengan benar sesuai bagian Jadwal Pelajaran di bawah.
- **Navbar publik di mockup** disembunyikan di layar kecil tanpa pengganti. Tambahkan tombol hamburger untuk menu mobile.
- **Modal pesan `showModalMsg`** di mockup dipanggil lewat JavaScript. Di Laravel muncul otomatis dari pesan flash session (`sukses` / `error`) dengan tampilan modal yang sama.

## Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Database**: MySQL (XAMPP/Laragon saat development)
- **Tampilan**: Blade + Tailwind CSS lewat CDN (`https://cdn.tailwindcss.com`) + Font Awesome 6.4 + font Inter (Google Fonts), persis seperti `index.html`
- **Autentikasi**: login manual dengan `Auth::attempt` (username + password), tanpa Breeze/Jetstream
- **Barcode**: QR Code dibuat di browser dengan `qrcodejs` (`qrcode.min.js` 1.0.0). QR Code dipilih karena kamera HP lebih andal membacanya daripada barcode garis.
- **Scan kamera**: `html5-qrcode` 2.3.8 (`Html5QrcodeScanner`), seperti di `index.html`
- **Lokasi**: `navigator.geolocation` di browser, dikirim bersama hasil scan
- **Upload file**: `storage/app/public` dengan `php artisan storage:link`
- **Zona waktu**: `Asia/Jakarta`, locale `id` (di `config/app.php`)

**Catatan penting kamera dan lokasi:** browser hanya mengizinkan kamera dan GPS di halaman **HTTPS** atau `localhost`. Saat dites dari HP lewat IP jaringan lokal (`http://192.168.x.x`), kamera dan lokasi akan diblokir. Untuk uji coba di HP pakai tunnel HTTPS (contoh ngrok) atau hosting yang punya SSL.

## Struktur Project

```
cms-sekolah/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PublikController.php        semua halaman publik
│   │   │   ├── LoginController.php
│   │   │   ├── Admin/                      DashboardController, ProfilSekolahController, PengaturanController, SiswaController,
│   │   │   │                               KelasController, GuruController, RuanganController, MapelController, JadwalController,
│   │   │   │                               BarcodeController, LaporanAbsensiController, BeritaController, GaleriController,
│   │   │   │                               UserController, JurusanController, EkskulController, PrestasiController, LogController
│   │   │   ├── Guru/                       DashboardController, AbsensiGuruController, AbsensiSiswaController
│   │   │   └── Sekretaris/                 DashboardController, AbsensiPenggantiController
│   │   └── Middleware/
│   │       └── CekRole.php                 cek role user, tolak kalau tidak cocok
│   └── Models/                             satu model per tabel
├── database/
│   ├── migrations/
│   └── seeders/                            DatabaseSeeder (akun awal, pengaturan_sekolah, jam_pelajaran, data contoh dari mockup)
├── resources/views/
│   ├── layouts/                            publik.blade.php, admin.blade.php (sidebar), guru.blade.php (header + tab), sekretaris.blade.php
│   ├── partials/                           modal-pesan.blade.php, modal-login.blade.php
│   ├── publik/
│   ├── admin/
│   ├── guru/
│   └── sekretaris/
├── routes/web.php
└── public/storage/                         hasil storage:link (jangan diedit manual)
```

## Role dan Hak Akses

| Menu / Fitur                                        | Admin | Guru | Sekretaris |
| --------------------------------------------------- | :---: | :--: | :--------: |
| Kelola semua konten publik dan data master         | Ya    | Tidak | Tidak     |
| Kelola jadwal pelajaran                             | Ya    | Tidak | Tidak     |
| Generate dan cetak barcode guru                     | Ya    | Tidak | Tidak     |
| Lihat jadwal pelajaran semua kelas                  | Ya    | Tidak | Ya        |
| Lihat jadwal mengajar sendiri                       | Tidak | Ya   | Tidak      |
| Absensi guru lewat scan barcode                     | Tidak | Ya   | Tidak      |
| Absensi siswa per kelas yang diajar                 | Tidak | Ya   | Tidak      |
| Absensi pengganti guru (dengan alasan)              | Tidak | Tidak | Ya        |
| Laporan absensi guru dan siswa                      | Ya    | Tidak | Ya        |
| Rekap absensi diri sendiri                          | Tidak | Ya   | Tidak      |
| Log aktivitas                                       | Ya    | Tidak | Tidak     |

- Route dikelompokkan per role dengan prefix `admin`, `guru`, `sekretaris` dan middleware `auth` + `CekRole`. Mengetik URL role lain harus ditolak (abort 403).
- Setelah login, arahkan ke dashboard sesuai role.
- Akun dengan `status = Nonaktif` ditolak saat login dengan pesan jelas.
- Tombol **Keluar ke Portal** di tiap dashboard: logout lalu kembali ke beranda publik.

## Palet Warna dan Tampilan

Semua diambil dari kelas Tailwind di `index.html`.

| Elemen                         | Kelas Tailwind                                                     |
| ------------------------------ | ------------------------------------------------------------------ |
| Navbar publik                  | `bg-emerald-900 text-white`, tinggi `h-20`, `sticky top-0`         |
| Hero beranda                   | `bg-gradient-to-r from-emerald-950 via-emerald-900 to-slate-900`   |
| Tombol utama                   | `bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl`        |
| Tombol hero                    | `bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold`     |
| Sidebar admin                  | `bg-slate-900 text-slate-300`, lebar `md:w-64`                     |
| Menu sidebar aktif / hover     | `bg-emerald-700 text-white` / `hover:bg-slate-800 hover:text-white` |
| Latar halaman                  | `bg-slate-50`, teks `text-slate-800`, font Inter                   |
| Card                           | `bg-white rounded-2xl shadow-sm border border-slate-100 p-8`       |
| Header tabel                   | `bg-slate-100 text-slate-700 uppercase text-xs`, baris `divide-y divide-slate-100` |
| Input                          | `px-4 py-2.5 rounded-xl border border-slate-200 outline-none`      |
| Tombol hapus / keluar          | `text-rose-600` / `bg-rose-50 text-rose-600` (dashboard) dan `bg-rose-600/20 text-rose-300 hover:bg-rose-600` (sidebar) |
| Aksen sekretaris               | `bg-amber-600 hover:bg-amber-500`, badge `bg-amber-100 text-amber-800` |
| Modal                          | overlay `bg-slate-950/60 backdrop-blur-sm`, kotak `bg-white rounded-3xl p-8 max-w-md` |
| Footer publik                  | `bg-slate-950 text-slate-400`, 4 kolom                             |

**Badge status absensi:** Hadir `bg-emerald-100 text-emerald-800`, Terlambat `bg-amber-100 text-amber-800`, Izin `bg-yellow-100 text-yellow-800`, Sakit `bg-sky-100 text-sky-800`, Alpa `bg-rose-100 text-rose-800`.

**Warna matriks jadwal:** header kolom Carabika `bg-yellow-300`, nomor jam `bg-emerald-700 text-white`, Istirahat `bg-pink-400 text-white` (isi sel `bg-pink-300`), sel mapel `bg-emerald-100`, header `bg-slate-200`.

- Logo di navbar dan footer: kotak `bg-white text-emerald-900 font-black rounded-xl` berisi teks "13" (atau logo upload kalau sudah diisi di Pengaturan Sekolah), di sebelahnya nama sekolah huruf kapital.
- Format tanggal Indonesia (`Senin, 5 Oktober 2026`) lewat Carbon dengan locale `id`.
- Semua halaman harus rapi di HP, terutama dashboard Guru karena dipakai untuk scan.

## Halaman Publik

Memakai `layouts/publik.blade.php`: navbar, isi halaman, footer. Semua isi dari database. Tombol **Login Portal** di navbar membuka modal login.

Menu navbar (urutan seperti mockup): **Beranda, Profil & Sejarah, Jurusan, Berita & Informasi, Galeri & Fasilitas, Kontak**. Tambahan: dropdown **Kesiswaan** berisi Ekstrakurikuler dan Prestasi, serta hamburger untuk layar kecil.

- **Beranda**: (1) hero dengan pill slogan, judul "SMK Negeri 13 Bandung", paragraf singkat, dan dua tombol (Jelajahi Jurusan, Tentang Kami); (2) kartu **Sambutan Pimpinan**: foto kepala sekolah dengan label, judul sambutan, teks sambutan, nama dan jabatan; (3) **Berita & Informasi Terbaru** (3 berita terbaru berstatus Publish, kartu berisi gambar, badge kategori, judul, dua baris isi) dengan tautan "Lihat Semua".
- **Profil & Sejarah**: kartu **Sejarah Singkat**, kartu **Visi & Misi** (misi tampil sebagai daftar, satu baris data jadi satu poin), kartu **Struktur Organisasi Sekolah** (gambar bagan), lalu **Tenaga Pendidik & Staff Pengajar** (kartu foto bulat, nama, mapel; hanya yang `tampil_publik = 1`).
- **Jurusan**: kartu per jurusan berisi kotak ikon, nama, dan deskripsi.
- **Berita & Informasi**: kartu seperti di beranda (pagination 9 per halaman). Klik judul membuka **Detail Berita** (tambahan).
- **Galeri & Fasilitas**: grid 4 kolom, foto dengan overlay gelap di bawah berisi kategori (hijau) dan judul. Tambahan: tombol filter Semua / Kegiatan / Fasilitas.
- **Kontak**: alamat kampus, email resmi, nomor telepon, dan kotak **Jam Operasional Layanan**.
- **Ekstrakurikuler** dan **Prestasi** (tambahan): kartu dengan gaya yang sama seperti Jurusan dan Berita.
- **Footer**: logo + alamat, Tautan Cepat, Jam Operasional, Kontak, dan teks hak cipta.
- Kalau data kosong, tampilkan pesan ramah ("Belum ada berita"), jangan tampilkan area kosong.

## Dashboard Admin

Layout `layouts/admin.blade.php`: sidebar kiri (`md:w-64`, di HP menjadi blok di atas) berisi logo + tulisan **ADMINISTRATOR / CMS SMKN 13 Bandung**, daftar menu, dan tombol **Keluar ke Portal** di bawah. Konten di kanan.

**Urutan menu sidebar** (sesuai mockup, huruf ikut mockup):

```
Dashboard                  (tambahan, tampil pertama setelah login; acuan folder Flutter)
Barcode Absensi
A. Data Siswa
B. Manajemen Kelas
C. Data Guru
D. Data Ruangan
E. Mata Pelajaran
F. Jadwal Pelajaran
G. Laporan Absensi
H. Berita
I. Galeri & Fasilitas
J. Manajemen Akun
K. Pengaturan Sekolah
L. Profil & Sambutan       (tambahan)
M. Jurusan                 (tambahan)
N. Ekstrakurikuler         (tambahan)
O. Prestasi                (tambahan)
P. Log Aktivitas           (tambahan)
```

**Dashboard (tambahan)**: kartu statistik (jumlah guru, siswa, kelas, berita), jumlah guru hadir dan terlambat hari ini, daftar guru yang belum absen hari ini, dan 5 berita terbaru. Susunan mengikuti dashboard admin di folder Flutter.

**Pola CRUD untuk menu A sampai O**: judul halaman + tombol hijau di kanan atas (Tambah ...), isi berupa tabel (atau grid kartu untuk Guru, Galeri, dan daftar untuk Berita seperti di mockup), kolom Aksi berisi **Edit** dan **Hapus**. Tambah dan Edit memakai **modal** seperti di mockup. Pada Edit, field modal diisi lewat atribut `data-*` pada tombol Edit lewat fungsi JavaScript sederhana. Modal Tambah selalu kosong.

**Barcode Absensi**: dua kolom. Kiri: pilihan Guru, tombol **Buat Barcode Absensi**. Kanan: kotak berisi QR Code (`qrcodejs`, 140x140) dan label `Barcode: {nama guru}`. Tambahan di bawahnya: tabel guru (jenis Guru) dengan status barcode, tombol **Cetak** (kartu berisi nama, NIP, QR; `window.print()` dengan CSS cetak), **Cetak Semua**, dan **Generate Ulang** (kode lama otomatis tidak berlaku). Tombol Buat membuat `kode_barcode` acak 32 karakter lewat `Str::random(32)` dan menyimpannya ke tabel `guru`. Isi QR hanya `kode_barcode`.

**A. Data Siswa**: kolom No, NIS/NISN, Nama Lengkap, L/P, Kelas (badge hijau), Tahun Ajaran, Aksi. Modal: NIS/NISN, Nama, Jenis Kelamin (L/P), Kelas (**dropdown dari tabel kelas**, bukan teks bebas), Tahun Ajaran (contoh `2026/2027`).

**B. Manajemen Kelas**: kolom Nama Kelas, Ruangan, Wali Kelas, Aksi. Modal: Nama Kelas (contoh `XII RPL 1`), Ruangan (dropdown dari Data Ruangan), Wali Kelas (dropdown dari Data Guru).

**C. Data Guru & Staff**: grid kartu (foto bulat, nama, mapel, tombol Edit dan Hapus). Modal: Nama Lengkap & Gelar, Jenis (Guru/Staff), NIP (opsional), Jabatan, Mata Pelajaran Utama, Foto (upload), checkbox Tampil di halaman publik, dan pilihan **Akun Login** (dropdown akun role guru yang belum terhubung, opsional).

**D. Data Ruangan**: kolom Kode Ruang, Nama Ruangan, Kapasitas (`36 siswa`), Aksi. Modal: Kode Ruang (contoh `LAB-RPL`), Nama Ruangan, Kapasitas Siswa.

**E. Mata Pelajaran**: kolom Kode Mapel, Nama Mata Pelajaran, Kelompok (badge `bg-indigo-100 text-indigo-800`), Aksi. Modal: Kode Mapel (contoh `BING`), Nama, Kelompok (pilihan Produktif / Normatif / Adaptif).

**F. Jadwal Pelajaran**: lihat bagian Jadwal Pelajaran di bawah.

**G. Laporan Absensi**: dua tab, **Absensi Guru** dan **Absensi Siswa**.
- Absensi Guru: tabel Waktu, Nama Guru, Status (badge), Keterangan / Lokasi (koordinat dan jarak untuk scan, alasan untuk input sekretaris), Pencatat (**Scan Barcode** atau **Sekretaris Sekolah**). Filter bulan dan tahun, plus ringkasan jumlah Hadir/Terlambat/Izin/Sakit/Alpa per guru.
- Absensi Siswa: filter bulan, kelas, dan mapel (opsional); tabel per siswa berisi jumlah Hadir, Sakit, Izin, Alpa.
- Tombol **Cetak** (`window.print()`) dan **Export CSV** (fungsi PHP biasa dengan `fputcsv`, tanpa library Excel).

**H. Berita**: daftar kartu kecil (gambar, judul, kategori, Edit, Hapus). Modal "Tulis Berita": Judul, Kategori (contoh Prestasi, Kegiatan, Ekstrakurikuler), Gambar Header (upload), Isi Berita, Status (Draft/Publish), tombol **Publikasikan Berita**.

**I. Galeri & Fasilitas**: grid foto 4 kolom dengan tombol hapus bulat di pojok. Modal "Tambah Foto": Keterangan / Nama Fasilitas, Kategori (Fasilitas Sekolah / Kegiatan Siswa), Foto (upload).

**J. Manajemen Akun**: tabel akun (Nama, Username, Role, Status Aktif/Nonaktif, Aksi). Modal: Nama, Username (unik, huruf kecil), Role (admin/guru/sekretaris), Password (wajib saat Tambah, saat Edit kosong berarti tidak diganti), Status. Akun yang sedang login tidak boleh dihapus.

**K. Pengaturan Sekolah**: form berisi Nama Instansi, Alamat Resmi, Email, Telepon, Social Media (Instagram / YouTube), Jam Operasional, Logo (upload), serta bagian **Pengaturan Absensi**: Latitude Sekolah, Longitude Sekolah, Radius Absensi (meter), Jam Masuk (batas terlambat). Tombol **Simpan Perubahan**.

**L. Profil & Sambutan**: form berisi Slogan (teks pill di hero), Deskripsi Singkat (paragraf hero), Visi, Misi (satu baris satu poin), Sejarah, Nama Kepala Sekolah, Foto Kepala Sekolah, Judul Sambutan, Teks Sambutan, Gambar Struktur Organisasi.

**M. Jurusan**: modal berisi Kode (contoh `RPL`), Nama, Deskripsi, Ikon (pilihan nama ikon Font Awesome, contoh `fa-code`, `fa-network-wired`, `fa-satellite-dish`).

**N. Ekstrakurikuler** dan **O. Prestasi**: lihat tabel database untuk field-nya.

**P. Log Aktivitas**: daftar hanya-baca (siapa, aksi, keterangan, waktu). Method `catat($aksi, $keterangan)` di model `LogAktivitas` dipanggil saat tambah/ubah/hapus data penting dan absensi pengganti.

### Jadwal Pelajaran (matriks, mengikuti mockup)

- Header halaman: judul "F. Jadwal Pelajaran Sekolah", subjudul "Matriks jadwal harian (Kelas di kiri, Jam Ke & Waktu di atas)", tombol **Tambah Jadwal**.
- **Pemilih hari** (tambahan, mockup tidak punya): tombol Senin sampai Jumat di atas matriks. Default hari ini (kalau Sabtu/Minggu, Senin). Matriks menampilkan jadwal untuk hari terpilih.
- **Slot jam** (tabel `jam_pelajaran`, dari seeder, hanya-baca): Carabika 06.30-07.30, Jam 1 07.30-08.15, Jam 2 08.15-09.00, Jam 3 09.00-09.45, Istirahat 09.45-10.00, Jam 4 10.00-10.45, Jam 5 10.45-11.30, Istirahat 11.30-12.30, Jam 6 12.30-13.15, Jam 7 13.15-14.00.
- **Matriks**: tabel `min-w-[900px]` dalam kontainer `overflow-x-auto`. Baris header pertama berisi KELAS, JAM KE, lalu nama slot (Carabika kuning, nomor jam hijau, ISTIRAHAT pink); baris header kedua berisi rentang waktu. Untuk **setiap kelas** ada 3 baris: **MAPEL**, **RUANG**, **GURU** (label di kolom JAM KE). Nama kelas memakai `rowspan="3"`, sel Istirahat memakai `rowspan="3"`. Nama guru ditampilkan tanpa gelar (bagian sebelum koma).
- Kolom **Carabika** tidak bisa diisi mapel. Isinya teks tetap: `UPACARA` pada hari Senin dan `PERWALIAN` pada hari lain (sesuaikan kalau sekolah memakai kegiatan berbeda).
- Satu data jadwal bisa memanjang beberapa jam (contoh Matematika jam 1-3) dipakai `colspan` sebesar jumlah jam. Slot Istirahat di tengah blok tidak boleh dilewati: kalau jadwal melewati Istirahat, simpan sebagai dua baris jadwal. Sel yang kosong tampil `-`.
- Klik sel yang terisi membuka modal Edit/Hapus jadwal itu.
- **Modal Tambah/Edit Jadwal**: Hari, Kelas (dropdown), Mata Pelajaran (dropdown), Ruang (dropdown, default ruangan kelas), Guru (dropdown), Jam Ke Mulai, Jam Ke Selesai (angka 1-7).
- **Validasi bentrok** (pesan Bahasa Indonesia yang menyebut bentroknya): satu guru tidak boleh dua kelas di hari dan jam yang beririsan; satu kelas tidak boleh dua mapel di jam yang beririsan; satu ruangan tidak boleh dipakai dua kelas di jam yang beririsan; Jam Ke Selesai tidak boleh lebih kecil dari Jam Ke Mulai.

## Dashboard Guru

Layout `layouts/guru.blade.php` (susunan mockup, detail tampilan dicek ke folder Flutter): kontainer `max-w-7xl`, kartu header berisi badge **Portal Guru Pengajar**, judul **Dashboard Guru: {nama}**, dan tombol **Keluar ke Portal**. Di bawahnya kartu berisi tiga tombol tab. Tab aktif `bg-emerald-700 text-white shadow`, tab lain `text-slate-600 hover:bg-slate-100`.

**A. Jadwal Mengajar**: tabel Hari, Kelas (badge hijau), Mata Pelajaran, Ruangan, Status. Hanya jadwal milik guru yang login, diurut Senin sampai Jumat lalu jam. Baris hari ini diberi `bg-emerald-50` dan teks tebal. Kolom Status berisi "Hari Ini" atau "Aktif". Tampilkan jam (contoh `Jam 1-3, 07.30 - 09.45`) di kolom Mata Pelajaran atau kolom terpisah.

**B. Validasi Absensi (Scan)**: dua kolom.
- Kiri, kartu **Scan Barcode Kehadiran**: area `#reader` (`Html5QrcodeScanner`, `fps: 10`, `qrbox: 200`). Di atasnya status hari ini (Belum absen / Hadir jam berapa / Terlambat). Setelah scan sukses, kartu hijau **Scan Berhasil!** menampilkan **nama guru, tanggal, jam, lokasi (koordinat dan jarak dari sekolah), dan status**.
- Kanan, kartu **Kelas Hari Ini** (pengganti form mockup): daftar jadwal mengajar hari ini (kelas, mapel, jam) dengan tombol **Isi Absensi Siswa** per kelas, dan status "Sudah diisi" kalau absensi hari ini sudah tersimpan.

**Alur scan barcode:**
1. Kamera aktif lewat `html5-qrcode`.
2. Setelah kode terbaca, browser meminta lokasi (`getCurrentPosition`).
3. JavaScript mengirim `kode_barcode`, `latitude`, `longitude` lewat form POST (dengan CSRF) ke `AbsensiGuruController@simpan`.
4. Server memvalidasi berurutan dan menolak dengan pesan jelas kalau gagal: kode harus ada di tabel `guru` dan **milik guru yang sedang login**; guru belum absen hari ini; jarak ke sekolah (Haversine di server) tidak lebih dari `radius_meter`.
5. Kalau lolos, simpan ke `absensi_guru` dengan `metode = Scan`, status `Hadir`, atau `Terlambat` kalau lewat `jam_masuk`.
6. Kalau kamera atau lokasi ditolak pengguna, tampilkan pesan yang menjelaskan cara mengizinkannya.

```php
public function hitungJarak($lat1, $long1, $lat2, $long2)
{
    $radiusBumi = 6371000;
    $selisihLat = deg2rad($lat2 - $lat1);
    $selisihLong = deg2rad($long2 - $long1);
    $a = sin($selisihLat / 2) * sin($selisihLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($selisihLong / 2) * sin($selisihLong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $radiusBumi * $c;
}
```

**Halaman Absensi Siswa** (`guru/absensi-siswa/{jadwal}`): hanya bisa dibuka untuk jadwal milik guru itu **pada hari ini** (selain itu abort 403). Menampilkan kelas, mapel, tanggal, lalu daftar siswa kelas itu (No, NIS, Nama) dengan pilihan **Hadir / Sakit / Izin / Alpa** (radio, default Hadir) dan keterangan opsional. Di bawahnya kolom **Catatan Materi Pembelajaran** (dari mockup) dan tombol **Simpan Absensi**. Kalau sudah pernah diisi hari itu, form terisi dari data tersimpan dan simpan memperbarui, bukan membuat dobel.

**C. Rekap Absensi**: tabel Waktu, Keterangan, Status (badge), Catatan. Isinya riwayat absensi kehadiran guru itu sendiri (tanggal, jam, status, metode/alasan) dengan filter bulan dan pagination 15 baris. Kalau kosong: "Belum ada riwayat absensi."

## Dashboard Sekretaris

Layout `layouts/sekretaris.blade.php` (kontainer `max-w-4xl`, aksen amber): kartu header berisi badge **Portal Sekretaris Sekolah**, judul **Dashboard Sekretaris (Proxy Absensi)**, dan tombol **Keluar ke Portal**. Isi dibagi tiga tab seperti dashboard Guru: **A. Pengganti Absensi Guru**, **B. Jadwal Kelas**, **C. Laporan Absensi**.

**A. Pengganti Absensi Guru** (kartu pusat dengan ikon `fa-user-pen`, judul "Jadwal Kelas & Pengganti Absensi Guru", subjudul "Jika guru berhalangan hadir, sekretaris dapat menggantikan pencatatan absensi beserta alasannya."):
- Pilih **Guru Berhalangan** (dropdown).
- Setelah guru dipilih, tampil **jadwal guru itu hari ini** sebagai info (kelas yang terdampak). Hanya info, tidak disimpan.
- **Status Kehadiran**: Sakit, Izin, Alpa / Tanpa Keterangan (dan Hadir untuk guru yang lupa scan).
- **Tanggal**: hari ini, tidak bisa diubah (`readonly`).
- **Alasan Ketidakhadiran**: wajib diisi.
- Tombol **Simpan Absensi Pengganti Sekretaris** (`bg-amber-600`).
- Disimpan ke `absensi_guru` dengan `metode = Sekretaris`, `id_user_input` = sekretaris yang login, tanpa koordinat. Kalau guru itu hari ini sudah punya absensi `Scan` dengan status Hadir/Terlambat, tolak dengan pesan jelas. Kalau barisnya hasil input sekretaris, boleh diperbarui. Dicatat di log aktivitas.
- Di bawah form (tambahan): daftar guru yang **belum absen hari ini** dengan tombol cepat yang mengisi dropdown guru.

**B. Jadwal Kelas**: matriks jadwal yang sama dengan Admin, **hanya-baca**.

**C. Laporan Absensi**: laporan yang sama dengan Admin (tab Guru dan Siswa, cetak, CSV), **hanya-baca**.

## Aturan Tabel dan Form

- Tabel mengikuti mockup: `w-full text-left text-sm`, header `bg-slate-100 text-slate-700 uppercase text-xs` dengan sudut membulat di ujung, kontainer `overflow-x-auto`. Pagination `paginate(10)` (daftar absensi `paginate(15)`) dengan `->links()`.
- Kolom pencarian di atas tabel (`?cari=`), hasil tetap berpagination dan pencarian terbawa saat pindah halaman (`withQueryString()`).
- Data kosong: tampilkan pesan "Belum ada data" atau "Tidak ada data yang cocok", jangan tampilkan tabel kosong.
- Hapus selalu dengan konfirmasi (`confirm()` JavaScript) dan memakai form dengan `@method('DELETE')`.
- Setelah simpan/hapus, kembali ke halaman daftar dengan pesan flash yang ditampilkan lewat `partials/modal-pesan` (judul **Berhasil** / **Informasi**, tombol **Mengerti**). Error validasi ditampilkan sebagai kotak merah (`bg-rose-50 text-rose-700 rounded-xl`) di atas halaman berisi daftar pesan.
- Upload gambar: validasi `image|mimes:jpg,jpeg,png|max:2048`. Simpan ke `storage/app/public/{folder}` dengan nama unik. Saat gambar diganti atau data dihapus, file lama ikut dihapus.
- Hapus data yang masih dipakai (kelas yang masih punya siswa, guru atau mapel yang dipakai jadwal, ruangan yang dipakai kelas) harus ditolak dengan pesan jelas, jangan biarkan error foreign key muncul mentah.
- Pesan error teknis (exception) tidak boleh ditampilkan mentah ke pengguna.

## Database

Dibuat lewat **migration** dan data awal lewat **seeder** (contoh data dari mockup boleh dipakai). Primary key `id` bawaan Laravel, kolom status pakai `string` dengan nilai default, tanpa tabel lookup terpisah. Nama tabel dan kolom memakai Bahasa Indonesia, huruf kecil, dipisah garis bawah, dan model diberi `protected $table`.

### users
`id`, `name`, `username` (unik), `password` (bcrypt via `Hash::make`), `role` (`admin` / `guru` / `sekretaris`), `status` (default `Aktif`), timestamps. Seeder membuat 1 akun admin dan 1 akun sekretaris awal.

### pengaturan_sekolah
Satu baris saja (id selalu 1): `nama_sekolah`, `npsn`, `alamat`, `email`, `telepon`, `social_media`, `jam_operasional`, `logo`, `slogan`, `deskripsi_singkat`, `visi`, `misi`, `sejarah`, `nama_kepsek`, `foto_kepsek`, `judul_sambutan`, `sambutan`, `gambar_struktur`, `lat_sekolah`, `long_sekolah`, `radius_meter` (default 100), `jam_masuk` (default `07:00`).

### guru
`id`, `user_id` (nullable, FK `users`), `nama`, `nip` (nullable), `jenis` (`Guru` / `Staff`), `jabatan`, `mapel_utama`, `foto`, `tampil_publik` (boolean, default 1), `kode_barcode` (nullable, unik).

### ruangan
`id`, `kode` (unik, contoh `LAB-RPL`), `nama`, `kapasitas` (INT).

### kelas
`id`, `nama` (unik, contoh `XII RPL 1`), `id_ruangan` (nullable, FK), `id_wali_kelas` (nullable, FK `guru`).

### siswa
`id`, `nis` (unik), `nama`, `jenis_kelamin` (`L` / `P`), `id_kelas` (FK), `tahun_ajaran`.

### mapel
`id`, `kode` (unik), `nama`, `kelompok` (`Produktif` / `Normatif` / `Adaptif`).

### jam_pelajaran
`id`, `nama` (contoh `Carabika`, `Jam 1`, `Istirahat`), `jenis` (`Carabika` / `Pelajaran` / `Istirahat`), `jam_ke` (INT, nullable, 1 sampai 7 untuk jenis Pelajaran), `jam_mulai`, `jam_selesai`, `urutan`. Diisi seeder, tidak ada CRUD.

### jadwal
`id`, `id_kelas` (FK), `id_mapel` (FK), `id_guru` (FK), `id_ruangan` (FK), `hari` (Senin sampai Jumat), `jam_ke_mulai`, `jam_ke_selesai` (INT 1 sampai 7).

### absensi_guru
`id`, `id_guru` (FK), `tanggal` (DATE), `jam_masuk` (TIME, nullable), `status` (`Hadir` / `Terlambat` / `Izin` / `Sakit` / `Alpa`), `latitude`, `longitude`, `jarak_meter` (nullable), `metode` (`Scan` / `Sekretaris`), `alasan` (nullable), `id_user_input` (nullable, FK `users`). Kombinasi `id_guru` + `tanggal` **unik**.

### absensi_siswa
`id`, `id_jadwal` (FK), `id_siswa` (FK), `tanggal` (DATE), `status` (`Hadir` / `Sakit` / `Izin` / `Alpa`), `keterangan` (nullable), `id_guru_pengisi` (FK `guru`). Kombinasi `id_jadwal` + `id_siswa` + `tanggal` **unik**.

### jurnal_kelas
`id`, `id_jadwal` (FK), `tanggal` (DATE), `materi`, `id_guru` (FK). Kombinasi `id_jadwal` + `tanggal` **unik**.

### berita
`id`, `judul`, `kategori`, `isi`, `gambar`, `tanggal`, `status` (default `Draft`), `id_user`, timestamps.

### galeri
`id`, `judul`, `kategori` (`Fasilitas` / `Kegiatan`), `foto`, `tanggal`.

### jurusan
`id`, `kode`, `nama`, `deskripsi`, `ikon` (nama kelas Font Awesome).

### ekstrakurikuler
`id`, `nama`, `deskripsi`, `pembina`, `jadwal`, `gambar` (nullable).

### prestasi
`id`, `judul`, `tingkat` (Sekolah/Kota/Provinsi/Nasional/Internasional), `tahun`, `nama_peraih`, `deskripsi`, `gambar` (nullable).

### log_aktivitas
`id`, `id_user` (FK), `aksi`, `keterangan`, `created_at`.

## Keamanan

- Password disimpan dengan `Hash::make`, tidak pernah ditampilkan atau dicatat di log.
- Semua form memakai `@csrf`.
- Pengecekan role dilakukan di server (middleware), bukan hanya menyembunyikan menu.
- Guru hanya bisa membuka data dan jadwal miliknya sendiri. Selalu cek kepemilikan di controller sebelum menampilkan atau menyimpan.
- `kode_barcode` acak dan sulit ditebak, dan hanya dianggap sah kalau cocok dengan guru yang login.
- Data siswa dan absensi hanya bisa dilihat user yang sudah login sesuai haknya.
- File `.env` tidak ikut di-commit.

## Urutan Pengerjaan Modul

Kerjakan bertahap. Setelah tiap tahap, pastikan project bisa dijalankan (`php artisan serve`) tanpa error sebelum lanjut.

1. **Fondasi**: install Laravel, atur `.env`, timezone dan locale, semua migration + model, seeder (akun awal, `pengaturan_sekolah`, `jam_pelajaran`, data contoh), `storage:link`.
2. **Autentikasi dan kerangka**: `LoginController`, modal login, middleware `CekRole`, empat layout (publik, admin, guru, sekretaris), `partials/modal-pesan`, dashboard kosong tiap role.
3. **Halaman publik**: semua halaman publik sesuai `index.html`, dengan data dari seeder.
4. **Admin: pengaturan dan konten**: K. Pengaturan Sekolah, L. Profil & Sambutan, H. Berita, I. Galeri, M. Jurusan, N. Ekstrakurikuler, O. Prestasi.
5. **Admin: data master**: D. Ruangan, E. Mapel, C. Guru, B. Kelas, A. Siswa, J. Akun.
6. **Jadwal pelajaran**: F. Jadwal (matriks, modal, validasi bentrok).
7. **Barcode dan absensi guru**: menu Barcode Absensi + cetak, lalu scan kamera + lokasi di dashboard Guru.
8. **Dashboard Guru**: A. Jadwal Mengajar, halaman Absensi Siswa + Jurnal, C. Rekap.
9. **Dashboard Sekretaris**: pengganti absensi, jadwal hanya-baca, laporan.
10. **Penutup**: G. Laporan Absensi (cetak + CSV), P. Log Aktivitas, Dashboard Admin (paling akhir karena merangkum semua modul).

## Catatan Penting

- Struktur tabel hanya lewat migration. Kalau perlu kolom baru, buat migration baru, jangan ubah migration lama yang sudah dijalankan.
- Semua teks, pesan, dan label dalam Bahasa Indonesia.
- Konten sekolah (sambutan, visi misi, sejarah, alamat) diisi lewat halaman admin, jangan ditulis langsung di Blade.
- Tailwind lewat CDN cukup untuk project sekolah dan development. Kalau nanti dipublikasikan sungguhan, tanyakan dulu apakah mau diganti ke build Tailwind.
- **Belum dikerjakan (ditunda, jangan ditambahkan kecuali diminta):** login siswa, PPDB online, komentar berita, notifikasi WhatsApp/email, multi-bahasa, edit absensi tanggal lampau, upload video, ekspor Excel (.xlsx), laporan nilai, pengaturan jam pelajaran lewat admin, aplikasi mobile.
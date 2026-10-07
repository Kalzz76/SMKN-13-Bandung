<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    public function run(): void
    {
        $mapelList = [
            ['kode' => 'DPK', 'nama' => 'Dasar Program Keahlian', 'jenis' => 'Produktif'],
            ['kode' => 'BING', 'nama' => 'Bahasa Inggris', 'jenis' => 'Umum'],
            ['kode' => 'BSUN', 'nama' => 'Bahasa Sunda', 'jenis' => 'Umum'],
            ['kode' => 'SJRH', 'nama' => 'Sejarah', 'jenis' => 'Umum'],
            ['kode' => 'PABP', 'nama' => 'Pendidikan Agama dan Budi Pekerti', 'jenis' => 'Umum'],
            ['kode' => 'MATH', 'nama' => 'Matematika', 'jenis' => 'Umum'],
            ['kode' => 'PPAN', 'nama' => 'Pendidikan Pancasila', 'jenis' => 'Umum'],
            ['kode' => 'BPBK', 'nama' => 'Bimbingan dan Penyuluhan/Bimbingan Konseling', 'jenis' => 'Umum'],
            ['kode' => 'BIND', 'nama' => 'Bahasa Indonesia', 'jenis' => 'Umum'],
            ['kode' => 'IPAS', 'nama' => 'Ilmu Pengetahuan Alam dan Sosial', 'jenis' => 'Umum'],
            ['kode' => 'INFR', 'nama' => 'Informatika', 'jenis' => 'Umum'],
            ['kode' => 'PJOK', 'nama' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'jenis' => 'Umum'],
            ['kode' => 'SBDY', 'nama' => 'Seni Budaya', 'jenis' => 'Umum'],
            ['kode' => 'ATG', 'nama' => 'Agribisnis Tanaman', 'jenis' => 'Produktif'],
            ['kode' => 'ABA', 'nama' => 'Analisis Bahan Anorganik', 'jenis' => 'Produktif'],
            ['kode' => 'AKI', 'nama' => 'Analisis Kimia Instrumen', 'jenis' => 'Produktif'],
            ['kode' => 'STA', 'nama' => 'Sampling dan Teknik Analisis', 'jenis' => 'Produktif'],
            ['kode' => 'PKK', 'nama' => 'Produk Kreatif dan Kewirausahaan', 'jenis' => 'Produktif'],
            ['kode' => 'ABO', 'nama' => 'Analisis Bahan Organik', 'jenis' => 'Produktif'],
            ['kode' => 'MIKRO', 'nama' => 'Mikrokontroler', 'jenis' => 'Produktif'],
            ['kode' => 'BOBA', 'nama' => 'Botani dan Organisme / Biologi Terapan', 'jenis' => 'Produktif'],
            ['kode' => 'BJPG', 'nama' => 'Bahasa Jepang', 'jenis' => 'Umum'],
            ['kode' => 'KKA', 'nama' => 'Kromatografi dan Kimia Analitik', 'jenis' => 'Produktif'],
            ['kode' => 'PKPJ', 'nama' => 'Pemasangan dan Konfigurasi Perangkat Jaringan', 'jenis' => 'Produktif'],
            ['kode' => 'ASJ', 'nama' => 'Administrasi Sistem Jaringan', 'jenis' => 'Produktif'],
            ['kode' => 'TJKN', 'nama' => 'Teknologi Jaringan Kabel/Nirkabel', 'jenis' => 'Produktif'],
            ['kode' => 'PPJ', 'nama' => 'Perencanaan dan Pengamatan Jaringan', 'jenis' => 'Produktif'],
            ['kode' => 'PKK/KIK', 'nama' => 'Produk Kreatif dan Kewirausahaan', 'jenis' => 'Produktif'],
            ['kode' => 'CYBER SECURITY', 'nama' => 'Keamanan Siber', 'jenis' => 'Produktif'],
            ['kode' => 'MIKACAD CISCO2', 'nama' => 'MikroTik/Cisco', 'jenis' => 'Produktif'],
            ['kode' => 'PBTM', 'nama' => 'Pemograman Berbasis Teks', 'jenis' => 'Produktif'],
            ['kode' => 'PPB', 'nama' => 'Pemograman Perangkat Bergerak', 'jenis' => 'Produktif'],
            ['kode' => 'BASDAT', 'nama' => 'Basis Data', 'jenis' => 'Produktif'],
            ['kode' => 'MATDIS', 'nama' => 'Matematika Diskrit', 'jenis' => 'Produktif'],
            ['kode' => 'PW', 'nama' => 'Pemrograman Web', 'jenis' => 'Produktif'],
            ['kode' => 'FW/API', 'nama' => 'Framework/API', 'jenis' => 'Produktif'],
            ['kode' => 'PERSIAPAN TKA', 'nama' => 'Persiapan TKA', 'jenis' => 'Umum'],
        ];

        foreach ($mapelList as $m) {
            Mapel::updateOrCreate(
                ['kode' => $m['kode']],
                [
                    'nama' => $m['nama'],
                    'jenis' => $m['jenis'],
                ]
            );
        }
    }
}

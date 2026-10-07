<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $daftarGuruList = [
            'OMAN SOMANA, M.Pd.',
            'DADAN RUKMA DIAN DAWAN, S.Pd',
            'POPONG WARIATI, S.Pd.',
            'UJANG SUHARA, S.Pd.',
            'Dra. MIMY ARDIANY, M.Pd',
            'SARINAH Br GINTING, M.Pd.',
            'TAUFIK HIDAYAT,M.M.Pd',
            'RITA HARTATI, S.Pd, M.T.',
            'ADE HARTONO, S.Pd.',
            'TITA HERIYANTI, S.Pd.',
            'OCTAVINA SOPAMENA, M.Pd.',
            'LIA YULIANTI, S.Pd',
            'SANTIKA, M.Pd',
            'RINA DARYANI, M.Pd.',
            'Dra. RAHMI DALILAH FITRIANNI',
            'SYAFITRI K ARIEF, S.Pd, MT',
            'ADIWIGUNA, S.Pd.',
            'RANI RABIUSSANI, M.Pd.',
            'SUDARMI, M.Pd.',
            'IAH ROBIAH, S.Pd.Kim.',
            'MASPURI ANDEWI, S.Kom',
            'RUHYA, S.Ag, M.M.Pd',
            'MAYA KUSMAYANTI, M.Pd',
            'DINI KAROMNA, S.Pd.',
            'NOFA NIRAWATI, S.Pd, M.T',
            'HASAN AS\'ARI, M.Kom',
            'CECEP SURYANA, S.Si',
            'NINA DEWI KOSWARA, S.Pd.',
            'INA MARINA, S.T.,S.Pd.',
            'DANTY, S.Pd.',
            'SUGIYATMI, S.Si',
            'ULI SOLIHAT KAMALUDDIN, S.Si.',
            'ATEP AULIA RAHMAN, S.T.,M.Kom.',
            'ENDANG SUNANDAR, S.Pd. M.PKim',
            'HAZAR NURBANI, M.Pd.',
            'TINI ROSMAYANI, S.Si.',
            'R. PRIYO HADISURYO, S.ST',
            'NOGI MUHARAM, S.Kom.',
            'EVA ZULVA, S.Kom,i',
            'NENENG SUHARTINI, S.Si.,S.Pd.',
            'HALIDA FARHANI,S.Psi',
            'NUR FAUZIYAH RAHMAWATI,S.Pd',
            'MUCHAMAD HARRY ISMAIL, S.Tr.Kom,M.M.',
            'ERMAWATI, M.Kom',
            'KIKI AIMA MU\'MINA, S.Pd',
            'SAMSUDIN S.Ag.',
            'YENI MEILINA, S.Pd.',
            'KANIA DEWI WALUYA, S.ST',
            'RINI DWI WAHYUNI,S.Pd',
            'RUKMANA,S.Pd.I',
            'DESTA MULYANTI,S.Sn',
            'INDIRA SARI PAPUTUNGAN, M.Ed',
            'NADIA AFRILIANI, S.Pd',
            'SABILA FAUZIYYA, S.Kom',
            'JAYA SUMPENA, S.ST, M.Kom',
            'TUBAGUS SAPUTRA, S.Pd',
            'DENA HANDRIANA, M.Pd',
            'DEDI EPENDI, S.Kom',
            'REGINA FITRIE, S.Pd',
            'ARIANTONIUS SAGALA, S.Kom',
            'PRATIWI, S.Si',
            'NURUL DININGSIH, S.Hum',
            'ETI ARIESANTI,S.Pd',
            'NURLAELA, S.H',
            'MELI NOVITA M.Pd.',
            'ELA NURLAELA, S.Pd',
            'TESSA EKA YUNIAR, S.Pd',
            'SHENDY ANTARIKSA,S.Hum',
            'APRILIANI HARDIYANTI HARIYONO, S.Pd',
            'SUKMAWIDI, S.Pd',
            'FERTIKA, S.Pd',
            'ANNISA INTIKARUSDIANSARI, S.Pd',
            'ODANG SUPRIATNA, S.E',
            'ABDURRAHMAN RAJIB, S.Pd',
        ];

        $fotoMap = [
            'HASAN AS\'ARI, M.Kom' => 'guru/hasan.webp',
            'MAYA KUSMAYANTI, M.Pd' => 'guru/maya.webp',
            'NINA DEWI KOSWARA, S.Pd.' => 'guru/nina.webp',
            'SANTIKA, M.Pd' => 'guru/santika.webp',
        ];

        $mapelMapping = [
            'ABDURRAHMAN RAJIB, S.Pd' => ['PJOK'],
            'ADE HARTONO, S.Pd.' => ['ATG'],
            'ADIWIGUNA, S.Pd.' => ['SBDY'],
            'ANNISA INTIKARUSDIANSARI, S.Pd' => ['IPAS'],
            'APRILIANI HARDIYANTI HARIYONO, S.Pd' => ['SJRH'],
            'ARIANTONIUS SAGALA, S.Kom' => ['PW'],
            'CECEP SURYANA, S.Si' => ['ABO'],
            'DADAN RUKMA DIAN DAWAN, S.Pd' => ['MATH'],
            'DANTY, S.Pd.' => ['AKI'],
            'DENA HANDRIANA, M.Pd' => ['MATH', 'MATDIS', 'STA'],
            'DESTA MULYANTI, S.Sn' => ['BSUN'],
            'DINI KAROMNA, S.Pd.' => ['ABA', 'AKI'],
            'Dra. MIMY ARDIANY, M.Pd' => ['IPAS', 'PKK'],
            'Dra. RAHMI DALILAH FITRIANNI' => ['IPAS', 'PKK'],
            'ELA NURLAELA, S.Pd' => ['ABA'],
            'ENDANG SUNANDAR, S.Pd. M.PKim' => ['ABA', 'BOBA'],
            'ERMAWATI, M.Kom' => ['DPK', 'PKK'],
            'ETI ARIESANTI, S.Pd' => ['MATH'],
            'EVA ZULVA, S.Kom.I' => ['BPBK'],
            'FERTIKA, S.Pd' => ['MIKRO'],
            'HALIDA FARHANI, S.Psi' => ['BPBK'],
            'HASAN AS\'ARI, M.Kom' => ['PPJ'],
            'HAZAR NURBANI, M.Pd.' => ['BPBK', 'DPK', 'PPB'],
            'IAH ROBIAH, S.Pd.Kim.' => ['AKI'],
            'INA MARINA, S.T., S.Pd.' => ['ABO', 'AKI'],
            'INDIRA SARI PAPUTUNGAN, M.Ed' => ['BING'],
            'JAYA SUMPENA, S.ST, M.Kom' => ['DPK', 'INFR'],
            'KANIA DEWI WALUYA, S.ST' => ['INFR'],
            'KIKI AIMA MU\'MINA, S.Pd' => ['DPK'],
            'LIA YULIANTI, S.Pd' => ['BING', 'DPK'],
            'MASPURI ANDEWI, S.Kom' => ['INFR', 'PBTM', 'PKK'],
            'MAYA KUSMAYANTI, M.Pd' => ['PPAN'],
            'MELI NOVITA, M.Pd.' => ['BIND'],
            'MUCHAMAD HARRY ISMAIL, S.Tr.Kom, M.M.' => ['ASJ'],
            'NADIA AFRILIANI, S.Pd' => ['MATH'],
            'NENENG SUHARTINI, S.Si., S.Pd.' => ['MIKRO'],
            'NOFA NIRAWATI, S.Pd, M.T' => ['MATH', 'MATDIS', 'STA'],
            'NOGI MUHARAM, S.Kom.' => ['DPK', 'TJKN'],
            'NURLAELA, S.H' => ['PPAN'],
            'NURUL DININGSIH, S.Hum' => ['BIND'],
            'OCTAVINA SOPAMENA, M.Pd.' => ['ATG'],
            'ODANG SUPRIATNA, S.E' => ['IPAS'],
            'OMAN SOMANA, M.Pd.' => ['IPAS'],
            'POPONG WARIATI, S.Pd.' => ['AKI', 'PW'],
            'PRATIWI, S.Si' => ['BASDAT', 'INFR', 'KKA'],
            'R. PRIYO HADISURYO, S.ST' => ['PKPJ'],
            'RANI RABIUSSANI, M.Pd.' => ['BSUN'],
            'REGINA FITRIE, S.Pd' => ['BIND'],
            'RINA DARYANI, M.Pd.' => ['BIND'],
            'RINI DWI WAHYUNI, S.Pd' => ['BJPG'],
            'RITA HARTATI, S.Pd, M.T.' => ['DPK', 'KKA'],
            'RUHYA, S.Ag, M.M.Pd' => ['PABP'],
            'RUKMANA, S.Pd.I' => ['PABP'],
            'SABILA FAUZIYYA, S.Kom' => ['DPK', 'INFR', 'PBTM'],
            'SAMSUDIN, S.Ag.' => ['PABP'],
            'SARINAH Br GINTING, M.Pd.' => ['BIND', 'MATH', 'ABO', 'AKI'],
            'SHENDY ANTARIKSA, S.Hum' => ['BING'],
            'SUDARMI, M.Pd.' => ['SJRH'],
            'SUGIYATMI, S.Si' => ['AKI'],
            'SUKMAWIDI, S.Pd' => ['SJRH', 'PPAN'],
            'SYAFITRI K ARIEF, S.Pd, MT' => ['ABA', 'BOBA'],
            'TAUFIK HIDAYAT, M.M.Pd' => ['PJOK'],
            'TESSA EKA YUNIAR, S.Pd' => ['ABO'],
            'TINI ROSMAYANI, S.Si.' => ['BOBA'],
            'TITA HERIYANTI, S.Pd.' => ['DPK'],
            'TUBAGUS SAPUTRA, S.Pd' => ['SJRH', 'PPAN'],
            'UJANG SUHARA, S.Pd.' => ['BING'],
            'ULI SOLIHAT KAMALUDDIN, S.Si.' => ['DPK'],
            'YENI MEILINA, S.Pd.' => ['PJOK'],
            'SANTIKA, M.Pd' => ['MIKRO'],
            'NINA DEWI KOSWARA, S.Pd.' => ['MIKRO'],
            'ATEP AULIA RAHMAN, S.T., M.Kom.' => ['MIKACAD CISCO2', 'CYBER SECURITY'],
            'NUR FAUZIYAH RAHMAWATI, S.Pd' => ['BPBK'],
            'DEDI EPENDI, S.Kom' => ['ASJ'],
        ];

        $allMapels = Mapel::all()->keyBy('kode');
        $mapelLookup = [];
        foreach ($mapelMapping as $gNama => $kodes) {
            $cleanKey = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($gNama));
            $mapelLookup[$cleanKey] = $kodes;
        }

        $usedUsernames = ['admin', 'sekretaris'];
        $createdGuruIds = [];
        $passwordHash = Hash::make('guru123');

        foreach ($daftarGuruList as $index => $nama) {
            // Tentukan username otomatis murni dari nama
            $clean = preg_replace('/^(dra\.|dr\.|ir\.|drs\.|r\.)\s+/i', '', trim($nama));
            $parts = preg_split('/[\s,\.]+/', $clean);
            $base = strtolower($parts[0] ?? 'guru');
            if (strlen($base) < 3 && isset($parts[1])) {
                $base = strtolower($parts[1]);
            }
            $username = $base;
            $counter = 1;
            while (in_array($username, $usedUsernames)) {
                $counter++;
                $username = $base . $counter;
            }
            $usedUsernames[] = $username;

            // Buat atau perbarui User
            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'name' => $nama,
                    'password' => $passwordHash,
                    'role' => 'guru',
                    'status' => 'Aktif',
                ]
            );

            // NIP format 18 digit
            $nipTahunLahir = 1970 + ($index % 30);
            $nipBulanLahir = sprintf('%02d', 1 + ($index % 12));
            $nipTahunMasuk = 2000 + ($index % 24);
            $nipUrut = sprintf('%04d', $index + 1);
            $nip = "{$nipTahunLahir}{$nipBulanLahir}01{$nipTahunMasuk}01{$nipUrut}";

            // Tentukan mapel berdasarkan mapping
            $cleanNama = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($nama));
            $kodes = $mapelLookup[$cleanNama] ?? [];
            $mapelIds = [];
            $mapelNames = [];
            foreach ($kodes as $kode) {
                if (isset($allMapels[$kode])) {
                    $m = $allMapels[$kode];
                    $mapelIds[] = $m->id;
                    $mapelNames[] = $m->nama;
                }
            }

            $primaryMapelId = $mapelIds[0] ?? null;
            $mapelUtama = !empty($mapelNames) ? implode(', ', $mapelNames) : 'Tenaga Pendidik';

            // Buat atau perbarui Guru
            $guru = Guru::updateOrCreate(
                ['nama' => $nama],
                [
                    'user_id' => $user->id,
                    'nip' => $nip,
                    'jenis' => 'Guru',
                    'jabatan' => 'Tenaga Pendidik',
                    'id_mapel' => $primaryMapelId,
                    'mapel_utama' => $mapelUtama,
                    'foto' => $fotoMap[$nama] ?? null,
                    'tampil_publik' => true,
                    'kode_barcode' => 'BARCODE-' . sprintf('%03d', $index + 1),
                ]
            );

            if (!empty($mapelIds)) {
                $guru->mapels()->sync($mapelIds);
            }

            $createdGuruIds[] = $guru->id;
        }

        // Hapus seeder guru lama yang namanya tidak ada di daftar baru (dengan penanganan relasi)
        $oldGuruIds = Guru::whereNotIn('id', $createdGuruIds)->pluck('id');
        if ($oldGuruIds->isNotEmpty()) {
            $defaultGuruId = $createdGuruIds[0] ?? null;
            \App\Models\Kelas::whereIn('id_wali_kelas', $oldGuruIds)->update(['id_wali_kelas' => $defaultGuruId]);
            \App\Models\Jadwal::whereIn('id_guru', $oldGuruIds)->update(['id_guru' => $defaultGuruId]);
            \App\Models\AbsensiGuru::whereIn('id_guru', $oldGuruIds)->delete();
            \App\Models\AbsensiSiswa::whereIn('id_guru_pengisi', $oldGuruIds)->update(['id_guru_pengisi' => $defaultGuruId]);
            \App\Models\JurnalKelas::whereIn('id_guru', $oldGuruIds)->update(['id_guru' => $defaultGuruId]);
            Guru::whereIn('id', $oldGuruIds)->delete();
        }

        // Bersihkan akun user guru lama yang sudah tidak memiliki profil guru
        $activeUserIds = Guru::pluck('user_id')->filter();
        User::where('role', 'guru')->whereNotIn('id', $activeUserIds)->delete();
    }
}

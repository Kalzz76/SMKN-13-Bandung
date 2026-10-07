<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanSekolah extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_sekolah';

    protected $guarded = ['id'];

    protected $casts = [
        'struktur_organisasi' => 'array',
    ];

    public static function defaultStruktur(): array
    {
        return [
            'tahun_pelajaran' => '2025 - 2026',
            'kepala_sekolah' => 'Agus Nugroho, S.Pd., M.T.',
            'komite_sekolah' => 'Melinda T. Dadyana',
            'pendamping_sekolah' => 'Dra. Euis Rohaeti Purnamawati, M.M.',
            'wakasek_kurikulum' => 'NINA DEWI KOSWARA, S.Pd.',
            'koor_tefa' => 'ENDANG SUNANDAR, S.Pd. M.PKim',
            'sekretaris_blud' => 'Windy Novia Anggraeni, S.Si.',
            'bendahara_blud' => 'ANNISA INTIKARUSDIANSARI, S.Pd',
            'staf_kurikulum' => 'Naris Hirawati, S.Pd., M.T.',
            'staf_pbm' => 'Diri Koswara, S.Pd.',
            'staf_adm_akademik' => 'DENA HANDRIANA, M.Pd',
            'kepala_perpus' => 'Nina Haryani, M.Pd.',
            'wakasek_kesiswaan' => 'MAYA KUSMAYANTI, M.Pd',
            'staf_pembiasaan' => 'NURUL DININGSIH, S.Hum',
            'staf_tatib' => 'Nurhasis, S.H.',
            'staf_osis' => 'TESSA EKA YUNIAR, S.Pd',
            'staf_ekskul' => 'Salmawati, S.Pd.',
            'staf_karakter' => 'Dianny Annartisa, S.Hum.',
            'koor_bk' => 'Esa Zuhra, S.Kons.',
            'wakasek_sarpras' => 'HASAN AS\'ARI, M.Kom',
            'staf_gedung' => 'ADIWIGUNA, S.Pd.',
            'staf_lab_kimia' => 'POPONG WARIATI, S.Pd.',
            'staf_lab_rpl' => 'Alianturi Tri Sugata, S.T.',
            'koor_lh' => 'ODANG SUPRIATNA, S.E',
            'koor_kopmynta' => 'INDIRA SARI PAPUTUNGAN, M.Ed',
            'sekretaris_kopmynta' => 'FERTIKA, S.Pd',
            'bendahara_kopmynta' => 'NADIA AFRILIANI, S.Pd',
            'wakasek_hubinmas' => 'SANTIKA, M.Pd',
            'koor_bkk' => 'Siti Salbiah K., S.Si., Gr.',
            'staf_bkk' => 'Ropi Mulyana, S.Kom.',
            'staf_hubin_1' => 'NUR FAUZIYAH RAHMAWATI,S.Pd',
            'staf_hubin_2' => 'KIKI AIMA MU\'MINA, S.Pd',
            'staf_magang' => 'APRILIANI HARDIYANTI HARIYONO, S.Pd',
            'koor_spw' => 'Darma Syariatna, S.E.',
            'koor_tu' => 'Ali Fauzan, S.Ip., M.M.',
            'tenaga_adm' => 'Tenaga Administrasi',
            'wakil_mutu' => 'Reni Rokhansari, M.Pd.',
            'pj_aset_digital' => 'PJ Pengelola Aset Digital Sekolah',
            'staf_mutu' => 'REGINA FITRIE, S.Pd',
            'staf_sdm' => 'Dania Mulyani, S.E.',
            'kaprog_kimia' => 'TINI ROSMAYANI, S.Si.',
            'kaprog_tjkt' => 'DEDI EPENDI, S.Kom',
            'kaprog_pplg' => 'JAYA SUMPENA, S.ST, M.Kom',
            'foto_kepala_sekolah' => null,
            'foto_komite_sekolah' => null,
            'foto_pendamping_sekolah' => null,
            'foto_wakasek_kurikulum' => null,
            'foto_wakasek_kesiswaan' => null,
            'foto_wakasek_sarpras' => null,
            'foto_wakasek_hubinmas' => null,
            'foto_koor_tu' => null,
            'foto_wakil_mutu' => null,
            'foto_kaprog_kimia' => null,
            'foto_kaprog_tjkt' => null,
            'foto_kaprog_pplg' => null,
        ];
    }

    public function getStrukturAttribute(): array
    {
        $default = self::defaultStruktur();
        $tersimpan = $this->struktur_organisasi ?? [];
        return array_merge($default, is_array($tersimpan) ? $tersimpan : []);
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!empty($this->logo)) {
            if (\Illuminate\Support\Str::startsWith($this->logo, ['http://', 'https://'])) {
                return $this->logo;
            }
            if (file_exists(public_path('storage/' . $this->logo)) || file_exists(storage_path('app/public/' . $this->logo))) {
                return asset('storage/' . $this->logo);
            }
            if (file_exists(public_path($this->logo))) {
                return asset($this->logo);
            }
            if (file_exists(public_path('Assets/' . basename($this->logo)))) {
                return asset('Assets/' . basename($this->logo));
            }
            if (file_exists(public_path('assets/' . basename($this->logo)))) {
                return asset('assets/' . basename($this->logo));
            }
        }

        if (file_exists(public_path('Assets/LOGOS.png'))) {
            return asset('Assets/LOGOS.png');
        }
        if (file_exists(public_path('Assets/LOGOS.jpg'))) {
            return asset('Assets/LOGOS.jpg');
        }
        if (file_exists(public_path('assets/LOGOS.png'))) {
            return asset('assets/LOGOS.png');
        }

        return null;
    }
}

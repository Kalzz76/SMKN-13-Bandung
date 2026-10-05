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
            'wakasek_kurikulum' => 'Nina Dwi Kurniawati, S.Pd.',
            'koor_tefa' => 'Endang Gunandar, S.Pd., M.M.Pd.',
            'sekretaris_blud' => 'Windy Novia Anggraeni, S.Si.',
            'bendahara_blud' => 'Annisa Isti Karunia Insani, S.Pd.',
            'staf_kurikulum' => 'Naris Hirawati, S.Pd., M.T.',
            'staf_pbm' => 'Diri Koswara, S.Pd.',
            'staf_adm_akademik' => 'Dera Herdiana, M.Pd.',
            'kepala_perpus' => 'Nina Haryani, M.Pd.',
            'wakasek_kesiswaan' => 'Maya Kusmayanti, M.Pd.',
            'staf_pembiasaan' => 'Nurul Shiroqoth, S.Hum.',
            'staf_tatib' => 'Nurhasis, S.H.',
            'staf_osis' => 'Tovan Eka Yuniar, S.Pd.',
            'staf_ekskul' => 'Salmawati, S.Pd.',
            'staf_karakter' => 'Dianny Annartisa, S.Hum.',
            'koor_bk' => 'Esa Zuhra, S.Kons.',
            'wakasek_sarpras' => 'Hasan Basari, M.Kom.',
            'staf_gedung' => 'Adi Wiguna, S.Pd.',
            'staf_lab_kimia' => 'Popong Mariati, S.Pd.',
            'staf_lab_rpl' => 'Alianturi Tri Sugata, S.T.',
            'koor_lh' => 'Odang Supriatna, S.E.',
            'koor_kopmynta' => 'Indra Dwi Payukungan, M.Ed., Gr.',
            'sekretaris_kopmynta' => 'Festika, S.Pd.',
            'bendahara_kopmynta' => 'Nadia Afriani, S.Pd.',
            'wakasek_hubinmas' => 'Sartika, M.Pd.',
            'koor_bkk' => 'Siti Salbiah K., S.Si., Gr.',
            'staf_bkk' => 'Ropi Mulyana, S.Kom.',
            'staf_hubin_1' => 'Nur Fauziah, S.Pd.',
            'staf_hubin_2' => 'Kiki Alma Nurtina, S.Pd.',
            'staf_magang' => 'Apriliani Hardyanti N., S.Pd.',
            'koor_spw' => 'Darma Syariatna, S.E.',
            'koor_tu' => 'Ali Fauzan, S.Ip., M.M.',
            'tenaga_adm' => 'Tenaga Administrasi',
            'wakil_mutu' => 'Reni Rokhansari, M.Pd.',
            'pj_aset_digital' => 'PJ Pengelola Aset Digital Sekolah',
            'staf_mutu' => 'Regina Fitria, S.Pd.',
            'staf_sdm' => 'Dania Mulyani, S.E.',
            'kaprog_kimia' => 'Titin Rohmayati, S.Si.',
            'kaprog_tjkt' => 'Dedi Supendi, S.Kom.',
            'kaprog_pplg' => 'Jaya Sumpena, M.Kom.',
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
}

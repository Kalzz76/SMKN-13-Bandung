<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanIzinGuru extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izin_guru';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_pada' => 'datetime',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function userPenyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}

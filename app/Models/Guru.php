<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'id_wali_kelas');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_guru');
    }

    public function absensi()
    {
        return $this->hasMany(AbsensiGuru::class, 'id_guru');
    }
}

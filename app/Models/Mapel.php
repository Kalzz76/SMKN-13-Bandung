<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapel';

    protected $guarded = ['id'];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_mapel');
    }

    public function guru()
    {
        return $this->hasMany(Guru::class, 'id_mapel');
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'mapel_id', 'guru_id')->withTimestamps();
    }
}

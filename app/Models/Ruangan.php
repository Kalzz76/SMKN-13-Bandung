<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $guarded = ['id'];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_ruangan');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_ruangan');
    }
}

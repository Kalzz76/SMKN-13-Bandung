<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $guarded = ['id'];

    protected $casts = [
        'struktur' => 'array',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'id_wali_kelas');
    }

    public function getTingkatAttribute(): ?string
    {
        return preg_match('/^\s*(XIII|XII|XI|X)\b/i', (string) $this->nama, $cocok)
            ? strtoupper($cocok[1])
            : null;
    }

    public function getKelompokPembiasaanAttribute(): ?string
    {
        return match ($this->tingkat) {
            'XII', 'XIII' => 'A',
            'X', 'XI' => 'B',
            default => null,
        };
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_kelas');
    }
}

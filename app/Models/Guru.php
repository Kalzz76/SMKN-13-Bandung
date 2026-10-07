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

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel', 'guru_id', 'mapel_id')->withTimestamps();
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

    public function getDaftarBadgeMapelAttribute(): array
    {
        $badges = [];

        if ($this->relationLoaded('mapels') ? $this->mapels->isNotEmpty() : $this->mapels()->exists()) {
            foreach ($this->mapels as $m) {
                $badges[] = [
                    'kode' => $m->kode ?: $m->nama,
                    'nama' => $m->nama
                ];
            }
        } elseif ($this->relationLoaded('mapel') ? $this->mapel : $this->mapel()->first()) {
            $m = $this->mapel;
            $badges[] = [
                'kode' => $m->kode ?: $m->nama,
                'nama' => $m->nama
            ];
        } elseif (!empty($this->mapel_utama)) {
            static $mapelsCache = null;
            if ($mapelsCache === null) {
                $mapelsCache = Mapel::all()->keyBy(function($m) {
                    return strtolower(trim($m->nama));
                });
            }
            $parts = explode(',', $this->mapel_utama);
            foreach ($parts as $p) {
                $trimmed = trim($p);
                if (!$trimmed) continue;
                $matched = $mapelsCache->get(strtolower($trimmed));
                $badges[] = [
                    'kode' => $matched?->kode ?: $trimmed,
                    'nama' => $matched?->nama ?: $trimmed
                ];
            }
        } elseif (!empty($this->jabatan)) {
            $badges[] = [
                'kode' => $this->jabatan,
                'nama' => $this->jabatan
            ];
        } else {
            $badges[] = [
                'kode' => 'Pengajar',
                'nama' => 'Tenaga Pengajar'
            ];
        }

        return $badges;
    }
}

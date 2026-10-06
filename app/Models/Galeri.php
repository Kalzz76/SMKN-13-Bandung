<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';

    protected $guarded = ['id'];

    public function fotos()
    {
        return $this->hasMany(GaleriFoto::class, 'galeri_id')->orderBy('urutan')->orderBy('id');
    }

    public function getFotoUtamaAttribute()
    {
        if ($this->foto) {
            return $this->foto;
        }

        $pertama = $this->fotos->first();
        return $pertama ? $pertama->foto : null;
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaleriFoto extends Model
{
    use HasFactory;

    protected $table = 'galeri_foto';

    protected $guarded = ['id'];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class, 'galeri_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return Galeri::formatFotoUrl($this->foto);
    }
}

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

    public static function formatFotoUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (file_exists(public_path('storage/' . $path)) || file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('Assets/' . basename($path)))) {
            return asset('Assets/' . basename($path));
        }

        return asset('storage/' . $path);
    }

    public function getFotoUtamaUrlAttribute(): ?string
    {
        return self::formatFotoUrl($this->foto_utama);
    }

    public function getFotoUrlAttribute(): ?string
    {
        return self::formatFotoUrl($this->foto);
    }
}


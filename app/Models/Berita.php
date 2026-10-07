<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function getGambarUrlAttribute(): ?string
    {
        if (empty($this->gambar)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($this->gambar, ['http://', 'https://'])) {
            return $this->gambar;
        }

        if (file_exists(public_path('storage/' . $this->gambar)) || file_exists(storage_path('app/public/' . $this->gambar))) {
            return asset('storage/' . $this->gambar);
        }

        if (file_exists(public_path($this->gambar))) {
            return asset($this->gambar);
        }

        if (file_exists(public_path('Assets/' . basename($this->gambar)))) {
            return asset('Assets/' . basename($this->gambar));
        }

        return asset('storage/' . $this->gambar);
    }
}

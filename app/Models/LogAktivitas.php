<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public static function catat($aksi, $keterangan)
    {
        return self::create([
            'id_user' => auth()->id(),
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'created_at' => now(),
        ]);
    }
}

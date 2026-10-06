<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPembiasaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pembiasaan';

    protected $guarded = ['id'];

    protected $casts = [
        'sesi' => 'integer',
    ];

    public static function sesiMingguIni(?Carbon $tanggal = null): int
    {
        $tanggal = $tanggal ?? Carbon::now('Asia/Jakarta');

        return $tanggal->isoWeek() % 2 === 1 ? 1 : 2;
    }

    public static function aturanHari(string $hari)
    {
        return static::where('hari', $hari)->get();
    }
}

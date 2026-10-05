<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_guru';

    protected $guarded = ['id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    public function userInput()
    {
        return $this->belongsTo(User::class, 'id_user_input');
    }
}

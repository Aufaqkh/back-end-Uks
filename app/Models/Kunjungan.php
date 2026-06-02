<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Kunjungan.php
class Kunjungan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_pasien',
        'umur',
        'keluhan',
        'status_jemput',
        'lokasi_jemput', // 🔥 INI YANG TADI KETINGGALAN BRE 🔥
        'status',
        'catatan_dokter'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

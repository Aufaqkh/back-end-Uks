<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id', 'petugas_id', 'kunjungan_id', 'pesan', 'status'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function petugas()
    {
        return $this->belongsTo(PetugasUks::class);
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
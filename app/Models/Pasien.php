<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien'; // Mengunci agar Laravel membaca tabel 'pasien'

    protected $fillable = [
        'user_id',
        'nisn',
        'nama_lengkap',
        'kelas',
        'no_hp', // ← 🔥 INI DIA TERSANGKANYA, WAJIB ADA DI SINI BRE! 🔥
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }
}

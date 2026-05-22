<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
protected $table = 'pasien'; // ← TAMBAH INI!
    protected $fillable = [
        'user_id', 'nisn', 'nama_lengkap', 'kelas', 'jurusan', 'no_hp'
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
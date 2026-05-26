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
        'name',
        'kelas',
        'no_hp'
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

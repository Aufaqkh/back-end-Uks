<?php

namespace App\Models;

use App\Models\Pasien;
use App\Models\PetugasUks;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name',
    'email',
    'nisn',  // ← NISN bukan NIS
    'role',
    'kelas', // ← 🔥 UDAH DITAMBAHIN DI SINI BRE!
    'password'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELATIONSHIPS
    public function pasien()
    {
        return $this->hasOne(Pasien::class);
    }

    public function petugasUks()
    {
        return $this->hasOne(PetugasUks::class);
    }

    public function kunjungans()  // ← INI DI DALAM CLASS!
    {
        return $this->hasMany(Kunjungan::class);
    }

    // AUTH HELPER
    public function isPetugasOrAdmin(): bool
    {
        return in_array($this->role, ['petugas', 'admin']);
    }
}

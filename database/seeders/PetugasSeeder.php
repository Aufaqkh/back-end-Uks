<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PetugasUks;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        // Petugas UKS
        $petugas = User::create([
            'name' => 'Petugas UKS',
            'email' => 'petugas@uks.com',
            'nisn' => null,
            'role' => 'petugas',
            'password' => Hash::make('password123'),
        ]);

        PetugasUks::create([
            'user_id' => $petugas->id,
            'nama_lengkap' => 'Petugas UKS',
            'nip' => 'NIP-001',
            'no_hp' => '08123456789'
        ]);

        // Admin UKS
        $admin = User::create([
            'name' => 'Admin UKS',
            'email' => 'admin@uks.com',
            'nisn' => null,
            'role' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        PetugasUks::create([
            'user_id' => $admin->id,
            'nama_lengkap' => 'Admin UKS',
            'nip' => 'NIP-000',
            'no_hp' => '08123456788'
        ]);
    }
}

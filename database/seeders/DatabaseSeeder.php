<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🔥 Cuma bikin 1 Akun Admin Permanen 🔥
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'nisn' => 'admin-123', // NISN Khusus Admin
            'password' => bcrypt('admin1234'), // Password yang aman
            'role' => 'admin'
        ]);
    }
}

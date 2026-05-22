<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;  // ← IMPORT INI!

class ObatSeeder extends Seeder
{
    public function run()
    {
        Obat::create(['nama' => 'Paracetamol 500mg', 'stok' => 100, 'harga' => 5000]);
        Obat::create(['nama' => 'Amoxicillin 500mg', 'stok' => 50, 'harga' => 15000]);
        Obat::create(['nama' => 'Omeprazole 20mg', 'stok' => 75, 'harga' => 8000]);
        Obat::create(['nama' => 'Cetirizine 10mg', 'stok' => 120, 'harga' => 3000]);
        Obat::create(['nama' => 'IBU 400mg', 'stok' => 80, 'harga' => 4500]);
    }
}
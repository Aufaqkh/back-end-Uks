<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_pasien');
            $table->integer('umur');
            $table->text('keluhan');
            // 🔥 BUG FIXED: Tambah 'disetujui' di enum biar gak error pas di-ACC
            $table->enum('status_jemput', ['jemput', 'datang', 'disetujui'])->default('datang');
            // 🔥 FITUR BARU: Kolom lokasi jemput (nullable karena gak wajib kalau datang sendiri)
            $table->string('lokasi_jemput')->nullable();
            $table->enum('status', ['menunggu', 'proses', 'selesai'])->default('menunggu');
            $table->text('catatan_dokter')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};

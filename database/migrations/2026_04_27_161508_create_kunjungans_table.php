<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() : void
{
    // database/migrations/create_kunjungans_table.php
Schema::create('kunjungans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('nama_pasien');
    $table->integer('umur');
    $table->text('keluhan');
    $table->enum('status_jemput', ['jemput', 'datang'])->default('datang');
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
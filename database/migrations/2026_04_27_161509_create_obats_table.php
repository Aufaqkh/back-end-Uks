<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('stok')->default(0);
            $table->decimal('harga', 10, 2)->nullable();
            $table->string('kategori')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obats');
    }
};
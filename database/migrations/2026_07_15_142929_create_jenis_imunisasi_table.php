<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_imunisasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode', 20)->unique();
            $table->string('usia_rekomendasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_imunisasi');
    }
};

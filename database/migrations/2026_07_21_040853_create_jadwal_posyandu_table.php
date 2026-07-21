<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_posyandu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained('posyandu')->cascadeOnDelete();
            $table->date('tgl_jadwal');
            $table->time('jam_mulai')->default('08:00:00');
            $table->time('jam_selesai')->nullable();
            $table->string('keterangan')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'batal'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_posyandu');
    }
};

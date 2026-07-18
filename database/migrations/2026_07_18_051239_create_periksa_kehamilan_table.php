<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periksa_kehamilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kehamilan_id')->constrained('kehamilan')->cascadeOnDelete();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->date('tgl_periksa');
            $table->enum('kunjungan_ke', ['K1', 'K2', 'K3', 'K4']);
            $table->integer('usia_kehamilan')->nullable()->comment('minggu');
            $table->decimal('berat_badan', 5, 2)->nullable()->comment('kg');
            $table->decimal('lila_cm', 5, 2)->nullable()->comment('cm');
            $table->integer('tensi_sistol')->nullable();
            $table->integer('tensi_diastol')->nullable();
            $table->integer('tablet_fe')->nullable()->comment('jumlah tablet');
            $table->string('status_gizi')->nullable()->comment('Normal/KEK');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periksa_kehamilan');
    }
};

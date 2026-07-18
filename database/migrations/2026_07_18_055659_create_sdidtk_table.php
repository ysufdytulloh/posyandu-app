<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdidtk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->cascadeOnDelete();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->date('tgl_periksa');
            $table->integer('usia_bulan');
            $table->enum('motorik_kasar', ['S', 'M', 'P'])->comment('Sesuai/Meragukan/Penyimpangan');
            $table->enum('motorik_halus', ['S', 'M', 'P']);
            $table->enum('bicara_bahasa', ['S', 'M', 'P']);
            $table->enum('sosial_kemandirian', ['S', 'M', 'P']);
            $table->enum('hasil', ['Normal', 'Suspek', 'Penyimpangan']);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdidtk');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imunisasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')
                  ->constrained('anak')
                  ->cascadeOnDelete();
            $table->foreignId('jenis_imunisasi_id')
                  ->constrained('jenis_imunisasi')
                  ->cascadeOnDelete();
            $table->foreignId('kader_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('tgl_imunisasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['anak_id', 'jenis_imunisasi_id'], 'imunisasi_anak_jenis_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imunisasi');
    }
};

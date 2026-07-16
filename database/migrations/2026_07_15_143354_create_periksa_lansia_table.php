<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periksa_lansia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lansia_id')
                  ->constrained('lansia')
                  ->cascadeOnDelete();
            $table->foreignId('posyandu_id')
                  ->constrained('posyandu')
                  ->cascadeOnDelete();
            $table->foreignId('kader_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('tgl_periksa');
            $table->decimal('berat_kg', 5, 2);
            $table->decimal('tinggi_cm', 5, 2);
            $table->decimal('imt', 5, 2)->nullable();
            $table->unsignedSmallInteger('tensi_sistol')->nullable();
            $table->unsignedSmallInteger('tensi_diastol')->nullable();
            $table->decimal('gula_darah', 6, 2)->nullable();
            $table->decimal('kolesterol', 6, 2)->nullable();
            $table->decimal('asam_urat', 5, 2)->nullable();
            $table->decimal('lingkar_perut', 5, 2)->nullable();
            $table->text('keluhan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['lansia_id', 'tgl_periksa'], 'periksa_lansia_tgl_idx');
            $table->index(['posyandu_id', 'tgl_periksa'], 'periksa_posyandu_tgl_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periksa_lansia');
    }
};

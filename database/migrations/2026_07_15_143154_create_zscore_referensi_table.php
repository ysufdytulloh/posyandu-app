<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zscore_referensi', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['BB/U', 'TB/U', 'BB/TB']);
            $table->enum('jk', ['L', 'P']);
            $table->unsignedSmallInteger('usia_bulan');
            $table->decimal('sd_min3', 6, 2);
            $table->decimal('sd_min2', 6, 2);
            $table->decimal('sd_min1', 6, 2);
            $table->decimal('median', 6, 2);
            $table->decimal('sd_plus1', 6, 2);
            $table->decimal('sd_plus2', 6, 2);
            $table->decimal('sd_plus3', 6, 2);
            $table->timestamps();

            $table->unique(['jenis', 'jk', 'usia_bulan'], 'zscore_unique');
            $table->index(['jenis', 'jk'], 'zscore_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zscore_referensi');
    }
};

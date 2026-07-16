<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timbang_balita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')
                  ->constrained('anak')
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
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['anak_id', 'tgl_periksa'], 'timbang_anak_tgl_idx');
            $table->index(['posyandu_id', 'tgl_periksa'], 'timbang_posyandu_tgl_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timbang_balita');
    }
};

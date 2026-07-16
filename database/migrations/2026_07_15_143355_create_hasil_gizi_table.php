<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_gizi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timbang_id')
                  ->constrained('timbang_balita')
                  ->cascadeOnDelete();
            $table->unique('timbang_id');
            $table->decimal('bbU_zscore', 6, 3)->nullable();
            $table->decimal('tbU_zscore', 6, 3)->nullable();
            $table->decimal('bbTb_zscore', 6, 3)->nullable();
            $table->string('status_bbU', 20)->nullable();
            $table->string('status_tbU', 20)->nullable();
            $table->string('status_bbTb', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_gizi');
    }
};

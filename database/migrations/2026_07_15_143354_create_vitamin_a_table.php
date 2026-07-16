<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitamin_a', function (Blueprint $table) {
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
            $table->date('tgl_distribusi');
            $table->enum('dosis', ['Biru (100.000 IU)', 'Merah (200.000 IU)']);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['anak_id', 'tgl_distribusi'], 'vita_anak_tgl_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitamin_a');
    }
};

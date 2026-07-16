<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pmt_distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_pmt_id')
                  ->constrained('jenis_pmt')
                  ->cascadeOnDelete();
            $table->morphs('penerima');
            $table->foreignId('posyandu_id')
                  ->constrained('posyandu')
                  ->cascadeOnDelete();
            $table->foreignId('kader_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('tgl_distribusi');
            $table->decimal('jumlah', 8, 2);
            $table->string('satuan', 30);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['posyandu_id', 'tgl_distribusi'], 'pmt_posyandu_tgl_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pmt_distribusi');
    }
};

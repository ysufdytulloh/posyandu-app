<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat_cacing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anak_id')->constrained('anak')->cascadeOnDelete();
            $table->foreignId('kader_id')->constrained('users')->cascadeOnDelete();
            $table->date('tgl_pemberian');
            $table->string('dosis')->default('500mg');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat_cacing');
    }
};

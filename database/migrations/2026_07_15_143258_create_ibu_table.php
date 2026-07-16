<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ibu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')
                  ->constrained('posyandu')
                  ->cascadeOnDelete();
            $table->string('nik', 20)->unique()->nullable();
            $table->string('nama');
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->enum('goldar', ['A', 'B', 'AB', 'O', 'Tidak Tahu'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ibu');
    }
};

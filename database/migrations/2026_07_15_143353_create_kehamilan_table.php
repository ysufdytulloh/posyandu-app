<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehamilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_id')
                  ->constrained('ibu')
                  ->cascadeOnDelete();
            $table->date('hpht');
            $table->unsignedSmallInteger('usia_kehamilan')->nullable();
            $table->date('tgl_perkiraan_lahir')->nullable();
            $table->enum('status', ['aktif', 'melahirkan', 'keguguran'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehamilan');
    }
};

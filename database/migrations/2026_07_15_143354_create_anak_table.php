<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ibu_id')
                  ->constrained('ibu')
                  ->cascadeOnDelete();
            $table->foreignId('posyandu_id')
                  ->constrained('posyandu')
                  ->cascadeOnDelete();
            $table->string('nik', 20)->unique()->nullable();
            $table->string('nama');
            $table->enum('jk', ['L', 'P']);
            $table->date('tgl_lahir');
            $table->unsignedTinyInteger('anak_ke')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};

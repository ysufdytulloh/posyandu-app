<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timbang_balita', function (Blueprint $table) {
            $table->decimal('lingkar_kepala_cm', 5, 2)->nullable()->after('tinggi_cm')->comment('cm');
            $table->decimal('lila_cm', 5, 2)->nullable()->after('lingkar_kepala_cm')->comment('cm');
        });
    }

    public function down(): void
    {
        Schema::table('timbang_balita', function (Blueprint $table) {
            $table->dropColumn(['lingkar_kepala_cm', 'lila_cm']);
        });
    }
};

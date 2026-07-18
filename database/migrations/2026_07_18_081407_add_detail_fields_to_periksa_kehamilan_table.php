<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periksa_kehamilan', function (Blueprint $table) {
            $table->decimal('tfu_cm', 5, 2)->nullable()->after('lila_cm')->comment('Tinggi Fundus Uteri');
            $table->integer('djj')->nullable()->after('tfu_cm')->comment('Denyut Jantung Janin per menit');
            $table->decimal('hb', 4, 1)->nullable()->after('djj')->comment('Hemoglobin g/dL');
            $table->enum('imunisasi_tt', ['TT1', 'TT2', 'TT3', 'TT4', 'TT5'])->nullable()->after('hb');
            $table->boolean('edema')->default(false)->after('imunisasi_tt')->comment('Ada/Tidak edema');
            $table->enum('protein_urin', ['Negatif', 'Positif +1', 'Positif +2', 'Positif +3'])->nullable()->after('edema');
        });
    }

    public function down(): void
    {
        Schema::table('periksa_kehamilan', function (Blueprint $table) {
            $table->dropColumn(['tfu_cm', 'djj', 'hb', 'imunisasi_tt', 'edema', 'protein_urin']);
        });
    }
};

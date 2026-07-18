<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periksa_lansia', function (Blueprint $table) {
            $table->integer('spo2')->nullable()->after('asam_urat')->comment('Saturasi oksigen %');
            $table->integer('nadi')->nullable()->after('spo2')->comment('Denyut nadi per menit');
            $table->text('obat_rutin')->nullable()->after('nadi')->comment('Obat rutin yang dikonsumsi');
        });
    }

    public function down(): void
    {
        Schema::table('periksa_lansia', function (Blueprint $table) {
            $table->dropColumn(['spo2', 'nadi', 'obat_rutin']);
        });
    }
};

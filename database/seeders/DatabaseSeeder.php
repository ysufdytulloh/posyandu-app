<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ZscoreReferensiSeeder::class,
            JenisImunisasiSeeder::class,
            JenisPmtSeeder::class,
            DatabaseCompleteSeeder::class,
        ]);
    }
}

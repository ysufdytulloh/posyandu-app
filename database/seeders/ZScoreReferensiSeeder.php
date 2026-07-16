<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZScoreReferensiSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('data/zscore_referensi.csv');

        if (!file_exists($file)) {
            $this->command->error('File zscore_referensi.csv tidak ditemukan di database/data/');
            return;
        }

        DB::table('zscore_referensi')->truncate();

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // skip header

        $batch = [];
        $now   = now();

        while (($row = fgetcsv($handle)) !== false) {
            $batch[] = [
                'jenis'      => $row[0],
                'jk'         => $row[1],
                'usia_bulan' => (int)   $row[2],
                'sd_min3'    => (float) $row[3],
                'sd_min2'    => (float) $row[4],
                'sd_min1'    => (float) $row[5],
                'median'     => (float) $row[6],
                'sd_plus1'   => (float) $row[7],
                'sd_plus2'   => (float) $row[8],
                'sd_plus3'   => (float) $row[9],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert per 100 rows
            if (count($batch) >= 100) {
                DB::table('zscore_referensi')->insert($batch);
                $batch = [];
            }
        }

        // Insert sisa
        if (!empty($batch)) {
            DB::table('zscore_referensi')->insert($batch);
        }

        fclose($handle);
        $this->command->info('ZScore referensi berhasil di-seed: 546 rows');
    }
}

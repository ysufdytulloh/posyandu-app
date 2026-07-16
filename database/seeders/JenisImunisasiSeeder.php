<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisImunisasi;

class JenisImunisasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Hepatitis B',   'kode' => 'HB',    'usia_rekomendasi' => '0 bulan'],
            ['nama' => 'BCG',           'kode' => 'BCG',   'usia_rekomendasi' => '1 bulan'],
            ['nama' => 'Polio 1',       'kode' => 'OPV1',  'usia_rekomendasi' => '1 bulan'],
            ['nama' => 'Polio 2',       'kode' => 'OPV2',  'usia_rekomendasi' => '2 bulan'],
            ['nama' => 'Polio 3',       'kode' => 'OPV3',  'usia_rekomendasi' => '3 bulan'],
            ['nama' => 'Polio 4',       'kode' => 'OPV4',  'usia_rekomendasi' => '4 bulan'],
            ['nama' => 'DPT-HB-Hib 1', 'kode' => 'DPT1',  'usia_rekomendasi' => '2 bulan'],
            ['nama' => 'DPT-HB-Hib 2', 'kode' => 'DPT2',  'usia_rekomendasi' => '3 bulan'],
            ['nama' => 'DPT-HB-Hib 3', 'kode' => 'DPT3',  'usia_rekomendasi' => '4 bulan'],
            ['nama' => 'IPV',           'kode' => 'IPV',   'usia_rekomendasi' => '4 bulan'],
            ['nama' => 'Campak/MR',     'kode' => 'MR',    'usia_rekomendasi' => '9 bulan'],
            ['nama' => 'DPT Lanjutan', 'kode' => 'DPT-L', 'usia_rekomendasi' => '18 bulan'],
            ['nama' => 'Campak Lanjutan', 'kode' => 'MR-L', 'usia_rekomendasi' => '24 bulan'],
        ];

        foreach ($data as $item) {
            JenisImunisasi::firstOrCreate(['kode' => $item['kode']], $item);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPmt;

class JenisPmtSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Biskuit Balita',     'satuan' => 'bungkus'],
            ['nama' => 'Susu Formula',       'satuan' => 'kaleng'],
            ['nama' => 'Bubur Bayi',         'satuan' => 'bungkus'],
            ['nama' => 'Telur',              'satuan' => 'butir'],
            ['nama' => 'Kacang Hijau',       'satuan' => 'kg'],
            ['nama' => 'Minyak Goreng',      'satuan' => 'liter'],
            ['nama' => 'Susu Ibu Hamil',     'satuan' => 'kaleng'],
            ['nama' => 'Tablet Tambah Darah','satuan' => 'tablet'],
        ];

        foreach ($data as $item) {
            JenisPmt::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}

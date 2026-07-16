<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Posyandu;

class PosyanduSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'      => 'Posyandu Melati',
                'alamat'    => 'Jl. Melati No. 1, RT 01 RW 01',
                'kecamatan' => 'Mojoroto',
                'kelurahan' => 'Sukorame',
                'rt'        => '01',
                'rw'        => '01',
                'is_active' => true,
            ],
            [
                'nama'      => 'Posyandu Mawar',
                'alamat'    => 'Jl. Mawar No. 5, RT 02 RW 01',
                'kecamatan' => 'Mojoroto',
                'kelurahan' => 'Sukorame',
                'rt'        => '02',
                'rw'        => '01',
                'is_active' => true,
            ],
            [
                'nama'      => 'Posyandu Anggrek',
                'alamat'    => 'Jl. Anggrek No. 3, RT 03 RW 02',
                'kecamatan' => 'Mojoroto',
                'kelurahan' => 'Sukorame',
                'rt'        => '03',
                'rw'        => '02',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Posyandu::firstOrCreate(['nama' => $item['nama']], $item);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Posyandu;
use App\Models\User;
use App\Models\Ibu;
use App\Models\Anak;
use App\Models\Lansia;
use App\Models\Kehamilan;
use App\Models\PeriksaKehamilan;
use App\Models\TimbangBalita;
use App\Models\HasilGizi;
use App\Models\ZscoreReferensi;
use App\Models\Imunisasi;
use App\Models\JenisImunisasi;
use App\Models\VitaminA;
use App\Models\ObatCacing;
use App\Models\Sdidtk;
use App\Models\PeriksaLansia;
use App\Models\PmtDistribusi;
use App\Models\JenisPmt;
use App\Models\JadwalPosyandu;

class DatabaseCompleteSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

       // ── 1. POSYANDU ──
        $melati = Posyandu::create([
            'nama'       => 'Posyandu Melati',
            'alamat'     => 'Jl. Melati No. 1 RT 01 RW 02',
            'kelurahan'  => 'Sukorame',
            'kecamatan'  => 'Mojoroto',
            'rt'         => '01',
            'rw'         => '02',
            'nama_kader' => 'Siti Aminah',
            'is_active'  => true,
        ]);

        $mawar = Posyandu::create([
            'nama'       => 'Posyandu Mawar',
            'alamat'     => 'Jl. Mawar No. 5 RT 03 RW 01',
            'kelurahan'  => 'Bandar Lor',
            'kecamatan'  => 'Mojoroto',
            'rt'         => '03',
            'rw'         => '01',
            'nama_kader' => 'Dewi Rahayu',
            'is_active'  => true,
        ]);

        $anggrek = Posyandu::create([
            'nama'       => 'Posyandu Anggrek',
            'alamat'     => 'Jl. Anggrek No. 10 RT 02 RW 03',
            'kelurahan'  => 'Banjarmlati',
            'kecamatan'  => 'Mojoroto',
            'rt'         => '02',
            'rw'         => '03',
            'nama_kader' => 'Rina Wati',
            'is_active'  => true,
        ]);

       // ── 2. USERS ──
        $admin = User::firstOrCreate(
            ['email' => 'admin@posyandu.test'],
            [
                'name'        => 'Admin Posyandu',
                'password'    => Hash::make('password'),
                'role'        => 'admin_desa',
                'posyandu_id' => null,
            ]
        );

        $kaderMelati = User::create([
            'name'        => 'Siti Aminah',
            'email'       => 'siti@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'kader',
            'posyandu_id' => $melati->id,
        ]);

        $kaderMawar = User::create([
            'name'        => 'Dewi Rahayu',
            'email'       => 'dewi@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'kader',
            'posyandu_id' => $mawar->id,
        ]);

        $kaderAnggrek = User::create([
            'name'        => 'Rina Wati',
            'email'       => 'rina@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'kader',
            'posyandu_id' => $anggrek->id,
        ]);

        // ── 3. IBU — MELATI ──
        $sariIndah = Ibu::create([
            'posyandu_id' => $melati->id,
            'nama'        => 'SARI INDAH',
            'nik'         => '3571010101900001',
            'tgl_lahir'   => '1990-05-15',
            'no_hp'       => '081234560001',
            'goldar'      => 'O',
            'alamat'      => 'Jl. Melati No. 2 RT 01 RW 02',
        ]);

        $dewiRahayuIbu = Ibu::create([
            'posyandu_id' => $melati->id,
            'nama'        => 'DEWI RAHAYU',
            'nik'         => '3571010101920002',
            'tgl_lahir'   => '1992-08-20',
            'no_hp'       => '081234560002',
            'goldar'      => 'A',
            'alamat'      => 'Jl. Melati No. 4 RT 01 RW 02',
        ]);

        $fitriHandayani = Ibu::create([
            'posyandu_id' => $melati->id,
            'nama'        => 'FITRI HANDAYANI',
            'nik'         => '3571010101950003',
            'tgl_lahir'   => '1995-03-10',
            'no_hp'       => '081234560003',
            'goldar'      => 'B',
            'alamat'      => 'Jl. Melati No. 6 RT 02 RW 02',
        ]);

        // ── 4. IBU — MAWAR ──
        $anaKurnia = Ibu::create([
            'posyandu_id' => $mawar->id,
            'nama'        => 'ANA KURNIA',
            'nik'         => '3571010101910004',
            'tgl_lahir'   => '1991-11-25',
            'no_hp'       => '081234560004',
            'goldar'      => 'AB',
            'alamat'      => 'Jl. Mawar No. 3 RT 03 RW 01',
        ]);

        $riniSusanti = Ibu::create([
            'posyandu_id' => $mawar->id,
            'nama'        => 'RINI SUSANTI',
            'nik'         => '3571010101930005',
            'tgl_lahir'   => '1993-06-14',
            'no_hp'       => '081234560005',
            'goldar'      => 'O',
            'alamat'      => 'Jl. Mawar No. 7 RT 03 RW 01',
        ]);

        $wahyuNingsih = Ibu::create([
            'posyandu_id' => $mawar->id,
            'nama'        => 'WAHYU NINGSIH',
            'nik'         => '3571010101960006',
            'tgl_lahir'   => '1996-09-08',
            'no_hp'       => '081234560006',
            'goldar'      => 'A',
            'alamat'      => 'Jl. Mawar No. 12 RT 04 RW 01',
        ]);

        // ── 5. IBU — ANGGREK ──
        $linaMarlina = Ibu::create([
            'posyandu_id' => $anggrek->id,
            'nama'        => 'LINA MARLINA',
            'nik'         => '3571010101890007',
            'tgl_lahir'   => '1989-02-18',
            'no_hp'       => '081234560007',
            'goldar'      => 'B',
            'alamat'      => 'Jl. Anggrek No. 4 RT 02 RW 03',
        ]);

        $yuniAstuti = Ibu::create([
            'posyandu_id' => $anggrek->id,
            'nama'        => 'YUNI ASTUTI',
            'nik'         => '3571010101940008',
            'tgl_lahir'   => '1994-07-22',
            'no_hp'       => '081234560008',
            'goldar'      => 'O',
            'alamat'      => 'Jl. Anggrek No. 8 RT 02 RW 03',
        ]);

        $endahPurwanti = Ibu::create([
            'posyandu_id' => $anggrek->id,
            'nama'        => 'ENDAH PURWANTI',
            'nik'         => '3571010101970009',
            'tgl_lahir'   => '1997-12-05',
            'no_hp'       => '081234560009',
            'goldar'      => 'AB',
            'alamat'      => 'Jl. Anggrek No. 15 RT 03 RW 03',
        ]);

        // ── 6. AKUN ORANG TUA ──
        User::create([
            'name'        => 'Ibu Sari Indah',
            'email'       => 'ortu1@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'orang_tua',
            'ibu_id'      => $sariIndah->id,
            'posyandu_id' => $melati->id,
        ]);

        User::create([
            'name'        => 'Ibu Ana Kurnia',
            'email'       => 'ortu2@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'orang_tua',
            'ibu_id'      => $anaKurnia->id,
            'posyandu_id' => $mawar->id,
        ]);

        User::create([
            'name'        => 'Ibu Lina Marlina',
            'email'       => 'ortu3@posyandu.test',
            'password'    => Hash::make('password123'),
            'role'        => 'orang_tua',
            'ibu_id'      => $linaMarlina->id,
            'posyandu_id' => $anggrek->id,
        ]);

        // ── 7. ANAK — MELATI ──
        $budiSantoso = Anak::create([
            'ibu_id'      => $sariIndah->id,
            'posyandu_id' => $melati->id,
            'nama'        => 'BUDI SANTOSO',
            'nik'         => '3571010101250001',
            'jk'          => 'L',
            'tgl_lahir'   => '2025-07-16',
            'anak_ke'     => 1,
        ]);

        $dinaSari = Anak::create([
            'ibu_id'      => $sariIndah->id,
            'posyandu_id' => $melati->id,
            'nama'        => 'DINA SARI',
            'nik'         => '3571010101230002',
            'jk'          => 'P',
            'tgl_lahir'   => '2023-07-10',
            'anak_ke'     => 2,
        ]);

        $ahmadFauzi = Anak::create([
            'ibu_id'      => $dewiRahayuIbu->id,
            'posyandu_id' => $melati->id,
            'nama'        => 'AHMAD FAUZI',
            'nik'         => '3571010101240003',
            'jk'          => 'L',
            'tgl_lahir'   => '2024-01-20',
            'anak_ke'     => 1,
        ]);

        $sitiNurhaliza = Anak::create([
            'ibu_id'      => $fitriHandayani->id,
            'posyandu_id' => $melati->id,
            'nama'        => 'SITI NURHALIZA',
            'nik'         => '3571010101260004',
            'jk'          => 'P',
            'tgl_lahir'   => '2026-01-15',
            'anak_ke'     => 1,
        ]);

        // ── 8. ANAK — MAWAR ──
        $rizkiPratama = Anak::create([
            'ibu_id'      => $anaKurnia->id,
            'posyandu_id' => $mawar->id,
            'nama'        => 'RIZKI PRATAMA',
            'nik'         => '3571010101240005',
            'jk'          => 'L',
            'tgl_lahir'   => '2024-04-10',
            'anak_ke'     => 1,
        ]);

        $putriRahayu = Anak::create([
            'ibu_id'      => $anaKurnia->id,
            'posyandu_id' => $mawar->id,
            'nama'        => 'PUTRI RAHAYU',
            'nik'         => '3571010101220006',
            'jk'          => 'P',
            'tgl_lahir'   => '2022-01-05',
            'anak_ke'     => 2,
        ]);

        $dafaArjuna = Anak::create([
            'ibu_id'      => $riniSusanti->id,
            'posyandu_id' => $mawar->id,
            'nama'        => 'DAFA ARJUNA',
            'nik'         => '3571010101250007',
            'jk'          => 'L',
            'tgl_lahir'   => '2025-10-12',
            'anak_ke'     => 1,
        ]);

        $nauraSalsabila = Anak::create([
            'ibu_id'      => $wahyuNingsih->id,
            'posyandu_id' => $mawar->id,
            'nama'        => 'NAURA SALSABILA',
            'nik'         => '3571010101210008',
            'jk'          => 'P',
            'tgl_lahir'   => '2021-08-20',
            'anak_ke'     => 1,
        ]);

        // ── 9. ANAK — ANGGREK ──
        $fajarNugroho = Anak::create([
            'ibu_id'      => $linaMarlina->id,
            'posyandu_id' => $anggrek->id,
            'nama'        => 'FAJAR NUGROHO',
            'nik'         => '3571010101240009',
            'jk'          => 'L',
            'tgl_lahir'   => '2024-11-03',
            'anak_ke'     => 1,
        ]);

        $cantikaDewi = Anak::create([
            'ibu_id'      => $linaMarlina->id,
            'posyandu_id' => $anggrek->id,
            'nama'        => 'CANTIKA DEWI',
            'nik'         => '3571010101260010',
            'jk'          => 'P',
            'tgl_lahir'   => '2026-02-28',
            'anak_ke'     => 2,
        ]);

        $hafizRamadhan = Anak::create([
            'ibu_id'      => $yuniAstuti->id,
            'posyandu_id' => $anggrek->id,
            'nama'        => 'HAFIZ RAMADHAN',
            'nik'         => '3571010101220011',
            'jk'          => 'L',
            'tgl_lahir'   => '2022-03-15',
            'anak_ke'     => 1,
        ]);

        $kiranaSari = Anak::create([
            'ibu_id'      => $endahPurwanti->id,
            'posyandu_id' => $anggrek->id,
            'nama'        => 'KIRANA SARI',
            'nik'         => '3571010101250012',
            'jk'          => 'P',
            'tgl_lahir'   => '2025-05-20',
            'anak_ke'     => 1,
        ]);

        // ── 10. LANSIA — MELATI ──
        $sutarno = \App\Models\Lansia::create([
            'posyandu_id'      => $melati->id,
            'nama'             => 'SUTARNO',
            'nik'              => '3571010101500001',
            'jk'               => 'L',
            'tgl_lahir'        => '1950-04-12',
            'no_hp'            => '081234561001',
            'alamat'           => 'Jl. Melati No. 8 RT 01 RW 02',
            'riwayat_penyakit' => 'Hipertensi',
        ]);

        $suminah = \App\Models\Lansia::create([
            'posyandu_id'      => $melati->id,
            'nama'             => 'SUMINAH',
            'nik'              => '3571010101520002',
            'jk'               => 'P',
            'tgl_lahir'        => '1952-09-20',
            'no_hp'            => '081234561002',
            'alamat'           => 'Jl. Melati No. 10 RT 02 RW 02',
            'riwayat_penyakit' => 'Diabetes Melitus',
        ]);

        // ── 11. LANSIA — MAWAR ──
        $karyo = \App\Models\Lansia::create([
            'posyandu_id'      => $mawar->id,
            'nama'             => 'KARYO',
            'nik'              => '3571010101480003',
            'jk'               => 'L',
            'tgl_lahir'        => '1948-06-08',
            'no_hp'            => '081234561003',
            'alamat'           => 'Jl. Mawar No. 9 RT 03 RW 01',
            'riwayat_penyakit' => 'Asam Urat, Kolesterol',
        ]);

        $sumiati = \App\Models\Lansia::create([
            'posyandu_id'      => $mawar->id,
            'nama'             => 'SUMIATI',
            'nik'              => '3571010101550004',
            'jk'               => 'P',
            'tgl_lahir'        => '1955-12-15',
            'no_hp'            => '081234561004',
            'alamat'           => 'Jl. Mawar No. 14 RT 04 RW 01',
            'riwayat_penyakit' => 'Hipertensi, Rematik',
        ]);

        // ── 12. LANSIA — ANGGREK ──
        $poniman = \App\Models\Lansia::create([
            'posyandu_id'      => $anggrek->id,
            'nama'             => 'PONIMAN',
            'nik'              => '3571010101460005',
            'jk'               => 'L',
            'tgl_lahir'        => '1946-03-25',
            'no_hp'            => '081234561005',
            'alamat'           => 'Jl. Anggrek No. 6 RT 02 RW 03',
            'riwayat_penyakit' => 'Jantung, Hipertensi',
        ]);

        $marsih = \App\Models\Lansia::create([
            'posyandu_id'      => $anggrek->id,
            'nama'             => 'MARSIH',
            'nik'              => '3571010101580006',
            'jk'               => 'P',
            'tgl_lahir'        => '1958-07-11',
            'no_hp'            => '081234561006',
            'alamat'           => 'Jl. Anggrek No. 18 RT 03 RW 03',
            'riwayat_penyakit' => 'Osteoporosis',
        ]);

        // ── 13. KEHAMILAN ──
        $kehamilanFitri = Kehamilan::create([
            'ibu_id'              => $fitriHandayani->id,
            'hpht'                => '2026-01-10',
            'tgl_perkiraan_lahir' => '2026-10-17',
            'usia_kehamilan'      => 27,
            'status'              => 'aktif',
            'catatan'             => 'Kehamilan pertama, kondisi baik',
        ]);

        $kehamilanWahyu = Kehamilan::create([
            'ibu_id'              => $wahyuNingsih->id,
            'hpht'                => '2026-02-05',
            'tgl_perkiraan_lahir' => '2026-11-12',
            'usia_kehamilan'      => 24,
            'status'              => 'aktif',
            'catatan'             => 'Kehamilan kedua',
        ]);

        $kehamilanYuni = Kehamilan::create([
            'ibu_id'              => $yuniAstuti->id,
            'hpht'                => '2025-12-20',
            'tgl_perkiraan_lahir' => '2026-09-26',
            'usia_kehamilan'      => 30,
            'status'              => 'aktif',
            'catatan'             => 'Kehamilan ketiga',
        ]);

        // ── 14. PERIKSA KEHAMILAN ──
        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanFitri->id,
            'kader_id'        => $kaderMelati->id,
            'tgl_periksa'     => '2026-02-15',
            'kunjungan_ke'    => 'K1',
            'usia_kehamilan'  => 5,
            'berat_badan'     => 52.5,
            'lila_cm'         => 24.5,
            'tensi_sistol'    => 110,
            'tensi_diastol'   => 70,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 11.5,
            'tfu_cm'          => null,
            'djj'             => null,
            'catatan'         => 'Kondisi ibu dan janin baik',
        ]);

        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanFitri->id,
            'kader_id'        => $kaderMelati->id,
            'tgl_periksa'     => '2026-04-20',
            'kunjungan_ke'    => 'K2',
            'usia_kehamilan'  => 15,
            'berat_badan'     => 54.0,
            'lila_cm'         => 25.0,
            'tensi_sistol'    => 115,
            'tensi_diastol'   => 75,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 12.0,
            'tfu_cm'          => 16.0,
            'djj'             => 142,
            'catatan'         => 'Pertumbuhan janin normal',
        ]);

        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanWahyu->id,
            'kader_id'        => $kaderMawar->id,
            'tgl_periksa'     => '2026-03-10',
            'kunjungan_ke'    => 'K1',
            'usia_kehamilan'  => 4,
            'berat_badan'     => 48.0,
            'lila_cm'         => 23.0,
            'tensi_sistol'    => 100,
            'tensi_diastol'   => 65,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 11.0,
            'catatan'         => 'Perlu pemantauan LILA',
        ]);

        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanYuni->id,
            'kader_id'        => $kaderAnggrek->id,
            'tgl_periksa'     => '2026-01-25',
            'kunjungan_ke'    => 'K1',
            'usia_kehamilan'  => 5,
            'berat_badan'     => 58.0,
            'lila_cm'         => 26.5,
            'tensi_sistol'    => 120,
            'tensi_diastol'   => 80,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 12.5,
            'catatan'         => 'Kondisi baik',
        ]);

        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanYuni->id,
            'kader_id'        => $kaderAnggrek->id,
            'tgl_periksa'     => '2026-03-28',
            'kunjungan_ke'    => 'K2',
            'usia_kehamilan'  => 14,
            'berat_badan'     => 60.5,
            'lila_cm'         => 27.0,
            'tensi_sistol'    => 118,
            'tensi_diastol'   => 78,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 12.8,
            'tfu_cm'          => 14.0,
            'djj'             => 138,
            'catatan'         => 'Janin aktif bergerak',
        ]);

        PeriksaKehamilan::create([
            'kehamilan_id'    => $kehamilanYuni->id,
            'kader_id'        => $kaderAnggrek->id,
            'tgl_periksa'     => '2026-05-30',
            'kunjungan_ke'    => 'K3',
            'usia_kehamilan'  => 23,
            'berat_badan'     => 63.0,
            'lila_cm'         => 27.5,
            'tensi_sistol'    => 122,
            'tensi_diastol'   => 80,
            'tablet_fe'       => 30,
            'status_gizi'     => 'Normal',
            'hb'              => 12.5,
            'tfu_cm'          => 22.0,
            'djj'             => 145,
            'catatan'         => 'Kondisi sangat baik',
        ]);

        // ── 15. TIMBANG BALITA ──
        $timbangData = [
            // BUDI SANTOSO (L, lahir 2025-07-16, usia ~12 bln)
            [$budiSantoso->id, $melati->id, $kaderMelati->id, '2026-05-20', 8.5, 72.0, 45.5, 13.0],
            [$budiSantoso->id, $melati->id, $kaderMelati->id, '2026-06-18', 9.0, 73.5, 46.0, 13.2],
            [$budiSantoso->id, $melati->id, $kaderMelati->id, '2026-07-16', 9.5, 75.0, 46.5, 13.5],

            // DINA SARI (P, lahir 2023-07-10, usia ~36 bln)
            [$dinaSari->id, $melati->id, $kaderMelati->id, '2026-05-15', 13.0, 93.0, 49.5, 16.0],
            [$dinaSari->id, $melati->id, $kaderMelati->id, '2026-06-12', 13.3, 94.0, 49.8, 16.2],
            [$dinaSari->id, $melati->id, $kaderMelati->id, '2026-07-10', 13.6, 95.0, 50.0, 16.5],

            // AHMAD FAUZI (L, lahir 2024-01-20, usia ~18 bln)
            [$ahmadFauzi->id, $melati->id, $kaderMelati->id, '2026-05-20', 9.8, 80.0, 47.5, 14.0],
            [$ahmadFauzi->id, $melati->id, $kaderMelati->id, '2026-06-18', 10.1, 81.5, 47.8, 14.2],
            [$ahmadFauzi->id, $melati->id, $kaderMelati->id, '2026-07-16', 10.4, 83.0, 48.0, 14.5],

            // SITI NURHALIZA (P, lahir 2026-01-15, usia ~6 bln)
            [$sitiNurhaliza->id, $melati->id, $kaderMelati->id, '2026-05-20', 6.5, 63.0, 42.0, 13.5],
            [$sitiNurhaliza->id, $melati->id, $kaderMelati->id, '2026-06-18', 7.0, 65.0, 42.5, 13.8],
            [$sitiNurhaliza->id, $melati->id, $kaderMelati->id, '2026-07-16', 7.4, 67.0, 43.0, 14.0],

            // RIZKI PRATAMA (L, lahir 2024-04-10, usia ~15 bln)
            [$rizkiPratama->id, $mawar->id, $kaderMawar->id, '2026-05-15', 9.5, 78.0, 47.0, 13.8],
            [$rizkiPratama->id, $mawar->id, $kaderMawar->id, '2026-06-12', 9.8, 79.5, 47.3, 14.0],
            [$rizkiPratama->id, $mawar->id, $kaderMawar->id, '2026-07-10', 10.2, 81.0, 47.5, 14.2],

            // PUTRI RAHAYU (P, lahir 2022-01-05, usia ~54 bln)
            [$putriRahayu->id, $mawar->id, $kaderMawar->id, '2026-05-15', 15.0, 103.0, 51.0, 17.0],
            [$putriRahayu->id, $mawar->id, $kaderMawar->id, '2026-06-12', 15.3, 104.0, 51.2, 17.2],
            [$putriRahayu->id, $mawar->id, $kaderMawar->id, '2026-07-10', 15.6, 105.0, 51.5, 17.5],

            // DAFA ARJUNA (L, lahir 2025-10-12, usia ~9 bln)
            [$dafaArjuna->id, $mawar->id, $kaderMawar->id, '2026-05-15', 7.8, 70.0, 44.0, 13.5],
            [$dafaArjuna->id, $mawar->id, $kaderMawar->id, '2026-06-12', 8.2, 71.5, 44.5, 13.8],
            [$dafaArjuna->id, $mawar->id, $kaderMawar->id, '2026-07-10', 8.5, 73.0, 45.0, 14.0],

            // NAURA SALSABILA (P, lahir 2021-08-20, usia ~59 bln) - STUNTING
            [$nauraSalsabila->id, $mawar->id, $kaderMawar->id, '2026-05-15', 14.0, 100.0, 51.0, 16.5],
            [$nauraSalsabila->id, $mawar->id, $kaderMawar->id, '2026-06-12', 14.2, 101.0, 51.2, 16.7],
            [$nauraSalsabila->id, $mawar->id, $kaderMawar->id, '2026-07-10', 14.4, 102.0, 51.5, 16.9],

            // FAJAR NUGROHO (L, lahir 2024-11-03, usia ~20 bln)
            [$fajarNugroho->id, $anggrek->id, $kaderAnggrek->id, '2026-05-20', 10.5, 84.0, 48.0, 14.5],
            [$fajarNugroho->id, $anggrek->id, $kaderAnggrek->id, '2026-06-18', 10.8, 85.5, 48.3, 14.8],
            [$fajarNugroho->id, $anggrek->id, $kaderAnggrek->id, '2026-07-16', 11.2, 87.0, 48.5, 15.0],

            // CANTIKA DEWI (P, lahir 2026-02-28, usia ~5 bln) - GIZI KURANG
            [$cantikaDewi->id, $anggrek->id, $kaderAnggrek->id, '2026-05-20', 5.0, 58.0, 40.0, 12.0],
            [$cantikaDewi->id, $anggrek->id, $kaderAnggrek->id, '2026-06-18', 5.3, 59.5, 40.5, 12.2],
            [$cantikaDewi->id, $anggrek->id, $kaderAnggrek->id, '2026-07-16', 5.6, 61.0, 41.0, 12.4],

            // HAFIZ RAMADHAN (L, lahir 2022-03-15, usia ~52 bln)
            [$hafizRamadhan->id, $anggrek->id, $kaderAnggrek->id, '2026-05-20', 17.0, 106.0, 52.0, 17.5],
            [$hafizRamadhan->id, $anggrek->id, $kaderAnggrek->id, '2026-06-18', 17.3, 107.0, 52.2, 17.8],
            [$hafizRamadhan->id, $anggrek->id, $kaderAnggrek->id, '2026-07-16', 17.6, 108.0, 52.5, 18.0],

            // KIRANA SARI (P, lahir 2025-05-20, usia ~14 bln)
            [$kiranaSari->id, $anggrek->id, $kaderAnggrek->id, '2026-05-20', 8.8, 76.0, 46.5, 13.5],
            [$kiranaSari->id, $anggrek->id, $kaderAnggrek->id, '2026-06-18', 9.1, 77.5, 46.8, 13.8],
            [$kiranaSari->id, $anggrek->id, $kaderAnggrek->id, '2026-07-16', 9.4, 79.0, 47.0, 14.0],
        ];

        foreach ($timbangData as $t) {
            TimbangBalita::create([
                'anak_id'           => $t[0],
                'posyandu_id'       => $t[1],
                'kader_id'          => $t[2],
                'tgl_periksa'       => $t[3],
                'berat_kg'          => $t[4],
                'tinggi_cm'         => $t[5],
                'lingkar_kepala_cm' => $t[6],
                'lila_cm'           => $t[7],
            ]);
        }

        // ── 16. IMUNISASI ──
        $jenisImunisasi = JenisImunisasi::all()->keyBy('nama');

        $imunisasiData = [
            // BUDI SANTOSO (12 bln)
            [$budiSantoso->id, 'HB0 (Hepatitis B)', $kaderMelati->id, '2025-07-16'],
            [$budiSantoso->id, 'BCG', $kaderMelati->id, '2025-08-20'],
            [$budiSantoso->id, 'Polio 1', $kaderMelati->id, '2025-08-20'],
            [$budiSantoso->id, 'DPT-HB-Hib 1', $kaderMelati->id, '2025-09-20'],
            [$budiSantoso->id, 'Polio 2', $kaderMelati->id, '2025-09-20'],
            [$budiSantoso->id, 'DPT-HB-Hib 2', $kaderMelati->id, '2025-10-20'],
            [$budiSantoso->id, 'Polio 3', $kaderMelati->id, '2025-10-20'],
            [$budiSantoso->id, 'DPT-HB-Hib 3', $kaderMelati->id, '2025-11-20'],
            [$budiSantoso->id, 'Polio 4 + IPV', $kaderMelati->id, '2025-11-20'],
            [$budiSantoso->id, 'Campak/MR', $kaderMelati->id, '2026-04-20'],

            // DINA SARI (36 bln)
            [$dinaSari->id, 'HB0 (Hepatitis B)', $kaderMelati->id, '2023-07-10'],
            [$dinaSari->id, 'BCG', $kaderMelati->id, '2023-08-10'],
            [$dinaSari->id, 'DPT-HB-Hib 1', $kaderMelati->id, '2023-09-10'],
            [$dinaSari->id, 'DPT-HB-Hib 2', $kaderMelati->id, '2023-10-10'],
            [$dinaSari->id, 'DPT-HB-Hib 3', $kaderMelati->id, '2023-11-10'],
            [$dinaSari->id, 'Campak/MR', $kaderMelati->id, '2024-04-10'],

            // RIZKI PRATAMA (15 bln)
            [$rizkiPratama->id, 'HB0 (Hepatitis B)', $kaderMawar->id, '2024-04-10'],
            [$rizkiPratama->id, 'BCG', $kaderMawar->id, '2024-05-10'],
            [$rizkiPratama->id, 'DPT-HB-Hib 1', $kaderMawar->id, '2024-06-10'],
            [$rizkiPratama->id, 'DPT-HB-Hib 2', $kaderMawar->id, '2024-07-10'],
            [$rizkiPratama->id, 'DPT-HB-Hib 3', $kaderMawar->id, '2024-08-10'],
            [$rizkiPratama->id, 'Campak/MR', $kaderMawar->id, '2025-01-10'],

            // HAFIZ RAMADHAN (52 bln)
            [$hafizRamadhan->id, 'HB0 (Hepatitis B)', $kaderAnggrek->id, '2022-03-15'],
            [$hafizRamadhan->id, 'BCG', $kaderAnggrek->id, '2022-04-15'],
            [$hafizRamadhan->id, 'DPT-HB-Hib 1', $kaderAnggrek->id, '2022-05-15'],
            [$hafizRamadhan->id, 'DPT-HB-Hib 2', $kaderAnggrek->id, '2022-06-15'],
            [$hafizRamadhan->id, 'DPT-HB-Hib 3', $kaderAnggrek->id, '2022-07-15'],
            [$hafizRamadhan->id, 'Campak/MR', $kaderAnggrek->id, '2022-12-15'],
        ];

        foreach ($imunisasiData as $im) {
            $jenis = $jenisImunisasi->get($im[1]);
            if ($jenis) {
                Imunisasi::create([
                    'anak_id'            => $im[0],
                    'jenis_imunisasi_id' => $jenis->id,
                    'kader_id'           => $im[2],
                    'tgl_imunisasi'      => $im[3],
                ]);
            }
        }

        // ── 17. VITAMIN A ──
        $vitaminAData = [
            [$budiSantoso->id,   $melati->id,  $kaderMelati->id,  '2026-02-20', 'Biru (100.000 IU)'],
            [$dinaSari->id,      $melati->id,  $kaderMelati->id,  '2026-02-20', 'Merah (200.000 IU)'],
            [$ahmadFauzi->id,    $melati->id,  $kaderMelati->id,  '2026-02-20', 'Merah (200.000 IU)'],
            [$rizkiPratama->id,  $mawar->id,   $kaderMawar->id,   '2026-02-15', 'Merah (200.000 IU)'],
            [$putriRahayu->id,   $mawar->id,   $kaderMawar->id,   '2026-02-15', 'Merah (200.000 IU)'],
            [$fajarNugroho->id,  $anggrek->id, $kaderAnggrek->id, '2026-02-20', 'Merah (200.000 IU)'],
            [$hafizRamadhan->id, $anggrek->id, $kaderAnggrek->id, '2026-02-20', 'Merah (200.000 IU)'],
            [$kiranaSari->id,    $anggrek->id, $kaderAnggrek->id, '2026-02-20', 'Biru (100.000 IU)'],
        ];

        foreach ($vitaminAData as $v) {
            VitaminA::create([
                'anak_id'        => $v[0],
                'posyandu_id'    => $v[1],
                'kader_id'       => $v[2],
                'tgl_distribusi' => $v[3],
                'dosis'          => $v[4],
            ]);
        }

        // ── 18. OBAT CACING ──
        $obatCacingData = [
            [$budiSantoso->id,   $kaderMelati->id,  '2026-01-20', '500mg'],
            [$dinaSari->id,      $kaderMelati->id,  '2026-01-20', '500mg'],
            [$ahmadFauzi->id,    $kaderMelati->id,  '2026-01-20', '500mg'],
            [$rizkiPratama->id,  $kaderMawar->id,   '2026-01-15', '500mg'],
            [$putriRahayu->id,   $kaderMawar->id,   '2026-01-15', '500mg'],
            [$fajarNugroho->id,  $kaderAnggrek->id, '2026-01-20', '500mg'],
            [$hafizRamadhan->id, $kaderAnggrek->id, '2026-01-20', '500mg'],
        ];

        foreach ($obatCacingData as $o) {
            ObatCacing::create([
                'anak_id'       => $o[0],
                'kader_id'      => $o[1],
                'tgl_pemberian' => $o[2],
                'dosis'         => $o[3],
            ]);
        }

        // ── 19. SDIDTK ──
        $sdidtkData = [
            [$budiSantoso->id,  $kaderMelati->id,  '2026-07-16', 12, 'S','S','S','S','Normal'],
            [$dinaSari->id,     $kaderMelati->id,  '2026-07-10', 36, 'S','S','S','S','Normal'],
            [$ahmadFauzi->id,   $kaderMelati->id,  '2026-07-16', 18, 'S','S','M','S','Suspek'],
            [$rizkiPratama->id, $kaderMawar->id,   '2026-07-10', 15, 'S','S','S','S','Normal'],
            [$putriRahayu->id,  $kaderMawar->id,   '2026-07-10', 54, 'S','S','S','S','Normal'],
            [$fajarNugroho->id, $kaderAnggrek->id, '2026-07-16', 20, 'S','S','S','S','Normal'],
            [$hafizRamadhan->id,$kaderAnggrek->id, '2026-07-16', 52, 'S','S','S','S','Normal'],
        ];

        foreach ($sdidtkData as $s) {
            Sdidtk::create([
                'anak_id'            => $s[0],
                'kader_id'           => $s[1],
                'tgl_periksa'        => $s[2],
                'usia_bulan'         => $s[3],
                'motorik_kasar'      => $s[4],
                'motorik_halus'      => $s[5],
                'bicara_bahasa'      => $s[6],
                'sosial_kemandirian' => $s[7],
                'hasil'              => $s[8],
            ]);
        }

        // ── 20. PERIKSA LANSIA ──
        $periksaLansiaData = [
            [$sutarno->id,  $melati->id,  $kaderMelati->id,  '2026-05-20', 65.0, 168.0, 130, 85, 140, 195, 5.8, 96, 82, 'Amlodipine 5mg', 'Pusing kadang-kadang'],
            [$sutarno->id,  $melati->id,  $kaderMelati->id,  '2026-06-18', 64.5, 168.0, 128, 82, 135, 190, 5.5, 97, 80, 'Amlodipine 5mg', 'Kondisi membaik'],
            [$sutarno->id,  $melati->id,  $kaderMelati->id,  '2026-07-16', 64.0, 168.0, 125, 80, 130, 185, 5.2, 97, 78, 'Amlodipine 5mg', 'Stabil'],
            [$suminah->id,  $melati->id,  $kaderMelati->id,  '2026-05-20', 58.0, 155.0, 120, 80, 180, 200, 6.0, 95, 76, 'Metformin 500mg', 'Gula darah perlu kontrol'],
            [$suminah->id,  $melati->id,  $kaderMelati->id,  '2026-07-16', 57.5, 155.0, 118, 78, 160, 195, 5.8, 96, 75, 'Metformin 500mg', 'Gula darah membaik'],
            [$karyo->id,    $mawar->id,   $kaderMawar->id,   '2026-05-15', 60.0, 162.0, 135, 88, 120, 240, 7.5, 95, 84, '-', 'Nyeri sendi'],
            [$karyo->id,    $mawar->id,   $kaderMawar->id,   '2026-07-10', 59.5, 162.0, 132, 85, 118, 230, 7.2, 96, 82, '-', 'Nyeri berkurang'],
            [$sumiati->id,  $mawar->id,   $kaderMawar->id,   '2026-05-15', 55.0, 152.0, 140, 90, 130, 210, 6.2, 94, 80, 'Captopril 25mg', 'Tekanan darah tinggi'],
            [$sumiati->id,  $mawar->id,   $kaderMawar->id,   '2026-07-10', 54.5, 152.0, 135, 88, 125, 205, 6.0, 95, 78, 'Captopril 25mg', 'Membaik'],
            [$poniman->id,  $anggrek->id, $kaderAnggrek->id, '2026-05-20', 62.0, 165.0, 145, 92, 128, 220, 6.5, 93, 86, 'Ramipril 5mg, Aspirin', 'Sesak nafas ringan'],
            [$poniman->id,  $anggrek->id, $kaderAnggrek->id, '2026-07-16', 61.5, 165.0, 140, 90, 125, 215, 6.3, 94, 84, 'Ramipril 5mg, Aspirin', 'Kondisi stabil'],
            [$marsih->id,   $anggrek->id, $kaderAnggrek->id, '2026-05-20', 52.0, 150.0, 115, 75, 115, 195, 5.5, 97, 74, 'Kalsium', 'Nyeri tulang'],
            [$marsih->id,   $anggrek->id, $kaderAnggrek->id, '2026-07-16', 52.5, 150.0, 112, 73, 112, 192, 5.3, 97, 72, 'Kalsium', 'Membaik'],
        ];

        foreach ($periksaLansiaData as $pl) {
            $tinggi = $pl[4] > 0 ? $pl[5] / 100 : 1;
            $imt = $pl[4] > 0 ? round($pl[4] / ($tinggi * $tinggi), 2) : null;
            PeriksaLansia::create([
                'lansia_id'     => $pl[0],
                'posyandu_id'   => $pl[1],
                'kader_id'      => $pl[2],
                'tgl_periksa'   => $pl[3],
                'berat_kg'      => $pl[4],
                'tinggi_cm'     => $pl[5],
                'imt'           => $imt,
                'tensi_sistol'  => $pl[6],
                'tensi_diastol' => $pl[7],
                'gula_darah'    => $pl[8],
                'kolesterol'    => $pl[9],
                'asam_urat'     => $pl[10],
                'spo2'          => $pl[11],
                'nadi'          => $pl[12],
                'obat_rutin'    => $pl[13],
                'keluhan'       => $pl[14],
            ]);
        }

        // ── 21. PMT DISTRIBUSI ──
        $jenisPmt = JenisPmt::all()->first();
        if ($jenisPmt) {
            $pmtData = [
                ['App\Models\Anak', $budiSantoso->id,  $melati->id,  $kaderMelati->id,  '2026-06-20', 4, 'bungkus'],
                ['App\Models\Anak', $cantikaDewi->id,  $anggrek->id, $kaderAnggrek->id, '2026-06-20', 4, 'bungkus'],
                ['App\Models\Ibu',  $fitriHandayani->id, $melati->id, $kaderMelati->id, '2026-06-20', 2, 'kg'],
                ['App\Models\Ibu',  $wahyuNingsih->id,  $mawar->id,  $kaderMawar->id,  '2026-06-20', 2, 'kg'],
                ['App\Models\Lansia', $sutarno->id,  $melati->id,  $kaderMelati->id,  '2026-06-20', 1, 'paket'],
                ['App\Models\Lansia', $poniman->id,  $anggrek->id, $kaderAnggrek->id, '2026-06-20', 1, 'paket'],
            ];

            foreach ($pmtData as $pmt) {
                PmtDistribusi::create([
                    'posyandu_id'   => $pmt[2],
                    'jenis_pmt_id'  => $jenisPmt->id,
                    'kader_id'      => $pmt[3],
                    'tgl_distribusi'=> $pmt[4],
                    'penerima_type' => $pmt[0],
                    'penerima_id'   => $pmt[1],
                    'jumlah'        => $pmt[5],
                    'satuan'        => $pmt[6],
                ]);
            }
        }

        // ── 22. JADWAL POSYANDU ──
        $jadwalData = [
            [$melati->id,  '2026-08-20', '08:00:00', '11:00:00', 'Posyandu rutin bulanan Agustus 2026'],
            [$melati->id,  '2026-09-17', '08:00:00', '11:00:00', 'Posyandu rutin bulanan September 2026'],
            [$mawar->id,   '2026-08-15', '08:00:00', '11:00:00', 'Posyandu rutin bulanan Agustus 2026'],
            [$mawar->id,   '2026-09-19', '08:00:00', '11:00:00', 'Posyandu rutin bulanan September 2026'],
            [$anggrek->id, '2026-08-22', '08:00:00', '11:00:00', 'Posyandu rutin bulanan Agustus 2026'],
            [$anggrek->id, '2026-09-26', '08:00:00', '11:00:00', 'Posyandu rutin bulanan September 2026'],
        ];

        foreach ($jadwalData as $j) {
            JadwalPosyandu::create([
                'posyandu_id' => $j[0],
                'tgl_jadwal'  => $j[1],
                'jam_mulai'   => $j[2],
                'jam_selesai' => $j[3],
                'keterangan'  => $j[4],
                'status'      => 'aktif',
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Data lengkap berhasil dibuat!');
        $this->command->info('Posyandu: 3 | Kader: 3 | Ibu: 9 | Anak: 12 | Lansia: 6');
        $this->command->info('Kehamilan: 3 | Timbang: 36 | Imunisasi: 28 | Jadwal: 6');
    }
}

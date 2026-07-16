<?php

namespace App\Services;

use App\Models\ZscoreReferensi;
use App\Models\HasilGizi;
use App\Models\TimbangBalita;

class ZScoreCalculator
{
    public function calculate(TimbangBalita $timbang): void
{
    $anak   = $timbang->anak;
    $umur   = $anak->umurBulan();
    $jk     = $anak->jk;
    $berat  = $timbang->berat_kg;
    $tinggi = $timbang->tinggi_cm;

    $bbU  = $this->hitungZScore('BB/U',  $jk, $umur, $berat);
    $tbU  = $this->hitungZScore('TB/U',  $jk, $umur, $tinggi);

    // BB/TB: key = tinggi * 10, dibulatkan ke 0.5 cm terdekat
    $tinggiKey = (int) round(round($tinggi * 2) / 2 * 10);
    $bbTb = $this->hitungZScore('BB/TB', $jk, $tinggiKey, $berat);

    HasilGizi::updateOrCreate(
        ['timbang_id' => $timbang->id],
        [
            'bbU_zscore'  => $bbU,
            'tbU_zscore'  => $tbU,
            'bbTb_zscore' => $bbTb,
            'status_bbU'  => $this->statusBBU($bbU),
            'status_tbU'  => $this->statusTBU($tbU),
            'status_bbTb' => $this->statusBBTB($bbTb),
        ]
    );
}

    private function hitungZScore(string $jenis, string $jk, int $usia, float $nilai): ?float
    {
        $ref = ZscoreReferensi::where('jenis', $jenis)
            ->where('jk', $jk)
            ->where('usia_bulan', $usia)
            ->first();

        if (!$ref) return null;

        // Hitung z-score dengan metode LMS WHO
        if ($nilai < $ref->median) {
            $sd = $ref->median - $ref->sd_min1;
        } else {
            $sd = $ref->sd_plus1 - $ref->median;
        }

        if ($sd == 0) return null;

        return round(($nilai - $ref->median) / $sd, 2);
    }

    private function statusBBU(?float $z): string
    {
        if ($z === null) return 'Tidak Diketahui';
        if ($z < -3)     return 'Gizi Buruk';
        if ($z < -2)     return 'Gizi Kurang';
        if ($z <= 2)     return 'Normal';
        return 'Gizi Lebih';
    }

    private function statusTBU(?float $z): string
    {
        if ($z === null) return 'Tidak Diketahui';
        if ($z < -3)     return 'Sangat Pendek';
        if ($z < -2)     return 'Pendek';
        if ($z <= 2)     return 'Normal';
        return 'Tinggi';
    }

    private function statusBBTB(?float $z): string
    {
        if ($z === null) return 'Tidak Diketahui';
        if ($z < -3)     return 'Sangat Kurus';
        if ($z < -2)     return 'Kurus';
        if ($z <= 2)     return 'Normal';
        if ($z <= 3)     return 'Gemuk';
        return 'Obesitas';
    }
}

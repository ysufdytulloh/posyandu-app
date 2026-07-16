<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZscoreReferensi extends Model
{
    protected $table = 'zscore_referensi';

    protected $fillable = [
        'jenis', 'jk', 'usia_bulan',
        'sd_min3', 'sd_min2', 'sd_min1',
        'median',
        'sd_plus1', 'sd_plus2', 'sd_plus3',
    ];

    protected $casts = [
        'sd_min3'  => 'float', 'sd_min2'  => 'float', 'sd_min1'  => 'float',
        'median'   => 'float',
        'sd_plus1' => 'float', 'sd_plus2' => 'float', 'sd_plus3' => 'float',
    ];
}

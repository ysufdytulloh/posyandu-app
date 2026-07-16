<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriksaLansia extends Model
{
    use SoftDeletes;

    protected $table = 'periksa_lansia';

    protected $fillable = [
        'lansia_id', 'posyandu_id', 'kader_id', 'tgl_periksa',
        'berat_kg', 'tinggi_cm', 'imt',
        'tensi_sistol', 'tensi_diastol',
        'gula_darah', 'kolesterol', 'asam_urat',
        'lingkar_perut', 'keluhan',
    ];

    protected $casts = [
        'tgl_periksa'   => 'date',
        'berat_kg'      => 'float',
        'tinggi_cm'     => 'float',
        'imt'           => 'float',
        'gula_darah'    => 'float',
        'kolesterol'    => 'float',
        'asam_urat'     => 'float',
        'lingkar_perut' => 'float',
    ];

    public function lansia(): BelongsTo    { return $this->belongsTo(Lansia::class); }
    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function kader(): BelongsTo     { return $this->belongsTo(User::class, 'kader_id'); }

    // Kalkulasi IMT otomatis sebelum save
    protected static function booted(): void
    {
        static::saving(function (PeriksaLansia $model) {
            if ($model->berat_kg && $model->tinggi_cm) {
                $tinggiMeter = $model->tinggi_cm / 100;
                $model->imt  = round($model->berat_kg / ($tinggiMeter ** 2), 2);
            }
        });
    }
}

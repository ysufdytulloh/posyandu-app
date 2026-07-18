<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PeriksaKehamilan extends Model
{
    use SoftDeletes;

    protected $table = 'periksa_kehamilan';

    protected $fillable = [
        'kehamilan_id', 'kader_id', 'tgl_periksa',
        'kunjungan_ke', 'usia_kehamilan', 'berat_badan',
        'lila_cm', 'tfu_cm', 'djj', 'hb',
        'tensi_sistol', 'tensi_diastol',
        'tablet_fe', 'imunisasi_tt', 'edema',
        'protein_urin', 'status_gizi', 'catatan',
    ];

    protected $casts = [
        'tgl_periksa' => 'date',
    ];

    public function kehamilan(): BelongsTo
    {
        return $this->belongsTo(Kehamilan::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kader_id');
    }
}

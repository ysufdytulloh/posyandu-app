<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sdidtk extends Model
{
    use SoftDeletes;

    protected $table = 'sdidtk';

    protected $fillable = [
        'anak_id', 'kader_id', 'tgl_periksa', 'usia_bulan',
        'motorik_kasar', 'motorik_halus', 'bicara_bahasa',
        'sosial_kemandirian', 'hasil', 'catatan',
    ];

    protected $casts = ['tgl_periksa' => 'date'];

    public function anak(): BelongsTo   { return $this->belongsTo(Anak::class); }
    public function kader(): BelongsTo  { return $this->belongsTo(User::class, 'kader_id'); }
}

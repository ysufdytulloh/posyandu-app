<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimbangBalita extends Model
{
    use SoftDeletes;

    protected $table = 'timbang_balita';

    protected $fillable = [
        'anak_id', 'posyandu_id', 'kader_id',
        'tgl_periksa', 'berat_kg', 'tinggi_cm', 'catatan',
    ];

    protected $casts = [
        'tgl_periksa' => 'date',
        'berat_kg'    => 'float',
        'tinggi_cm'   => 'float',
    ];

    public function anak(): BelongsTo      { return $this->belongsTo(Anak::class); }
    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function kader(): BelongsTo     { return $this->belongsTo(User::class, 'kader_id'); }
    public function hasilGizi(): HasOne    { return $this->hasOne(HasilGizi::class, 'timbang_id'); }
}

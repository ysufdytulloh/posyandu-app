<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PmtDistribusi extends Model
{
    use SoftDeletes;

    protected $table = 'pmt_distribusi';

    protected $fillable = [
        'jenis_pmt_id', 'penerima_type', 'penerima_id',
        'posyandu_id', 'kader_id',
        'tgl_distribusi', 'jumlah', 'satuan', 'catatan',
    ];

    protected $casts = [
        'tgl_distribusi' => 'date',
        'jumlah'         => 'float',
    ];

    // Polymorphic — penerima bisa Anak, Ibu, atau Lansia
    public function penerima(): MorphTo    { return $this->morphTo(); }
    public function jenisPmt(): BelongsTo  { return $this->belongsTo(JenisPmt::class); }
    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function kader(): BelongsTo     { return $this->belongsTo(User::class, 'kader_id'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObatCacing extends Model
{
    use SoftDeletes;

    protected $table = 'obat_cacing';

    protected $fillable = [
        'anak_id', 'kader_id', 'tgl_pemberian', 'dosis', 'keterangan',
    ];

    protected $casts = ['tgl_pemberian' => 'date'];

    public function anak(): BelongsTo  { return $this->belongsTo(Anak::class); }
    public function kader(): BelongsTo { return $this->belongsTo(User::class, 'kader_id'); }
}

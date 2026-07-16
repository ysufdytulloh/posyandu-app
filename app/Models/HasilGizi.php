<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilGizi extends Model
{
    protected $table = 'hasil_gizi';

    protected $fillable = [
        'timbang_id',
        'bbU_zscore', 'tbU_zscore', 'bbTb_zscore',
        'status_bbU', 'status_tbU', 'status_bbTb',
    ];

    protected $casts = [
        'bbU_zscore'  => 'float',
        'tbU_zscore'  => 'float',
        'bbTb_zscore' => 'float',
    ];

    public function timbang(): BelongsTo
    {
        return $this->belongsTo(TimbangBalita::class, 'timbang_id');
    }
}

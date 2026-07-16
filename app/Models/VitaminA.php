<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitaminA extends Model
{
    use SoftDeletes;

    protected $table = 'vitamin_a';

    protected $fillable = [
        'anak_id', 'posyandu_id', 'kader_id',
        'tgl_distribusi', 'dosis',
    ];

    protected $casts = ['tgl_distribusi' => 'date'];

    public function anak(): BelongsTo      { return $this->belongsTo(Anak::class); }
    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function kader(): BelongsTo     { return $this->belongsTo(User::class, 'kader_id'); }
}

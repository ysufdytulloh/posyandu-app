<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kehamilan extends Model
{
    use SoftDeletes;

    protected $table = 'kehamilan';

    protected $fillable = [
        'ibu_id', 'hpht', 'usia_kehamilan',
        'tgl_perkiraan_lahir', 'status', 'catatan',
    ];

    protected $casts = [
        'hpht'               => 'date',
        'tgl_perkiraan_lahir' => 'date',
    ];

    public function ibu(): BelongsTo { return $this->belongsTo(Ibu::class); }

    public function periksaKehamilan(): HasMany
    {
        return $this->hasMany(PeriksaKehamilan::class);
    }
}

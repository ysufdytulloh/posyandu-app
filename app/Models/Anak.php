<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anak extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ibu_id', 'posyandu_id', 'nik', 'nama',
        'jk', 'tgl_lahir', 'anak_ke',
    ];

    protected $table = 'anak';
    protected $casts = ['tgl_lahir' => 'date'];

    public function ibu(): BelongsTo       { return $this->belongsTo(Ibu::class); }
    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function timbang(): HasMany     { return $this->hasMany(TimbangBalita::class); }
    public function imunisasi(): HasMany   { return $this->hasMany(Imunisasi::class); }
    public function vitaminA(): HasMany    { return $this->hasMany(VitaminA::class); }

    public function umurBulan(): int
    {
        return (int) $this->tgl_lahir->diffInMonths(now());
    }

    public function sdidtk(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Sdidtk::class);
    }

    public function obatCacing(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ObatCacing::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lansia extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'posyandu_id', 'nik', 'nama', 'jk',
        'tgl_lahir', 'alamat', 'no_hp', 'riwayat_penyakit',
    ];

    protected $casts = ['tgl_lahir' => 'date'];

    public function posyandu(): BelongsTo    { return $this->belongsTo(Posyandu::class); }
    public function pemeriksaan(): HasMany   { return $this->hasMany(PeriksaLansia::class); }

    public function umurTahun(): int
    {
        return (int) $this->tgl_lahir->diffInYears(now());
    }
}

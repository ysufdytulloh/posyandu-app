<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisImunisasi extends Model
{
    protected $table = 'jenis_imunisasi';
    protected $fillable = ['nama', 'kode', 'usia_rekomendasi', 'keterangan'];

    public function imunisasi(): HasMany { return $this->hasMany(Imunisasi::class); }
}

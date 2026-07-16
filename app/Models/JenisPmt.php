<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisPmt extends Model
{
    protected $fillable = ['nama', 'satuan', 'keterangan'];

    public function distribusi(): HasMany { return $this->hasMany(PmtDistribusi::class); }
}

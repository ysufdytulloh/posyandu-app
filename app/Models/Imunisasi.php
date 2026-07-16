<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imunisasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'anak_id', 'jenis_imunisasi_id', 'kader_id',
        'tgl_imunisasi', 'keterangan',
    ];

    protected $casts = ['tgl_imunisasi' => 'date'];

    protected $table = 'imunisasi';

    public function anak(): BelongsTo            { return $this->belongsTo(Anak::class); }
    public function jenisImunisasi(): BelongsTo  { return $this->belongsTo(JenisImunisasi::class); }
    public function kader(): BelongsTo           { return $this->belongsTo(User::class, 'kader_id'); }
}

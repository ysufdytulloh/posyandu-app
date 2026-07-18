<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ibu extends Model
{
    use SoftDeletes;

    protected $table = 'ibu';

    protected $fillable = [
        'posyandu_id', 'nik', 'nama', 'tgl_lahir',
        'alamat', 'no_hp', 'goldar',
    ];

    protected $casts = ['tgl_lahir' => 'date'];

    public function posyandu(): BelongsTo  { return $this->belongsTo(Posyandu::class); }
    public function user(): HasOne         { return $this->hasOne(User::class); }
    public function kehamilan(): HasMany   { return $this->hasMany(Kehamilan::class); }
    public function anak(): HasMany        { return $this->hasMany(Anak::class); }

    public function kehamilanAktif()
    {
        return $this->hasOne(Kehamilan::class)->where('status', 'aktif');
    }

}

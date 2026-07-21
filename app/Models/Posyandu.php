<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Posyandu extends Model
{
    protected $table = 'posyandu';

    protected $fillable = [
        'nama', 'alamat', 'kecamatan', 'kelurahan',
        'rt', 'rw', 'logo', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function users(): HasMany   { return $this->hasMany(User::class); }
    public function ibu(): HasMany     { return $this->hasMany(Ibu::class); }
    public function anak(): HasMany    { return $this->hasMany(Anak::class); }
    public function lansia(): HasMany  { return $this->hasMany(Lansia::class); }

    public function jadwalPosyandu()
    {
        return $this->hasMany(JadwalPosyandu::class);
    }

    public function jadwalBerikutnya()
    {
        return $this->hasOne(JadwalPosyandu::class)
            ->where('tgl_jadwal', '>=', now()->toDateString())
            ->where('status', 'aktif')
            ->orderBy('tgl_jadwal');
    }
}

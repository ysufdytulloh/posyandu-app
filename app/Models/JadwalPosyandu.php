<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPosyandu extends Model
{
    protected $table = 'jadwal_posyandu';

    protected $fillable = [
        'posyandu_id',
        'tgl_jadwal',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tgl_jadwal'  => 'date',
        'jam_mulai'   => 'string',
        'jam_selesai' => 'string',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}

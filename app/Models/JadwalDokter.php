<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_dokter',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public $timestamps = true;

    public function dokter()
    {
        return $this->belongsTo(ManajemenDokter::class, 'id_dokter', 'id');
    }
}

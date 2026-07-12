<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenDokter extends Model
{
    protected $table = 'dokter';

    protected $primaryKey = 'id_dokter';

    protected $fillable = [
        'nama',
        'spesialis',
        'no_str',
        'no_hp',
        'alamat',
        'jadwal_praktek',
    ];
}

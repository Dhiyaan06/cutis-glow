<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenDokter extends Model
{
    protected $table = 'manajemen_dokter';

    protected $fillable = [
        'nama',
        'spesialis',
        'jadwal_praktek',
        'nomor_lisensi',
        'nomor_hp',
        'alamat',
    ];
}

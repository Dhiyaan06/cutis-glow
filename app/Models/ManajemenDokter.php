<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManajemenDokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'id_dokter';

    protected $fillable = [
    'id_pengguna',
    'spesialis',
    'no_str',
    'no_hp',
    'alamat',
    'jadwal_praktek',
    ];
    //relasi ke tabel users (pengguna)
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokter extends Model
{
    use HasFactory;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function jadwal(){
        return $this->hasMany(JadwalDokter::class, 'id_dokter', 'id_dokter');

    }

    public function bookings(){
        return $this->hasMany(BookingKonsultasi::class, 'id_dokter', 'id_dokter');
    }

    public function riwayat(){
        return $this->hasMany(RiwayatLayanan::class, 'id_dokter', 'id_dokter');
    }
}

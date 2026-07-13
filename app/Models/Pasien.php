<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';

    protected $fillable = [
        'id_pengguna',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'jenis_kelamin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function bookings()
    {
        return $this->hasMany(BookingKonsultasi::class, 'id_pasien', 'id_pasien');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatLayanan::class, 'id_pasien', 'id_pasien');
    }
}

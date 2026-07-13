<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingKonsultasi extends Model
{
    use HasFactory;

    protected $table = 'booking_konsultasi';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'id_pasien',
        'id_dokter',
        'tanggal_booking',
        'jam_booking',
        'status', // pending, dikonfirmasi, selesai, dibatalkan
        'catatan'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function riwayat()
    {
        return $this->hasOne(RiwayatLayanan::class, 'id_booking', 'id_booking');
    }
}

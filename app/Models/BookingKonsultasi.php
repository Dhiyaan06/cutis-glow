<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingKonsultasi extends Model
{
    protected $table = 'booking_konsultasi';

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'id_pasien',
        'id_dokter',
        'jadwal_konsultasi',
        'status_booking',
        'keluhan',
    ];

    public $timestamps = true;

    public function pasien()
    {
        return $this->belongsTo(ManajemenPasien::class, 'id_pasien', 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(ManajemenDokter::class, 'id_dokter', 'id_dokter');
    }
}

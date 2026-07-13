<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatLayanan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_layanan';
    protected $primaryKey = 'id_riwayat';

    protected $fillable = [
        'id_booking',
        'id_pasien',
        'id_dokter',
        'id_layanan',
        'tanggal_treatment',
        'status',
        'catatan',
        'harga',
        'qty',
    ];

    public function booking()
    {
        return $this->belongsTo(BookingKonsultasi::class, 'id_booking', 'id_booking');
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'id_dokter', 'id_dokter');
    }

    public function layanan()
    {
        return $this->belongsTo(MasterLayanan::class, 'id_layanan', 'id_layanan');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceHistory extends Model
{
    protected $fillable = [
        'booking_id',
        'patient_id',
        'doctor_id',
        'service_id',
        'treatment_date',
        'status',
        'notes',
        'price',
        'qty',
        'total',
    ];

    public function booking()
    {
        return $this->belongsTo(ConsultationBooking::class, 'booking_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(doctor::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(MasterService::class, 'service_id');
    }
}

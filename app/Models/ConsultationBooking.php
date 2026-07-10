<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationBooking extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'booking_date',
        'booking_time',
        'status',
        'notes',
    ];

    /**
     * Get the patient who made the booking.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor assigned to the booking.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the doctor's schedule.
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class);
    }
}

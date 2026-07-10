<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'phone',
        'address',
    ];

    /**
     * Get the user that owns the patient.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all consultation bookings for the patient.
     */
    public function consultationBookings(): HasMany
    {
        return $this->hasMany(ConsultationBooking::class);
    }
    public function serviceHistories()
    {
    return $this->hasMany(ServiceHistory::class, 'patient_id');
    }
}

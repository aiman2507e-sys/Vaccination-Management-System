<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Hospital
 * 
 * Represents a registered healthcare institution or vaccination facility.
 */
class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'location',
        'contact_person',
        'operating_hours',
        'status',
    ];

    /**
     * User account associated with this hospital (for login)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Appointments scheduled at this hospital
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'hospital_id');
    }

    /**
     * Scope for only active hospitals
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

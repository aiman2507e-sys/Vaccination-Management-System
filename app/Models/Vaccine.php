<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Vaccine
 * 
 * Represents an immunizing biological agent registered in the system.
 */
class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_code',
        'target_disease',
        'recommended_age_days',
        'recommended_age_label',
        'dosage',
        'route',
        'status',
        'description',
        'precautions',
    ];

    protected function casts(): array
    {
        return [
            'recommended_age_days' => 'integer',
        ];
    }

    /**
     * Scope for vaccines marked available
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Check if vaccine is currently available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Appointments requesting this vaccine
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'vaccine_id');
    }
}

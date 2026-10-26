<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Child
 * 
 * Represents an infant or child registered for immunization tracking.
 */
class Child extends Model
{
    use HasFactory;

    protected $table = 'children';

    protected $fillable = [
        'parent_id',
        'name',
        'dob',
        'gender',
        'blood_group',
        'birth_weight',
        'birth_place',
        'allergies',
        'medical_history',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'birth_weight' => 'decimal:2',
        ];
    }

    /**
     * Parent user account who registered this child
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Vaccination appointments for this child
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'child_id');
    }

    /**
     * Completed vaccinations
     */
    public function completedVaccinations(): HasMany
    {
        return $this->hasMany(Appointment::class, 'child_id')->where('status', 'vaccinated');
    }

    /**
     * Format child's human-readable age (e.g., "4 months" or "2 years 1 month")
     */
    public function getFormattedAgeAttribute(): string
    {
        if (!$this->dob) {
            return 'N/A';
        }

        $now = Carbon::now();
        $diffYears = $this->dob->diffInYears($now);
        $diffMonths = $this->dob->diffInMonths($now);
        $diffDays = $this->dob->diffInDays($now);

        if ($diffDays < 30) {
            return $diffDays . ' ' . ($diffDays === 1 ? 'day' : 'days') . ' old';
        } elseif ($diffMonths < 24) {
            return $diffMonths . ' ' . ($diffMonths === 1 ? 'month' : 'months') . ' old';
        } else {
            $remainingMonths = $diffMonths % 12;
            return $diffYears . ' yrs' . ($remainingMonths > 0 ? " {$remainingMonths} mos" : '');
        }
    }

    /**
     * Age in days
     */
    public function getAgeInDaysAttribute(): int
    {
        return $this->dob ? $this->dob->diffInDays(Carbon::now()) : 0;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Appointment
 * 
 * Represents a vaccination appointment workflow instance.
 */
class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_reference',
        'parent_id',
        'child_id',
        'vaccine_id',
        'hospital_id',
        'appointment_date',
        'appointment_time',
        'status',
        'admin_notes',
        'admin_action_at',
        'administered_date',
        'batch_number',
        'administered_by',
        'hospital_remarks',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'administered_date' => 'date',
            'admin_action_at' => 'datetime',
        ];
    }

    /**
     * Automatically generate unique booking reference if not present
     */
    protected static function booted()
    {
        static::creating(function ($appointment) {
            if (empty($appointment->booking_reference)) {
                $appointment->booking_reference = 'VAC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    /**
     * Parent who placed this appointment request
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Child for whom this vaccination is scheduled
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class, 'child_id');
    }

    /**
     * Selected vaccine
     */
    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class, 'vaccine_id');
    }

    /**
     * Selected hospital clinic
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    /**
     * Scopes for appointment statuses
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeVaccinated($query)
    {
        return $query->where('status', 'vaccinated');
    }

    /**
     * UI Status Badge color class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'approved' => 'bg-blue-100 text-blue-800 border-blue-300',
            'vaccinated' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'not_vaccinated' => 'bg-rose-100 text-rose-800 border-rose-300',
            'rejected' => 'bg-gray-100 text-gray-800 border-gray-300',
            default => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }

    /**
     * Human readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Approval',
            'approved' => 'Approved / Scheduled',
            'vaccinated' => 'Vaccinated (Completed)',
            'not_vaccinated' => 'Not Vaccinated / Missed',
            'rejected' => 'Rejected',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}

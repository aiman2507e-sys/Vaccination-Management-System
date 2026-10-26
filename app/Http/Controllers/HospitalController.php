<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Controller: HospitalController
 * 
 * Manages hospital clinical staff operations:
 * - Hospital facility dashboard
 * - View admin-approved vaccination appointments
 * - Update vaccine status: Mark as Vaccinated or Not Vaccinated with batch and clinical notes
 * - Manage hospital profile & operating hours
 */
class HospitalController extends Controller
{
    /**
     * Hospital Dashboard: Today's queue and summary counters
     */
    public function dashboard()
    {
        $hospital = $this->getAuthHospital();

        if (!$hospital) {
            return redirect()->route('login')->with('error', 'Hospital facility profile not linked.');
        }

        $stats = [
            'today_count' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'approved')
                ->whereDate('appointment_date', Carbon::today())
                ->count(),
            'upcoming_approved' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'approved')
                ->whereDate('appointment_date', '>=', Carbon::today())
                ->count(),
            'total_vaccinated' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'vaccinated')
                ->count(),
            'total_missed' => Appointment::where('hospital_id', $hospital->id)
                ->where('status', 'not_vaccinated')
                ->count(),
        ];

        // Today's appointments awaiting vaccination
        $todaysAppointments = Appointment::with(['child.parent', 'vaccine'])
            ->where('hospital_id', $hospital->id)
            ->where('status', 'approved')
            ->whereDate('appointment_date', Carbon::today())
            ->orderBy('appointment_time')
            ->get();

        // Upcoming appointments in next 14 days
        $upcomingAppointments = Appointment::with(['child.parent', 'vaccine'])
            ->where('hospital_id', $hospital->id)
            ->where('status', 'approved')
            ->whereDate('appointment_date', '>', Carbon::today())
            ->orderBy('appointment_date')
            ->take(8)
            ->get();

        return view('hospital.dashboard', compact('hospital', 'stats', 'todaysAppointments', 'upcomingAppointments'));
    }

    /**
     * List all approved vaccination appointments for this hospital
     */
    public function appointments(Request $request)
    {
        $hospital = $this->getAuthHospital();
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['approved', 'vaccinated', 'not_vaccinated', 'all'])],
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $status = $validated['status'] ?? 'approved';
        $search = $validated['search'] ?? null;

        $query = Appointment::with(['child.parent', 'vaccine'])
            ->where('hospital_id', $hospital->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('child', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(12)->withQueryString();

        return view('hospital.appointments.index', compact('hospital', 'appointments', 'status', 'search'));
    }

    /**
     * Show form to update vaccine administration status
     */
    public function showUpdateStatus($id)
    {
        $hospital = $this->getAuthHospital();
        $appointment = Appointment::with(['child.parent', 'vaccine', 'hospital'])
            ->where('hospital_id', $hospital->id)
            ->findOrFail($id);

        return view('hospital.appointments.update-status', compact('hospital', 'appointment'));
    }

    /**
     * Module: Update Vaccine status
     * Mandated in SRS: "Hospital will receive the appointment once its booked from admin side.
     * If vaccination is completed they will update the status to Vaccinated or not."
     */
    public function updateVaccineStatus(Request $request, $id)
    {
        $hospital = $this->getAuthHospital();
        $appointment = Appointment::where('hospital_id', $hospital->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:vaccinated,not_vaccinated',
            'administered_date' => 'required_if:status,vaccinated|nullable|date|before_or_equal:today',
            'batch_number' => 'required_if:status,vaccinated|nullable|string|max:50',
            'administered_by' => 'required_if:status,vaccinated|nullable|string|max:100',
            'hospital_remarks' => 'nullable|string|max:1000',
        ]);

        $appointment->status = $validated['status'];
        $appointment->administered_date = $validated['administered_date'] ?? ($validated['status'] === 'vaccinated' ? Carbon::today() : null);
        $appointment->batch_number = $validated['batch_number'] ?? null;
        $appointment->administered_by = $validated['administered_by'] ?? null;
        $appointment->hospital_remarks = $validated['hospital_remarks'] ?? null;
        $appointment->save();

        $statusMessage = ($validated['status'] === 'vaccinated')
            ? "Appointment #{$appointment->booking_reference} marked as Vaccinated successfully."
            : "Appointment #{$appointment->booking_reference} marked as Not Vaccinated / Missed.";

        return redirect()->route('hospital.appointments.index')
            ->with('success', $statusMessage);
    }

    /**
     * Hospital Facility Profile
     */
    public function profile()
    {
        $hospital = $this->getAuthHospital();
        return view('hospital.profile', compact('hospital'));
    }

    /**
     * Update Hospital Facility details
     */
    public function updateProfile(Request $request)
    {
        $hospital = $this->getAuthHospital();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'operating_hours' => 'required|string|max:100',
        ]);

        $hospital->update($validated);

        return redirect()->route('hospital.profile')
            ->with('success', 'Hospital profile updated successfully.');
    }

    /**
     * Get associated Hospital record for the authenticated user
     */
    protected function getAuthHospital(): ?Hospital
    {
        $user = Auth::user();
        if (!$user) return null;

        // Try direct user relation
        if ($user->hospital) {
            return $user->hospital;
        }

        // Fallback by email match
        return Hospital::where('email', $user->email)->first();
    }
}

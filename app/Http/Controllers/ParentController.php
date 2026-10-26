<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Hospital;
use App\Models\Vaccine;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Controller: ParentController
 * 
 * Manages parent self-service actions:
 * - Dashboard with upcoming vaccination alerts
 * - Child profile registration & maintenance
 * - Hospital discovery & appointment request booking
 * - Vaccination taken history reports & printable immunization cards
 * - Profile details update
 */
class ParentController extends Controller
{
    /**
     * Parent Dashboard: Summary, upcoming appointment alerts, age-based vaccine milestones
     */
    public function dashboard()
    {
        $parent = Auth::user();
        $children = $parent->children()->with('appointments.vaccine')->get();

        $childIds = $children->pluck('id');

        // Upcoming scheduled appointments (approved or pending)
        $upcomingAppointments = Appointment::with(['child', 'vaccine', 'hospital'])
            ->whereIn('child_id', $childIds)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('appointment_date', '>=', Carbon::today())
            ->orderBy('appointment_date')
            ->get();

        // Vaccinations already completed
        $completedCount = Appointment::whereIn('child_id', $childIds)
            ->where('status', 'vaccinated')
            ->count();

        // Calculate upcoming age-based vaccination alerts for each child
        $vaccineAlerts = [];
        $availableVaccines = Vaccine::where('status', 'available')->get();

        foreach ($children as $child) {
            $ageInDays = $child->age_in_days;
            $takenVaccineIds = $child->appointments()
                ->where('status', 'vaccinated')
                ->pluck('vaccine_id')
                ->toArray();

            foreach ($availableVaccines as $vac) {
                // If not yet taken and recommended age is near (within +/- 30 days) or due
                if (!in_array($vac->id, $takenVaccineIds)) {
                    $dueDaysDiff = $vac->recommended_age_days - $ageInDays;
                    if ($dueDaysDiff <= 30 && $dueDaysDiff >= -120) {
                        $vaccineAlerts[] = [
                            'child' => $child,
                            'vaccine' => $vac,
                            'is_overdue' => ($dueDaysDiff < 0),
                            'due_label' => $vac->recommended_age_label,
                        ];
                    }
                }
            }
        }

        return view('parent.dashboard', compact('parent', 'children', 'upcomingAppointments', 'completedCount', 'vaccineAlerts'));
    }

    /**
     * Parent Profile View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('parent.profile', compact('user'));
    }

    /**
     * Update Parent Profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|max:255|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->city = $validated['city'] ?? $user->city;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('parent.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Module: Details of Child - List
     */
    public function childrenIndex()
    {
        $children = Auth::user()->children()
            ->withCount(['appointments', 'appointments as vaccinated_count' => function ($q) {
                $q->where('status', 'vaccinated');
            }])
            ->latest()
            ->get();

        return view('parent.children.index', compact('children'));
    }

    /**
     * Create Child Form
     */
    public function createChild()
    {
        return view('parent.children.create');
    }

    /**
     * Store Child
     */
    public function storeChild(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'birth_weight' => 'nullable|numeric|min:0.5|max:15.0',
            'birth_place' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:1000',
        ]);

        $child = Auth::user()->children()->create($validated);

        return redirect()->route('parent.children.show', $child->id)
            ->with('success', "Child '{$child->name}' registered successfully.");
    }

    /**
     * View Child Detail & Complete Immunization Matrix
     */
    public function childDetail($id)
    {
        $child = Auth::user()->children()
            ->with(['appointments.vaccine', 'appointments.hospital'])
            ->findOrFail($id);

        $allVaccines = Vaccine::orderBy('recommended_age_days')->get();

        return view('parent.children.show', compact('child', 'allVaccines'));
    }

    /**
     * Edit Child Form
     */
    public function editChild($id)
    {
        $child = Auth::user()->children()->findOrFail($id);
        return view('parent.children.edit', compact('child'));
    }

    /**
     * Update Child Details
     */
    public function updateChild(Request $request, $id)
    {
        $child = Auth::user()->children()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'birth_weight' => 'nullable|numeric|min:0.5|max:15.0',
            'birth_place' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:1000',
        ]);

        $child->update($validated);

        return redirect()->route('parent.children.show', $child->id)
            ->with('success', "Child details for '{$child->name}' updated successfully.");
    }

    /**
     * Module: Vaccination Dates (Upcoming & Timeline)
     * Parent notification center for scheduled visits and upcoming milestone due dates
     */
    public function vaccinationDates()
    {
        $parent = Auth::user();
        $children = $parent->children()->with('appointments.vaccine', 'appointments.hospital')->get();
        $childIds = $children->pluck('id');

        $scheduledAppointments = Appointment::with(['child', 'vaccine', 'hospital'])
            ->whereIn('child_id', $childIds)
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('appointment_date')
            ->get();

        $availableVaccines = Vaccine::where('status', 'available')->orderBy('recommended_age_days')->get();

        return view('parent.vaccination-dates', compact('children', 'scheduledAppointments', 'availableVaccines'));
    }

    /**
     * Module: Book Hospital
     * Allows parent to discover hospitals and book a schedule for their child's vaccination
     */
    public function bookHospital(Request $request)
    {
        $parent = Auth::user();
        $children = $parent->children()->get();

        if ($children->isEmpty()) {
            return redirect()->route('parent.children.create')
                ->with('info', 'Please register your child before booking a vaccination appointment.');
        }

        $validated = $request->validate([
            'child_id' => ['nullable', 'integer', Rule::exists('children', 'id')->where('parent_id', $parent->id)],
            'vaccine_id' => ['nullable', 'integer', Rule::exists('vaccines', 'id')->where('status', 'available')],
            'hospital_id' => ['nullable', 'integer', Rule::exists('hospitals', 'id')->where('status', 'active')],
        ]);
        $preselectedChildId = $validated['child_id'] ?? null;
        $preselectedVaccineId = $validated['vaccine_id'] ?? null;

        $vaccines = Vaccine::where('status', 'available')->orderBy('recommended_age_days')->get();
        $hospitals = Hospital::active()->orderBy('name')->get();

        return view('parent.book-hospital', compact('children', 'vaccines', 'hospitals', 'preselectedChildId', 'preselectedVaccineId'));
    }

    /**
     * Submit Hospital Appointment Request
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'child_id' => ['required', 'integer', Rule::exists('children', 'id')->where('parent_id', Auth::id())],
            'vaccine_id' => ['required', 'integer', Rule::exists('vaccines', 'id')->where('status', 'available')],
            'hospital_id' => ['required', 'integer', Rule::exists('hospitals', 'id')->where('status', 'active')],
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => ['required', Rule::in([
                '09:00 AM - 10:00 AM',
                '10:30 AM - 11:30 AM',
                '11:30 AM - 12:30 PM',
                '02:00 PM - 03:00 PM',
                '03:30 PM - 04:30 PM',
            ])],
            'notes' => 'nullable|string|max:500',
        ]);

        $appointmentDate = Carbon::parse($validated['appointment_date']);
        if ($appointmentDate->isToday()) {
            $slotStartTime = explode(' - ', $validated['appointment_time'])[0];
            $slotStart = Carbon::parse($appointmentDate->toDateString().' '.$slotStartTime);

            if ($slotStart->lessThanOrEqualTo(now())) {
                throw ValidationException::withMessages([
                    'appointment_time' => 'Please select a time slot that has not already started.',
                ]);
            }
        }

        $appointment = Appointment::create([
            'parent_id' => Auth::id(),
            'child_id' => $validated['child_id'],
            'vaccine_id' => $validated['vaccine_id'],
            'hospital_id' => $validated['hospital_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'status' => 'pending',
            'admin_notes' => null,
        ]);

        return redirect()->route('parent.dashboard')
            ->with('success', "Appointment request #{$appointment->booking_reference} submitted successfully! Awaiting Admin approval.");
    }

    /**
     * Module: Report of Vaccination Taken
     * Official immunization card with past completed vaccines, batch numbers, and hospital info
     */
    public function vaccinationReport(Request $request)
    {
        $parent = Auth::user();
        $children = $parent->children()->get();
        $validated = $request->validate([
            'child_id' => [
                'nullable',
                'integer',
                Rule::exists('children', 'id')->where('parent_id', $parent->id),
            ],
        ]);
        $selectedChildId = $validated['child_id'] ?? $children->first()?->id;

        $selectedChild = $children->firstWhere('id', $selectedChildId);

        $completedVaccinations = collect();
        if ($selectedChild) {
            $completedVaccinations = Appointment::with(['vaccine', 'hospital'])
                ->where('child_id', $selectedChild->id)
                ->where('status', 'vaccinated')
                ->orderBy('administered_date', 'desc')
                ->get();
        }

        return view('parent.reports.index', compact('children', 'selectedChild', 'completedVaccinations'));
    }
}

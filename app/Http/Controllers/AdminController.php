<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use App\Models\Child;
use App\Models\Vaccine;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller: AdminController
 * 
 * Handles all administrator capabilities mandated by the SRS:
 * - Central KPI Dashboard
 * - View all child profile details & medical records
 * - Upcoming dates & times of vaccination for all children
 * - Date-wise and child-wise vaccination reporting with CSV export
 * - Vaccine registry management & availability toggling
 * - Parent appointment request approvals / rejections
 * - Full Hospital CRUD management
 * - Central booking records
 */
class AdminController extends Controller
{
    /**
     * Admin Dashboard: KPI metrics, analytics, and quick approval queues
     */
    public function dashboard()
    {
        $stats = [
            'total_children' => Child::count(),
            'total_hospitals' => Hospital::count(),
            'total_vaccines' => Vaccine::count(),
            'available_vaccines' => Vaccine::where('status', 'available')->count(),
            'pending_requests' => Appointment::where('status', 'pending')->count(),
            'approved_appointments' => Appointment::where('status', 'approved')->count(),
            'total_vaccinated' => Appointment::where('status', 'vaccinated')->count(),
            'todays_appointments' => Appointment::whereDate('appointment_date', Carbon::today())->count(),
        ];

        // Pending appointment requests awaiting admin review
        $pendingRequests = Appointment::with(['parent', 'child', 'vaccine', 'hospital'])
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        // Recent completed vaccinations
        $recentVaccinations = Appointment::with(['child', 'vaccine', 'hospital'])
            ->where('status', 'vaccinated')
            ->latest('administered_date')
            ->take(5)
            ->get();

        // Upcoming appointments in next 7 days
        $upcomingSchedule = Appointment::with(['child', 'vaccine', 'hospital'])
            ->whereIn('status', ['approved', 'pending'])
            ->whereBetween('appointment_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingRequests', 'recentVaccinations', 'upcomingSchedule'));
    }

    /**
     * Module: All Child Details
     * View all registered children profiles with parent contact and immunization counters.
     */
    public function allChildren(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'blood_group' => ['nullable', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
        ]);
        $search = $validated['search'] ?? null;
        $bloodGroup = $validated['blood_group'] ?? null;
        $gender = $validated['gender'] ?? null;

        $query = Child::with(['parent', 'appointments.vaccine']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('parent', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($bloodGroup) {
            $query->where('blood_group', $bloodGroup);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        $children = $query->latest()->paginate(10)->withQueryString();

        return view('admin.children.index', compact('children', 'search', 'bloodGroup', 'gender'));
    }

    /**
     * View individual child detailed medical profile and complete vaccination record
     */
    public function childProfile($id)
    {
        $child = Child::with(['parent', 'appointments.vaccine', 'appointments.hospital'])
            ->findOrFail($id);

        $availableVaccines = Vaccine::all();

        return view('admin.children.show', compact('child', 'availableVaccines'));
    }

    /**
     * Module: Date & Time of Vaccination
     * Displays upcoming scheduled vaccination dates of all children across all clinics
     */
    public function vaccinationDates(Request $request)
    {
        $validated = $request->validate([
            'hospital_id' => ['nullable', 'integer', 'exists:hospitals,id'],
            'vaccine_id' => ['nullable', 'integer', 'exists:vaccines,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', Rule::when($request->filled('start_date'), ['after_or_equal:start_date'])],
        ]);
        $hospitalId = $validated['hospital_id'] ?? null;
        $vaccineId = $validated['vaccine_id'] ?? null;
        $startDate = $validated['start_date'] ?? Carbon::today()->toDateString();
        $endDate = $validated['end_date'] ?? Carbon::parse($startDate)->addDays(30)->toDateString();

        $query = Appointment::with(['child.parent', 'vaccine', 'hospital'])
            ->whereIn('status', ['approved', 'pending'])
            ->whereBetween('appointment_date', [$startDate, $endDate]);

        if ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        }

        if ($vaccineId) {
            $query->where('vaccine_id', $vaccineId);
        }

        $appointments = $query->orderBy('appointment_date')->orderBy('appointment_time')->paginate(15)->withQueryString();

        $hospitals = Hospital::active()->orderBy('name')->get();
        $vaccines = Vaccine::orderBy('name')->get();

        return view('admin.vaccination-dates', compact('appointments', 'hospitals', 'vaccines', 'hospitalId', 'vaccineId', 'startDate', 'endDate'));
    }

    /**
     * Module: Report of Vaccination
     * Comprehensive date-wise and child-wise report with filtering and export capability
     */
    public function vaccinationReport(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', Rule::when($request->filled('start_date'), ['after_or_equal:start_date'])],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated'])],
            'child_id' => ['nullable', 'integer', 'exists:children,id'],
            'vaccine_id' => ['nullable', 'integer', 'exists:vaccines,id'],
            'hospital_id' => ['nullable', 'integer', 'exists:hospitals,id'],
        ]);
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $status = $validated['status'] ?? null;
        $childId = $validated['child_id'] ?? null;
        $vaccineId = $validated['vaccine_id'] ?? null;
        $hospitalId = $validated['hospital_id'] ?? null;

        $query = Appointment::with(['child.parent', 'vaccine', 'hospital']);

        if ($startDate && $endDate) {
            $query->whereBetween('appointment_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('appointment_date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('appointment_date', '<=', $endDate);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($childId) {
            $query->where('child_id', $childId);
        }

        if ($vaccineId) {
            $query->where('vaccine_id', $vaccineId);
        }

        if ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        }

        $reports = $query->latest('appointment_date')->paginate(15)->withQueryString();

        $allChildren = Child::orderBy('name')->get();
        $allVaccines = Vaccine::orderBy('name')->get();
        $allHospitals = Hospital::orderBy('name')->get();

        return view('admin.reports.index', compact(
            'reports',
            'startDate',
            'endDate',
            'status',
            'childId',
            'vaccineId',
            'hospitalId',
            'allChildren',
            'allVaccines',
            'allHospitals'
        ));
    }

    /**
     * Export vaccination report to CSV
     */
    public function exportReportCsv(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', Rule::when($request->filled('start_date'), ['after_or_equal:start_date'])],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated'])],
            'hospital_id' => ['nullable', 'integer', 'exists:hospitals,id'],
        ]);
        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;
        $status = $validated['status'] ?? null;
        $hospitalId = $validated['hospital_id'] ?? null;

        $query = Appointment::with(['child.parent', 'vaccine', 'hospital']);

        if ($startDate && $endDate) {
            $query->whereBetween('appointment_date', [$startDate, $endDate]);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($hospitalId) {
            $query->where('hospital_id', $hospitalId);
        }

        $records = $query->latest('appointment_date')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="vaccination_report_' . date('Y_m_d_His') . '.csv"',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Booking Ref',
                'Child Name',
                'DOB',
                'Parent Name',
                'Parent Phone',
                'Vaccine',
                'Hospital',
                'Scheduled Date',
                'Status',
                'Administered Date',
                'Batch Number',
                'Administered By',
                'Remarks'
            ]);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->booking_reference,
                    $row->child->name ?? 'N/A',
                    $row->child->dob?->format('Y-m-d') ?? 'N/A',
                    $row->parent->name ?? 'N/A',
                    $row->parent->phone ?? 'N/A',
                    $row->vaccine->name ?? 'N/A',
                    $row->hospital->name ?? 'N/A',
                    $row->appointment_date?->format('Y-m-d') ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $row->status)),
                    $row->administered_date?->format('Y-m-d') ?? '-',
                    $row->batch_number ?? '-',
                    $row->administered_by ?? '-',
                    $row->hospital_remarks ?? '-',
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Module: List of Vaccine
     * Shows all vaccines with status (available or unavailable)
     */
    public function vaccinesIndex()
    {
        $vaccines = Vaccine::withCount('appointments')->orderBy('recommended_age_days')->get();
        return view('admin.vaccines.index', compact('vaccines'));
    }

    /**
     * Store new vaccine
     */
    public function storeVaccine(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:30|unique:vaccines,short_code',
            'target_disease' => 'required|string|max:255',
            'recommended_age_days' => 'required|integer|min:0|max:2147483647',
            'recommended_age_label' => 'required|string|max:100',
            'dosage' => 'required|string|max:50',
            'route' => 'required|string|max:50',
            'status' => 'required|in:available,unavailable',
            'description' => 'nullable|string|max:5000',
            'precautions' => 'nullable|string|max:5000',
        ]);

        Vaccine::create($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', "Vaccine '{$validated['name']}' added successfully.");
    }

    /**
     * Update existing vaccine
     */
    public function updateVaccine(Request $request, $id)
    {
        $vaccine = Vaccine::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => ['required', 'string', 'max:30', Rule::unique('vaccines', 'short_code')->ignore($id)],
            'target_disease' => 'required|string|max:255',
            'recommended_age_days' => 'required|integer|min:0|max:2147483647',
            'recommended_age_label' => 'required|string|max:100',
            'dosage' => 'required|string|max:50',
            'route' => 'required|string|max:50',
            'status' => 'required|in:available,unavailable',
            'description' => 'nullable|string|max:5000',
            'precautions' => 'nullable|string|max:5000',
        ]);

        $vaccine->update($validated);

        return redirect()->route('admin.vaccines.index')
            ->with('success', "Vaccine '{$vaccine->name}' updated successfully.");
    }

    /**
     * Toggle vaccine status (Available / Unavailable)
     */
    public function toggleVaccineStatus($id)
    {
        $vaccine = Vaccine::findOrFail($id);
        $vaccine->status = ($vaccine->status === 'available') ? 'unavailable' : 'available';
        $vaccine->save();

        return redirect()->back()
            ->with('success', "Vaccine '{$vaccine->name}' status updated to {$vaccine->status}.");
    }

    /**
     * Delete vaccine
     */
    public function deleteVaccine($id)
    {
        $vaccine = Vaccine::findOrFail($id);
        $vaccine->delete();

        return redirect()->route('admin.vaccines.index')
            ->with('success', "Vaccine deleted successfully.");
    }

    /**
     * Module: Request from Parents
     * Dedicated approval queue for incoming booking requests
     */
    public function parentRequests(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated', 'all'])],
        ]);
        $status = $validated['status'] ?? 'pending';

        $query = Appointment::with(['parent', 'child', 'vaccine', 'hospital']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->latest()->paginate(12)->withQueryString();

        return view('admin.parent-requests.index', compact('requests', 'status'));
    }

    /**
     * Approve appointment request
     */
    public function approveRequest(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $appointment->status = 'approved';
        $appointment->admin_notes = $validated['admin_notes'] ?? 'Approved by administrator.';
        $appointment->admin_action_at = Carbon::now();
        $appointment->save();

        return redirect()->back()
            ->with('success', "Appointment {$appointment->booking_reference} has been approved.");
    }

    /**
     * Reject appointment request
     */
    public function rejectRequest(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $appointment->status = 'rejected';
        $appointment->admin_notes = $validated['admin_notes'] ?? 'Request could not be accommodated.';
        $appointment->admin_action_at = Carbon::now();
        $appointment->save();

        return redirect()->back()
            ->with('info', "Appointment {$appointment->booking_reference} has been rejected.");
    }

    /**
     * Module: Add Hospital, Update/Delete Hospital, List of Hospitals
     */
    public function hospitalsIndex()
    {
        $hospitals = Hospital::withCount(['appointments', 'appointments as vaccinated_count' => function ($q) {
            $q->where('status', 'vaccinated');
        }])->latest()->paginate(10);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    /**
     * Add Hospital (creates both hospital institution and optional staff user login)
     */
    public function storeHospital(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', 'unique:hospitals,email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $userId = null;
        if (!empty($validated['password'])) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'hospital',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
            ]);
            $userId = $user->id;
        }

        Hospital::create([
            'user_id' => $userId,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'location' => $validated['location'],
            'contact_person' => $validated['contact_person'] ?? null,
            'operating_hours' => $validated['operating_hours'] ?? '08:00 AM - 04:00 PM',
            'status' => 'active',
        ]);

        return redirect()->route('admin.hospitals.index')
            ->with('success', "Hospital '{$validated['name']}' added successfully.");
    }

    /**
     * Update Hospital details
     */
    public function updateHospital(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('hospitals', 'email')->ignore($hospital->id),
                Rule::unique('users', 'email')->ignore($hospital->user_id),
            ],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-().\s]+$/'],
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'operating_hours' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $hospital->update($validated);

        if ($hospital->user) {
            $hospital->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
            ]);
        }

        return redirect()->route('admin.hospitals.index')
            ->with('success', "Hospital '{$hospital->name}' updated successfully.");
    }

    /**
     * Delete Hospital
     */
    public function deleteHospital($id)
    {
        $hospital = Hospital::findOrFail($id);
        if ($hospital->user) {
            $hospital->user->delete();
        }
        $hospital->delete();

        return redirect()->route('admin.hospitals.index')
            ->with('success', 'Hospital removed successfully.');
    }

    /**
     * Module: Booking Details
     * Master registry of all bookings from parent side
     */
    public function bookingDetails(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated'])],
        ]);
        $search = $validated['search'] ?? null;
        $status = $validated['status'] ?? null;

        $query = Appointment::with(['parent', 'child', 'vaccine', 'hospital']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('child', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('parent', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings', 'search', 'status'));
    }
}

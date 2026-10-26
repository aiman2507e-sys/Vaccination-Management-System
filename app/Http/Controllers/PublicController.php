<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Vaccine;
use App\Models\Child;
use App\Models\Appointment;
use Illuminate\Http\Request;

/**
 * Controller: PublicController
 * 
 * Provides public awareness, hospital discovery, and immunization reference.
 */
class PublicController extends Controller
{
    /**
     * Home Landing Page
     */
    public function home()
    {
        $stats = [
            'children_protected' => Appointment::where('status', 'vaccinated')->count(),
            'active_hospitals' => Hospital::active()->count(),
            'available_vaccines' => Vaccine::available()->count(),
            'appointments_booked' => Appointment::count(),
        ];

        $vaccines = Vaccine::orderBy('recommended_age_days')->take(8)->get();
        $hospitals = Hospital::active()->take(4)->get();

        return view('public.home', compact('stats', 'vaccines', 'hospitals'));
    }

    /**
     * Public Immunization Schedule
     */
    public function vaccineSchedule()
    {
        $vaccines = Vaccine::orderBy('recommended_age_days')->get();
        return view('public.schedule', compact('vaccines'));
    }

    /**
     * Public Hospital Directory
     */
    public function hospitalsList(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = $validated['search'] ?? null;
        $query = Hospital::active();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $hospitals = $query->paginate(9)->withQueryString();

        return view('public.hospitals', compact('hospitals', 'search'));
    }
}

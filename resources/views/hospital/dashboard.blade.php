@extends('layouts.hospital')

@section('title', 'Hospital Clinic Dashboard')
@section('page_title', $hospital->name . ' - Clinical Operations')

@section('content')
<div class="space-y-8">
    <!-- Facility Info Banner -->
    <div class="bg-gradient-to-r from-teal-700 to-cyan-800 rounded-3xl p-8 text-white shadow-lg shadow-teal-700/15 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="inline-flex items-center gap-2 bg-teal-600/60 px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase mb-2">
                <i class="fa-solid fa-hospital"></i> Verified Immunization Center
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight">{{ $hospital->name }}</h1>
            <p class="text-teal-100 text-sm mt-1">
                <i class="fa-solid fa-location-dot mr-1 text-teal-300"></i> {{ $hospital->location }}, {{ $hospital->city }} &bull; 
                <i class="fa-regular fa-clock ml-2 mr-1 text-teal-300"></i> {{ $hospital->operating_hours }}
            </p>
        </div>

        <div>
            <a href="{{ route('hospital.appointments.index') }}" class="inline-flex items-center gap-2 bg-white text-teal-900 hover:bg-teal-50 font-bold px-5 py-3 rounded-2xl text-xs transition-all shadow-sm">
                <i class="fa-solid fa-list-check"></i>
                <span>View Full Queue</span>
            </a>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Scheduled Today</p>
                <h3 class="text-3xl font-extrabold text-teal-600 mt-1">{{ $stats['today_count'] }}</h3>
                <p class="text-xs text-teal-700 font-semibold mt-1"><i class="fa-solid fa-calendar-day mr-1"></i> Today's Queue</p>
            </div>
            <div class="w-13 h-13 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Upcoming Approved</p>
                <h3 class="text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['upcoming_approved'] }}</h3>
                <p class="text-xs text-blue-700 font-semibold mt-1"><i class="fa-solid fa-clock mr-1"></i> Forward Schedules</p>
            </div>
            <div class="w-13 h-13 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Doses Administered</p>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['total_vaccinated'] }}</h3>
                <p class="text-xs text-emerald-700 font-semibold mt-1"><i class="fa-solid fa-circle-check mr-1"></i> Completed</p>
            </div>
            <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-syringe"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Missed Visits</p>
                <h3 class="text-3xl font-extrabold text-rose-600 mt-1">{{ $stats['total_missed'] }}</h3>
                <p class="text-xs text-rose-700 font-semibold mt-1"><i class="fa-solid fa-circle-xmark mr-1"></i> Not Vaccinated</p>
            </div>
            <div class="w-13 h-13 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-xmark"></i>
            </div>
        </div>
    </div>

    <!-- Today's Queue Section -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-ping"></span>
                <h3 class="font-bold text-base text-slate-900">Today's Vaccination Queue</h3>
            </div>
            <span class="text-xs text-slate-400">{{ date('l, F j, Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3.5 px-6">Time Slot</th>
                        <th class="py-3.5 px-6">Booking Ref #</th>
                        <th class="py-3.5 px-6">Child Name & Age</th>
                        <th class="py-3.5 px-6">Parent & Phone</th>
                        <th class="py-3.5 px-6">Vaccine to Administer</th>
                        <th class="py-3.5 px-6 text-right">Update Status (SRS)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($todaysAppointments as $apt)
                        <tr class="hover:bg-slate-50">
                            <td class="py-4 px-6 whitespace-nowrap font-bold text-teal-800">
                                {{ $apt->appointment_time }}
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-slate-700">
                                {{ $apt->booking_reference }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-sm">{{ $apt->child->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->child->formatted_age }} &bull; {{ $apt->child->blood_group }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $apt->child->parent->name ?? 'Parent' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->child->parent->phone ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-extrabold text-slate-900">{{ $apt->vaccine->name }}</div>
                                <div class="text-[11px] text-brand-700 font-semibold">Dose: {{ $apt->vaccine->dosage }} ({{ $apt->vaccine->route }})</div>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('hospital.appointments.status.form', $apt->id) }}" class="inline-flex items-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-xs">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Update Vaccine Status</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No appointments queued for today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

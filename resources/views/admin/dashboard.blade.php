@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Central Immunization Control Panel')

@section('content')
<!-- KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Card 1: Total Children -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Children</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total_children'] }}</h3>
            <p class="text-xs text-brand-600 font-semibold mt-1"><i class="fa-solid fa-child mr-1"></i> Registered Infants</p>
        </div>
        <div class="w-13 h-13 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-children"></i>
        </div>
    </div>

    <!-- Card 2: Pending Requests -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pending Requests</p>
            <h3 class="text-3xl font-extrabold text-amber-600 mt-1">{{ $stats['pending_requests'] }}</h3>
            <p class="text-xs text-amber-600 font-semibold mt-1"><i class="fa-solid fa-clock mr-1"></i> Awaiting Approval</p>
        </div>
        <div class="w-13 h-13 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

    <!-- Card 3: Vaccinated (Completed) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Vaccinated</p>
            <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['total_vaccinated'] }}</h3>
            <p class="text-xs text-emerald-600 font-semibold mt-1"><i class="fa-solid fa-circle-check mr-1"></i> Doses Completed</p>
        </div>
        <div class="w-13 h-13 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-syringe"></i>
        </div>
    </div>

    <!-- Card 4: Hospitals -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Partner Hospitals</p>
            <h3 class="text-3xl font-extrabold text-indigo-600 mt-1">{{ $stats['total_hospitals'] }}</h3>
            <p class="text-xs text-indigo-600 font-semibold mt-1"><i class="fa-solid fa-hospital mr-1"></i> Active Facilities</p>
        </div>
        <div class="w-13 h-13 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
            <i class="fa-solid fa-hospital-user"></i>
        </div>
    </div>
</div>

<!-- Pending Requests Queue (SRS: Request from parents - Approve or Reject) -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs mb-8 overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <h3 class="text-lg font-bold text-slate-900">Incoming Parent Appointment Requests</h3>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Review and approve parent booking schedules to dispatch to hospitals</p>
        </div>
        <a href="{{ route('admin.requests.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1.5">
            <span>View All Requests</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <th class="py-3 px-6">Ref #</th>
                    <th class="py-3 px-6">Child & Parent</th>
                    <th class="py-3 px-6">Vaccine</th>
                    <th class="py-3 px-6">Hospital</th>
                    <th class="py-3 px-6">Requested Date</th>
                    <th class="py-3 px-6 text-right">Approve / Reject Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($pendingRequests as $req)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-4 px-6 font-mono font-bold text-xs text-slate-600">
                            {{ $req->booking_reference }}
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900">{{ $req->child->name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-500">Parent: {{ $req->parent->name ?? 'N/A' }} ({{ $req->parent->phone ?? 'N/A' }})</div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-semibold text-slate-800">{{ $req->vaccine->name ?? 'N/A' }}</span>
                            <div class="text-xs text-slate-500">{{ $req->vaccine->short_code ?? '' }}</div>
                        </td>
                        <td class="py-4 px-6 text-slate-700">
                            <i class="fa-solid fa-hospital text-xs text-slate-400 mr-1"></i>
                            {{ $req->hospital->name ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6 text-slate-700 whitespace-nowrap">
                            <div class="font-medium">{{ $req->appointment_date?->format('M d, Y') }}</div>
                            <div class="text-xs text-slate-500">{{ $req->appointment_time }}</div>
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap">
                            <div class="inline-flex items-center gap-2">
                                <form action="{{ route('admin.requests.approve', $req->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-all shadow-xs flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Approve</span>
                                    </button>
                                </form>

                                <form action="{{ route('admin.requests.reject', $req->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to reject this appointment request?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1">
                                        <i class="fa-solid fa-xmark"></i>
                                        <span>Reject</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                            <i class="fa-solid fa-inbox text-3xl mb-2 text-slate-300 block"></i>
                            No pending parent appointment requests right now.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Two-Column Grid: Upcoming Schedules & Recent Vaccinations -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Upcoming Schedules -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Upcoming Vaccination Dates</h3>
                <p class="text-xs text-slate-500">Next 7 days across all clinics</p>
            </div>
            <a href="{{ route('admin.vaccination.dates') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">View All</a>
        </div>

        <div class="space-y-3">
            @forelse($upcomingSchedule as $item)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900 text-sm">{{ $item->child->name ?? 'N/A' }}</div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            <span class="font-semibold text-brand-700">{{ $item->vaccine->name ?? 'N/A' }}</span> &bull; {{ $item->hospital->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-800">{{ $item->appointment_date?->format('M d') }}</span>
                        <div class="text-[11px] text-slate-500">{{ $item->appointment_time }}</div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">No upcoming appointments in the next 7 days.</p>
            @endforelse
        </div>
    </div>

    <!-- Recently Administered Vaccinations -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Recently Administered Doses</h3>
                <p class="text-xs text-slate-500">Verified by hospital clinical staff</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">Full Report</a>
        </div>

        <div class="space-y-3">
            @forelse($recentVaccinations as $item)
                <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-100 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <span>{{ $item->child->name ?? 'N/A' }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">Vaccinated</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $item->vaccine->name ?? 'N/A' }} &bull; Lot: <span class="font-mono">{{ $item->batch_number ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="text-right text-xs">
                        <span class="font-bold text-emerald-800">{{ $item->administered_date?->format('M d, Y') ?? 'Done' }}</span>
                        <div class="text-[11px] text-slate-500 truncate max-w-[120px]">{{ $item->administered_by ?? $item->hospital->name }}</div>
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">No completed vaccinations recorded yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

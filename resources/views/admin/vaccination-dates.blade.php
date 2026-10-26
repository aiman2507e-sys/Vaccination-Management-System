@extends('layouts.admin')

@section('title', 'Upcoming Vaccination Dates')
@section('page_title', 'Date & Time of Vaccination of All Children')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <form method="GET" action="{{ route('admin.vaccination.dates') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Start Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Hospital Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Hospital Clinic</label>
                <select name="hospital_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Hospitals</option>
                    @foreach($hospitals as $hosp)
                        <option value="{{ $hosp->id }}" {{ $hospitalId == $hosp->id ? 'selected' : '' }}>{{ $hosp->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Vaccine Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Vaccine</label>
                <select name="vaccine_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Vaccines</option>
                    @foreach($vaccines as $vac)
                        <option value="{{ $vac->id }}" {{ $vaccineId == $vac->id ? 'selected' : '' }}>{{ $vac->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-xs">
                    Apply Filter
                </button>
                <a href="{{ route('admin.vaccination.dates') }}" class="p-2.5 bg-slate-200 text-slate-600 hover:text-slate-900 rounded-xl text-sm" title="Reset">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Upcoming Vaccination Dates Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Scheduled Vaccination Timeline</h3>
                <p class="text-xs text-slate-500">All children with confirmed or pending vaccination schedules</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Scheduled Date & Time</th>
                        <th class="py-4 px-6">Child Name & Age</th>
                        <th class="py-4 px-6">Parent Contact</th>
                        <th class="py-4 px-6">Vaccine & Dose</th>
                        <th class="py-4 px-6">Designated Hospital</th>
                        <th class="py-4 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-regular fa-calendar-check text-brand-600"></i>
                                    <span>{{ $apt->appointment_date?->format('D, M d, Y') }}</span>
                                </div>
                                <div class="text-xs text-slate-500 ml-6">{{ $apt->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.children.show', $apt->child->id) }}" class="font-bold text-brand-600 hover:underline">
                                    {{ $apt->child->name ?? 'N/A' }}
                                </a>
                                <div class="text-xs text-slate-500">{{ $apt->child->formatted_age ?? '' }} &bull; {{ $apt->child->blood_group ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $apt->parent->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $apt->parent->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $apt->vaccine->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $apt->vaccine->dosage ?? '' }} ({{ $apt->vaccine->route ?? '' }})</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <div class="font-medium">{{ $apt->hospital->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400">{{ $apt->hospital->location ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $apt->status_badge_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-300 block"></i>
                                No upcoming vaccination appointments found in this range.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection

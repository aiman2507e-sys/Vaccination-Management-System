@extends('layouts.parent')

@section('title', 'Immunization Timeline - ' . $child->name)

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('parent.children.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to All Children</span>
        </a>
    </div>

    <!-- Child Profile Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 md:p-8 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-2xl">
                    {{ strtoupper(substr($child->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-2xl font-extrabold text-slate-900">{{ $child->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700">
                            {{ $child->gender }}
                        </span>
                    </div>
                    <p class="text-xs text-brand-700 font-bold mt-0.5">
                        {{ $child->formatted_age }} &bull; Born {{ $child->dob->format('M d, Y') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('parent.children.edit', $child->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-xs font-bold text-slate-700">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>Edit Profile</span>
                </a>
                <a href="{{ route('parent.book.hospital', ['child_id' => $child->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-xs">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>Book Vaccine</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 text-xs">
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider">Blood Group</span>
                <p class="font-bold text-sm text-rose-700 mt-0.5">{{ $child->blood_group }}</p>
            </div>
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider">Birth Weight</span>
                <p class="font-semibold text-sm text-slate-800 mt-0.5">{{ $child->birth_weight ? $child->birth_weight . ' kg' : 'N/A' }}</p>
            </div>
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider">Allergies</span>
                <p class="font-medium text-slate-700 mt-0.5">{{ $child->allergies ?: 'None known' }}</p>
            </div>
            <div>
                <span class="font-bold text-slate-400 uppercase tracking-wider">Birth Place</span>
                <p class="font-medium text-slate-700 mt-0.5">{{ $child->birth_place ?: 'Not specified' }}</p>
            </div>
        </div>
    </div>

    <!-- Complete Immunization Schedule & Progress -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Pediatric Immunization Matrix</h3>
                <p class="text-xs text-slate-500">Track which vaccines have been taken and schedule upcoming milestones</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Milestone Age</th>
                        <th class="py-4 px-6">Vaccine & Code</th>
                        <th class="py-4 px-6">Target Disease</th>
                        <th class="py-4 px-6">Status for {{ $child->name }}</th>
                        <th class="py-4 px-6">Administration / Booking Info</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($allVaccines as $vac)
                        @php
                            $appointment = $child->appointments->where('vaccine_id', $vac->id)->first();
                        @endphp
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold">
                                    {{ $vac->recommended_age_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-sm">{{ $vac->name }}</div>
                                <div class="font-mono text-slate-400">{{ $vac->short_code }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $vac->target_disease }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($appointment && $appointment->status === 'vaccinated')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-circle-check"></i> Vaccinated
                                    </span>
                                @elseif($appointment && $appointment->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-blue-100 text-blue-800">
                                        <i class="fa-regular fa-calendar-check"></i> Confirmed / Scheduled
                                    </span>
                                @elseif($appointment && $appointment->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-amber-100 text-amber-800">
                                        <i class="fa-solid fa-clock"></i> Pending Approval
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-semibold bg-slate-100 text-slate-600">
                                        Not Yet Taken
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                @if($appointment && $appointment->status === 'vaccinated')
                                    <div>Date Given: <strong class="text-emerald-700">{{ $appointment->administered_date?->format('M d, Y') }}</strong></div>
                                    <div>Hospital: {{ $appointment->hospital->name ?? 'Clinic' }}</div>
                                    <div class="text-[11px] text-slate-400">Lot: {{ $appointment->batch_number ?? 'N/A' }}</div>
                                @elseif($appointment)
                                    <div>Date: {{ $appointment->appointment_date?->format('M d, Y') }} ({{ $appointment->appointment_time }})</div>
                                    <div>Clinic: {{ $appointment->hospital->name ?? 'Hospital' }}</div>
                                @else
                                    <span class="text-slate-400">Dosage: {{ $vac->dosage }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if(!$appointment || $appointment->status === 'rejected')
                                    <a href="{{ route('parent.book.hospital', ['child_id' => $child->id, 'vaccine_id' => $vac->id]) }}" class="inline-flex items-center gap-1 bg-brand-600 hover:bg-brand-700 text-white font-bold px-3 py-1.5 rounded-lg shadow-xs">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                        <span>Book Now</span>
                                    </a>
                                @elseif($appointment->status === 'vaccinated')
                                    <a href="{{ route('parent.reports.index', ['child_id' => $child->id]) }}" class="inline-flex items-center gap-1 text-emerald-700 hover:underline font-bold">
                                        <i class="fa-solid fa-award"></i>
                                        <span>Certificate</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-mono text-[11px]">#{{ $appointment->booking_reference }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

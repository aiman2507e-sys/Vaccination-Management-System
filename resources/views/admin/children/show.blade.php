@extends('layouts.admin')

@section('title', 'Child Profile - ' . $child->name)
@section('page_title', 'Child Profile & Immunization History')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.children.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to All Children</span>
        </a>
    </div>

    <!-- Child Summary Profile Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-5">
                <div class="w-18 h-18 rounded-3xl bg-gradient-to-tr from-brand-600 to-teal-400 text-white flex items-center justify-center font-black text-2xl shadow-md shadow-brand-500/20">
                    {{ strtoupper(substr($child->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-extrabold text-slate-900">{{ $child->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                            {{ $child->gender }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        Child Registration Code: <span class="font-mono font-bold text-slate-700">#CH-{{ str_pad($child->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </p>
                </div>
            </div>

            <!-- Print Card Button -->
            <button onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-xs self-start md:self-auto">
                <i class="fa-solid fa-print"></i>
                <span>Print Immunization Card</span>
            </button>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-6 text-sm">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Date of Birth</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $child->dob->format('M d, Y') }}</p>
                <p class="text-xs text-brand-700 font-bold mt-0.5">{{ $child->formatted_age }}</p>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Blood Group</span>
                <p class="font-bold text-rose-700 mt-0.5">{{ $child->blood_group }}</p>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Birth Weight</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $child->birth_weight ? $child->birth_weight . ' kg' : 'Not recorded' }}</p>
            </div>
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Birth Place</span>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $child->birth_place ?? 'Hospital/Clinic' }}</p>
            </div>
        </div>

        <!-- Parent & Health Details -->
        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-500 mb-2">Parent / Guardian Information</h4>
                <p class="font-bold text-slate-900">{{ $child->parent->name ?? 'N/A' }}</p>
                <p class="text-xs text-slate-600 mt-1"><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i> {{ $child->parent->phone ?? 'N/A' }}</p>
                <p class="text-xs text-slate-600 mt-0.5"><i class="fa-regular fa-envelope mr-1.5 text-slate-400"></i> {{ $child->parent->email ?? 'N/A' }}</p>
                <p class="text-xs text-slate-600 mt-0.5"><i class="fa-solid fa-map-pin mr-1.5 text-slate-400"></i> {{ $child->parent->address ?? 'N/A' }}, {{ $child->parent->city ?? '' }}</p>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-500 mb-2">Medical History & Allergies</h4>
                <p class="text-xs text-slate-700"><strong class="text-slate-900">Allergies:</strong> {{ $child->allergies ?: 'No known allergies reported.' }}</p>
                <p class="text-xs text-slate-700 mt-2"><strong class="text-slate-900">Clinical History:</strong> {{ $child->medical_history ?: 'Routine pediatric growth profile.' }}</p>
            </div>
        </div>
    </div>

    <!-- Complete Vaccination Record -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Vaccination Appointments & History</h3>
                <p class="text-xs text-slate-500">Record of all past, scheduled, and pending doses for this child</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3.5 px-6">Ref #</th>
                        <th class="py-3.5 px-6">Vaccine Name</th>
                        <th class="py-3.5 px-6">Scheduled Date</th>
                        <th class="py-3.5 px-6">Hospital Facility</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6">Administration Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($child->appointments as $apt)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-4 px-6 font-mono font-bold text-xs text-slate-600">{{ $apt->booking_reference }}</td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $apt->vaccine->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $apt->vaccine->target_disease ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $apt->appointment_date?->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $apt->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                {{ $apt->hospital->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $apt->status_badge_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600">
                                @if($apt->status === 'vaccinated')
                                    <div><strong>Given:</strong> {{ $apt->administered_date?->format('M d, Y') }}</div>
                                    <div><strong>Batch:</strong> <span class="font-mono">{{ $apt->batch_number ?? '-' }}</span></div>
                                    <div><strong>By:</strong> {{ $apt->administered_by ?? '-' }}</div>
                                    @if($apt->hospital_remarks)
                                        <div class="text-slate-500 italic mt-0.5">"{{ $apt->hospital_remarks }}"</div>
                                    @endif
                                @elseif($apt->status === 'approved')
                                    <span class="text-blue-700 font-semibold">Scheduled & Confirmed</span>
                                @elseif($apt->status === 'pending')
                                    <span class="text-amber-700 font-semibold">Pending Admin Review</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                No vaccination appointments recorded yet for this child.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

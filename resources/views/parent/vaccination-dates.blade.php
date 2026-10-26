@extends('layouts.parent')

@section('title', 'Upcoming Vaccination Dates')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Upcoming Vaccination Schedules & Due Dates</h2>
            <p class="text-xs text-slate-500">Live timeline of scheduled hospital visits and upcoming pediatric milestone recommendations</p>
        </div>
        <a href="{{ route('parent.book.hospital') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-brand-600/20">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Schedule Next Visit</span>
        </a>
    </div>

    <!-- 1. Scheduled / Confirmed Appointments -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                <h3 class="font-bold text-base text-slate-900">Confirmed & Scheduled Hospital Appointments</h3>
            </div>
            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full">{{ $scheduledAppointments->count() }} Scheduled</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Date & Time</th>
                        <th class="py-4 px-6">Child</th>
                        <th class="py-4 px-6">Vaccine Dose</th>
                        <th class="py-4 px-6">Designated Hospital</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($scheduledAppointments as $apt)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-900 text-sm">{{ $apt->appointment_date?->format('l, M d, Y') }}</div>
                                <div class="text-slate-500 mt-0.5"><i class="fa-regular fa-clock mr-1"></i> {{ $apt->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $apt->child->name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->child->formatted_age }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800 text-sm">{{ $apt->vaccine->name }}</div>
                                <div class="text-[11px] text-brand-700 font-semibold">{{ $apt->vaccine->target_disease }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <div class="font-medium">{{ $apt->hospital->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $apt->hospital->location }}, {{ $apt->hospital->city }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full font-bold border {{ $apt->status_badge_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-500 whitespace-nowrap">
                                {{ $apt->booking_reference }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                No upcoming appointments scheduled.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. Recommended Upcoming Vaccination Milestones Per Child -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <div class="pb-4 mb-6 border-b border-slate-100">
            <h3 class="font-bold text-base text-slate-900">Standard Timeline Recommendations</h3>
            <p class="text-xs text-slate-500">Upcoming milestone doses recommended for your children by age</p>
        </div>

        <div class="space-y-6">
            @foreach($children as $child)
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/60">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($child->name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-base">{{ $child->name }}</h4>
                                <p class="text-xs text-slate-500">Age: {{ $child->formatted_age }} &bull; DOB: {{ $child->dob->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('parent.children.show', $child->id) }}" class="text-xs font-bold text-brand-600 hover:underline">
                            Full Timeline &rarr;
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                        @foreach($availableVaccines->take(6) as $vac)
                            @php
                                $isTaken = $child->appointments->where('vaccine_id', $vac->id)->where('status', 'vaccinated')->isNotEmpty();
                            @endphp
                            <div class="p-3 rounded-xl bg-white border {{ $isTaken ? 'border-emerald-200 bg-emerald-50/30' : 'border-slate-200' }} flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $vac->recommended_age_label }}</span>
                                    <div class="font-bold text-slate-900 mt-0.5">{{ $vac->name }}</div>
                                </div>
                                <div>
                                    @if($isTaken)
                                        <span class="text-emerald-600 text-sm" title="Vaccinated"><i class="fa-solid fa-circle-check"></i></span>
                                    @else
                                        <a href="{{ route('parent.book.hospital', ['child_id' => $child->id, 'vaccine_id' => $vac->id]) }}" class="px-2 py-1 rounded-md bg-brand-50 hover:bg-brand-100 text-brand-700 font-bold text-[11px]">
                                            Book
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@extends('layouts.hospital')

@section('title', 'Hospital Vaccination Appointments')
@section('page_title', 'Vaccine Appointments Queue')

@section('content')
<div class="space-y-6">
    <!-- Header with Filter & Search -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('hospital.appointments.index', ['status' => 'approved']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'approved' ? 'bg-teal-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-clock mr-1"></i> Scheduled / Approved
            </a>
            <a href="{{ route('hospital.appointments.index', ['status' => 'vaccinated']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'vaccinated' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-circle-check mr-1"></i> Vaccinated
            </a>
            <a href="{{ route('hospital.appointments.index', ['status' => 'not_vaccinated']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'not_vaccinated' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-circle-xmark mr-1"></i> Missed
            </a>
            <a href="{{ route('hospital.appointments.index', ['status' => 'all']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All Records
            </a>
        </div>

        <!-- Search input -->
        <form method="GET" action="{{ route('hospital.appointments.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ref # or child name..." class="pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500">
            </div>
            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-3 py-2 rounded-xl text-xs">Search</button>
            @if($search)
                <a href="{{ route('hospital.appointments.index', ['status' => $status]) }}" class="p-2 bg-slate-200 text-slate-600 rounded-xl text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Date & Slot</th>
                        <th class="py-4 px-6">Booking Ref #</th>
                        <th class="py-4 px-6">Child Name</th>
                        <th class="py-4 px-6">Parent Info</th>
                        <th class="py-4 px-6">Vaccine</th>
                        <th class="py-4 px-6">Current Status</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-slate-50">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $apt->appointment_date?->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $apt->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-slate-700 whitespace-nowrap">
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
                                <div class="text-[11px] text-brand-700 font-semibold">{{ $apt->vaccine->dosage }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full font-bold border {{ $apt->status_badge_class }}">
                                    {{ $apt->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('hospital.appointments.status.form', $apt->id) }}" class="inline-flex items-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-all shadow-xs">
                                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                                    <span>Update Status</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                No appointments found for selected filter.
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

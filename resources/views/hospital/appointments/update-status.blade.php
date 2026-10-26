@extends('layouts.hospital')

@section('title', 'Update Vaccine Status - ' . $appointment->booking_reference)
@section('page_title', 'Update Vaccine Administration Status')

@section('content')
<div class="max-w-2xl mx-auto space-y-6" x-data="{
    statusValue: '{{ old('status', $appointment->status === 'vaccinated' ? 'vaccinated' : ($appointment->status === 'not_vaccinated' ? 'not_vaccinated' : 'vaccinated')) }}'
}">
    <div>
        <a href="{{ route('hospital.appointments.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-teal-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Appointments Queue</span>
        </a>
    </div>

    <!-- Appointment & Patient Summary Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-600">Patient File</span>
                <h3 class="text-lg font-extrabold text-slate-900">{{ $appointment->child->name }}</h3>
                <p class="text-xs text-slate-500">{{ $appointment->child->formatted_age }} &bull; Blood Group: <strong class="text-rose-600">{{ $appointment->child->blood_group }}</strong></p>
            </div>
            <div class="text-right">
                <span class="font-mono text-xs font-bold text-slate-600">Ref: {{ $appointment->booking_reference }}</span>
                <div class="mt-1">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $appointment->status_badge_class }}">
                        {{ $appointment->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 font-bold uppercase">Prescribed Vaccine</span>
                <p class="font-extrabold text-slate-900 text-sm mt-0.5">{{ $appointment->vaccine->name }}</p>
                <p class="text-teal-700 font-semibold mt-0.5">{{ $appointment->vaccine->target_disease }} ({{ $appointment->vaccine->dosage }})</p>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl">
                <span class="text-slate-400 font-bold uppercase">Parent Contact</span>
                <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $appointment->child->parent->name ?? 'Parent' }}</p>
                <p class="text-slate-600 mt-0.5"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $appointment->child->parent->phone ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Status Update Form -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <form action="{{ route('hospital.appointments.status.update', $appointment->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. Status Selection (Mandated by SRS) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Vaccine Administration Status (SRS Module) *</label>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Option 1: Vaccinated -->
                    <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer hover:bg-emerald-50/30 transition-all" :class="statusValue === 'vaccinated' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500' : 'border-slate-200'">
                        <input type="radio" name="status" value="vaccinated" x-model="statusValue" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">
                                <i class="fa-solid fa-syringe"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Vaccinated</h4>
                                <p class="text-[11px] text-emerald-800">Dose successfully administered</p>
                            </div>
                        </div>
                    </label>

                    <!-- Option 2: Not Vaccinated / Missed -->
                    <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer hover:bg-rose-50/30 transition-all" :class="statusValue === 'not_vaccinated' ? 'border-rose-500 bg-rose-50/50 ring-2 ring-rose-500' : 'border-slate-200'">
                        <input type="radio" name="status" value="not_vaccinated" x-model="statusValue" class="sr-only">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-base">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900">Not Vaccinated</h4>
                                <p class="text-[11px] text-rose-800">Child missed visit or deferred</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Fields when status is Vaccinated -->
            <div x-show="statusValue === 'vaccinated'" x-transition class="space-y-4 pt-4 border-t border-slate-100 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Date Administered -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Administration Date *</label>
                        <input type="date" name="administered_date" value="{{ old('administered_date', $appointment->administered_date?->format('Y-m-d') ?? date('Y-m-d')) }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                    </div>

                    <!-- Batch / Lot Number -->
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Vaccine Vial Batch / Lot # *</label>
                        <input type="text" name="batch_number" value="{{ old('batch_number', $appointment->batch_number ?? 'LOT-' . strtoupper(substr(uniqid(), -6))) }}" placeholder="e.g. BCG-SERUM-8902A" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <!-- Administered By -->
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Administering Nurse / Medical Officer Name *</label>
                    <input type="text" name="administered_by" value="{{ old('administered_by', $appointment->administered_by ?? auth()->user()->name) }}" placeholder="e.g. Dr. Eleanor Vance / Nurse Jenkins" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <!-- Clinical Remarks -->
            <div class="text-xs">
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Clinical Remarks & Observations
                </label>
                <textarea name="hospital_remarks" rows="3" placeholder="Enter observations: e.g. Child showed no immediate adverse event. Parent advised to monitor temperature." class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">{{ old('hospital_remarks', $appointment->hospital_remarks) }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('hospital.appointments.index') }}" class="px-5 py-2.5 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md shadow-teal-600/20">
                    Save Vaccine Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

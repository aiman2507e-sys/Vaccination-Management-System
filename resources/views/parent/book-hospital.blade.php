@extends('layouts.parent')

@section('title', 'Book Hospital Vaccination Appointment')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="text-center max-w-xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full">Appointment Request</span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-2">Book Hospital Vaccination Slot</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Select your child, choose the required vaccine, pick an authorized hospital, and choose your preferred date and time.
        </p>
    </div>

    <!-- Booking Form Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <form action="{{ route('parent.book.hospital.store') }}" method="POST" class="space-y-6" data-timezone-offset="{{ now()->offset }}">
            @csrf

            <!-- 1. Select Child -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">1. Select Child *</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($children as $child)
                        <label class="relative flex items-center p-4 rounded-2xl border cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40 has-[:checked]:ring-2 has-[:checked]:ring-brand-500">
                            <input type="radio" name="child_id" value="{{ $child->id }}" {{ ($preselectedChildId == $child->id || ($loop->first && !$preselectedChildId)) ? 'checked' : '' }} required class="sr-only">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-sm">
                                    {{ strtoupper(substr($child->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900">{{ $child->name }}</h4>
                                    <p class="text-[11px] text-slate-500">{{ $child->formatted_age }} &bull; {{ $child->blood_group }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- 2. Select Vaccine -->
            <div>
                <label for="vaccine_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">2. Choose Vaccine *</label>
                <select name="vaccine_id" id="vaccine_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Choose Vaccine from Approved WHO Registry --</option>
                    @foreach($vaccines as $vac)
                        <option value="{{ $vac->id }}" {{ $preselectedVaccineId == $vac->id ? 'selected' : '' }}>
                            {{ $vac->name }} ({{ $vac->short_code }}) - Due: {{ $vac->recommended_age_label }} [{{ $vac->target_disease }}]
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Choose Hospital -->
            <div>
                <label for="hospital_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">3. Search & Select Hospital Clinic *</label>
                <select name="hospital_id" id="hospital_id" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500">
                    <option value="">-- Select an Authorized Hospital / Clinic --</option>
                    @foreach($hospitals as $hosp)
                        <option value="{{ $hosp->id }}" {{ request('hospital_id') == $hosp->id ? 'selected' : '' }}>
                            {{ $hosp->name }} - {{ $hosp->location }}, {{ $hosp->city }} ({{ $hosp->operating_hours }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">
                    Want to see addresses and maps? <a href="{{ route('public.hospitals') }}" target="_blank" class="text-brand-600 font-bold underline">Browse the hospital directory</a>.
                </p>
            </div>

            <!-- 4. Date and Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="appointment_date" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">4. Preferred Date *</label>
                    <input type="date" name="appointment_date" id="appointment_date" min="{{ now()->toDateString() }}" value="{{ old('appointment_date', now()->addDay()->toDateString()) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500">
                    @error('appointment_date')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_time" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">5. Time Slot *</label>
                    <select name="appointment_time" id="appointment_time" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-2xl text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="">-- Choose a time slot --</option>
                        <option value="09:00 AM - 10:00 AM" data-start-time="09:00" @selected(old('appointment_time') === '09:00 AM - 10:00 AM')>09:00 AM - 10:00 AM (Morning)</option>
                        <option value="10:30 AM - 11:30 AM" data-start-time="10:30" @selected(old('appointment_time') === '10:30 AM - 11:30 AM')>10:30 AM - 11:30 AM (Morning)</option>
                        <option value="11:30 AM - 12:30 PM" data-start-time="11:30" @selected(old('appointment_time') === '11:30 AM - 12:30 PM')>11:30 AM - 12:30 PM (Midday)</option>
                        <option value="02:00 PM - 03:00 PM" data-start-time="14:00" @selected(old('appointment_time') === '02:00 PM - 03:00 PM')>02:00 PM - 03:00 PM (Afternoon)</option>
                        <option value="03:30 PM - 04:30 PM" data-start-time="15:30" @selected(old('appointment_time') === '03:30 PM - 04:30 PM')>03:30 PM - 04:30 PM (Afternoon)</option>
                    </select>
                    @error('appointment_time')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info note -->
            <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-200/60 text-xs text-brand-900 flex items-start gap-3">
                <i class="fa-solid fa-circle-info text-brand-600 text-sm mt-0.5"></i>
                <div>
                    <p class="font-bold">Next Step: Admin Approval</p>
                    <p class="text-[11px] text-brand-700 mt-0.5">
                        In accordance with system policy, your appointment request will be reviewed by the Health Administration to confirm vaccine stock and doctor slot availability. Once approved, the hospital will be notified.
                    </p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-2xl text-sm transition-all shadow-md shadow-brand-600/30 hover:shadow-lg flex items-center justify-center gap-2">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Submit Appointment Request</span>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    (() => {
        const dateInput = document.getElementById('appointment_date');
        const timeSelect = document.getElementById('appointment_time');

        if (!dateInput || !timeSelect) {
            return;
        }

        const timezoneOffset = Number(dateInput.form.dataset.timezoneOffset) * 1000;

        const updateAvailableSlots = () => {
            const now = new Date(Date.now() + timezoneOffset);
            const today = now.toISOString().slice(0, 10);
            const currentTime = `${String(now.getUTCHours()).padStart(2, '0')}:${String(now.getUTCMinutes()).padStart(2, '0')}`;

            dateInput.min = today;

            Array.from(timeSelect.options).forEach((option) => {
                if (!option.dataset.startTime) {
                    return;
                }

                option.disabled = dateInput.value < today
                    || (dateInput.value === today && option.dataset.startTime <= currentTime);
            });

            if (timeSelect.selectedOptions[0]?.disabled) {
                timeSelect.value = '';
            }
        };

        dateInput.addEventListener('change', updateAvailableSlots);
        updateAvailableSlots();
    })();
</script>
@endpush
@endsection

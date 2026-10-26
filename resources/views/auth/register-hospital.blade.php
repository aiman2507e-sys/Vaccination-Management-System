@extends('layouts.app')

@section('title', 'Hospital Clinic Registration')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex w-14 h-14 rounded-2xl bg-slate-900 text-teal-400 items-center justify-center font-bold text-2xl shadow-lg shadow-slate-900/30 mb-3">
            <i class="fa-solid fa-hospital"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Hospital / Clinic Onboarding</h1>
        <p class="text-xs text-slate-500 mt-1">Register your healthcare institution to receive vaccination appointments</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl shadow-slate-100">
        <form method="POST" action="{{ route('register.hospital') }}" class="space-y-5">
            @csrf

            <!-- Hospital Name -->
            <div>
                <label for="hospital_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hospital / Clinic Official Name *</label>
                <div class="relative">
                    <i class="fa-solid fa-hospital absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="hospital_name" name="hospital_name" value="{{ old('hospital_name') }}" required placeholder="e.g. City Children & Maternity Hospital" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Contact Person -->
            <div>
                <label for="contact_person" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Medical Officer / In-Charge Name *</label>
                <div class="relative">
                    <i class="fa-solid fa-user-doctor absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required placeholder="e.g. Dr. Eleanor Vance (Chief Pediatrician)" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Official Hospital Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="clinic@hospital.org" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Official Phone *</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="923123456789" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Hospital Street Address *</label>
                <textarea id="address" name="address" rows="2" required placeholder="Complete physical facility address" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">{{ old('address') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- City -->
                <div>
                    <label for="city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City *</label>
                    <input type="text" id="city" name="city" value="{{ old('city', '') }}" placeholder="City Name" required class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>

                <!-- Location / District -->
                <div>
                    <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Zone / Landmark / District *</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="e.g. Central Zone, Block C" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <!-- Operating Hours -->
            <div>
                <label for="operating_hours" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Vaccination Wing Operating Hours</label>
                <input type="text" id="operating_hours" name="operating_hours" value="{{ old('operating_hours', 'Mon-Sat 08:00 AM - 05:00 PM') }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password *</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl text-sm transition-all shadow-md shadow-slate-900/20 hover:shadow-lg flex items-center justify-center gap-2 mt-4">
                <i class="fa-solid fa-building-circle-check"></i>
                <span>Register Hospital Facility</span>
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Already registered? <a href="{{ route('login') }}" class="font-bold text-teal-600 hover:text-teal-700">Sign in</a>
        </div>
    </div>
</div>
@endsection

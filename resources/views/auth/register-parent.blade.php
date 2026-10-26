@extends('layouts.app')

@section('title', 'Parent Registration')

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <div class="inline-flex w-14 h-14 rounded-2xl bg-brand-600 text-white items-center justify-center font-bold text-2xl shadow-lg shadow-brand-600/30 mb-3">
            <i class="fa-solid fa-person-breastfeeding"></i>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Parent Account Registration</h1>
        <p class="text-xs text-slate-500 mt-1">Register to maintain your infant's vaccination schedules and book appointments</p>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl shadow-slate-100">
        <form method="POST" action="{{ route('register.parent') }}" class="space-y-5">
            @csrf

            <!-- Parent Name -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Parent / Guardian Full Name *</label>
                <div class="relative">
                    <i class="fa-regular fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your name" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address *</label>
                    <div class="relative">
                        <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="parent@example.com" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Contact Phone *</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="923123456789" class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Residential Street Address *</label>
                <div class="relative">
                    <i class="fa-solid fa-map-pin absolute left-4 top-3 text-slate-400"></i>
                    <textarea id="address" name="address" rows="2" required placeholder="Apartment / Street address" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">City / Municipality</label>
                <input type="text" id="city" name="city" value="{{ old('city', '') }}" placeholder="City Name" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Password (Min 8) *</label>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                </div>
            </div>

            <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl text-sm transition-all shadow-md shadow-brand-600/30 hover:shadow-lg flex items-center justify-center gap-2 mt-4">
                <i class="fa-solid fa-user-plus"></i>
                <span>Complete Parent Registration</span>
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500">
            Already have an account? <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700">Sign in</a>
        </div>
    </div>
</div>
@endsection

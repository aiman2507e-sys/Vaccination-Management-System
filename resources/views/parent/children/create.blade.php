@extends('layouts.parent')

@section('title', 'Register New Child')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('parent.children.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-brand-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Children</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-xl">
                <i class="fa-solid fa-baby"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">Register Infant / Child</h2>
                <p class="text-xs text-slate-500">Accurate birth dates enable our algorithm to generate precise vaccination milestones</p>
            </div>
        </div>

        <form action="{{ route('parent.children.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf

            <!-- Child Name -->
            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Child Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Leo Johnson" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Date of Birth -->
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Date of Birth *</label>
                    <input type="date" name="dob" value="{{ old('dob') }}" max="{{ date('Y-m-d') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>

                <!-- Gender -->
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Gender *</label>
                    <select name="gender" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Blood Group -->
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Blood Group *</label>
                    <select name="blood_group" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group', 'O+') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Birth Weight -->
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Birth Weight (kg)</label>
                    <input type="number" step="0.01" name="birth_weight" value="{{ old('birth_weight') }}" placeholder="e.g. 3.25" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <!-- Place of Birth -->
            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Place of Birth / Hospital Delivered</label>
                <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="e.g. Metropolis Central Maternity Center" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Allergies -->
            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Known Allergies</label>
                <input type="text" name="allergies" value="{{ old('allergies') }}" placeholder="e.g. Egg protein, latex, penicillin (or None)" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Medical History -->
            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Medical Background / Pediatric Notes</label>
                <textarea name="medical_history" rows="2" placeholder="Full-term birth, premature, jaundice, or other medical details" class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">{{ old('medical_history') }}</textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('parent.children.index') }}" class="px-5 py-2.5 border border-slate-300 rounded-xl font-semibold text-slate-600 hover:bg-slate-100">Cancel</a>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-md shadow-brand-600/20">
                    Register Child
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.hospital')

@section('title', 'Hospital Facility Profile')
@section('page_title', 'Hospital Facility Settings')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs">
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-2xl">
                <i class="fa-solid fa-hospital"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $hospital->name }}</h2>
                <p class="text-xs text-slate-500">Authorized Municipal Vaccination Facility</p>
            </div>
        </div>

        <form action="{{ route('hospital.profile.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Hospital Official Name *</label>
                <input type="text" name="name" value="{{ old('name', $hospital->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Hospital Contact Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $hospital->phone) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">In-Charge / Medical Officer</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $hospital->contact_person) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Street Address *</label>
                <textarea name="address" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">{{ old('address', $hospital->address) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City *</label>
                    <input type="text" name="city" value="{{ old('city', $hospital->city) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Location / Zone *</label>
                    <input type="text" name="location" value="{{ old('location', $hospital->location) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Operating Hours *</label>
                <input type="text" name="operating_hours" value="{{ old('operating_hours', $hospital->operating_hours) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-md shadow-teal-600/20">
                    Save Facility Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

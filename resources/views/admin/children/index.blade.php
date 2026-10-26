@extends('layouts.admin')

@section('title', 'All Child Details')
@section('page_title', 'All Child Profile Details')

@section('content')
<div class="space-y-6">
    <!-- Header with Search and Filters -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <form method="GET" action="{{ route('admin.children.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Search Child / Parent</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by child name, parent name, or parent phone..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white">
                </div>
            </div>

            <!-- Blood Group Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Blood Group</label>
                <select name="blood_group" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Blood Groups</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ $bloodGroup === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gender Filter & Submit -->
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Gender</label>
                    <select name="gender" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                        <option value="">All Genders</option>
                        <option value="Male" {{ $gender === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $gender === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ $gender === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-all shadow-xs">
                    Filter
                </button>
                @if($search || $bloodGroup || $gender)
                    <a href="{{ route('admin.children.index') }}" class="p-2.5 bg-slate-200 text-slate-600 hover:text-slate-900 rounded-xl text-sm" title="Clear Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Children Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Child Name</th>
                        <th class="py-4 px-6">Parent Details</th>
                        <th class="py-4 px-6">Date of Birth & Age</th>
                        <th class="py-4 px-6">Gender / Blood Group</th>
                        <th class="py-4 px-6">Vaccines Taken</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($children as $child)
                        @php
                            $completedDoses = $child->appointments->where('status', 'vaccinated')->count();
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($child->name, 0, 1)) }}
                                    </span>
                                    <span>{{ $child->name }}</span>
                                </div>
                                <div class="text-xs text-slate-400 ml-10">ID: #CH-{{ str_pad($child->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $child->parent->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-phone text-[10px]"></i> {{ $child->parent->phone ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $child->dob->format('M d, Y') }}</div>
                                <div class="text-xs text-brand-700 font-semibold mt-0.5">{{ $child->formatted_age }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="inline-flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium">{{ $child->gender }}</span>
                                    <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">{{ $child->blood_group }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    <i class="fa-solid fa-syringe text-xs"></i>
                                    {{ $completedDoses }} Completed
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('admin.children.show', $child->id) }}" class="inline-flex items-center gap-1.5 bg-brand-50 hover:bg-brand-100 text-brand-700 font-semibold px-3 py-1.5 rounded-lg text-xs transition-colors border border-brand-200">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>View Profile</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <i class="fa-solid fa-child text-3xl mb-2 text-slate-300 block"></i>
                                No children records found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $children->links() }}
        </div>
    </div>
</div>
@endsection

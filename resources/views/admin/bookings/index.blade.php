@extends('layouts.admin')

@section('title', 'Master Booking Details')
@section('page_title', 'Parent Vaccination Booking Details')

@section('content')
<div class="space-y-6">
    <!-- Header with Search and Status Filters -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Search Booking Records</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by booking reference #, child name, or parent..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>
            </div>

            <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="vaccinated" {{ $status === 'vaccinated' ? 'selected' : '' }}>Vaccinated</option>
                        <option value="not_vaccinated" {{ $status === 'not_vaccinated' ? 'selected' : '' }}>Not Vaccinated / Missed</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <button type="submit" class="bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-all shadow-xs">
                    Search
                </button>
                @if($search || $status)
                    <a href="{{ route('admin.bookings.index') }}" class="p-2.5 bg-slate-200 text-slate-600 rounded-xl text-sm" title="Clear">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Booking Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Ref #</th>
                        <th class="py-4 px-6">Child Name & Age</th>
                        <th class="py-4 px-6">Parent Info</th>
                        <th class="py-4 px-6">Vaccine</th>
                        <th class="py-4 px-6">Hospital Clinic</th>
                        <th class="py-4 px-6">Date & Slot</th>
                        <th class="py-4 px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-xs text-slate-700 whitespace-nowrap">
                                {{ $b->booking_reference }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.children.show', $b->child->id) }}" class="font-bold text-slate-900 hover:text-brand-600">
                                    {{ $b->child->name ?? 'N/A' }}
                                </a>
                                <div class="text-xs text-slate-500">{{ $b->child->formatted_age ?? '' }} &bull; {{ $b->child->blood_group ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $b->parent->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $b->parent->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $b->vaccine->name ?? 'N/A' }}</div>
                                <div class="text-xs text-brand-700 font-semibold">{{ $b->vaccine->short_code ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <div class="font-medium">{{ $b->hospital->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400">{{ $b->hospital->location }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $b->appointment_date?->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $b->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $b->status_badge_class }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 text-xs">
                                No booking details found matching query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection

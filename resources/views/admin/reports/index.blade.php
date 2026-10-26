@extends('layouts.admin')

@section('title', 'Vaccination Reports')
@section('page_title', 'Vaccination Report (Date-Wise & Child-Wise)')

@section('content')
<div class="space-y-6">
    <!-- Filter Card & Export Actions -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900">Custom Immunization Reports</h3>
                <p class="text-xs text-slate-500">Generate child-specific, date-specific, or hospital-specific statistical records</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- CSV Export Button with active query string -->
                <a href="{{ route('admin.reports.export', request()->all()) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-xs">
                    <i class="fa-solid fa-file-csv"></i>
                    <span>Export to CSV</span>
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-xs">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Start Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">From Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- End Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">To Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">All Statuses</option>
                    <option value="vaccinated" {{ $status === 'vaccinated' ? 'selected' : '' }}>Vaccinated</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved / Scheduled</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="not_vaccinated" {{ $status === 'not_vaccinated' ? 'selected' : '' }}>Not Vaccinated / Missed</option>
                </select>
            </div>

            <!-- Child Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Child</label>
                <select name="child_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">All Children</option>
                    @foreach($allChildren as $c)
                        <option value="{{ $c->id }}" {{ $childId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Vaccine Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Vaccine</label>
                <select name="vaccine_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-brand-500">
                    <option value="">All Vaccines</option>
                    @foreach($allVaccines as $v)
                        <option value="{{ $v->id }}" {{ $vaccineId == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-2 rounded-xl text-xs transition-all shadow-xs">
                    Generate
                </button>
                <a href="{{ route('admin.reports.index') }}" class="p-2 bg-slate-200 text-slate-600 rounded-xl text-xs" title="Reset">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-3.5 px-5">Booking Ref</th>
                        <th class="py-3.5 px-5">Child Name & Age</th>
                        <th class="py-3.5 px-5">Parent & Contact</th>
                        <th class="py-3.5 px-5">Vaccine</th>
                        <th class="py-3.5 px-5">Hospital</th>
                        <th class="py-3.5 px-5">Scheduled Date</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Administration Record</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $row)
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-3.5 px-5 font-mono font-bold text-slate-700 whitespace-nowrap">{{ $row->booking_reference }}</td>
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $row->child->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">DOB: {{ $row->child->dob?->format('Y-m-d') }} ({{ $row->child->blood_group }})</div>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="font-semibold text-slate-800">{{ $row->parent->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $row->parent->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="font-bold text-slate-900">{{ $row->vaccine->name ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $row->vaccine->target_disease ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-5 text-slate-700">
                                {{ $row->hospital->name ?? 'N/A' }}
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $row->appointment_date?->format('Y-m-d') }}</div>
                                <div class="text-[11px] text-slate-500">{{ $row->appointment_time }}</div>
                            </td>
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full font-bold border {{ $row->status_badge_class }}">
                                    {{ $row->status_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600">
                                @if($row->status === 'vaccinated')
                                    <div>Date: <strong class="text-emerald-700">{{ $row->administered_date?->format('Y-m-d') }}</strong></div>
                                    <div>Lot: <span class="font-mono font-semibold">{{ $row->batch_number ?? '-' }}</span></div>
                                    <div class="text-[11px] text-slate-500">By: {{ $row->administered_by ?? '-' }}</div>
                                @elseif($row->status === 'not_vaccinated')
                                    <div class="text-rose-600 italic">Missed: {{ $row->hospital_remarks ?: 'Did not attend' }}</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                No records found matching the specified report parameters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

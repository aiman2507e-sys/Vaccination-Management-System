@extends('layouts.admin')

@section('title', 'Parent Appointment Requests')
@section('page_title', 'Parent Appointment Requests (Approve / Reject)')

@section('content')
<div class="space-y-6" x-data="{
    rejectModal: false,
    rejectAppointmentId: null,
    rejectRef: '',
    openReject(id, ref) {
        this.rejectAppointmentId = id;
        this.rejectRef = ref;
        this.rejectModal = true;
    }
}">
    <!-- Status Tabs -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-4 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.requests.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'pending' ? 'bg-amber-500 text-slate-950 shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-clock mr-1"></i> Pending Requests
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'approved' ? 'bg-brand-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-circle-check mr-1"></i> Approved
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <i class="fa-solid fa-ban mr-1"></i> Rejected
            </a>
            <a href="{{ route('admin.requests.index', ['status' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ $status === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                All Requests
            </a>
        </div>

        <p class="text-xs text-slate-400">Showing filtered requests: <strong class="text-slate-700 capitalize">{{ $status }}</strong></p>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Ref #</th>
                        <th class="py-4 px-6">Child Profile</th>
                        <th class="py-4 px-6">Parent Contact</th>
                        <th class="py-4 px-6">Vaccine Requested</th>
                        <th class="py-4 px-6">Requested Hospital</th>
                        <th class="py-4 px-6">Scheduled Date & Time</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Admin Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-xs text-slate-600 whitespace-nowrap">
                                {{ $req->booking_reference }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.children.show', $req->child->id) }}" class="font-bold text-slate-900 hover:text-brand-600">
                                    {{ $req->child->name ?? 'N/A' }}
                                </a>
                                <div class="text-xs text-slate-500">{{ $req->child->formatted_age ?? '' }} &bull; {{ $req->child->gender }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $req->parent->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-500">{{ $req->parent->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-slate-900">{{ $req->vaccine->name ?? 'N/A' }}</span>
                                <div class="text-xs text-brand-700 font-semibold">{{ $req->vaccine->target_disease ?? '' }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <div class="font-medium">{{ $req->hospital->name ?? 'N/A' }}</div>
                                <div class="text-xs text-slate-400">{{ $req->hospital->city }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="font-medium text-slate-800">{{ $req->appointment_date?->format('M d, Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $req->appointment_time }}</div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $req->status_badge_class }}">
                                    {{ $req->status_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @if($req->status === 'pending')
                                    <div class="inline-flex items-center gap-2">
                                        <!-- Approve Action -->
                                        <form action="{{ route('admin.requests.approve', $req->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-all shadow-xs flex items-center gap-1">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject Trigger Modal -->
                                        <button type="button" @click="openReject({{ $req->id }}, '{{ $req->booking_reference }}')" class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold px-3 py-1.5 rounded-lg text-xs transition-all flex items-center gap-1">
                                            <i class="fa-solid fa-xmark"></i>
                                            <span>Reject</span>
                                        </button>
                                    </div>
                                @elseif($req->status === 'approved')
                                    <span class="text-xs text-emerald-700 font-semibold flex items-center justify-end gap-1">
                                        <i class="fa-solid fa-circle-check text-xs"></i> Dispatched to Hospital
                                    </span>
                                @elseif($req->status === 'rejected')
                                    <span class="text-xs text-rose-600 italic">Rejected</span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                                No requests found for status '{{ $status }}'.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $requests->links() }}
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="rejectModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="rejectModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Reject Appointment Request</h3>
                <button @click="rejectModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form :action="'{{ url('admin/requests') }}/' + rejectAppointmentId + '/reject'" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PATCH')
                <p class="text-xs text-slate-600">
                    Are you sure you want to reject appointment reference <strong class="font-mono text-slate-900" x-text="rejectRef"></strong>?
                </p>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Reason / Admin Note</label>
                    <textarea name="admin_notes" rows="3" required placeholder="Please enter reason (e.g. Clinic fully booked on this date, please choose alternate clinic)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-rose-500"></textarea>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="rejectModal = false" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-xs">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

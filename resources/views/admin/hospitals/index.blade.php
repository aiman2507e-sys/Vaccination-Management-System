@extends('layouts.admin')

@section('title', 'Manage Hospitals')
@section('page_title', 'Hospital Clinics Management')

@section('content')
<div class="space-y-6" x-data="{
    addModal: false,
    editModal: false,
    currentHospital: {},
    openEdit(hosp) {
        this.currentHospital = hosp;
        this.editModal = true;
    }
}">
    <!-- Header -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-lg text-slate-900">Hospital Centers Registry</h3>
            <p class="text-xs text-slate-500">Add, edit, deactivate, and monitor authorized vaccination clinics</p>
        </div>
        <button @click="addModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-brand-600/20">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Hospital</span>
        </button>
    </div>

    <!-- Hospitals Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Hospital Name</th>
                        <th class="py-4 px-6">Location & City</th>
                        <th class="py-4 px-6">Contact & In-Charge</th>
                        <th class="py-4 px-6">Operating Hours</th>
                        <th class="py-4 px-6">Activity Stats</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($hospitals as $hosp)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        <i class="fa-solid fa-hospital"></i>
                                    </div>
                                    <span>{{ $hosp->name }}</span>
                                </div>
                                <div class="text-xs text-slate-400 ml-10 truncate max-w-[220px]">{{ $hosp->address }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-800">{{ $hosp->location }}</div>
                                <div class="text-xs text-slate-500">{{ $hosp->city }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-slate-800">{{ $hosp->contact_person ?: 'Medical Admin' }}</div>
                                <div class="text-xs text-slate-500"><i class="fa-solid fa-phone text-[10px] mr-1"></i> {{ $hosp->phone }}</div>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600">
                                {{ $hosp->operating_hours }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-xs space-y-0.5">
                                    <div><strong class="text-slate-800">{{ $hosp->appointments_count }}</strong> Bookings</div>
                                    <div class="text-emerald-700 font-semibold"><i class="fa-solid fa-check text-[10px]"></i> {{ $hosp->vaccinated_count }} Administered</div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @if($hosp->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-700">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <button @click="openEdit({{ json_encode($hosp) }})" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Hospital">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.hospitals.destroy', $hosp->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this hospital and its user account?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Hospital">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                No hospitals registered yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $hospitals->links() }}
        </div>
    </div>

    <!-- Modal: Add New Hospital -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="addModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Add New Hospital Facility</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.hospitals.store') }}" method="POST" class="mt-4 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Hospital Name *</label>
                    <input type="text" name="name" required placeholder="e.g. City Central Children Hospital" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Email (Login) *</label>
                        <input type="email" name="email" required placeholder="hospital@vaccine.test" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Phone Number *</label>
                        <input type="text" name="phone" required placeholder="923123456789" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Facility Street Address *</label>
                    <textarea name="address" rows="2" required placeholder="Street address" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City *</label>
                        <input type="text" name="city" required value="" placeholder="City Name" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Location / Zone *</label>
                        <input type="text" name="location" required placeholder="e.g. Central Zone" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Contact Officer</label>
                        <input type="text" name="contact_person" placeholder="Dr. John Doe" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Operating Hours</label>
                        <input type="text" name="operating_hours" value="08:00 AM - 05:00 PM" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Staff Portal Password (Optional)</label>
                    <input type="password" name="password" placeholder="Leave empty or set password for clinic portal login" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    <p class="text-[11px] text-slate-400 mt-1">If provided, creates a staff user login for this hospital.</p>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 rounded-xl font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Create Hospital</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Hospital -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Update Hospital Facility</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form :action="'{{ url('admin/hospitals') }}/' + currentHospital.id" method="POST" class="mt-4 space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Hospital Name *</label>
                    <input type="text" name="name" :value="currentHospital.name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Email *</label>
                        <input type="email" name="email" :value="currentHospital.email" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Phone *</label>
                        <input type="text" name="phone" :value="currentHospital.phone" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Address *</label>
                    <textarea name="address" rows="2" :value="currentHospital.address" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">City *</label>
                        <input type="text" name="city" :value="currentHospital.city" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Location *</label>
                        <input type="text" name="location" :value="currentHospital.location" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Contact Person</label>
                        <input type="text" name="contact_person" :value="currentHospital.contact_person" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Operating Hours</label>
                        <input type="text" name="operating_hours" :value="currentHospital.operating_hours" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Status *</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                        <option value="active" :selected="currentHospital.status === 'active'">Active</option>
                        <option value="inactive" :selected="currentHospital.status === 'inactive'">Inactive</option>
                    </select>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-300 rounded-xl font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Update Hospital</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

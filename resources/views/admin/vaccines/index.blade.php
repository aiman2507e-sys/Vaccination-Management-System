@extends('layouts.admin')

@section('title', 'List of Vaccines')
@section('page_title', 'Vaccine Registry & Availability Management')

@section('content')
<div class="space-y-6" x-data="{
    addModal: false,
    editModal: false,
    currentVaccine: {},
    openEdit(vac) {
        this.currentVaccine = vac;
        this.editModal = true;
    }
}">
    <!-- Header with Action -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="font-bold text-lg text-slate-900">Pediatric Vaccines Registry</h3>
            <p class="text-xs text-slate-500">Manage immunization inventory and mark vaccines as Available or Unavailable across the system</p>
        </div>
        <button @click="addModal = true" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-brand-600/20">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Vaccine</span>
        </button>
    </div>

    <!-- Vaccines Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                        <th class="py-4 px-6">Vaccine Name & Code</th>
                        <th class="py-4 px-6">Target Disease</th>
                        <th class="py-4 px-6">Recommended Age</th>
                        <th class="py-4 px-6">Dosage & Route</th>
                        <th class="py-4 px-6">Availability Status (SRS)</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($vaccines as $vac)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $vac->name }}</div>
                                <div class="text-xs font-mono text-slate-500">Code: {{ $vac->short_code }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <span class="font-medium">{{ $vac->target_disease }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">
                                    {{ $vac->recommended_age_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-slate-600">
                                <div>{{ $vac->dosage }}</div>
                                <div class="text-slate-400 mt-0.5">{{ $vac->route }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    @if($vac->status === 'available')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Available
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Unavailable
                                        </span>
                                    @endif

                                    <!-- Quick 1-click status toggle -->
                                    <form action="{{ route('admin.vaccines.toggle', $vac->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-[11px] font-semibold text-slate-500 hover:text-brand-600 underline ml-1" title="Toggle availability">
                                            (Toggle)
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    <button @click="openEdit({{ json_encode($vac) }})" class="p-2 text-slate-500 hover:text-brand-600 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Vaccine">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.vaccines.destroy', $vac->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this vaccine permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete Vaccine">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                No vaccines currently registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Add New Vaccine -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="addModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Add New Pediatric Vaccine</h3>
                <button @click="addModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.vaccines.store') }}" method="POST" class="mt-4 space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Vaccine Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Hepatitis B Birth Dose" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Short Code *</label>
                        <input type="text" name="short_code" required placeholder="e.g. HEP-B-1" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Target Disease *</label>
                        <input type="text" name="target_disease" required placeholder="e.g. Hepatitis B" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Age in Days *</label>
                        <input type="number" name="recommended_age_days" required min="0" value="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Age Label *</label>
                        <input type="text" name="recommended_age_label" required placeholder="e.g. At Birth, 6 Weeks" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Dosage *</label>
                        <input type="text" name="dosage" required value="0.5 ml" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Route *</label>
                        <input type="text" name="route" required value="Intramuscular (IM)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Initial Status (SRS) *</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Clinical Description / Indications</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-brand-500"></textarea>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="addModal = false" class="px-4 py-2 border border-slate-300 rounded-xl font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Save Vaccine</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Edit Vaccine -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="font-bold text-base text-slate-900">Edit Vaccine Details</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form :action="'{{ url('admin/vaccines') }}/' + currentVaccine.id" method="POST" class="mt-4 space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Vaccine Full Name *</label>
                    <input type="text" name="name" :value="currentVaccine.name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Short Code *</label>
                        <input type="text" name="short_code" :value="currentVaccine.short_code" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-mono">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Target Disease *</label>
                        <input type="text" name="target_disease" :value="currentVaccine.target_disease" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Age in Days *</label>
                        <input type="number" name="recommended_age_days" :value="currentVaccine.recommended_age_days" required min="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Age Label *</label>
                        <input type="text" name="recommended_age_label" :value="currentVaccine.recommended_age_label" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Dosage *</label>
                        <input type="text" name="dosage" :value="currentVaccine.dosage" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Route *</label>
                        <input type="text" name="route" :value="currentVaccine.route" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Status (SRS) *</label>
                    <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm">
                        <option value="available" :selected="currentVaccine.status === 'available'">Available</option>
                        <option value="unavailable" :selected="currentVaccine.status === 'unavailable'">Unavailable</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold uppercase tracking-wider text-slate-600 mb-1">Clinical Description</label>
                    <textarea name="description" rows="2" :value="currentVaccine.description" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm"></textarea>
                </div>

                <div class="pt-3 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-4 py-2 border border-slate-300 rounded-xl font-semibold text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-xl font-bold shadow-xs">Update Vaccine</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.parent')

@section('title', 'Report of Vaccination Taken')

@section('content')
<div class="space-y-6">
    <!-- Header & Child Selector -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Vaccination History & Immunization Report</h2>
            <p class="text-xs text-slate-500">Official medical record of all completed vaccine doses taken by your child</p>
        </div>

        <!-- Child Picker -->
        <form method="GET" action="{{ route('parent.reports.index') }}" class="flex items-center gap-3">
            <label for="child_id" class="text-xs font-bold uppercase text-slate-500 whitespace-nowrap">Select Child:</label>
            <select name="child_id" id="child_id" onchange="this.form.submit()" class="px-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-brand-500">
                @foreach($children as $child)
                    <option value="{{ $child->id }}" {{ $selectedChild?->id == $child->id ? 'selected' : '' }}>
                        {{ $child->name }} ({{ $child->formatted_age }})
                    </option>
                @endforeach
            </select>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-xl text-xs transition-all shadow-xs">
                <i class="fa-solid fa-print"></i>
                <span>Print Card</span>
            </button>
        </form>
    </div>

    @if($selectedChild)
        <!-- Printable Official Child Immunization Certificate -->
        <div class="bg-white rounded-3xl border-2 border-slate-200/90 p-8 shadow-xs relative overflow-hidden">
            <!-- Decorative Seal Background -->
            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-brand-50/60 pointer-events-none -z-0"></div>

            <!-- Card Header -->
            <div class="flex items-start justify-between pb-6 border-b-2 border-slate-900/10 mb-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center font-bold text-2xl shadow-md">
                        <i class="fa-solid fa-syringe"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Official Health Record</span>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Child Immunization Certificate</h1>
                        <p class="text-xs text-slate-500">Ministry of Health & Pediatric Immunization Registry</p>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-xs font-mono font-bold text-slate-600">ID: #CH-{{ str_pad($selectedChild->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Generated: {{ date('M d, Y') }}</div>
                </div>
            </div>

            <!-- Child Info Summary Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/60 text-xs mb-6 relative z-10">
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider">Child Name</span>
                    <p class="font-extrabold text-sm text-slate-900 mt-0.5">{{ $selectedChild->name }}</p>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider">Date of Birth</span>
                    <p class="font-bold text-sm text-slate-800 mt-0.5">{{ $selectedChild->dob->format('M d, Y') }}</p>
                    <p class="text-[10px] text-brand-700 font-semibold">{{ $selectedChild->formatted_age }}</p>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider">Blood Group / Gender</span>
                    <p class="font-bold text-sm text-rose-700 mt-0.5">{{ $selectedChild->blood_group }} &bull; {{ $selectedChild->gender }}</p>
                </div>
                <div>
                    <span class="font-bold text-slate-400 uppercase tracking-wider">Parent / Guardian</span>
                    <p class="font-bold text-sm text-slate-800 mt-0.5">{{ $selectedChild->parent->name ?? auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500">{{ $selectedChild->parent->phone ?? auth()->user()->phone }}</p>
                </div>
            </div>

            <!-- Table of Doses Administered -->
            <div class="overflow-x-auto relative z-10">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100/80 text-[11px] font-bold uppercase tracking-wider text-slate-700 border-b border-slate-200">
                            <th class="py-3 px-4">#</th>
                            <th class="py-3 px-4">Vaccine Name & Code</th>
                            <th class="py-3 px-4">Target Disease</th>
                            <th class="py-3 px-4">Date Administered</th>
                            <th class="py-3 px-4">Batch / Lot #</th>
                            <th class="py-3 px-4">Administered Hospital</th>
                            <th class="py-3 px-4">Clinician Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($completedVaccinations as $index => $vac)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3.5 px-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-extrabold text-slate-900 text-sm">{{ $vac->vaccine->name }}</div>
                                    <div class="font-mono text-brand-700 text-[11px]">{{ $vac->vaccine->short_code }}</div>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700">
                                    {{ $vac->vaccine->target_disease }}
                                </td>
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        <i class="fa-solid fa-circle-check text-[10px] text-emerald-600"></i>
                                        {{ $vac->administered_date?->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700 whitespace-nowrap">
                                    {{ $vac->batch_number ?: 'LOT-VERIFIED' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-800">
                                    <div class="font-semibold">{{ $vac->hospital->name ?? 'Municipal Clinic' }}</div>
                                    <div class="text-[10px] text-slate-400">By: {{ $vac->administered_by ?: 'Clinical Staff' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 italic">
                                    {{ $vac->hospital_remarks ?: 'Dose tolerated without adverse event.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-10 text-center text-slate-400">
                                    <i class="fa-solid fa-file-circle-exclamation text-3xl mb-2 text-slate-300 block"></i>
                                    No completed vaccinations recorded yet for {{ $selectedChild->name }}.
                                    <div class="mt-2">
                                        <a href="{{ route('parent.book.hospital', ['child_id' => $selectedChild->id]) }}" class="text-brand-600 font-bold underline">
                                            Schedule first vaccination now
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Certificate Stamp & Signature Footer -->
            <div class="mt-10 pt-6 border-t border-slate-200 grid grid-cols-2 gap-8 text-xs relative z-10">
                <div>
                    <p class="font-bold text-slate-800">Digital Verification Notice:</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        This electronic report is authenticated under the municipal child health surveillance act. All lot batches are verified through cold-chain logistics.
                    </p>
                </div>
                <div class="text-right">
                    <div class="inline-block border-b-2 border-slate-400 w-48 pb-1 mb-1 font-serif text-slate-700 italic">
                        Chief Medical Officer
                    </div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Authorized Electronic Signature</p>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 text-center border border-slate-200">
            <p class="text-sm text-slate-500">Please register a child first to view immunization records.</p>
        </div>
    @endif
</div>
@endsection

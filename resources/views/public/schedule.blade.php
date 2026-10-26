@extends('layouts.app')

@section('title', 'Standard Pediatric Vaccination Schedule')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-10 text-center max-w-3xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-3 py-1 rounded-full">Official Immunization Schedule</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-3">National Pediatric Vaccination Timeline</h1>
        <p class="text-slate-600 text-sm sm:text-base mt-2">
            Recommended pediatric immunization schedule from birth through childhood. Following these dates protects infants against 12+ deadly communicable diseases.
        </p>
    </div>

    <!-- Table of Vaccines -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-4 px-6">Milestone Age</th>
                        <th class="py-4 px-6">Vaccine & Code</th>
                        <th class="py-4 px-6">Target Disease</th>
                        <th class="py-4 px-6">Dosage & Route</th>
                        <th class="py-4 px-6">System Availability</th>
                        <th class="py-4 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($vaccines as $vac)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-brand-50 text-brand-700 font-bold text-xs">
                                    <i class="fa-regular fa-clock text-xs"></i>
                                    {{ $vac->recommended_age_label }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $vac->name }}</div>
                                <div class="text-xs font-mono text-slate-500 mt-0.5">Code: {{ $vac->short_code }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-700">
                                <span class="font-medium">{{ $vac->target_disease }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                <div><strong class="text-slate-800">Dose:</strong> {{ $vac->dosage }}</div>
                                <div class="mt-0.5"><strong class="text-slate-800">Route:</strong> {{ $vac->route }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($vac->status === 'available')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                @auth
                                    @if(auth()->user()->isParent())
                                        <a href="{{ route('parent.book.hospital', ['vaccine_id' => $vac->id]) }}" class="inline-flex items-center gap-1.5 bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs px-3.5 py-2 rounded-xl transition-all shadow-xs">
                                            <i class="fa-solid fa-calendar-plus"></i>
                                            <span>Book Slot</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Available in Portal</span>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs px-3.5 py-2 rounded-xl transition-all shadow-xs">
                                        <i class="fa-solid fa-arrow-right"></i>
                                        <span>Book</span>
                                    </a>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

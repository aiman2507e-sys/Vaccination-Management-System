@extends('layouts.parent')

@section('title', 'Details of Child')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Child Button -->
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Your Registered Children</h2>
            <p class="text-xs text-slate-500">Maintain pediatric profiles, track growth milestones, and manage immunization status</p>
        </div>
        <a href="{{ route('parent.children.create') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-4 py-2.5 rounded-xl text-xs transition-all shadow-md shadow-brand-600/20">
            <i class="fa-solid fa-plus"></i>
            <span>Register New Child</span>
        </a>
    </div>

    <!-- Children Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($children as $child)
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 shadow-xs hover:shadow-md transition-shadow flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg shrink-0">
                            {{ strtoupper(substr($child->name, 0, 1)) }}
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            Blood Group: {{ $child->blood_group }}
                        </span>
                    </div>

                    <h3 class="font-bold text-lg text-slate-900">{{ $child->name }}</h3>
                    <p class="text-xs text-brand-700 font-bold mt-0.5">{{ $child->formatted_age }} ({{ $child->gender }})</p>

                    <div class="mt-4 pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-600">
                        <p class="flex items-center justify-between">
                            <span class="text-slate-400">Date of Birth:</span>
                            <span class="font-semibold text-slate-800">{{ $child->dob->format('M d, Y') }}</span>
                        </p>
                        <p class="flex items-center justify-between">
                            <span class="text-slate-400">Birth Weight:</span>
                            <span class="font-semibold text-slate-800">{{ $child->birth_weight ? $child->birth_weight . ' kg' : 'N/A' }}</span>
                        </p>
                        <p class="flex items-center justify-between">
                            <span class="text-slate-400">Vaccines Completed:</span>
                            <span class="font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $child->vaccinated_count }} Doses</span>
                        </p>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2">
                    <a href="{{ route('parent.children.show', $child->id) }}" class="text-center py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 rounded-xl text-xs font-bold transition-colors">
                        <i class="fa-solid fa-timeline mr-1"></i> Timeline
                    </a>
                    <a href="{{ route('parent.children.edit', $child->id) }}" class="text-center py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-200">
                <div class="w-16 h-16 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center mx-auto text-2xl mb-3">
                    <i class="fa-solid fa-baby"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900">No Children Registered Yet</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Add your infant's birth details to start receiving automated vaccination due date reminders.</p>
                <a href="{{ route('parent.children.create') }}" class="inline-flex items-center gap-2 bg-brand-600 hover:bg-brand-700 text-white font-bold px-6 py-2.5 rounded-xl text-xs mt-4 shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    <span>Register Child</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
